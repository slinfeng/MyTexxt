<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\AccountsInvoice;
use App\Models\LetterOfTransmittal;

use App\Models\RequestSetting;
use App\Models\RequestSettingExtra;
use App\Models\RequestSettingGlobal;

use App\Models\User;
use App\Traits\PCGateTrait;
use App\Traits\ShowDatatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class LetterOfTransmittalController extends Controller
{
    use ShowDatatable;

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $this->deniesForView(LetterOfTransmittal::class);
        $requestSettingGlobal = RequestSettingGlobal::select('calendar_search_unit','position_type','tax_type')->find(6);
        $searchMode = $requestSettingGlobal->calendar_search_unit;
        $requestSettingExtra = RequestSettingExtra::select('local_ip_addr')->first();
        $position = isset($_GET['re_position'])?$_GET['re_position']:$requestSettingGlobal->position_type;
        $re_period = isset($_GET['re_period'])?$_GET['re_period']:date('Y-m-01').'～'.date('Y-m-t');
        $re_cid = isset($_GET['re_client_id'])?$_GET['re_client_id']:'';
        return view('admin.letterOfTransmittal.index',compact('requestSettingExtra','searchMode','position','re_period','re_cid'));
    }

    /**
     * @param $model
     */
    private function deniesForView($model){
        abort_if(Gate::none([$model::VIEW,$model::MODIFY,$model::SELF_MODIFY]), Response::HTTP_FORBIDDEN);
    }

    /**
     * @param $model
     */
    private function deniesForModify($model){
        abort_if(Gate::none([$model::MODIFY,$model::SELF_MODIFY]), Response::HTTP_FORBIDDEN);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->deniesForModify(LetterOfTransmittal::class);
        $requestSettingGlobal = RequestSettingGlobal::find(LetterOfTransmittal::SETTING_ID);
        $requestSettingExtra = RequestSettingExtra::first();
        return view('admin/letterOfTransmittal/create', compact('requestSettingGlobal', 'requestSettingExtra'));
    }

    /**
     * @return array
     */
    public function getValidatorArr(){
        return [
            'delivery_date' => ['bail','required','date'],
            'client_address' => ['bail','required'],
            'title' => ['bail','required'],
            'content' => ['bail','required'],
            'document_send' => ['bail','required'],
        ];
    }

    /**
     * @return array
     */
    public function getValidatorMessage(){
        return [
            'client_address.required' => __('validation.required', ['attribute' => '送付先情報']),
            'client_address.max' => __('validation.max', ['attribute' => '送付先情報']),
            'memo.required' => __('validation.required', ['attribute' => '送付状名称・メモ']),
            'memo.max' => __('validation.max', ['attribute' => '送付状名称・メモ']),
            'title.required' => __('validation.required', ['attribute' => '送付状タイトル']),
            'title.max' => __('validation.max', ['attribute' => '送付状タイトル']),
        ];
    }

    /**
     *
     * @param $request
     * @return mixed
     */
    public function fillArr($request){
        $reqData = $request->all();
        if (!isset($reqData['client_id'])) $reqData['client_id'] = 0;
        if (!isset($reqData['use_seal'])) $reqData['use_seal'] = 'off';
        if (!isset($reqData['use_init_val'])) $reqData['use_init_val'] = 'off';
        return $reqData;
    }

    /**
     * 登録
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function store(Request $request)
    {
        $this->deniesForModify(LetterOfTransmittal::class);
        $validator = Validator::make($request->all(), $this->getValidatorArr(), $this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $reqData = $this->fillArr($request);
        DB::beginTransaction();
        if($reqData['client_id']==0) $reqData['client_id']='';
        $requestSetting = RequestSetting::create($reqData);
        $reqData['request_setting_id'] = $requestSetting->id;
        $reqData['create_user_id']=auth()->user()->id;
        $letterOfTransmittal = LetterOfTransmittal::create($reqData);
        DB::commit();
        return Reply::success(__('送付状を追加しました.'), [$letterOfTransmittal->id]);
    }

    /**
     * 表示
     * @param LetterOfTransmittal $letterOfTransmittal
     */
    public function show(LetterOfTransmittal $letterOfTransmittal){}

    /**
     * 編集
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $this->deniesForView(LetterOfTransmittal::class);
        $letterOfTransmittal = LetterOfTransmittal::find($id);
        abort_if(!isset($letterOfTransmittal),Response::HTTP_FORBIDDEN);
        $createUserId=$letterOfTransmittal->create_user_id;
        abort_if((Gate::denies(LetterOfTransmittal::MODIFY)) && ($createUserId!==Auth::id()),Response::HTTP_FORBIDDEN);
        $requestSettingGlobal = RequestSettingGlobal::find(LetterOfTransmittal::SETTING_ID);
        $requestSettingExtra = RequestSettingExtra::all()->first();
        return view('admin/letterOfTransmittal/create', compact('requestSettingGlobal', 'letterOfTransmittal', 'requestSettingExtra'));
    }

    /**
     * 更新
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function update(Request $request)
    {
        $this->deniesForModify(LetterOfTransmittal::class);
        $reqData = $this->fillArr($request);
        $letterOfTransmittal = LetterOfTransmittal::find($reqData['id']);
        abort_if(!isset($letterOfTransmittal),Response::HTTP_FORBIDDEN);
        $createUserId=$letterOfTransmittal->create_user_id;
        abort_if((Gate::denies(LetterOfTransmittal::MODIFY)) && ($createUserId!==Auth::id()),Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(), $this->getValidatorArr(),$this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $requestSetting = RequestSetting::find($letterOfTransmittal->request_setting_id);
        if($reqData['client_id']==0) $reqData['client_id']='';
        DB::beginTransaction();
        $requestSetting->update($reqData);
        $letterOfTransmittal->update($reqData);
        DB::commit();
        $address = route('letteroftransmittal.index');
        return Reply::success(__('送付状を更新しました.'), [$address]);
    }

    /**
     * 削除
     * @param LetterOfTransmittal $letterOfTransmittal
     */
    public function destroy(LetterOfTransmittal $letterOfTransmittal){}

    /**
     * 送付状取得
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getLetterOfTransmittal(Request $request)
    {
        $this->deniesForView(LetterOfTransmittal::class);
        $requestMsg = $request->search_msg;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
//        $users=User::select('id')->where('client_id',0)->orWhereNull('client_id')->get()->toArray();
//        $userIdArr=[];
//        foreach ($users as $user){
//            array_push($userIdArr,$user['id']);
//        }
        $builder = LetterOfTransmittal::with('client')
            ->when(((Gate::allows(LetterOfTransmittal::SELF_MODIFY)) && (Gate::denies(LetterOfTransmittal::MODIFY))), function ($q) {
                return $q->where('create_user_id', auth()->user()->id);
            })
//            ->when(((Gate::denies(AccountsInvoice::SELF_MODIFY)) && (Gate::allows(LetterOfTransmittal::MODIFY))), function ($q) use ($userIdArr) {
//                return $q->whereIn('create_user_id', $userIdArr);
//            })
            ->when($requestMsg, function ($q) use ($requestMsg) {
                $q->whereRaw("(letter_of_transmittal.memo like concat('%{$requestMsg}%',''))");
            })
            ->when($startDate, function ($q) use ($startDate,$endDate) {
                $q->whereRaw("(letter_of_transmittal.delivery_date between '{$startDate}' and '{$endDate}')");
            })
            ->when($request->client_id, function ($q) use ($request) {
                return $q->where('client_id', $request->client_id);
            });
        $list = $builder->select('id','memo','delivery_date','client_id','client_address');
        return Datatables::of($list->get())
            ->addColumn('edit_id', function ($row) {
                return $this->getIdInput($row->id);
            })
            ->addColumn('client_abbreviation', function ($row) {
                return $row->client_id!=0 ? $row->client->client_abbreviation : '';
            })
            ->editColumn('client_name', function ($row) {
                return $row->client_id!=0 ? '<a href="javascript:(0)" onclick="searchClient(this)">'.$row->client_address.'</a>' : $row->client_address;
            })
            ->editColumn('memo', function ($row) {
                return $this->getEditLink($row->memo);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * 複数削除
     * @return array|string[]
     */
    public function accountsDelete()
    {
        $this->deniesForModify(LetterOfTransmittal::class);
        $idArr = $_POST['idArr'];
        LetterOfTransmittal::destroy($idArr);
        return Reply::success(__('選択された送付状は削除されました。'));
    }

    /**
     * 複数コピー
     * @return array|string[]
     */
    public function accountsCopy()
    {
        $this->deniesForModify(LetterOfTransmittal::class);
        $idArr = $_POST['idArr'];
        DB::beginTransaction();
        foreach ($idArr as $id) {
            $copy = LetterOfTransmittal::find($id)->replicate();
            $copy->delivery_date = Carbon::now()->format('Y-m-d');
            $copy->save();
        }
        DB::commit();
        return Reply::success(__('選択された送付状はコピーされました。'));
    }
}
