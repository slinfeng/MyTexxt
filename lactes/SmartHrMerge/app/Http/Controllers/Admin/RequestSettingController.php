<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\AccountsEstimate;
use App\Models\AccountsEstimateDetail;
use App\Models\AccountsInvoice;
use App\Models\AccountsInvoiceDetail;
use App\Models\AccountsOrder;
use App\Models\AccountsOrderConfirmation;
use App\Models\AdminSetting;
use App\Models\BankAccount;
use App\Models\BankAccountType;
use App\Models\Client;
use App\Models\ContractType;
use App\Models\EmailTemplate;
use App\Models\File;
use App\Models\FontFamilyType;
use App\Models\LetterOfTransmittal;
use App\Models\RequestSettingClient;
use App\Models\RequestSettingExtra;
use App\Models\RequestSettingGlobal;
use App\Models\User;
use App\Traits\PCGateTrait;
use Carbon\Carbon;
use Exception;
use Faker\Core\Number;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Console\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;
use Symfony\Component\HttpFoundation\Response;


class RequestSettingController extends Controller
{
    use PCGateTrait;

    /**
     * インデックス画面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
//        $this->deniesForView(RequestSettingGlobal::class);
        abort_if(Gate::none([AccountsInvoice::SELF_MODIFY,RequestSettingGlobal::VIEW,RequestSettingGlobal::MODIFY]) ,Response::HTTP_FORBIDDEN);
        $requestSettingGlobal = RequestSettingGlobal::all();
        $requestSettingExtra = RequestSettingExtra::first();
        $bankAccount = BankAccount::all();
        $bank_account_types = BankAccountType::all();
        $contractTypes = ContractType::all();
        $fontFamilyTypes = FontFamilyType::all();
        $mails=EmailTemplate::select()->whereIn('id',[4,5,6,7,8])->get();
        $users=User::whereHas('roles',function ($query){
            $query->whereHas('permissions',function ($q){
                $q->where('title',AccountsInvoice::MODIFY);
            });
        })->get();
        $requestSettingClient = '';
        $client='';
        if(Auth::user()->client_id>0){
            $requestSettingClient = RequestSettingClient::where('client_id',Auth::user()->client_id)->first();
            $client=Client::select('id','document_format')->where('id',Auth::user()->client_id)->first();
        }
        $receiver_arr=AdminSetting::first()->receiver_arr;
        return view('admin/requestSetting/index',compact("requestSettingGlobal","requestSettingExtra","bankAccount","bank_account_types",'contractTypes','fontFamilyTypes','mails','users','receiver_arr','requestSettingClient','client'));
    }

    /**
     * 編集　保存
     * @param $id
     * @param Request $request
     * @return array
     */
    public function update($id,Request $request){

        switch ($id){
            case -1:
                $this->deniesForModify(RequestSettingGlobal::class);
                $validator =  $this->commonEdit($request);
                break;
            case 0:
                $this->deniesForModify(RequestSettingGlobal::class);
                $validator =  $this->clientsEdit($request);
                break;
            case 1:
                $this->deniesForModify(RequestSettingGlobal::class);
                $validator =  $this->estimateEdit($request);
                break;
            case 2:
                $this->deniesForModify(RequestSettingGlobal::class);
                $validator =  $this->expenseEdit($request);
                break;
            case 3:
                $this->deniesForModify(RequestSettingGlobal::class);
                $validator =  $this->accountsOrderEdit($request);
                break;
            case 4:
                $this->deniesForModify(RequestSettingGlobal::class);
                $validator =  $this->invoiceEdit($request);
                break;
            case 5:
                $this->deniesForModify(RequestSettingGlobal::class);
                $validator =  $this->letterOfTransmittalEdit($request);
                break;
            case 6:
                $this->deniedForOutside(AccountsInvoice::class);
                $validator =  $this->outsideInvoiceEdit($request);
                break;
            default:
                abort(Response::HTTP_FORBIDDEN);
                exit();
        }
        $errMsg = $validator->errors()->first();
        if($errMsg!='') return Reply::fail($errMsg);
        return Reply::success(__('設定が正常に変更されました。'));
    }
   /**
    * 共通　保存
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function commonEdit($request){
        $temp = $request->all();
        $validator = Validator::make($temp, [
            'tax_rate' =>['bail','numeric', 'between:0,100'],
            'font_family_type_id' =>['bail','required', 'in:1,2'],
            'cloud_request_period' =>['bail','required', 'in:0,3,6,12'],
        ]);
        if ($validator->passes()){
            $requestSettingExtra = RequestSettingExtra::first();
            $requestSettingExtra->update($temp);
        }
        return $validator;
    }
    /**
     * 取引先　保存
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function clientsEdit($request){
        $validator = Validator::make($request->all(), [
            'calendar_search_unit0' => ['bail','required','in:0,1'],
            'client_sort_type' => ['bail','required','in:0,1'],
            'our_position_type' => ['bail','required','in:1,2'],
        ]);
        if ($validator->passes()) {
            DB::beginTransaction();
            $requestSettingGlobal = RequestSettingGlobal::find(Client::SETTING_ID);
            $requestSettingGlobal->calendar_search_unit = $request->calendar_search_unit0;
            $requestSettingGlobal->position_type = $request->our_position_type;
            $requestSettingGlobal->save();
            $requestSettingExtra = RequestSettingExtra::first();
            $requestSettingExtra->client_sort_type = $request->client_sort_type;
            $requestSettingExtra->save();
            DB::commit();
        }
        return $validator;
    }
    /**
     * 見積書　保存
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function estimateEdit($request){
        $messages = [
            'bank_account_id1.required' => __('口座番号を選択してください！！'),
            'create_month1.required_if' => __('作成日として本日を選択しない場合は、月を選択する必要があります！！'),
            'create_day1.required_if' => __('作成日として本日を選択しない場合は、日を選択する必要があります！！'),
            'seal_file1.dimensions' => __('正方形の画像をアップロードしてください。'),
        ];
        $validator = Validator::make($request->all(), [
            'create_month1' => ['bail','required_if:create_month_radio1,1','in:1,2'],
            'create_day1' => ['bail','required_if:create_month_radio1,1','min:1','max:31'],
            'bank_account_id1'=>['bail','required','exists:bank_accounts,id'],
            'period1' => ['bail','required','in:0,2'],

            'seal_file1'=>'dimensions:ratio=1/1',
            'calendar_search_unit1' => ['bail','required','in:0,1'],
            'use_init_val1' => ['bail','nullable','in:1'],
            'position_type1' => ['bail','required','in:1,2'],
            'tax_type1' => ['bail','required','in:0,1'],
            'use_seal1' => ['bail','nullable','in:1'],
        ],$messages);
        $validator->sometimes(['create_month1','create_day1'], 'different:0', function ($request) {
                return true;
        });
        if ($validator->passes()) {
            DB::beginTransaction();
            $requestSettingGlobal = RequestSettingGlobal::find(AccountsEstimate::SETTING_ID);
            $requestSettingGlobal->calendar_search_unit = $request->calendar_search_unit1;
            $requestSettingGlobal->position_type = $request->position_type1;
            if($request->create_month_radio1==0){
                $requestSettingGlobal->create_month =0;
                $requestSettingGlobal->create_day = 0;
            }else{
                $requestSettingGlobal->create_month =$request->create_month1;
                $requestSettingGlobal->create_day = $request->create_day1;
            }
            if(isset($request->use_init_val1)){
                $requestSettingGlobal->use_init_val = $request->use_init_val1;
            }else{
                $requestSettingGlobal->use_init_val =0;
            }
            if(isset($request->use_seal1)){
                $requestSettingGlobal->use_seal = $request->use_seal1;
            }else{
                $requestSettingGlobal->use_seal =0;
            }
            $requestSettingGlobal->remark_start = $request->remark_start1;
            $requestSettingGlobal->company_info = $request->company_info1;
            //********
            $fileinfo = $request->file("seal_file1");
            if (isset($fileinfo)) {
                if ($fileinfo->isValid()) {
                    $ext = $fileinfo->getClientOriginalExtension();
                    $name ='estimate_seal_file'.'.'.$ext;
                    $realPath = $fileinfo->getRealPath();
                    Storage::disk('electronicSeal')->put('/'.$name,file_get_contents($realPath));
                    $requestSettingGlobal->seal_file = '/electronicSeal/'.$name;
                }
            }
            //********
            $requestSettingGlobal->project_name = $request->project_name1;
            $requestSettingGlobal->tax_type = $request->tax_type1;
            $requestSettingGlobal->payment_contract = $request->payment_contract1;
            $requestSettingGlobal->work_place = $request->work_place1;
            $requestSettingGlobal->custom_title = $request->custom_title1;
            $requestSettingGlobal->custom_content = $request->custom_content1;
            $requestSettingGlobal->period = $request->period1;
            $requestSettingGlobal->acceptance_place = $request->acceptance_place1;
            $requestSettingGlobal->	bank_account_id = $request->bank_account_id1;
            $requestSettingGlobal->save();
            $requestSettingExtra = RequestSettingExtra::first();
            $requestSettingExtra->estimate_remark= $request->estimate_remark;
            $requestSettingExtra->save();
            DB::commit();
        }
        return $validator;
    }

    /**
     * 注文書　保存
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function expenseEdit($request){
        $messages = [
            'create_month2.required_if' => __('作成日として本日を選択しない場合は、月を選択する必要があります！！'),
            'create_day2.required_if' => __('作成日として本日を選択しない場合は、日を選択する必要があります！！'),
            'seal_file2.dimensions' => __('正方形の画像をアップロードしてください。'),
            'work_place2.required_if' => __(':otherが他の場合、:attributeを指定してください。'),
            'acceptance_place2.required_if' => __(':otherが他の場合、:attributeを指定してください。'),
            'expense_traffic_expence_paid_by.required_if' => __(':otherが他の場合、:attributeを指定してください。'),
        ];
        $validator = Validator::make($request->all(), [
            'create_month2' => ['bail','required_if:create_month_radio2,1','in:1,2'],
            'create_day2' => ['bail','required_if:create_month_radio2,1','min:1','max:31'],
            'period2' => ['bail','required','in:0,2'],
            'expense_delivery_files.*' => ['bail','required','in:0,1,2,3'],
            'work_place_val' => ['bail','required','in:0,1'],
            'work_place2' => 'required_if:work_place_val,0',
            'acceptance_place_val' => ['bail','required','in:0,1'],
            'acceptance_place2' => 'required_if:acceptance_place_val,0',
            'expense_traffic_expence_paid_by_val' => ['bail','required','in:0,1'],
            'expense_traffic_expence_paid_by' => 'required_if:expense_traffic_expence_paid_by_val,0',
            'print_num2.*' => ['bail','nullable','in:0,1,2'],
            'seal_file2'=>'dimensions:ratio=1/1',
            'calendar_search_unit2' => ['bail','required','in:0,1'],
            'use_init_val2' => ['bail','nullable','in:1'],
            'position_type2' => ['bail','required','in:1,2'],
            'use_seal2' => ['bail','nullable','in:1'],
        ],$messages);

        if ($validator->passes()) {
            DB::beginTransaction();
            $requestSettingGlobal = RequestSettingGlobal::find(AccountsOrder::SETTING_ID);
            $requestSettingGlobal->calendar_search_unit = $request->calendar_search_unit2;
            $requestSettingGlobal->position_type = $request->position_type2;
            if($request->create_month_radio2==0){
                $requestSettingGlobal->create_month =0;
                $requestSettingGlobal->create_day = 0;
            }else{
                $requestSettingGlobal->create_month =$request->create_month2;
                $requestSettingGlobal->create_day = $request->create_day2;
            }
            if(isset($request->use_init_val2)){
                $requestSettingGlobal->use_init_val = $request->use_init_val2;
            }else{
                $requestSettingGlobal->use_init_val =0;
            }
            if(isset($request->use_seal2)){
                $requestSettingGlobal->use_seal = $request->use_seal2;
            }else{
                $requestSettingGlobal->use_seal =0;
            }
            $requestSettingGlobal->remark_start = $request->remark_start2;
            $requestSettingGlobal->remark_end = $request->remark_end2;
            $requestSettingGlobal->company_info = $request->company_info2;
            //********
            $fileinfo = $request->file("seal_file2");
            if (isset($fileinfo)) {
                if ($fileinfo->isValid()) {
                    $ext = $fileinfo->getClientOriginalExtension();
                    $name ='expense_seal_file'.'.'.$ext;
                    $realPath = $fileinfo->getRealPath();
                    $bool = Storage::disk('electronicSeal')->put('/'.$name,file_get_contents($realPath));
                    $requestSettingGlobal->seal_file = '/electronicSeal/'.$name;
                }
            }
            //********
            $requestSettingGlobal->project_name = $request->project_name2;
            $requestSettingGlobal->payment_contract = $request->payment_contract2;
            $requestSettingGlobal->work_place = $request->work_place2;
            $requestSettingGlobal->custom_title = $request->custom_title2;
            $requestSettingGlobal->custom_content = $request->custom_content2;
            $requestSettingGlobal->work_place_val = $request->work_place_val;
            $requestSettingGlobal->period = $request->period2;
            $requestSettingGlobal->acceptance_place = $request->acceptance_place2;
            $requestSettingGlobal->acceptance_place_val = $request->acceptance_place_val;

            $print_num2 = $this->checkbox2($request->print_num2);
            $requestSettingGlobal->	print_num = $print_num2;
            $requestSettingGlobal->save();
            $requestSettingExtra = RequestSettingExtra::first();
            $requestSettingExtra->project_content= $request->project_content;

            $expense_delivery_files = $this->checkbox3($request->expense_delivery_files);
            $requestSettingExtra->expense_delivery_files = $expense_delivery_files;
            $requestSettingExtra->expense_traffic_expence_paid_by= $request->expense_traffic_expence_paid_by;
            $requestSettingExtra->expense_traffic_expence_paid_by_val= $request->expense_traffic_expence_paid_by_val;
            $requestSettingExtra->expense_outlay= $request->expense_outlay;
            $requestSettingExtra->expense_remark= $request->expense_remark;
            $requestSettingExtra->save();
            DB::commit();
        }
        return $validator;
    }

    /**
     * 注文請書　保存
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function accountsOrderEdit($request){
        $validator = Validator::make($request->all(), [
            'position_type3' => ['bail','required','in:1,2'],
            'calendar_search_unit3' => ['bail','required','in:0,1'],
            'period3' => ['bail','required','in:0,2'],
//            'company_info3' => ['bail','required'],
        ]);
        if ($validator->passes()) {
            $requestSettingGlobal = RequestSettingGlobal::find(AccountsOrderConfirmation::SETTING_ID);
            $requestSettingGlobal->position_type = $request->position_type3;
            $requestSettingGlobal->calendar_search_unit = $request->calendar_search_unit3;
            $requestSettingGlobal->remark_start = $request->remark_start3;
            $requestSettingGlobal->period = $request->period3;
//            $requestSettingGlobal->company_info = $request->company_info3;
            $requestSettingGlobal->save();
        }
        return $validator;
    }

    /**
     * 請求書　保存
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function invoiceEdit($request){
        $contract_type = ContractType::select('id')->max('id');
        $contract_type_val = $request->contract_type;
        $messages = [
            'bank_account_id4.not_in' => __('口座番号を選択してください！！'),
            'create_month4.required_if' => __('請求日として本日を選択しない場合は、月を選択する必要があります！！'),
            'create_day4.required_if' => __('請求日として本日を選択しない場合は、日を選択する必要があります！！'),
            'request_pay_month.required_if' => __('振込期限日を選択してください！！'),
            'request_pay_date.required_if' => __('振込期限日を選択してください！!'),
            'seal_file4.dimensions' => __('正方形の画像をアップロードしてください。'),
        ];
        $validator = Validator::make($request->all(), [
            'create_month4' => ['bail','required_if:create_month_radio4,1','in:1,2'],
            'create_day4' => ['bail','required_if:create_month_radio4,1','min:1','max:31'],
            'request_pay_month' => ['bail','required_if:request_pay_month_radio,1','in:1,2,3'],
            'request_pay_date' => ['bail','required_if:request_pay_month_radio,1','min:1','max:31'],
            'period4' => ['bail','required','in:0,1,2'],
            'bank_account_id4'=>['bail','required','exists:bank_accounts,id'],

            'seal_file4'=>'dimensions:ratio=1/1',
            'calendar_search_unit4' => ['bail','required','in:0,1'],
            'use_init_val4' => ['bail','nullable','in:1'],
            'position_type4' => ['bail','required','in:1,2'],
            'use_seal4' => ['bail','nullable','in:1'],

            'contract_type_other_remark' => [
                function ($attribute, $value, $fail) use ($contract_type,$contract_type_val){
                    if ($contract_type_val==$contract_type) {
                        if($value==''){
                            $fail('契約形態として「その他」を選択しない場合は、テキストボックスを入力する必要があります!');
                        }
                    }
                }],

        ],$messages);

        if ($validator->passes()) {
            DB::beginTransaction();
            $requestSettingGlobal = RequestSettingGlobal::find(AccountsInvoice::SETTING_ID);
            $requestSettingGlobal->calendar_search_unit = $request->calendar_search_unit4;
            $requestSettingGlobal->position_type = $request->position_type4;
            if($request->create_month_radio4==0){
                $requestSettingGlobal->create_month =0;
                $requestSettingGlobal->create_day = 0;
            }else{
                $requestSettingGlobal->create_month =$request->create_month4;
                $requestSettingGlobal->create_day = $request->create_day4;
            }
            if(isset($request->use_init_val4)){
                $requestSettingGlobal->use_init_val = $request->use_init_val4;
            }else{
                $requestSettingGlobal->use_init_val =0;
            }
            if(isset($request->use_seal4)){
                $requestSettingGlobal->use_seal = $request->use_seal4;
            }else{
                $requestSettingGlobal->use_seal =0;
            }
            $requestSettingGlobal->remark_start = $request->remark_start4;
            $requestSettingGlobal->remark_end = $request->remark_end4;
            $requestSettingGlobal->company_info = $request->company_info4;
            //********
            $fileinfo = $request->file("seal_file4");
            if (isset($fileinfo)) {
                if ($fileinfo->isValid()) {
                    $ext = $fileinfo->getClientOriginalExtension();
                    $name ='invoice_seal_file'.'.'.$ext;
                    $realPath = $fileinfo->getRealPath();
                    $bool = Storage::disk('electronicSeal')->put('/'.$name,file_get_contents($realPath));
                    $requestSettingGlobal->seal_file = '/electronicSeal/'.$name;
                }
            }
            //********
            $requestSettingGlobal->project_name = $request->project_name4;
            $requestSettingGlobal->tax_type = $request->tax_type4;
            $requestSettingGlobal->payment_contract = $request->payment_contract4;
            $requestSettingGlobal->work_place = $request->work_place4;
            $requestSettingGlobal->period = $request->period4;
            $requestSettingGlobal->acceptance_place = $request->acceptance_place4;
            $requestSettingGlobal->	bank_account_id = $request->bank_account_id4;
            $requestSettingGlobal->save();
            $requestSettingExtra = RequestSettingExtra::first();
            $requestSettingExtra->contract_type= $request->contract_type;

            if($request->contract_type==$contract_type){
                $requestSettingExtra->contract_type_other_remark= $request->contract_type_other_remark;
            }else{
                $requestSettingExtra->contract_type_other_remark='';
            }

            if($request->request_pay_month_radio==0){
                $requestSettingExtra->request_pay_month =0;
                $requestSettingExtra->request_pay_date =0;
            }else{
                $requestSettingExtra->request_pay_month =$request->request_pay_month;
                $requestSettingExtra->request_pay_date = $request->request_pay_date;
            }
            $requestSettingExtra->save();
            DB::commit();
        }
        return $validator;
    }

    /**
     * 送付状　保存
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function letterOfTransmittalEdit($request){
        $messages = [
            'vertical_distance.between' => __('縦距離の範囲が「20~50」mmです！'),
            'horizontal_distance.between' => __('横距離の範囲が「20~50」mmです！'),
        ];
        $validator = Validator::make($request->all(), [
            'vertical_distance' =>['bail','numeric', 'between:20,50',],
            'horizontal_distance' =>['bail','numeric', 'between:20,50',],
            'use_init_val5' => ['bail','nullable','in:1'],
            'use_seal5' => ['bail','nullable','in:1'],
        ],$messages);
        if ($validator->passes()) {
            DB::beginTransaction();
            $requestSettingGlobal = RequestSettingGlobal::find(LetterOfTransmittal::SETTING_ID);

            if(isset($request->use_init_val5)){
                $requestSettingGlobal->use_init_val = $request->use_init_val5;
            }else{
                $requestSettingGlobal->use_init_val =0;
            }
            if(isset($request->use_seal5)){
                $requestSettingGlobal->use_seal = $request->use_seal5;
            }else{
                $requestSettingGlobal->use_seal =0;
            }
            $requestSettingGlobal->remark_start = $request->remark_start5;
            $requestSettingGlobal->remark_end = $request->remark_end5;
            $requestSettingGlobal->company_info = $request->company_info5;

            $requestSettingGlobal->project_name = $request->project_name5;
            $requestSettingGlobal->save();
            $requestSettingExtra = RequestSettingExtra::first();
            $requestSettingExtra->vertical_distance= $request->vertical_distance;
            $requestSettingExtra->horizontal_distance= $request->horizontal_distance;
            $requestSettingExtra->save();
            DB::commit();
        }
        return $validator;
    }

    /**
     * 口座情報　新規追加と編集　保存
     * @param Request $request
     * @return array
     */
    public function bankInfoAddOrEdit(Request $request)
    {
        $this->deniesForModify(RequestSettingGlobal::class);
        $temp = $request->all();
        $validator = Validator::make($temp, [
            'bank_name' => 'required',
            'branch_name' => 'required',
            'branch_code' => ['bail','required','numeric'],
            'account_type' => ['bail','required','exists:bank_account_types,id'],
            'account_name' => ['required'],
            'account_num' => ['bail','required','numeric'],
        ]);

        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        DB::beginTransaction();
        $id=$request->id;
        if($id==''){
            //新規追加
            $bankAccount = new BankAccount();
            $bankAccount->save();
            $text='新し口座情報を追加しました。';
        }else{
            //編集
            if($this->bankAccountIsUsed($id)) return Reply::fail(__('当該口座情報は使用していますが、変更できません。'),[$id]);
            $bankAccount = BankAccount::find($id);
            $text='口座情報が正常に変更されました。';
        }
        $bankAccount->update($temp);
        $id=$bankAccount->id;
        DB::commit();
        return Reply::success(__($text),[$id]);
    }


