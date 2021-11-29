<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\AccountsEstimate;
use App\Models\AccountsEstimateDetail;
use App\Models\BankAccount;
use App\Models\Client;
use App\Models\File;
use App\Models\RequestSetting;
use App\Models\RequestSettingExtra;
use App\Models\RequestSettingGlobal;
use App\Rules\Amount;
use App\Rules\FileMimeType;
use App\Rules\Period;
use App\Traits\FillFileInfo;
use App\Traits\PCGateTrait;
use App\Traits\RequestManageTrait;
use App\Traits\ShowDatatable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class EstimatesController extends Controller{
    use RequestManageTrait,ShowDatatable,FillFileInfo,PCGateTrait;

    /**
     * インデックス画面
     * @return Application|Factory|View
     */
    public function index(){
        $this->deniesForView(AccountsEstimate::class);
        $array = $this->getIndexArray(AccountsEstimate::SETTING_ID);
        return view('admin.estimates.index',$array);
    }

    /**
     * 新規画面
     * @return Application|Factory|View
     */
    public function create(){
        $this->deniesForModify(AccountsEstimate::class);
        $array = $this->getCreateArray(AccountsEstimate::SETTING_ID);
        return view('admin.estimates.create',$array);
    }

    /**
     * 編集画面
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id){
        $this->deniesForView(AccountsEstimate::class);
        $data = AccountsEstimate::with(['accounts_estimate_detail'=>function($query){
            $query->orderBy('sort_index');
        }])->find($id);
        $array = $this->getEditArray($data,AccountsEstimate::SETTING_ID);
        return view('admin.estimates.edit',$array);
    }

    /**
     * データを格納
     * @param $AccountsEstimateDetail
     * @param $i
     * @param $request
     */
    public function fillDetail($AccountsEstimateDetail,$i,$request){
        $AccountsEstimateDetail->employee_name = $request->employee_name[$i];
        $AccountsEstimateDetail->project_name = $request->project_name[$i];
        $AccountsEstimateDetail->unit_price = $request->unit_price[$i];
        $AccountsEstimateDetail->total = $request->total[$i];
        $AccountsEstimateDetail->sort_index = $i;
        $AccountsEstimateDetail->save();
    }

    /**
     * 詳細インスタンス取得
     * @param $accounts_estimate_id
     * @return AccountsEstimateDetail
     */
    public function newDetail($accounts_estimate_id){
        $AccountsEstimateDetail = new AccountsEstimateDetail();
        $AccountsEstimateDetail->accounts_estimate_id = $accounts_estimate_id;
        return $AccountsEstimateDetail;
    }

    /**
     * バリデーション取得
     * @return array
     */
    public function getCommonValidator($id=0){
        return [
            'est_manage_code' => [Rule::unique('accounts_estimates')->whereNull('deleted_at')->ignore($id)],
            'client_id' => ['bail','required','exists:clients,id'],
            'period' => ['bail','required',new Period],
            'estimate_subtotal' => ['bail','required',new Amount('見積額')]
        ];
    }

    /**
     * バリデーション取得
     * @return array
     */
    public function getValidatorArr($id=0){
        return array_merge($this->getCommonValidator($id), [
//            'project_name_or_file_name' => ['bail','required','max:200'],
            'work_place' => ['bail','required','max:200'],
            'acceptance_place' => ['bail','required','max:200'],
            'employee_name.*' => ['bail','required','max:50'],
            'project_name.*' => ['bail','required','max:200'],
            'month.*' => ['bail','required','max:10'],
            'unit_price.*' => ['bail','required', new Amount('単金')],
            'bank_account_id' => ['bail','required'],
            'payment_contract' => ['bail','required','max:200'],
        ]);
    }

    /**
     * バリデーションメッセージ取得
     * @return array
     */
    public function getValidatorMessage(){
        return [
            'bank_account_id.required' => __('銀行情報を入力してください！'),
        ];
    }

    /**
     * 新規登録
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function store(Request $request){
        $this->deniesForModify(AccountsEstimate::class);
        $validator = Validator::make($request->all(), $this->getValidatorArr(),$this->getValidatorMessage());
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        DB::beginTransaction();
        $reqData = $request->all();
        $requestSetting = RequestSetting::create($reqData);
        $this->bankInfoSave($request->bank_account_id,$requestSetting->id);
        $reqData['request_setting_id'] = $requestSetting->id;
        $AccountsEstimate=AccountsEstimate::create($reqData);
        $accounts_estimate_id = $AccountsEstimate->id;
        for ($i=0; $i<count($request->employee_name);$i++){
            $AccountsEstimateDetail = $this->newDetail($accounts_estimate_id);
            $this->fillDetail($AccountsEstimateDetail,$i,$request);
        }
        DB::commit();
        return Reply::success(__('見積書が正常に追加されました。'));
    }

    /**
     * 変更
     * @param Request $request
     * @param $id
     * @return array|bool[]|string[]
     */
    public function update(Request $request,$id){
        $this->deniesForModify(AccountsEstimate::class);
        $validator = Validator::make($request->all(), $this->getValidatorArr($id),$this->getValidatorMessage());
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        DB::beginTransaction();
        $reqData = $request->all();
        if(!isset($request->use_init_val)) $reqData['use_init_val'] = 'off';
        if(!isset($request->use_seal)) $reqData['use_seal'] = 'off';
        $AccountsEstimate = AccountsEstimate::find($id);
        $AccountsEstimate->update($reqData);
        RequestSetting::find($AccountsEstimate->request_setting_id)->update($reqData);
        $this->bankInfoSave($request->bank_account_id,$AccountsEstimate->request_setting_id);
        AccountsEstimateDetail::where('accounts_estimate_id',$id)->whereNotIn('id', $request->accounts_estimate_detail_id)->delete();
        for($i=0;$i<count($request->accounts_estimate_detail_id);$i++){
            if($request->accounts_estimate_detail_id[$i]=='') $AccountsEstimateDetail = $this->newDetail($id);
            else $AccountsEstimateDetail = AccountsEstimateDetail::find($request->accounts_estimate_detail_id[$i]);
            $this->fillDetail($AccountsEstimateDetail,$i,$request);
        }
        DB::commit();
        return Reply::success(__('見積書が正常に更新されました。'));
    }

    /**
     * 銀行情報　新規と編集　保存
     * @param $bankId
     * @param $requestSettingId
     * @return void
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

    /**
     * データテーブル用
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function information(Request $request){
        $this->deniesForView(AccountsEstimate::class);
        $model = AccountsEstimate::class;
        $res = $this->getSearchResult($request,$model);
        $tax_type = RequestSettingGlobal::find($model::SETTING_ID)->tax_type;
        $res->with(['accounts_estimate_detail:id,accounts_estimate_id,employee_name'])->select('id','cname','period','est_manage_code','project_name_or_file_name','client_id','file_id','estimate_subtotal','estimate_total');
        return datatables()->of($res->get())
            ->editColumn('name',function ($row){
                return $this->getIdInput($row->id);
            })
            ->editColumn('period',function ($row){
                return $this->getStartDateFromPeriod($row->period);
            })->editColumn('est_manage_code',function ($row){
                return $this->getEditLink($row->est_manage_code);
            })
            ->editColumn('project_name_or_file_name',function($row){
                $accounts_estimate_details=$row->accounts_estimate_detail;
                if(sizeof($accounts_estimate_details)>0){
                    $employee_name = $this->getProjectNameWithEmployee($accounts_estimate_details);
                    return $row->project_name_or_file_name.'('.$employee_name.')';
                }else{
                    return $this->getFileLink($row->file_id,$row->project_name_or_file_name);
                }
//                return sizeof($accounts_estimate_detail)>0?($row->project_name_or_file_name.'('.$accounts_estimate_detail[0]->employee_name.')'):$this->getFileLink($row->file_id,$row->project_name_or_file_name);
            })
            ->editColumn('client_name',function ($row){
                return $this->getClientLink($row->cname);
            })
            ->editColumn('estimate_subtotal',function($row) use ($tax_type){
                if($tax_type == 0) $amount = $row->estimate_subtotal;
                else $amount = $row->estimate_total;
                return $this->showAmount($amount);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * 削除
     * @param Request $request
     * @return array|string[]
     */
    public function accountsDelete(Request $request){
        $this->deniesForModify(AccountsEstimate::class);
        AccountsEstimate::destroy($request->idArr);
        return Reply::success('選択された見積書は削除されました。');
    }

    /**
     * コピー処理
     * @param Request $request
     * @return array|string[]
     */
    public function accountsCopy(Request $request)
    {
        $this->deniesForModify(AccountsEstimate::class);
        DB::beginTransaction();
        $this->copyAccounts($request->idArr,AccountsEstimate::class);
        DB::commit();
        return Reply::success(__('選択された見積書はコピーされました。'));
    }

    /**
     * 詳細情報コピー処理
     * @param $accountsDetails
     * @param $copyId
     */
    public function detailCopy($accountsDetails,$copyId){
        foreach ($accountsDetails as $detail){
            $clone = $detail->replicate();
            $clone->accounts_estimate_id = $copyId;
            $clone->save();
        }
    }

    /**
     * 見積番号取得
     * @param $client_id
     * @param $date
     * @param $position
     * @return string[]
     */
    public function getPJNO($client_id,$date,$position){
        return array_merge($this->getManageCode(AccountsEstimate::class,$client_id,$date,$position),['calc_type'=>Client::select('calc_type')->find($client_id)->calc_type]);
    }

    /**
     * 見積番号取得
     * @return string[]
     */
    public function accountsGetPJNO(){
        $this->deniesForModify(AccountsEstimate::class);
        return $this->getPJNO($_POST["client_id"],$_POST["date"],$_POST["our_position_type"]);
    }

    /**
     * 見積書　アップロード
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function upload(Request $request){
        $this->deniesForModify(AccountsEstimate::class);
        $id = 0;
        if(array_key_exists('id',$_POST)) $id=$_POST['id'];
        $validator = Validator::make($request->all(), array_merge($this->getCommonValidator($id),[
//            'source' =>['bail','required_without:file_name','max_mb:10',new FileMimeType],
        ]),['required_without' => __('見積書のアップロードは必要となります！')]);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        //获取上传文件
        $isEdit = array_key_exists('id',$_POST);
        $estimate_subtotal = $_POST['estimate_subtotal'];
        $est_manage_code = $_POST['est_manage_code'];
        $currency = RequestSettingExtra::select('currency')->first()->currency;
        $tax_rate = RequestSettingExtra::select('tax_rate')->first()->tax_rate;
        $estimate_total = preg_replace('/[^0-9]/', '',$estimate_subtotal);
        $estimate_total = $currency.number_format($estimate_total*$tax_rate/100+$estimate_total);
        $fileInfo= $request->file('source');
        DB::beginTransaction();
        if(!$isEdit){
            $file=new File();
            $accounts_estimates = new AccountsEstimate();
            $accounts_estimates->save();
        }else{
            $accounts_estimates=AccountsEstimate::find($_POST['id']);
            $file=$accounts_estimates->file;
        }
        if(isset($fileInfo)){
            $cloud_request_period=RequestSettingExtra::select('id','cloud_request_period')->first()->cloud_request_period;
            if($cloud_request_period>0) $this->fillFileInfoWhenCloud($fileInfo,$file);
            else $this->fillFileInfoWhenLocal($fileInfo,$file);
            $file->path='request/['.str_pad($request->client_id,4,'0',STR_PAD_LEFT).']'.$request->cname.'/見積書/['.$est_manage_code.']'.$file->basename;
            if($cloud_request_period>0) Storage::disk('local')->put($file->path, file_get_contents($fileInfo->getRealPath()));

            $request['estimate_total']=$estimate_total;
        }
        $file->save();
        $request['project_name_or_file_name']=$file->basename;
        $request['file_id']=$file->id;
        $accounts_estimates->update($request->all());
        DB::commit();
        if(!$isEdit) return Reply::success(__('見積書は正常に追加されました。'));
        else return Reply::success(__('見積書は正常に更新されました。'));
    }
}
