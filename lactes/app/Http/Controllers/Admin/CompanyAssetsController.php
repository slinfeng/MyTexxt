<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\AssetInfo;
use App\Models\AssetRentalLog;
use App\Models\AssetType;
use App\Rules\Amount;
use App\Traits\PCGateTrait;
use App\Traits\ToolsTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CompanyAssetsController extends Controller
{
    use PCGateTrait;

    /**
     * モバイルアクセス禁止
     * CompanyAssetsController constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->abortNotFoundForMobile();
    }

    /**
     * インデックス画面
     * @return Application|Factory|View
     */
    public function index()
    {
        $this->deniesForView(AssetInfo::class);
        $currency = AdminSetting::select('currency_symbol')->first()->currency_symbol;
        return view('admin.companyassets.index',compact('currency'));
    }

    /**
     * データテーブル用
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getAssetInfo(Request $request)
    {
        $this->deniesForView(AssetInfo::class);
        $builder = AssetInfo::when($request->number != '', function ($query) use ($request) {
                $query->where('asset_type_id', '=', $request->number);
            })->when($request->manage_code, function ($query) use ($request) {
                $query->where('manage_code', 'like', '%' . $request->manage_code . '%');
            })->select('asset_info.*')->get();
        return datatables()
            ->of($builder)
            ->editColumn(
                'manage_code',
                function ($row) {
                    return '<a class="edit-link" href="javascript:void(0)" data-sort="'.$row->manage_code.'" data-href="'.route('companyassets.edit',$row->id)
                        .'">'.$row->manage_code.'</a>';
                }
            )
            ->editColumn(
                'type_maker_model',
                function ($row) {
                    return ($row->type??'無').'/'.($row->maker??'無').'/'.($row->model??'無');
                }
            )
            ->editColumn(
                'status',
                function ($row) {
                    $status = 3;
                    $len = sizeof($row->AssetRentalLog);
                    if($len>0){
                        $status = $row->AssetRentalLog[$len-1]->status;
                    }
                    $html = '<a class="edit-link" href="javascript:void(0)">';
                    switch ($status){
                        case 0:
                            $html .= '返却済';
                            break;
                        case 1:
                            $html .= '貸出中';
                            break;
                        case 2:
                            $html .= '廃棄';
                            break;
                        case 3:
                            $html .= '登録済';
                            break;
                    }
                    $html .= '</a>';
                    return $html;
                }
            )
            ->editColumn(
                'storage',
                function ($row) {
                    $status = 0;
                    $len = sizeof($row->AssetRentalLog);
                    if($len>0){
                        $status = $row->AssetRentalLog[$len-1]->status;
                    }
                    $html = '';
                    switch ($status){
                        case 1:
                            $html .= $row->AssetRentalLog[$len-1]->user;
                            break;
                        default:
                            $html .= $row->storage;
                            break;
                    }
                    return $html;
                }
            )
            ->editColumn(
                'asset_type',
                function ($row) {
                    return $row->asset_types->asset_type_name;
                }
            )
            ->editColumn(
                'delivery_date',
                function ($row) {
                    return substr($row->delivery_date,0,10);
                }
            )
            ->editColumn(
                'amount',
                function ($row) {
                    return '¥' . number_format($row->amount,0,'.',',');
                }
            )
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * バリデーションメッセージ
     * @return array
     */
    public function getCommonMessage(){
        return [
            'delivery_date.required' => __('validation.required', ['attribute' => '納品日']),
            'delivery_date.date' => __('validation.date', ['attribute' => '納品日']),
        ];
    }

    /**
     * 新規画面
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->deniesForModify(AssetInfo::class);
        $assetTypes = AssetType::orderBy('asset_type_code')->get();
        return view('admin.companyassets.create',compact('assetTypes'));
    }

    /**
     * 登録
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function store(Request $request)
    {
        $this->deniesForModify(AssetInfo::class);
        $validator = Validator::make($request->all(),[
            'manage_code' => ['bail','required','unique:asset_info,manage_code'],
            'asset_type_id' => ['bail','numeric','exists:asset_types,id'],
            'delivery_date' => ['bail','required','date'],
            'amount' => ['bail','required',new Amount('金額',true)],
        ],$this->getCommonMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        else {
            $info = $request->all();
            $info['amount'] = preg_replace('/[¥,]/', '',$request->amount);
            AssetInfo::create($info);
            return Reply::success(__('Company Asset is added successfully.'));
        }
    }

    /**
     * 廃棄
     * @param $id
     * @return array|string[]
     */
    public function destroy($id)
    {
        $this->deniesForModify(AssetInfo::class);
        $assetRental = array();
        $assetRental['status'] = 2;
        $assetRental['asset_info_id'] = $id;
        $assetRental['responsible_person'] = Auth::user()->id;
        AssetRentalLog::create($assetRental);
        return Reply::success(__('Company Asset is　deleted successfully.'));
    }

    /**
     * 回復
     * @param $id
     * @return array|string[]
     */
    public function restore($id){
        $this->deniesForModify(AssetInfo::class);
        $assetRental['status']=0;
        $assetRental['asset_info_id']=$id;
        $assetRental['responsible_person']=Auth::user()->id;
        AssetRentalLog::create($assetRental);
        return Reply::success('設備を回復しました。');
    }

    /**
     * 設備変更画面
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function edit(Request $request, $id)
    {
        $this->deniesForView(AssetInfo::class);
        $assetInfo = AssetInfo::find($id);
        $assetTypes = AssetType::orderBy('asset_type_code')->get();
        return view('admin.companyassets.edit_ajax', compact('assetInfo', 'assetTypes'));
    }

    /**
     * 設備変更
     * @param Request $request
     * @param $id
     * @return array|bool[]|string[]
     */
    public function update(Request $request, $id)
    {
        $this->deniesForModify(AssetInfo::class);
        $validator = Validator::make($request->all(),[
            'manage_code' => ['bail','required'],
            'asset_type_id' => ['bail','numeric','exists:asset_types,id'],
            'delivery_date' => ['bail','required','date'],
            'amount' => ['bail','required',new Amount('金額',true)],
        ],$this->getCommonMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        else {
            $assetInfo = AssetInfo::find($id);
            $info = $request->all();
            $info['amount'] = preg_replace('/[¥,]/', '', $request->amount);
            $assetInfo->update($info);
            return Reply::success(__('Company Asset is　updated successfully.'));
        }
    }

    /**
     * 該当番号最大値を取得
     * @param Request $request
     * @return string
     */
    public function getMaxNum(Request $request){
        $asset_type_id = $request->asset_type_id;
        $builder = AssetInfo::where('asset_type_id',$asset_type_id);
        $maxCode = $builder->max('manage_code');
        $asset = $builder->where('manage_code',$maxCode)->first();
        $asset_type_code = AssetType::where('id',$asset_type_id)->first()->asset_type_code;
        if(!isset($asset)){
            $num = $asset_type_code.'0001';
        }else{
            $temp = (int)substr($maxCode,4,4) + 1;
            $num = $asset_type_code.str_pad($temp.'',4,'0',STR_PAD_LEFT);
        }
        return $num;
    }

    /**
     * 貸出・返却画面
     * @param $id
     * @return Application|Factory|View
     */
    public function loan($id)
    {
        $this->deniesForView(AssetInfo::class);
        $assetInfo = AssetInfo::find($id);
        $assetRentalLog = $assetInfo->AssetRentalLog()->orderBy('id', 'desc')->first();
        $productName = $assetInfo->type.'/'.$assetInfo->maker.'/'.$assetInfo->model;
        while(str_starts_with($productName,'/'))
            $productName = substr_replace($productName,'',0,1);
        while(str_ends_with($productName,'/'))
            $productName = substr_replace($productName,'',-1,1);
        $assetInfo->productName = $productName;
        return view('admin.companyassets.loan', compact('assetInfo', 'assetRentalLog'));
    }

    /**
     * 貸出・返却
     * @param $id
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function rental($id,Request $request)
    {
        $this->deniesForModify(AssetInfo::class);
        $loan_or_return_date=AssetRentalLog::select('loan_or_return_date')->where('asset_info_id',$id)->orderBy('id','desc')->first();
        $validatorArr = [
            'status' => ['bail','required','in:0,1'],
            'user' => ['bail','required'],
        ];
        if($request->status == 0){
            $loan_or_return_date=date_format($loan_or_return_date->loan_or_return_date,'Y-m-d');
            $validatorArr['loan_or_return_date'] = ['bail','required','after_or_equal:'.$loan_or_return_date];
            $msg = '当該設備は返却されました。';
        }else{
            $validatorArr['loan_or_return_date'] = 'required';
            $msg = '当該設備は貸出されました。';
        }
        $validator = Validator::make($request->all(),$validatorArr,[
            'user.required' => __('validation.required', ['attribute' => '利用者']),
            ]);
        if ($validator->fails()){
            return Reply::fail($validator->errors()->first());
        } else {
            $rental = $request->all();
            $rental['asset_info_id'] = $id;
            $rental['responsible_person'] = Auth::user()->id;
            AssetRentalLog::create($rental);
            return Reply::success($msg);
        }
    }

    /**
     * データテーブル用、貸出情報
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getAssetRentalLogs(Request $request)
    {
        $this->deniesForView(AssetInfo::class);
        $builder = AssetRentalLog::with('user_responsible')->orderBy('id','desc')
        ->when($request->asset_info_id != '', function ($query) use ($request) {
            $query->where('asset_info_id', '=', $request->asset_info_id);
        })->select('*');
        return datatables()
            ->eloquent($builder)
            ->editColumn(
                'loan_or_return_date',
                function ($row) {
                    return substr($row->loan_or_return_date, 0, 10);
                }
            )
            ->editColumn(
                'user',
                function ($row) {
                    return $row->user??'';
                }
            )
            ->editColumn(
                'responsible_person',
                function ($row) {
                    return $row->user_responsible->name??'';
                }
            )
            ->editColumn(
                'status',
                function ($row) {
                    switch ($row->status) {
                        case "0":
                            return "返却済";
                            break;
                        case "1":
                            return "貸出中";
                            break;
                        case "2":
                            return "廃棄";
                            break;
                    }
                }
            )
            ->editColumn('action',function ($row){
                $string = '<div class="dropdown dropdown-action">
                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">';
                $string .= '<a class="dropdown-item edit_client" href="#" onclick="toEditRental('.$row->id.')">
                                <i class="fa fa-pencil m-r-5"></i> '.__('Edit').'</a>';
                $string .= '<a class="dropdown-item" href="#" data-action="'. route('companyassets.delRental',$row->id)
                    .'" onclick="toDelRental(this)">
                                <i class="fa fa-trash-o m-r-5"></i> '.__('Delete').'</a>';
                $string .= '</div></div>';
                return $string;
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * 貸出情報を削除
     * @param $id
     * @return array|string[]
     */
    public function delRental($id){
        $this->deniesForModify(AssetInfo::class);
        $assetID = AssetRentalLog::find($id)->asset_info_id;
        AssetRentalLog::destroy($id);
        $assetInfo = AssetInfo::find($assetID);
        $asset = $assetInfo->AssetRentalLog()->orderBy('id', 'desc')->first();
        return Reply::success('貸出情報を削除しました。',['asset_status'=>$asset->status??0]);
    }

    /**
     * 貸出情報変更画面
     * @param $id
     * @return mixed
     */
    public function editRental($id){
        $this->deniesForView(AssetInfo::class);
        $rental = AssetRentalLog::find($id);
        $rental->date_str = substr($rental->loan_or_return_date,0,10);
        return $rental;
    }

    /**
     * 貸出情報変更
     * @param Request $request
     * @param $id
     * @return array|string[]
     */
    public function updateRental(Request $request,$id){
        $this->deniesForModify(AssetInfo::class);
        $rental = AssetRentalLog::find($id);
        $rental->update($request->all());
        return Reply::success('貸出情報を更新しました。',['asset_status'=>$asset->status??0]);
    }
}