    public function outsideInvoiceEdit(Request $request){
        $contract_type = ContractType::select('id')->max('id');
        $contract_type_val = $request->contract_type_outside;
        $messages = [
            'create_month_outside.required_if' => __('請求日として本日を選択しない場合は、月を選択する必要があります！！'),
            'create_day_outside.required_if' => __('請求日として本日を選択しない場合は、日を選択する必要があります！！'),
            'request_pay_month_outside.required_if' => __('振込期限日を選択してください！！'),
            'request_pay_date_outside.required_if' => __('振込期限日を選択してください！!'),
            'seal_file_outside.dimensions' => __('正方形の画像をアップロードしてください。'),
            'period_outside.in' => __('期間を選択してください！！'),
        ];
        $validator = Validator::make($request->all(), [
            'create_month_outside' => ['bail','required_if:create_month_radio_outside,1','in:1,2'],
            'create_day_outside' => ['bail','required_if:create_month_radio_outside,1','min:1','max:31'],
            'request_pay_month_outside' => ['bail','required_if:request_pay_month_radio_outside,1','in:1,2,3'],
            'request_pay_date_outside' => ['bail','required_if:request_pay_month_radio_outside,1','min:1','max:31'],
            'period_outside' => ['bail','nullable','in:0,1,2'],
            'seal_file_outside'=>['nullable','dimensions:ratio=1/1'],
            'use_init_val_outside' => ['bail','nullable','in:1'],
            'use_seal_outside' => ['bail','nullable','in:1'],
            'contract_type_other_remark_outside' => [
                function ($attribute, $value, $fail) use ($contract_type,$contract_type_val){
                    if ($contract_type_val==$contract_type) {
                        if($value==''){
                            $fail('契約形態として「その他」を選択しない場合は、テキストボックスを入力する必要があります!');
                        }
                    }
                }],

        ],$messages);

        if ($validator->passes()) {
            DB::beginTransaction();
            $requestSettingClient = RequestSettingClient::where('client_id',Auth::user()->client_id)->first();

            if($request->create_month_radio_outside==0){
                $requestSettingClient->create_month =0;
                $requestSettingClient->create_day = 0;
            }else{
                $requestSettingClient->create_month =$request->create_month_outside;
                $requestSettingClient->create_day = $request->create_day_outside;
            }
            if(isset($request->use_init_val_outside)){
                $requestSettingClient->use_init_val = $request->use_init_val_outside;
            }else{
                $requestSettingClient->use_init_val =0;
            }
            if(isset($request->use_seal_outside)){
                $requestSettingClient->use_seal = $request->use_seal_outside;
            }else{
                $requestSettingClient->use_seal =0;
            }
            $requestSettingClient->remark_start = $request->remark_start_outside;
            $requestSettingClient->remark_end = $request->remark_end_outside;
            $requestSettingClient->company_info = $request->company_info_outside;
            //********
            $fileinfo = $request->file("seal_file_outside");
            if (isset($fileinfo)) {
                if ($fileinfo->isValid()) {
                    $ext = $fileinfo->getClientOriginalExtension();
                    $name ='outside_seal_file'.'.'.$ext;
                    $realPath = $fileinfo->getRealPath();
                    $bool = Storage::disk('electronicSeal')->put('/'.Auth::user()->client_id.'/'.$name,file_get_contents($realPath));
                    $requestSettingClient->seal_file = '/electronicSeal/'.Auth::user()->client_id.'/'.$name;
                }
            }
            //********
            $requestSettingClient->project_name = $request->project_name_outside;
            $requestSettingClient->payment_contract = $request->payment_contract_outside;
            $requestSettingClient->work_place = $request->work_place_outside;
            $requestSettingClient->period = $request->period_outside;
            $requestSettingClient->contract_type= $request->contract_type_outside;

            if($request->contract_type_outside==$contract_type){
                $requestSettingClient->contract_type_other_remark= $request->contract_type_other_remark_outside;
            }else{
                $requestSettingClient->contract_type_other_remark='';
            }

            if($request->request_pay_month_radio_outside==0){
                $requestSettingClient->request_pay_month =0;
                $requestSettingClient->request_pay_date =0;
            }else{
                $requestSettingClient->request_pay_month =$request->request_pay_month_outside;
                $requestSettingClient->request_pay_date = $request->request_pay_date_outside;
            }
            $requestSettingClient->save();
            DB::commit();
        }
        return $validator;
    }
    /**
     * 口座情報 削除
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        $this->deniesForModify(RequestSettingGlobal::class);
        if($this->bankAccountIsUsed($id)) return Reply::fail(__('当該口座情報は使用していますが、削除できません。'));
        BankAccount::destroy($id);
        return Reply::success(__('当該口座情報を削除しました。'));
    }

    /**
     * ローカルサーバー用、請求ファイルをクラウドからローカルサーバーへ移行する
     * @return Application|Factory|View
     * @throws Exception
     */
    function changeFilePath(){
        $cloud_request_period=RequestSettingExtra::select('id','cloud_request_period')->first()->cloud_request_period;
        $deadline = new Carbon('-'.$cloud_request_period.' month');
        $this->requestsLocal(AccountsEstimate::select('id','file_id')->whereHas('file',function ($query){
            $query->where('is_in_local','!=',1);
        })->where('created_at','<',$deadline)->whereNotNull('file_id')->get());
        $this->requestsLocal(AccountsInvoice::select('id','file_id')->whereHas('file',function ($query){
            $query->where('is_in_local','!=',1);
        })->where('created_at','<',$deadline)->whereNotNull('file_id')->get());
        $this->requestsLocal(AccountsOrder::select('id','file_id')->whereHas('file',function ($query){
            $query->where('is_in_local','!=',1);
        })->where('created_at','<',$deadline)->whereNotNull('file_id')->get());
        $this->requestsLocal(AccountsOrderConfirmation::select('id','file_id')->whereHas('file',function ($query){
            $query->where('is_in_local','!=',1);
        })->where('created_at','<',$deadline)->whereNotNull('file_id')->get());
        return view('admin.attendances.closeTab');
    }

