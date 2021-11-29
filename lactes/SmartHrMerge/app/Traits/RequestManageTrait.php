<?php

namespace App\Traits;

use App\Models\AccountsInvoice;
use App\Models\AccountsOrderConfirmation;
use App\Models\AdminSetting;
use App\Models\BankAccount;
use App\Models\Client;
use App\Models\File;
use App\Models\OurPositionType;
use App\Models\RequestSetting;
use App\Models\RequestSettingClient;
use App\Models\RequestSettingExtra;
use App\Models\RequestSettingGlobal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

trait RequestManageTrait{
    /**
     * 一覧表画面に遷移
     *
     * @param $settingID
     * @return array
     */
    public function getIndexArray($settingID)
    {
        $requestSettingExtra = RequestSettingExtra::select('currency','local_ip_addr','client_sort_type')->first();
        $requestSettingGlobal = RequestSettingGlobal::select('calendar_search_unit','position_type','tax_type')->find($settingID);
        $searchMode = $requestSettingGlobal->calendar_search_unit;
        $position = isset($_GET['re_position'])?$_GET['re_position']:$requestSettingGlobal->position_type;
        $re_period = isset($_GET['re_period'])?$_GET['re_period']:date('Y-m-01').'～'.date('Y-m-t');
        $re_cid = isset($_GET['re_client_id'])?$_GET['re_client_id']:'';
        $tax_type = $requestSettingGlobal->tax_type==0?'（税抜）':'（税込）';
        $client=Client::select('id','document_format')->where('id',Auth::user()->client_id)->first();
        return compact(['searchMode','position','requestSettingExtra','tax_type','re_period','re_cid','requestSettingGlobal','client']);
    }

    /**
     * 担当者名付け
     * @param $data
     * @return string
     */
    private function getProjectNameWithEmployee($data){
        $employee_name = '';
        $index = 0;
        foreach ($data as $temp){
            if($temp->employee_name!='') {
                if($index==0){
                    $employee_name.=str_replace(["　"," "], '',$temp->employee_name);
                    $index++;
                }else{
                    $employee_name.='...';
                    break;
                }
            }
        }
        return $employee_name;
    }

    /**
     * 請求管理「四つの書類」の作成画面へ送信するデータを配列に格納
     *
     * @param $settingID
     * @return array
     */
    public function getCreateArray($settingID)
    {
        $position = isset($_GET['re_position'])?$_GET['re_position']:'';
        $re_period = isset($_GET['re_period'])?$_GET['re_period']:'';
        $re_cid = isset($_GET['re_client_id'])?$_GET['re_client_id']:'';
        $client=Client::select('id','document_format')->where('id',Auth::user()->client_id)->first();
//        if(Gate::allows(AccountsInvoice::SELF_MODIFY)){
//            $array=$this->getShagaiInfoArr();
//            $bankAccounts=$array['bankAccounts'];
//            $bankAccountsUse=$array['bankAccountsUse'];
//            $requestSettingGlobal=$array['requestSettingGlobal'];
//            $requestSettingExtra=$array['requestSettingExtra'];
//        }else{
            $bankAccounts = BankAccount::with('BankAccountType')->get();
            $requestSettingGlobal = RequestSettingGlobal::find($settingID);
            $bankAccountsUse = BankAccount::with('BankAccountType')->where('id',$requestSettingGlobal->bank_account_id)->first();
            $requestSettingGlobal->remark_confirmation = RequestSettingGlobal::select('remark_start')->find(AccountsOrderConfirmation::SETTING_ID)->remark_start;
            $requestSettingExtra = RequestSettingExtra::first();
//        }
        $company_name = AdminSetting::select('company_name')->first()->company_name;
        $requestSettingGlobal['company_name'] = $company_name;
        return compact('bankAccounts','bankAccountsUse','requestSettingGlobal','requestSettingExtra','position','re_period','re_cid','client');
    }

    private function getShagaiInfoArr($client_id){
        $requestSettingClient=RequestSettingClient::where('client_id',$client_id)->first();
        $requestSettingExtra = RequestSettingExtra::first();
        $bankAccounts=[
            [
                'id'=>0,
                'bank_name'=>$requestSettingClient->bank_name,
                'branch_name'=>$requestSettingClient->branch_name,
                'branch_code'=>$requestSettingClient->branch_code,
                'account_type'=>$requestSettingClient->account_type,
                'account_name'=>$requestSettingClient->account_name,
                'account_num'=>$requestSettingClient->account_num
            ]
        ];
        $bankAccountsUse=[
            'id'=>0,
            'bank_name'=>$requestSettingClient->bank_name,
            'branch_name'=>$requestSettingClient->branch_name,
            'branch_code'=>$requestSettingClient->branch_code,
            'account_type_name'=>$requestSettingClient->BankAccountType->account_type_name,
            'account_name'=>$requestSettingClient->account_name,
            'account_num'=>$requestSettingClient->account_num
        ];
        $requestSettingGlobal=[
            'period'=>$requestSettingClient->period,
            'bank_account_id'=>0,
            'company_info'=>$requestSettingClient->company_info,
            'seal_file'=>$requestSettingClient->seal_file,
            'print_num'=>0,
            'remark_start'=>$requestSettingClient->remark_start,
            'create_month'=>$requestSettingClient->create_month,
            'create_day'=>$requestSettingClient->create_day,
            'remark_end'=>$requestSettingClient->remark_end,
            'project_name'=>$requestSettingClient->project_name,
            'work_place'=>$requestSettingClient->work_place,
            'payment_contract'=>$requestSettingClient->payment_contract,
            'use_init_val'=>$requestSettingClient->use_init_val,
            'use_seal'=>$requestSettingClient->use_seal,
        ];
        $requestSettingExtra->request_pay_date=$requestSettingClient->request_pay_date;
        $requestSettingExtra->request_pay_month=$requestSettingClient->request_pay_month;
        $requestSettingExtra->contract_type=$requestSettingClient->contract_type;
        $requestSettingExtra->contract_type_other_remark=$requestSettingClient->contract_type_other_remark;
        $array=[
            'bankAccounts'=>$bankAccounts,
            'bankAccountsUse'=>$bankAccountsUse,
            'requestSettingGlobal'=>$requestSettingGlobal,
            'requestSettingExtra'=>$requestSettingExtra
        ];
        return $array;
    }

