<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\AccountsInvoice;
use App\Models\AccountsInvoiceDetail;
use App\Models\AdminSetting;
use App\Models\BankAccount;
use App\Models\BankAccountType;
use App\Models\Client;
use App\Models\ContractType;
use App\Models\EmailTemplate;
use App\Models\File;
use App\Http\Controllers\Controller;
use App\Models\RequestSetting;
use App\Models\RequestSettingExtra;
use App\Models\User;
use App\Rules\Amount;
use App\Rules\FileMimeType;
use App\Rules\Period;
use App\Traits\FillFileInfo;
use App\Traits\PCGateTrait;
use App\Traits\RequestManageTrait;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class InvoicesController extends Controller
{
    use RequestManageTrait,ShowDatatable,FillFileInfo,PCGateTrait;

    function index(){
        $this->deniesForView(AccountsInvoice::class);
        return view('admin.invoice.index',$this->getIndexArray(AccountsInvoice::SETTING_ID));
    }

    private function deniesForView($model){
        abort_if(Gate::none([$model::VIEW,$model::MODIFY,$model::SELF_MODIFY]),Response::HTTP_FORBIDDEN);
    }

    private function justAllowSelfModify(){
        return Gate::allows(AccountsInvoice::SELF_MODIFY) && Gate::denies(AccountsInvoice::MODIFY);
    }

    /**
     * datatable用のAjax
     * @param Request $request
     * @return false|string
     * @throws Exception
     */
    public function getInvoices(Request $request)
    {
        $this->deniesForView(AccountsInvoice::class);
        $position = $request['our_position_type'];
        if($this->justAllowSelfModify()){
            $request['client_id_when_out']=Auth::user()->client_id;
            $position=1;
        }
        $currency = RequestSettingExtra::select('currency')->first()->currency;
        $client_id_when_out = $request->client_id_when_out;
        $model = AccountsInvoice::class;
        if(!isset($client_id_when_out)){
            $invoices = $this->getSearchResult($request,$model,'created_date');
            $invoices->with('user:id,name')->whereIn('status',[0,1,2,4]);
        }else{
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $invoices = AccountsInvoice::with('file:id,type,path')->with('client')->with('user:id,name')->with('accounts_invoice_details:id,employee_name')->select('id','file_id','invoice_manage_code','project_name_or_file_name','cname',
                'invoice_total','pay_deadline','paid_total','status','created_date')->where('created_by_client_id',$client_id_when_out);
            if(isset($startDate)) $invoices->where('created_date', '>=', $startDate)->where('created_date', '<=', $endDate);
        }
        return datatables()->of($invoices->get())
            ->editColumn('select', function ($row){
                return $this->getIdInput($row->id);
            })
            ->editColumn('invoice_manage_code', function ($row){
//                if($this->justAllowSelfModify())
//                    return $row->invoice_manage_code;
                return $this->getEditLink($row->invoice_manage_code);
            })
            ->editColumn('project_name_or_file_name', function ($row) {
                $accounts_invoice_details=$row->accounts_invoice_details;
                if(sizeof($accounts_invoice_details)>0){
                    $employee_name = $this->getProjectNameWithEmployee($accounts_invoice_details);
                    return $row->project_name_or_file_name.'('.$employee_name.')';
                }else{
                    return $this->getFileLink($row->file_id,$row->project_name_or_file_name);
                }
//                return sizeof($accounts_invoice_details)>0?($row->project_name_or_file_name.'('.$accounts_invoice_details[0]->employee_name.')'):$this->getFileLink($row->file_id,$row->project_name_or_file_name);
            })
            ->editColumn('client_name', function ($row){return $this->getClientLink($row->cname);})
            ->editColumn('invoice_total', function ($row){

                return $this->showAmount($row->invoice_total);
            })
            ->editColumn('pay_deadline', function($row){
                $pay_deadline = str_replace(['年','月'],'-',$row->pay_deadline);
                return str_replace(['日'],'',$pay_deadline);
            })
            ->editColumn('paid_total', function ($row) use ($currency){
                $bankInfo='';
                if($row->our_position_type==1){
                    if(!isset($row->file)){
                        $bankInfo = $row->client->requestSettingClients->bank_name;
                        $bankInfo .= "　".$row->client->requestSettingClients->branch_name;
                        $bankInfo .= "　(".$row->client->requestSettingClients->branch_code.")";
                        $bankInfo .= "　".$row->client->requestSettingClients->BankAccountType->account_type_name;
                        $bankInfo .= "　".$row->client->requestSettingClients->account_num;
                        $bankInfo .= "　".$row->client->requestSettingClients->account_name;
                    }
                }
                return '<input able-tab="1" data-sort="'.preg_replace('/[^0-9]/','',$row->paid_total).'" class="disable-input amount text-right" style="width: 100%" maxlength="8" size="10" name="paid_total" value="'.(!empty($row->paid_total)?$row->paid_total:$currency.'0').'" data-bank="'.$bankInfo.'"/>';
            })
            ->editColumn('employee_name', function ($row){
                $accounts_invoice_details=$row->accounts_invoice_details;
                return sizeof($accounts_invoice_details)>0?$accounts_invoice_details[0]->employee_name:'';
            })
            ->editColumn('status', function ($row) use ($position){
                $statusInfo=[
                    0=>($this->justAllowSelfModify())?'承認済':($position==1?'未支払':'未入金'),
                    1=>($this->justAllowSelfModify())?'承認済':($position==1?'支払済':'入金済'),
                    2=>($this->justAllowSelfModify())?'承認済': '<span style="color:#ff0000">要修正</span>',
                    3=>'作成中',
                    4=>'<span style="color:red">承認待</span>',
                ];
                $status=isset($row->status)?$row->status:0;
                return $statusInfo[$status];
            })
            ->editColumn('user_name', '{{isset($user)?$user["name"]:""}}')
            ->escapeColumns([])//文字列に変換できる列の設定、できない場合はHTMLタグのままで表示する
            ->make(true);//sql文の前処理
    }

    /**
     * 最新の請求番号を取得
     * @param $client_id
     * @param $date
     * @param $position
     * @return array
     */
    public function getPJNO($client_id,$date,$position)
    {
        return array_merge($this->getManageCode(AccountsInvoice::class,$client_id,$date,$position),['calc_type'=>Client::select('calc_type')->find($client_id)->calc_type]);
    }

    public function accountsGetPJNO(){
        $this->deniesForModify(AccountsInvoice::class);
        if($this->justAllowSelfModify()) $_POST["client_id"] = Auth::user()->client_id;
        return $this->getPJNO($_POST["client_id"],$_POST["date"],$_POST["our_position_type"]);
    }

    private function deniesForModify($model){
        abort_if(Gate::none([$model::MODIFY,$model::SELF_MODIFY]),Response::HTTP_FORBIDDEN);
    }

    /**
     * 新規作成画面に遷移
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->deniesForModify(AccountsInvoice::class);
        $contractTypes = ContractType::all();
        $client=Client::select('id','document_format')->where('id',Auth::user()->client_id)->first();
        if(Gate::allows(AccountsInvoice::SELF_MODIFY)){
            $position = isset($_GET['re_position'])?$_GET['re_position']:'';
            $re_period = isset($_GET['re_period'])?$_GET['re_period']:'';
            $re_cid = isset($_GET['re_client_id'])?$_GET['re_client_id']:'';
            $array=$this->getShagaiInfoArr($client->id);
            $bankAccounts=$array['bankAccounts'];
            $bankAccountsUse=$array['bankAccountsUse'];
            $requestSettingGlobal=$array['requestSettingGlobal'];
            $requestSettingExtra=$array['requestSettingExtra'];
            $company_name = AdminSetting::select('company_name')->first()->company_name;
            $requestSettingGlobal['company_name'] = $company_name;
            $status=1;
            return view('admin.invoice.create',compact('bankAccounts','bankAccountsUse','requestSettingGlobal','requestSettingExtra','position','re_period','re_cid','client','contractTypes','status'));
        }else{
            $array = $this->getCreateArray(AccountsInvoice::SETTING_ID);
            return view('admin.invoice.create',array_merge($array,['contractTypes'=>$contractTypes]));
        }

    }

    public function fillDetails($accountInvoiceDetail,$request,$i){
        $currency = RequestSettingExtra::select('currency')->first()->currency;
        $accountInvoiceDetail->employee_name = $request->employee_name[$i];
        $accountInvoiceDetail->period = $request->employee_period[$i];
        $accountInvoiceDetail->detail_content = $request->detail_content[$i];
        $price = $request->unit_price_commuting_sub[$i];
        if ($price == $currency."0") {
            $accountInvoiceDetail->is_outlay = false;
            $accountInvoiceDetail->unit_price = $request->unit_price_working_sub[$i];
        } else {
            $accountInvoiceDetail->is_outlay = true;
            $accountInvoiceDetail->unit_price = $request->unit_price_commuting_sub[$i];
        }
        $accountInvoiceDetail->sort_index = $i;
        $accountInvoiceDetail->save();
    }

    public function getCommonValidator($id){
        return [
            'invoice_manage_code' => [Rule::unique('accounts_invoices')->whereNull('deleted_at')->ignore($id)],
            'client_id' => ['bail','required','exists:clients,id'],
            'created_date' => ['bail','required','date'],
            'invoice_total' => ['bail','required',new Amount('請求金額')],
            'pay_deadline' => ['bail','required','regex:/^[0-9]{4}年[0-9]{2}月[0-9]{2}日$/'],
        ];
    }

    public function getValidatorArrOut($id=0){
        return array_merge($this->getCommonValidator($id),[
//            'project_name_or_file_name' => ['bail','required_without:file_name','max_mb:10',new FileMimeType],
        ]);
    }

    public function getValidatorArr($id=0){
        return array_merge($this->getCommonValidator($id),[
//            'project_name_or_file_name' => ['bail','required'],
            'period' => ['bail','required',new Period('期間')],
            'work_place' => ['bail','required','max:200'],
            'payment_contract' => ['bail','required','max:200'],
            'employee_name.0' => ['bail','required','max:50'],
            'employee_period.0' => ['bail','required',new Period],
            'detail_content.*' => ['bail','required','max:200'],
            'unit_price_commuting_sub.*' => 'required_without:unit_price_working_sub.*',
            'unit_price_working_sub.*' => 'required_without:unit_price_commuting_sub.*',
            'bank_account_id' => ['bail','required'],
        ]);
    }

    public function getValidatorMessage(){
        return [
            'created_date.required' => __('validation.required',['attribute' => '請求日']),
            'created_date.date' => __('validation.date',['attribute' => '請求日']),
            'project_name_or_file_name.required' => __('validation.required',['attribute' => '業務名']),
            'project_name_or_file_name.max' => __('validation.max',['attribute' => '業務名']),
            'project_name_or_file_name.required_without' => '請求書のアップロードは必要となります!',
            'period.required' => __('validation.required',['attribute' => '期間']),
            'employee_name.0.required' => __('一行目の作業担当者名は必要となります！'),
            'employee_name.0.max' => __('一行目の作業担当者は、50文字以下にしてください。！'),
            'employee_period.0.required' => __('一行目の作業期間は必要となります！'),
            'bank_account_id.required' => __('銀行情報を入力してください！'),
        ];
    }

    /**
     * データベースに登録
     * @param Request $request
     * @return array|string[]
     */
    public function store(Request $request)
    {
        $this->deniesForModify(AccountsInvoice::class);
        $temp = $request->all();
        if($this->justAllowSelfModify()){
            $temp['client_id'] = Auth::user()->client_id;
            $temp['cname'] = Auth::user()->client->client_name;
            $temp['status'] = 3;
            $temp['our_position_type'] = 1;
            $temp['bank_account_id'] = 0;
            $temp['created_by_client_id'] = Auth::user()->client_id;
        }
        $format = $request->file_format_type;
        $validateArray = $format == 0?$this->getValidatorArr():$this->getValidatorArrOut();
//        $validateArray =$this->getValidatorArr();
        $validator = Validator::make($temp, $validateArray,$this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
//        if ($validator->fails()) return Reply::fail("123".$request->file_format_type);
        DB::beginTransaction();
        if ($format == 0) {
            $requestSetting = RequestSetting::create($temp);
            $temp['request_setting_id'] = $requestSetting->id;
//            $this->bankInfoSave($request->bank_account_id,$requestSetting->id);
            $accountInvoice = Accountsinvoice::create($temp);
            for ($i = 0; $i < count($request->employee_name); $i++) {
                $accountInvoiceDetail = new AccountsInvoiceDetail;
                $accountInvoiceDetail->accounts_invoice_id = $accountInvoice->id;
                $this->fillDetails($accountInvoiceDetail, $request, $i);
            }
        } else {
            $fileInfo = $request->file("project_name_or_file_name");
            if (isset($fileInfo)) {
                $file = new File;
                $this->saveFile($fileInfo,$file,$temp);
                $temp['file_id'] = $file->id;
                $temp['project_name_or_file_name'] = $file->basename;
            }else{
                $file = new File;
                $file->save();
                $temp['file_id'] = $file->id;
                $temp['project_name_or_file_name'] = $file->basename;
            }
            Accountsinvoice::create($temp);
        }
        DB::commit();
        return Reply::success(__('請求書は正常に追加されました。'));
    }

    public function saveFile($fileInfo,$file,$temp){
        $cloud_request_period=RequestSettingExtra::select('id','cloud_request_period')->first()->cloud_request_period;
        if($cloud_request_period>0) $this->fillFileInfoWhenCloud($fileInfo,$file);
        else $this->fillFileInfoWhenLocal($fileInfo,$file);
        $file->path = 'request/['.str_pad($temp['client_id'],4,'0',STR_PAD_LEFT).']'.$temp['cname'].'/請求書/['.$temp['invoice_manage_code'].']'.$file->basename;
        if($cloud_request_period>0) Storage::disk('local')->put($file->path, file_get_contents($fileInfo->getRealPath()));
        $file->save();
    }

    /**
     * 編集画面に遷移
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $this->deniesForView(AccountsInvoice::class);
        $data = AccountsInvoice::with(['accounts_invoice_details'=>function($query){
            $query->orderBy('sort_index');
        }])->where("id", $id)->first();
//        if($this->justAllowSelfModify()) abort_if($data->file->user_id!=Auth::id(),Response::HTTP_FORBIDDEN);
        $contractTypes = ContractType::all();
        $client=Client::select('id','document_format')->where('id',Auth::user()->client_id)->first();
        if($data->created_by_client_id>0){
            $position = isset($_GET['re_position'])?$_GET['re_position']:'';
            $re_period = isset($_GET['re_period'])?$_GET['re_period']:'';
            $re_cid = isset($_GET['re_client_id'])?$_GET['re_client_id']:'';
            $array=$this->getShagaiInfoArr($data->created_by_client_id);
            $bankAccounts=$array['bankAccounts'];
            $bankAccountsUse=$array['bankAccountsUse'];
            $requestSettingGlobal=$array['requestSettingGlobal'];
            $requestSettingExtra=$array['requestSettingExtra'];
            $company_name = AdminSetting::select('company_name')->first()->company_name;
            $requestSettingGlobal['company_name'] = $company_name;
            $status=1;
            return view('admin.invoice.edit',compact('data','bankAccounts','bankAccountsUse','requestSettingGlobal','requestSettingExtra','position','re_period','re_cid','client','contractTypes','status'));
        }else{
            $array = $this->getEditArray($data,AccountsInvoice::SETTING_ID);
        }
        $client_id=Auth::user()->client_id;
        if($client_id>0 && $data->status!=4){
            $data->new_notice=0;
            $data->save();
        }
        return view('admin.invoice.edit',array_merge($array,['contractTypes'=>$contractTypes]));
    }

    /**
     * データベースに更新
     *
     * @param Request $request
     * @return array|string[]
     */
    public function update(Request $request)
    {
        $this->deniesForModify(AccountsInvoice::class);
        $accountInvoice = AccountsInvoice::find($request->id);
        $format = $accountInvoice->file_format_type;
        $temp = $request->all();
        if($accountInvoice->created_by_client_id!=Auth::user()->client_id){
            return Reply::fail('この請求書が編集できない。');
        }
        if($this->justAllowSelfModify()) {
//            abort_if($accountInvoice->file->user_id!=Auth::id(),Response::HTTP_FORBIDDEN);
            abort_if($accountInvoice->status!=3,Response::HTTP_FORBIDDEN);
            $temp['client_id'] = Auth::user()->client_id;
            $temp['cname'] = Auth::user()->client->client_name;
            $temp['status'] = 3;
            $temp['our_position_type'] = 1;
            $temp['bank_account_id'] = 0;
            $temp['created_by_client_id'] = Auth::user()->client_id;
        }
        $validateArray = $format == 0?$this->getValidatorArr($accountInvoice->id):$this->getValidatorArrOut($accountInvoice->id);
        $validator = Validator::make($temp, $validateArray,$this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());

        DB::beginTransaction();
        if ($format == 0) {
            $idArr = array();
            for ($i = 0; $i < count($request->detail_content); $i++) {
                if (!isset($request->account_invocie_detail_id) || $request->account_invocie_detail_id[$i] == "") {
                    $accountInvoiceDetail = new AccountsInvoiceDetail;
                    $accountInvoiceDetail->accounts_invoice_id = $accountInvoice->id;
                } else {
                    $account_invocie_detail_id = $request->account_invocie_detail_id[$i];
                    $accountInvoiceDetail = AccountsInvoiceDetail::find($account_invocie_detail_id);
                }
                $this->fillDetails($accountInvoiceDetail, $request, $i);
                array_push($idArr, $accountInvoiceDetail->id);
            }
            AccountsInvoiceDetail::where('accounts_invoice_id', $request->id)->whereNotIn('id', $idArr)->delete();
            if (!isset($request->use_init_val)) $temp['use_init_val'] = 'off';
            if (!isset($request->use_seal)) $temp['use_seal'] = 'off';
            RequestSetting::find($accountInvoice->request_setting_id)->update($temp);
//            $this->bankInfoSave($request->bank_account_id,$accountInvoice->request_setting_id);
        } else {
            $fileInfo = $request->file("project_name_or_file_name");
            if (isset($fileInfo)) {
                $file = File::find($accountInvoice->file_id);
                $this->saveFile($fileInfo,$file,$temp);
                $temp['file_id'] = $file->id;
                $temp['project_name_or_file_name'] = $file->basename;
            }
        }
        $accountInvoice->update($temp);
        DB::commit();
        return Reply::success(__('請求書は正常に更新されました。'));
    }

    /**
     * 銀行情報　新規と編集　保存
     * @param $id
     * @param  $requestSetting
     * @return
     */
    private function bankInfoSave($bankId,$requestSettingId){
        if($bankId!=0){
            $bankInfo=BankAccount::find($bankId);
            $bankInfoSave=$bankInfo->only($bankInfo->getFillable());
            $bankInfoSave['account_type_name']=$bankInfo->BankAccountType->account_type_name;
            $requestSetting=RequestSetting::find($requestSettingId);
            $requestSetting->update($bankInfoSave);
        }
        return;
    }
    public function delInvoiceClient($id)
    {
        $this->deniesForSelfModify(AccountsInvoice::class);
        $accountInvoice= AccountsInvoice::find($id);
        abort_if($accountInvoice->status!=3,Response::HTTP_FORBIDDEN);
        DB::beginTransaction();
        $accountInvoice->delete();
        if(isset($accountInvoice->file_id)){
            if(Storage::disk('local')->exists($accountInvoice->file->path)){
                Storage::disk('local')->delete($accountInvoice->file->path);
            }
        }
        DB::commit();
        return Reply::success(__('請求書は削除されました.'));
    }

    private function deniesForSelfModify($model){
        abort_if(Gate::denies($model::SELF_MODIFY),Response::HTTP_FORBIDDEN);
    }

    private function deniesForManagerModify($model){
        abort_if(Gate::denies($model::MODIFY),Response::HTTP_FORBIDDEN);
    }

    /**
     * バッチデリート
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function delInvoice(Request $request)
    {
        $this->deniesForManagerModify(AccountsInvoice::class);
        $userIdArr=[];
        DB::beginTransaction();
        foreach ($request->idArr as $id) {
            $accountInvoice=AccountsInvoice::find($id);
            AccountsInvoice::destroy($id);
            $userId=$this->isOutsideCompany($id);
            if($userId!==0) {
                array_push($userIdArr,$userId);
            }
            if($accountInvoice->file_format_type==1){
                if(Storage::disk('local')->exists($accountInvoice->file->path)){
                    Storage::disk('local')->delete($accountInvoice->file->path);
                }
            }
        }
        DB::commit();
        if(sizeof($userIdArr)>0){
            try {
                $this->sendOutEmail('INVOICE_DELETE',$userIdArr);
            }catch (Exception $e) {
                return Reply::fail($e->getMessage());
            }
        }
        return Reply::success(__('請求書を正常に削除しました。'));
    }

    /**
     * 確認状態更新
     * @param Request $request
     * @return mixed
     */
    public function fillInvoice(Request $request)
    {
        $this->deniesForManagerModify(AccountsInvoice::class);
        $idArr = $request->idArr;
        $amountArr = $request->amountArr;
        $statusArr = $request->statusArr;
        if(isset($idArr)){
            $userIdArr=[];
            for ($i = 0; $i < count($idArr); $i++) {
                $accountInvoice = AccountsInvoice::find($idArr[$i]);
                if($accountInvoice->status!=0 && $accountInvoice->paid_total!=$amountArr[$i]){
                    $accountInvoice->approved_by_user_id = Auth::user()->id;
                }else if($accountInvoice->status==0){
                    $accountInvoice->approved_by_user_id = null;
                }
                if(isset($amountArr)) $accountInvoice->paid_total = $amountArr[$i];
                $accountInvoice->status = $statusArr[$i];

                DB::beginTransaction();
                if($request->action=='承認'){
                    $accountInvoice->new_notice=1;
                }
                $accountInvoice->save();
                $userId=$this->isOutsideCompany($idArr[$i]);
                if($userId!=0){
                    array_push($userIdArr,$userId);
                }

                DB::commit();
            }
            if($request->action=='承認'){
                try {
                    $this->sendOutEmail('INVOICE_CONFIRM',$userIdArr);
                }catch (Exception $e) {
                    return Reply::fail($e->getMessage());
                }
            }
            return Reply::success('請求書は正常に'.$request->action.'されました。',['userName'=>Auth::user()->name]);
        }else{
            return Reply::fail($request->action.'できる項目を選択してください！');
        }
    }

    /**
     * コピーして作成
     * @param Request $request
     * @return array|string[]
     */
    public function copyToCreate(Request $request)
    {
        $this->deniesForManagerModify(AccountsInvoice::class);
        DB::beginTransaction();
        foreach ($request->idArr as $id) {
            $accountInvoice = AccountsInvoice::with('accounts_invoice_details')->find($id)->replicate();
            $createdDate = $accountInvoice->created_date;
            $tempCreatedDate = Carbon::parse($createdDate)->addMonth(1);
            $endDate = Carbon::parse($createdDate)->endOfMonth()->format('Y-m-d');
            if($createdDate==$endDate || $tempCreatedDate->format('d')!=Carbon::parse($createdDate)->format('d'))
                $tempCreatedDate = Carbon::parse($createdDate)->firstOfMonth()->addMonth(1)
                    ->endOfMonth()->format('Y-m-d');
            else
                $tempCreatedDate = $tempCreatedDate->format('Y-m-d');
            $newTempCode=$this->getPJNO($accountInvoice->client_id, str_replace('-','',$tempCreatedDate), $accountInvoice->our_position_type);
            $accountInvoice->invoice_manage_code = $newTempCode['code'];
            $accountInvoice->created_date = $tempCreatedDate;
            $accountInvoice->paid_total = null;
            $accountInvoice->approved_by_user_id = null;
            $accountInvoice->status = 0;
            if ($accountInvoice->file_format_type==1) {
                $accountInvoice->file_id = $this->copyWithLocalFile();
                $accountInvoice->project_name_or_file_name  = '';
            }else{
                $accountInvoice->request_setting_id = $this->copyWithSetting($accountInvoice);
            }
            $accountInvoice->push();
            foreach ($accountInvoice->accounts_invoice_details as $temp){
                unset($temp->id);
                $accountInvoice->accounts_invoice_details()->create($temp->toArray());
            }
        }
        DB::commit();
        return Reply::success(__('請求書を正常にコービーしました。'));
    }

    public function approveRequest($id){
        $this->deniesForSelfModify(AccountsInvoice::class);
        $accountInvoice= AccountsInvoice::find($id);
        abort_if($accountInvoice->status!=3,Response::HTTP_FORBIDDEN);
        DB::beginTransaction();
        $accountInvoice->update([
            'status'=>4,
            'new_notice'=>2
            ]);
        try {
            $this->sendEmail('INVOICE_REQUEST');
        } catch (Exception $e) {
            return Reply::fail($e->getMessage());
        }
        DB::commit();
        return Reply::success(__('請求要請は正常に提出されました。'));
    }

    public function requestCallBack($id){
        $this->deniesForSelfModify(AccountsInvoice::class);
        $accountInvoice= AccountsInvoice::find($id);
        abort_if($accountInvoice->status!=4,Response::HTTP_FORBIDDEN,'請求要請が承認済でした、却下したい場合は我社に連絡してください。');
        DB::beginTransaction();
        $accountInvoice->update([
            'status'=>3,
            'new_notice'=>0
        ]);
        try {
            $this->sendEmail('INVOICE_CALLBACK');
        } catch (Exception $e) {
            return Reply::fail($e->getMessage());
        }
        DB::commit();
        return Reply::success(__('請求要請は正常に却下されました。'));
    }

    private function isOutsideCompany($id){
        $accountInvoice= AccountsInvoice::find($id);
        try{
            $user = $accountInvoice->file->user;
            if((isset($user->client_id))&&($user->client_id>0)){
                return $user->id;
            }
            return 0;
        } catch(Exception $e){
            return 0;
        }
    }

    private function getEmailInfo($userIdArr){
        $emailInfoArr=[];
        $emailToArr=[];
        $emailToNameArr=[];
        foreach ($userIdArr as $userId){
            $user=User::find($userId);
            array_push($emailToArr,$user->email);
            array_push($emailToNameArr,$user->name);
        }
        array_push($emailInfoArr,$emailToArr);
        array_push($emailInfoArr,$emailToNameArr);
        return $emailInfoArr;
    }

    private function getOutEmailInfo($userIdArr){
//        $emailInfoArr=[];
        $emailToArr=[];
        foreach ($userIdArr as $userId){
            $user=User::find($userId);
            $emailToArr=[];
            array_push($emailToArr,[$user->email,$user->name]);
        }
//        array_push($emailInfoArr,$emailToArr);
        return $emailToArr;
    }

    /**
     * @param $emailId
     * @throws Exception
     */
    private function sendEmail($emailId) {
        $adminSetting=AdminSetting::first();
        $userIdArr=[];
        foreach ($adminSetting->receiver_arr as $id){
            array_push($userIdArr,$id);
        }
        if(sizeof($userIdArr)==0) throw new \Exception('受信先なし、担当者までご連絡ください。',500);
        $emailInfoArr=$this->getEmailInfo($userIdArr);
        $fieldValues=[
            'COMPANYNAME'=>$adminSetting->company_short_name,
            'CLIENTNAME'=>Auth::user()->name
        ];
        EmailTemplate::prepareAndSendEmail($emailId, $emailInfoArr, $fieldValues,true);
    }

    /**
     * @param $emailId
     * @param $userIdArr
     * @throws Exception
     */
    private function sendOutEmail($emailId, $userIdArr) {
        $adminSetting=AdminSetting::first();
        $emailInfoArr=$this->getOutEmailInfo($userIdArr);
        $index = 0;
        foreach ($emailInfoArr as $emailInfo){
            $fieldValues=[
                'COMPANYNAME'=>$adminSetting->company_short_name,
                'CLIENTNAME'=>User::find($userIdArr[$index])->client->client_name
            ];
            EmailTemplate::prepareAndSendEmail($emailId, $emailInfo, $fieldValues,true);
            $index++;
        }
    }
}
