<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\AccountsInvoice;
use App\Models\AccountsOrder;
use App\Models\AccountsOrderConfirmation;
use App\Models\AdminSetting;
use App\Models\EmailTemplate;
use App\Models\File;
use App\Models\RequestSetting;
use App\Models\RequestSettingClient;
use App\Models\RequestSettingExtra;
use App\Models\RequestSettingGlobal;
use App\Models\User;
use App\Rules\Amount;
use App\Rules\FileMimeType;
use App\Rules\Period;
use App\Traits\FillFileInfo;
use App\Traits\PCGateTrait;
use App\Traits\RequestManageTrait;
use App\Traits\ShowDatatable;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\AccountsOrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    use RequestManageTrait,ShowDatatable,FillFileInfo,PCGateTrait;

    /**
     * インデックス
     * @return Application|Factory|View
     */
    //显示一览画面
    public function index()
    {
        $this->deniesForView(AccountsOrder::class);
        $array = $this->getIndexArray(AccountsOrder::SETTING_ID);
        return view('admin.expense.index',$array);
    }

    private function deniesForView($model){
        abort_if(Gate::none([$model::VIEW,$model::MODIFY,AccountsInvoice::SELF_MODIFY]),Response::HTTP_FORBIDDEN);
    }

    private function justAllowSelfModify(){
        return Gate::allows(AccountsInvoice::SELF_MODIFY);
    }

    /**
     * 获取 一览画面 情报
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function information(Request $request)
    {
        $this->deniesForView(AccountsOrder::class);
        $model = AccountsOrder::class;
        if($this->justAllowSelfModify()){
            $client_id = Auth::user()->client_id;
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $records=AccountsOrder::with('client:id,client_name')
                ->with('file:id,type,path')
                ->with(['accounts_order_detail:id,accounts_order_id,employee_name,unit_price'])
                ->select('id','cname','period','project_manage_code','project_name_or_file_name','client_id','file_id','estimate_subtotal','expense_status')
                ->when($client_id!=null,function($query) use ($client_id) {
                    return $query->where('client_id', '=', $client_id);
                })
                ->when($startDate!=null,function($query) use ($startDate, $endDate){
                    $condition="created_date between '".$startDate."' and '".$endDate."'";
                    $query->whereRaw($condition);
                })
                ->where("client_id", "=", $client_id)
                ->where("expense_status", "=", 2);
        }else{
            $res = $this->getSearchResult($request,$model);
            $records = $res->with(['accounts_order_detail:id,accounts_order_id,employee_name,unit_price'])->select('id','cname','period','project_manage_code','project_name_or_file_name','client_id','file_id','estimate_subtotal','expense_status');
        }
        return Datatables()::of($records->get())
            ->editColumn('name', function ($row){
                return $this->getIdInput($row->id);
            })
            ->editColumn('period', function ($row) {
                return $this->getStartDateFromPeriod($row->period);
            })
            ->editColumn('project_manage_code', function ($row) {
                return $this->getEditLink($row->project_manage_code);
            })
            ->editColumn('expense_status', function ($row) {
                $statusInfo=[
                    0=>'',
                    1=>'<span style="color:#ff0000">発注待ち</span>',
                    2=>'発注済',
                ];
                return $statusInfo[$row->expense_status];
            })
            ->editColumn('month_sum', function ($row) {
                if(isset($row->accounts_order_detail)){
                    $estimate_subtotal =preg_replace('/[^0-9]/','',$row->estimate_subtotal);
                    $unit_price =preg_replace('/[^0-9]/','',$row->accounts_order_detail->unit_price);
                    return $estimate_subtotal/$unit_price.'ヶ月';
                }else{
                    $period = $row->period;
                    list($dateStart, $dateEnd) = explode('～', $period);
                    list($sy, $sm,$sd) = explode('-', $dateStart);
                    list($ey, $em,$ed) = explode('-', $dateEnd);
                    return (round(((($ey-$sy)*12+($em-$sm))*30+$ed-$sd)/30*4)/4).'ヶ月';
                }
            })
            ->editColumn('project_name_or_file_name', function ($row) {
                $accounts_order_detail=$row->accounts_order_detail;
                if(isset($accounts_order_detail)){
                    $employee_name=str_replace(["　"," "], '',$accounts_order_detail->employee_name);
                    return $row->project_name_or_file_name.'('.$employee_name.')';
                }else{
                    return $this->getFileLink($row->file_id,$row->project_name_or_file_name);
                }
//                return isset($accounts_order_detail)?($row->project_name_or_file_name.'('.$accounts_order_detail->employee_name.')'):$this->getFileLink($row->file_id,$row->project_name_or_file_name);
            })
            ->editColumn('client_name', function ($row) {
                return $this->getClientLink($row->cname);
            })
            ->editColumn('estimate_subtotal', function ($row) {
                return $this->showAmount($row->estimate_subtotal);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     *　新規 跳转 新增 甲/乙 画面
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->deniesForModify(AccountsOrder::class);
        $array = $this->getCreateArray(AccountsOrder::SETTING_ID);
        return view('admin.expense.create',$array);
    }

    private function deniesForModify($model){
        abort_if(Gate::none([$model::MODIFY]),Response::HTTP_FORBIDDEN);
    }

    /**
     * 編集　跳转 编辑 画面
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $this->deniesForView(AccountsOrder::class);
        $data = AccountsOrder::with(['accounts_order_detail'])->find($id);
        $array = $this->getEditArray($data,AccountsOrder::SETTING_ID);
        $requestSettingGlobal=$array['requestSettingGlobal'];
        if($this->justAllowSelfModify()){
            $requestSettingGlobal['remark_confirmation'] = $requestSettingClient=RequestSettingClient::where('client_id',Auth::user()->client_id)->first()->remark_start;
        }else{
            $requestSettingGlobal['remark_confirmation'] = RequestSettingGlobal::find(AccountsOrderConfirmation::SETTING_ID)->remark_start;
        }
        $client_id=Auth::user()->client_id;
        if($client_id>0){
            $data->new_notice=0;
            $data->save();
        }

        $requestSettingGlobal['company_name'] = AdminSetting::select('company_name')->first()->company_name;
        $array['requestSettingGlobal']=$requestSettingGlobal;
        return view('admin.expense.edit',$array);
    }

    /**
     * バリデーション取得
     * @return array
     */
    public function getCommonValidator($id=0){
        return [
            'project_manage_code' => [Rule::unique('accounts_orders')->whereNull('deleted_at')->ignore($id)],
            'client_id' => ['bail','required','exists:clients,id'],
            'period' => ['bail','required',new Period],
            'estimate_subtotal' => ['bail','required',new Amount('注文額（税抜）')]
        ];
    }

    /**
     * バリデーションメッセージ取得
     * @return array
     */
    public function getCommonMessage(){
        return [
            'estimate_subtotal.required' => __('validation.required', ['attribute' => '注文額（税抜）']),
        ];
    }

    /**
     * バリデーション取得
     * @return array
     */
    public function getValidatorArr($id=0){
        return array_merge($this->getCommonValidator($id), [
            'project_name' => ['bail','required','max:200'],
            'unit_price' => ['bail','required', new Amount('基本金額/発注金額')],
            'employee_name' => ['bail','required','max:50'],
            'outlay' => ['bail','required','max:200'],
        ]);
    }

    /**
     * バリデーションメッセージ取得
     * @return array
     */
    public function getValidatorMessage(){
        return array_merge($this->getCommonMessage(), [
            'project_name.required' => __('validation.required', ['attribute' => '業務名称']),
            'unit_price.required' => __('validation.required', ['attribute' => '基本金額/発注金額']),
            'project_name.max' => __('validation.max', ['attribute' => '業務名称']),
        ]);
    }

    /**
     * 登録
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function store(Request $request)
    {
        $this->deniesForModify(AccountsOrder::class);
        $validator = Validator::make($request->all(),$this->getValidatorArr(),$this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $temp = $request->all();
        $temp['project_name_or_file_name'] = $_POST['project_name'];
        $users=User::where('client_id',$temp['client_id'])->count();
        if($temp['our_position_type']==1 && $users>0){
            $temp['expense_status']=1;
        }else{
            $temp['expense_status']=0;
        }
        DB::beginTransaction();
        $requestSetting = RequestSetting::create($temp);
        $temp['request_setting_id'] = $requestSetting->id;
        $accountsOrder = AccountsOrder::create($temp);
        $temp['accounts_order_id'] = $accountsOrder->id;
        $temp = $this->accountsAddOrUpdateOperation($temp);
        AccountsOrderDetail::create($temp);
        DB::commit();
        return Reply::success(__('注文書が正常に追加されました。'));
    }

    /**
     * 更新
     * @param $id
     * @param Request $request
     * @return array|bool[]|string[]
     */
    //编辑
    public function update($id,Request $request)
    {
        $this->deniesForModify(AccountsOrder::class);
        $validator = Validator::make($request->all(), $this->getValidatorArr($id),$this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $temp = $request->all();
        $accountsOrder = AccountsOrder::find($id);
        $temp = $this->accountsAddOrUpdateOperation($temp);
        DB::beginTransaction();
        $accountsOrder->accounts_order_detail()->first()->update($temp);
        $temp['project_name_or_file_name'] = $_POST['project_name'];
        $accountsOrder->update($temp);
        $requestSetting = $accountsOrder->request_setting()->first();
        if (!isset($request->use_init_val)) $temp['use_init_val'] = 'off';
        if (!isset($request->use_seal)) $temp['use_seal'] = 'off';
        $requestSetting->update($temp);
        DB::commit();
        return Reply::success(__('注文書が正常に更新されました。'));
    }

    /**
     * 登録/更新前処理
     * @param $temp
     * @return mixed
     */
    public function accountsAddOrUpdateOperation($temp)
    {
        $temp['total'] = $temp['estimate_subtotal'];
        $delivery_files = $_POST['delivery_files'];
        $a = 0;
        $b = 0;
        $c = 0;
        for ($i = 0; $i < count($delivery_files); $i++) {
            if ($delivery_files[$i] == 1) {
                $a = 1;
            }
            if ($delivery_files[$i] == 2) {
                $b = 1;
            }
            if ($delivery_files[$i] == 3) {
                $c = 1;
            }
        }
        $delivery_file = $a . $b . $c;
        $temp['delivery_files'] = $delivery_file;
        return $temp;
    }

    /**
     * 自动生成PJNO
     * @param $client_id
     * @param $date
     * @param $position
     * @return string[]
     */
    public function getPJNO($client_id,$date,$position)
    {
        return $this->getManageCode(AccountsOrder::class,$client_id,$date,$position);
    }


    /**
     * PJNOを取得
     * @return string[]
     */
    public function accountsGetPJNO(){
        $this->deniesForModify(AccountsOrder::class);
        return $this->getPJNO($_POST["client_id"],$_POST["date"],$_POST["our_position_type"]);
    }


    /**
     * 削除
     * @param Request $request
     * @return array|string[]
     */
    public function accountsDelete(Request $request)
    {
        $this->deniesForModify(AccountsOrder::class);
        AccountsOrder::destroy($request->idArr);
        return Reply::success(__('選択された注文書を削除しました。'));
    }

    /**
     * コピー
     * @param Request $request
     * @return array|string[]
     */
    public function accountsCopy(Request $request)
    {
        $this->deniesForModify(AccountsOrder::class);
        DB::beginTransaction();
        $this->copyAccounts($request->idArr,AccountsOrder::class);
        DB::commit();
        return Reply::success(__('選択された注文書をコピーしました。'));
    }

    /**
     * 詳細内容をコピー
     * @param $accountsDetails
     * @param $copyId
     */
    public function detailCopy($accountsDetails,$copyId){
        $clone = $accountsDetails->replicate();
        $clone->accounts_order_id = $copyId;
        $clone->save();
    }

    /**
     * 上传文件  (乙： 添加 修改)
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function upload(Request $request)
    {
        $this->deniesForModify(AccountsOrder::class);
        $id = 0;
        if(array_key_exists('id',$_POST)) $id=$_POST['id'];
        $validator = Validator::make($request->all(), array_merge($this->getCommonValidator($id),[
//            'source' =>['bail','required_without:file_name','max_mb:10',new FileMimeType],
        ]),array_merge($this->getCommonMessage(),[
            'required_without' => __('注文書のアップロードは必要となります！'),
        ]));
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $accounts_orders = null;
        $temp = $request->all();
        //获取上传文件
        $fileInfo = $request->file('source');
        $isEdit = array_key_exists('id',$_POST);
        DB::beginTransaction();
        if(!$isEdit){
            $file=new File();
            $accounts_orders = new AccountsOrder();
            $users=User::where('client_id',$temp['client_id'])->count();
            if($users>0){
                $temp['expense_status']=1;
            }else{
                $temp['expense_status']=0;
            }
            $accounts_orders->save();
        }else{
            $accounts_orders=AccountsOrder::find($_POST['id']);
            $file=$accounts_orders->file;
        }
        $project_manage_code = $_POST['project_manage_code'];
        if (isset($fileInfo)) {
            $cloud_request_period=RequestSettingExtra::select('id','cloud_request_period')->first()->cloud_request_period;
            if($cloud_request_period>0) $this->fillFileInfoWhenCloud($fileInfo,$file);
            else $this->fillFileInfoWhenLocal($fileInfo,$file);
            $file->path='request/['.str_pad($request->client_id,4,'0',STR_PAD_LEFT).']'.$request->cname.'/注文書/['.$project_manage_code.']'.$file->basename;
            if($cloud_request_period>0) Storage::disk('local')->put($file->path, file_get_contents($fileInfo->getRealPath()));

        }
        $file->save();
        $temp['project_name_or_file_name'] = $file->basename;
        $temp['file_id'] = $file->id;
        $accounts_orders->update($temp);
        DB::commit();
        if(!$isEdit) return Reply::success(__('注文書は正常に追加されました。'));
        else return Reply::success(__('注文書は正常に更新されました。'));
//        return Reply::success($temp['expense_status']);
    }

    public function hacchuu(Request $request){
        $idArr=$request->idArr;
        foreach ($idArr as $id){
            DB::beginTransaction();
            $expense=AccountsOrder::find($id);
            $users=User::where('client_id',$expense->client_id)->get();
            $emailInfoArr=[];
            foreach ($users as $user){
                array_push($emailInfoArr,[$user->email,$user->name]);
            }
            $adminSetting=AdminSetting::first();
            $index = 0;
            foreach ($emailInfoArr as $emailInfo){
                $fieldValues=[
                    'COMPANYNAME'=>$adminSetting->company_short_name,
                    'CLIENTNAME'=>$users[$index]->client->client_name
                ];
                EmailTemplate::prepareAndSendEmail('EXPENSE_HACCHUU', $emailInfo, $fieldValues,true);
                $index++;
            }
            $expense->expense_status=2;
            $expense->new_notice=1;
            $expense->save();
            DB::commit();
        }
        return Reply::success(__('注文書は正常に発注されました。'));
    }

}