    /**
     * 請求管理「四つの書類」の編集画面へ送信するデータを配列に格納
     *
     * @param $data
     * @param $settingID
     * @return array
     */
    public function getEditArray($data,$settingID){
        $position = isset($_GET['re_position'])?$_GET['re_position']:'';
        $re_period = isset($_GET['re_period'])?$_GET['re_period']:'';
        $re_cid = isset($_GET['re_client_id'])?$_GET['re_client_id']:'';
        $client=Client::select('id','document_format')->where('id',Auth::user()->client_id)->first();

//        if(Gate::allows(AccountsInvoice::SELF_MODIFY)){
//            $array=$this->getShagaiInfoArr();
//            $bankAccounts=$array['bankAccounts'];
//            $bankAccountsUse=$array['bankAccountsUse'];
//            $requestSettingGlobal=$array['requestSettingGlobal'];
//            $requestSettingExtra=$array['requestSettingExtra'];
//        }else{
            $bankAccounts = BankAccount::with('BankAccountType')->get();
            $bankAccountsUse = '';
            if(isset($data->bank_account_id)){
                $bankAccountsUse = BankAccount::with('BankAccountType')->where('id',$data->bank_account_id)->first();
            }
            $requestSettingGlobal = RequestSettingGlobal::find($settingID);
            $requestSettingExtra = RequestSettingExtra::first();
//        }
        $company_name = AdminSetting::select('company_name')->first()->company_name;
        $requestSettingGlobal['company_name'] = $company_name;
        return compact('data','bankAccounts','bankAccountsUse','requestSettingGlobal','requestSettingExtra','position','re_period','re_cid','client');
    }

    /**
     * 条件によって検索
     * @param $request
     * @param $model
     * @param string $dateField
     * @return mixed
     */
    public function getSearchResult($request,$model,$dateField = 'LEFT(`period`,10)'){
        $our_position_type = $request->our_position_type;
        $client_id = $request->client_id;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        return $records = $model::with('client:id,client_name')->with('file:id,type,path')
            ->when($client_id!=null,function($query) use ($client_id) {
                return $query->where('client_id', '=', $client_id);
            })
            ->when($startDate!=null,function($query) use ($dateField, $startDate, $endDate){
                $condition=$dateField." between '".$startDate."' and '".$endDate."'";
                $query->whereRaw($condition);
            })
            ->where("our_position_type", "=", $our_position_type);
    }

    /**
     * @param $period
     * @return string
     */
    public function getStartDateFromPeriod($period){
        return isset($period) ? mb_strcut($period, 0, 10) : "";
    }

    public function getClientLink($client_name){
        return '<a href="javascript:(0)" onclick="searchClient(this)">'.$client_name.'</a>';
    }

    public function getManageCode($model,$client_id,$date,$position)
    {
        $code = OurPositionType::select('our_position_type_initials')->where('id',$position)->first()->our_position_type_initials;
        $symbol = RequestSettingGlobal::select('symbol')->find($model::SETTING_ID)->symbol;
        $manage_code = $model::where('client_id', $client_id)
            ->where($model::CODE_FIELD, 'like', $code . $symbol . mb_substr($date, 0, 6) . '%')->max($model::CODE_FIELD);
        $num = $code . $symbol . mb_substr($date, 0, 6) . str_pad($client_id, 4,'0',STR_PAD_LEFT);
        if (isset($manage_code)) {
            $max=(int)substr($manage_code, -2, 2);
            $num.=str_pad($max+1,2,'0',STR_PAD_LEFT);
        } else
            $num .= "01";
        return ['code'=>$num];
    }

    public function copyAccounts($idArr,$model)
    {
        $code_field = $model::CODE_FIELD;
        foreach ($idArr as $id) {
            $copy = $model::find($id)->replicate();
            $period = str_replace('-','',$copy->period);
            $PJNO = $this->getPJNO($copy->client_id, $period, $copy->our_position_type);
            $copy->$code_field = $PJNO['code'];
            if (isset($copy->file_id)) {
                $copy->file_id = $this->copyWithLocalFile();
                $copy->project_name_or_file_name = '';
                $copy->save();
            } else {
                $copy->request_setting_id = $this->copyWithSetting($copy);
                $copy->save();
                $this->copyDetails($id,$copy->id,$model);
            }
        }
    }

    /**
     * @return int
     */
    public function copyWithLocalFile(){
        $file = new File();
        $file->path = '';
        $file->is_in_local = File::IN_LOCAL;
        $file->save();
        return $file->id;
    }

    public function copyWithSetting($data){
        $data->file_id = null;
        $requestSetting = RequestSetting::find($data->request_setting_id)->replicate();
        $requestSetting->push();
        return $requestSetting->id;
    }

    public function copyDetails($id,$newId,$model){
        $detail_table = $model::DETAIL_TABLE;
        $accountsDetails = $model::find($id)->$detail_table;
        $this->detailCopy($accountsDetails,$newId);
    }

}