    function requestsLocal($requests){
        DB::beginTransaction();
        foreach ($requests as $request){
            $file = File::find($request->file_id);
            $file->update(['is_in_local'=>File::IN_LOCAL]);
            if(Storage::disk('local')->exists($file->path)){
                Storage::disk('local')->delete($file->path);
            }
        }
        DB::commit();
    }

    /**
     * @param $bankAccountId
     * @return bool
     */
    private function bankAccountIsUsed($bankAccountId){
        return (AccountsEstimate::where('bank_account_id',$bankAccountId)->count()+AccountsInvoice::where('bank_account_id',$bankAccountId)->count())>0;
    }

    /**
     * 見積書と請求書　口座情報選択ボックス 表示
     * @return BankAccount[]|Collection
     */
    public function bankInfoGet(){
        $this->deniesForView(RequestSettingGlobal::class);
        return BankAccount::all();
    }

    private function checkbox2($array){
        if(count($array)==1){
            $str = '00';
        }else{
            if(count($array)==2){
                if($array[1]==1){
                    $str = '10';
                }else{
                    $str = '01';
                }
            }else{
                $str = '11';
            }
        }
        return $str;
    }

    private function checkbox3($array){
        if(count($array)==1){
            $str='000';
        }else{
            if(count($array)==2){
                if($array[1]==1){
                    $str = '100';
                }else if($array[1]==2){
                    $str = '010';
                }else{
                    $str ='001';
                }
            }else if(count($array)==3){
                if($array[1]==1){
                    if($array[2]==2){
                        $str = '110';
                    }else{
                        $str ='101';
                    }
                }else{
                    $str ='011';
                }
            }else{
                $str = '111';
            }
        }
        return $str;
    }

}
