<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\AccountsInvoice;
use App\Models\AdminSetting;
use App\Models\AssetSetting;
use App\Models\Client;


use App\Models\OurPositionType;
use App\Models\Receipt;
use App\Models\RequestSetting;
use App\Models\RequestSettingExtra;
use App\Models\RequestSettingGlobal;
use App\Models\User;
use App\Rules\Amount;
use App\Traits\PCGateTrait;
use App\Traits\RequestManageTrait;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    use RequestManageTrait,PCGateTrait;

    /**
     * インデックス画面
     * @return Factory|View
     */
    public function index()
    {
        $this->deniesForView(Receipt::class);
        $currency = RequestSettingExtra::select('currency')->first()->currency;
        $searchMode = AssetSetting::first()->search_mode;
        return view('admin.receipt.index',compact('searchMode','currency'));
    }
    private function deniesForView($model){
        abort_if(Gate::none([$model::VIEW,$model::MODIFY,$model::SELF_MODIFY]), Response::HTTP_FORBIDDEN);
    }

    private function deniesForModify($model){
        abort_if(Gate::none([$model::MODIFY,$model::SELF_MODIFY]), Response::HTTP_FORBIDDEN);
    }

    public function getValidatorArr(){
        return [
            'name_or_memo' => ['bail','nullable','max:200'],
            'client_name' => ['bail','required','max:200'],
            'receipt_date' => ['bail','required','date'],
            'receipt_amount' => ['bail','required',new Amount('領収金額')],
            'document_end' => ['bail','required'],
            'company_info' => ['bail','required'],
        ];
    }

    public function getValidatorMessage(){
        return [
            'name_or_memo.max' => __('validation.max', ['attribute' => '領収書名称・メモ']),
            'client_name.required' => __('validation.required', ['attribute' => '領収方']),
            'client_name.max' => __('validation.max', ['attribute' => '領収方']),
            'document_end.required' => __('validation.required', ['attribute' => '但し内容']),
        ];
    }

    public function create()
    {
        $this->deniesForModify(Receipt::class);
        $assetSetting = AssetSetting::first();
        $requestSettingExtra = RequestSettingExtra::all()->first();
        return view('admin/receipt/create', compact('assetSetting','requestSettingExtra'));
    }


    public function store(Request $request)
    {
        $this->deniesForModify(Receipt::class);
        $validator = Validator::make($request->all(), $this->getValidatorArr(), $this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
            DB::beginTransaction();
            $reqData=$request->all();
            if(!isset($reqData['use_seal'])){
                $reqData['use_seal']='off';
            }
            if(!isset($reqData['use_init_val'])){
                $reqData['use_init_val']='off';
            }
            $requestSetting=RequestSetting::create($reqData);
            $reqData['request_setting_id']=$requestSetting->id;
            $reqData['create_user_id']=auth()->user()->id;
            $receipt=Receipt::create($reqData);
            DB::commit();
            return Reply::success(__('領収書を追加しました.'),[$receipt->id]);
    }

    public function edit($id)
    {
        $this->deniesForView(Receipt::class);
        $receipt=Receipt::find($id);
        abort_if(!isset($receipt),Response::HTTP_FORBIDDEN);
        $createUserId=$receipt->create_user_id;
        abort_if((Gate::denies(Receipt::MODIFY)) && ($createUserId!==Auth::id()),Response::HTTP_FORBIDDEN);
        $assetSetting = AssetSetting::first();
        $requestSettingGlobal = RequestSettingGlobal::find(5);
        $requestSettingExtra = RequestSettingExtra::all()->first();
        return view('admin/receipt/create', compact('requestSettingGlobal','receipt','requestSettingExtra','assetSetting'));
    }


    public function update(Request $request)
    {
        $this->deniesForModify(Receipt::class);
        $reqData=$request->all();
        $receipt=Receipt::find($reqData['id']);
        abort_if(!isset($receipt),Response::HTTP_FORBIDDEN);
        $createUserId=$receipt->create_user_id;
        abort_if((Gate::denies(Receipt::MODIFY)) && ($createUserId!==Auth::id()),Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(), $this->getValidatorArr(), $this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        DB::beginTransaction();
        if(!isset($reqData['use_seal'])){
            $reqData['use_seal']='off';
        }
        if(!isset($reqData['use_init_val'])){
            $reqData['use_init_val']='off';
        }
        $requestSetting=RequestSetting::find($receipt->request_setting_id);
        $requestSetting->update($reqData);
        $receipt->update($reqData);
        $address=route('receipt.index');
        DB::commit();
        return Reply::success(__('領収書を更新しました.'),[$address]);
    }


    public function getReceipt(Request $request)
    {
        $this->deniesForView(Receipt::class);
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $requestMsg=$request->search_msg;
        $users=User::select('id')->where('client_id',0)->orWhereNull('client_id')->get()->toArray();
        $userIdArr=[];
        foreach ($users as $user){
            array_push($userIdArr,$user['id']);
        }
        $builder = Receipt::with('client')
            ->when(((Gate::allows(Receipt::SELF_MODIFY))&&(Gate::denies(Receipt::MODIFY))), function ($q) {
                return $q->where('create_user_id', auth()->user()->id);
            })
            ->when(((Gate::denies(AccountsInvoice::SELF_MODIFY)) && (Gate::allows(Receipt::MODIFY))), function ($q) use ($userIdArr) {
                return $q->whereIn('create_user_id', $userIdArr);
            })
            ->when($startDate, function ($query) use ($startDate,$endDate) {
                return $query->where('receipt_date', '>=', $startDate)->where('receipt_date', '<=', $endDate);
            })
            ->when($requestMsg,function ($query) use ($requestMsg){
                $condition="(name_or_memo like concat('%{$requestMsg}%',''))";
                $query->whereRaw($condition);
            })
            ->when($request->client_id, function ($query) use ($request) {
                return $query->where('client_id', $request->client_id);
            });

        $list = $builder->get();
        return Datatables::of($list)
            ->addColumn('edit_id', function ($row) {
                return '<input type="hidden" value="'.$row->id.'" name="id">';
            })
            ->addColumn('receipt_code', function ($row) {
                return '<a href="javascript:void(0)" data-href="'.route("receipt.edit", $row->id).'" onclick="toCreate(this)">' .str_pad($row->id,10,0, STR_PAD_LEFT). '</a>';
            })
            ->editColumn(
                'receipt_date',
                function ($row) {
                    return date('Y-m-d',strtotime($row->receipt_date));
                })
            ->editColumn('client_name', function ($row){
                    return isset($row->client_id)?'<a href="javascript:(0)" onclick="searchClient(this)">'.$row->client_name.'</a>':$row->client_name;
                })
            ->editColumn(
                'name_or_memo',
                function ($row) {
                   $memo=empty($row->name_or_memo)?'未入力':$row->name_or_memo;
                    return '<a href="javascript:void(0)" data-href="'.route("receipt.edit", $row->id).'" onclick="toCreate(this)">' .$memo. '</a>';
                }
            )
            ->escapeColumns([])
            ->make(true);
    }

    //删除
    public function accountsDelete(){
        $this->deniesForModify(Receipt::class);
        DB::beginTransaction();
        $idArr = $_POST['idArr'];
        foreach ($idArr as $id){
            $receipt = Receipt::find($id);
            $receipt->delete();
        }
        DB::commit();
        return Reply::success('選択された領収書を削除しました。');
    }

    //copy
    public function accountsCopy()
    {
        $this->deniesForModify(Receipt::class);
        DB::beginTransaction();
        $idArr = $_POST['idArr'];
        foreach ($idArr as $id) {
            $copy = Receipt::find($id)->replicate();
            $copy->save();
        }
        DB::commit();
        return Reply::success('選択された領収書をコピーしました。');
    }

    public function accountsNum()
    {
        $minNum=0;
        $count=Receipt::count('*');
        $maxNum=Receipt::max('id');
        if($maxNum>$count){
            $maxNum=$count;
        }else{
            return $maxNum+1;
        }
        while((($maxNum-$minNum)>1)||(($maxNum-$minNum-$count)>1)){
            $midNum=(int)(($maxNum-$minNum)/2);
            $maxNum=($maxNum-$midNum);
            $count=Receipt::where('id','>',$minNum)->where('id','<=',$maxNum)->count('*');
            if($count===($maxNum-$minNum)){
                $minNum=$maxNum;
                $maxNum=$maxNum+$midNum;
            }
        }
        return $maxNum;
    }
}
