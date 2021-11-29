<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Constants\RedisKey;
use App\Models\AdminSetting;
use App\Models\BankAccountType;
use App\Models\Department;
use App\Models\EmployeeBank;
use App\Models\EmployeeBase;
use App\Http\Controllers\Controller;
use App\Models\EmployeeContact;
use App\Models\EmployeeDependentRelation;
use App\Models\EmployeeInsurance;
use App\Models\EmployeeStay;
use App\Models\File;
use App\Models\HireType;
use App\Models\HrSetting;
use App\Models\PositionType;
use App\Models\RequestSettingExtra;
use App\Models\ResidenceType;
use App\Models\RetireType;
use App\Models\Role;
use App\Models\User;
use App\Rules\Amount;
use App\Traits\NotifyTrait;
use App\Traits\PCAndMobileGateTrait;
use App\Traits\ShowDatatable;
use App\Traits\ThumbnailTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class EmployeeInfoController extends Controller
{
    use PCAndMobileGateTrait;
    use NotifyTrait,ThumbnailTrait;
    use ShowDatatable;

    /**
     * インデックス画面
     * @return Application|Factory|View
     */
    public function index()
    {
        $this->abortNotFoundForMobile();
        $this->deniesForPCView(EmployeeBase::class);
        $user_view = Auth::user()->user_view;
        if($user_view=='card') return view('admin.employees.indexCard',compact('user_view'));
        return view('admin.employees.indexList',compact('user_view'));
    }

    /**
     * インデックス画面　カード表示用
     * @return RedirectResponse
     */
    public function indexCard(){
        $user = Auth::user();
        $user->user_view='card';
        $user->save();
        return redirect()->route('employees.index');
    }

    /**
     * インデックス画面　リスト表示用
     * @return RedirectResponse
     */
    public function indexList()
    {
        $user = Auth::user();
        $user->user_view='list';
        $user->save();
        return redirect()->route('employees.index');
    }

    /**
     * 共通バリデーション取得
     * @return \string[][]
     */
    private function getCommonValidator(){
        $birthday = (date('Y')-18).'-'.date('m-d');
        return [
            'name_roman' => ['bail','required','max:50'],
            'name_phonetic' => ['bail','required','max:50'],
            'birthday' => ['bail','date','before_or_equal:'.$birthday],
            'sex' => ['bail','required','in:0,1,2'],
        ];
    }

    /**
     * バリデーション取得
     * @return array
     */
    private function getValidatorArr(){
        return array_merge($this->getCommonValidator(), [
            'department_type_id' => ['bail','numeric','exists:departments,id'],
            'hire_type_id' => ['bail','numeric','exists:hire_types,id'],
            'position_type_id' => ['bail','numeric','exists:position_types,id'],
        ]);
    }

    /**
     * バリデーションメッセージ取得
     * @return array
     */
    private function getValidatorMessage(){
        return [
            'name_phonetic.required' => __('「フリガナ」を入力してください！'),
            'name_roman.required' => __('「ローマ名」を入力してください！'),
            'employee_code.regex' => __('「社員番号」の範囲が「0001~9999」です！'),
            'birthday.before_or_equal' => __('「生年月日」は年齢制限の条件を満たしている必要があります。'),
            'date_hire.before_or_equal' => __('「入社日」は条件を満たしていない。'),
            'date_hire.after_or_equal' => __('「入社日」は年齢制限の条件を満たしている必要があります。'),
            'date_retire.before_or_equal' => __('「退社日」は条件を満たしていない。'),
            'date_retire.after_or_equal' => __('「退社日」は条件を満たしていない。'),
            'relationship_type.*.between' => __('「扶養親族」の「区分」を指定してください！'),
            'basic_pension_number.required' => __('「社会保険」を参加された場合、「基礎年金番号」を入力してください！'),
            'sign.required' => __('「社会保険」を参加された場合、「記号」を入力してください！'),
            'organize_number.required' => __('「社会保険」を参加された場合、「整理番号」を入力してください！'),
            'social_start_date.required' => __('「社会保険」を参加された場合、「資格取得日」を入力してください！'),
            'social_end_date.required' => __('「社会保険」を参加された場合、「基準額」を入力してください！'),
            'base_amount.required' => __('「社会保険」を参加された場合、「資格喪失日」を入力してください！'),
            'office_number.required' => __('「雇用保険」を参加された場合、「事業所番号」を入力してください！'),
            'insured_number.required' => __('「雇用保険」を参加された場合、「被保険者番号」を入力してください！'),
            'employment_start_date.required' => __('「雇用保険」を参加された場合、「雇用資格取得日」を入力してください！'),
            'employment_end_date.required' => __('「雇用保険」を参加された場合、「雇用資格喪失日」を入力してください！'),
            'department_type_id.numeric' => __('「部門」を指定してください！'),
            'hire_type_id.numeric' => __('「契約形態」を指定してください！'),
            'position_type_id.numeric' => __('「役職」を指定してください！'),
            'codeArr.*.regex'=>__('「社員番号」の範囲が「0001~9999」です！'),
        ];
    }

    /**
     * 登録
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function store(Request $request)
    {
        $this->deniesForPCModify(EmployeeBase::class);
        $age =  date("Y-m-d", strtotime("+18 years", strtotime($request->birthday)));
        $today = date('Y-m-d');
        $validator = Validator::make($request->all(),array_merge($this->getValidatorArr(),[
            'date_hire' => ['bail','date','before_or_equal:'.$today,'after_or_equal:'.$age,],
            'phone' => ['bail','nullable','regex:/^[0-9]{6,14}$/'],
        ]),$this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $reqData = $request->all();
        DB::beginTransaction();
        if($request->user_id==0){
            $user = User::create($reqData);
            $reqData['user_id']=$user->id;
        } else{
            $user = User::find($request->user_id);
        }
        $reqData['employee_code'] = $this->newEmployeeCode();
        $employeeBase=EmployeeBase::create($reqData);
        $employeeContacts=new EmployeeContact();
        $employeeContacts->employee_id=$employeeBase->id;
        $employeeContacts->phone=$request->phone;
        $employeeContacts->save();
        $employeeBank=new EmployeeBank();
        $employeeBank->employee_id=$employeeBase->id;
        $employeeBank->save();
        $employeeInsurance=new EmployeeInsurance();
        $employeeInsurance->employee_id=$employeeBase->id;
        $employeeInsurance->save();
        $employeeStay=new EmployeeStay();
        $employeeStay->employee_id=$employeeBase->id;
        $employeeStay->save();
        $roles=[];
        $updateUser=User::find($user->id);
        $updateUserRoles=$updateUser->roles;
        foreach ($updateUserRoles as $role){
            array_push($roles,$role->id);
        }
        array_push($roles,7);
        $roles=array_unique($roles);
        $updateUser->roles()->sync($roles);
        DB::commit();
        $this->historyAndModify($employeeBase->id,true);
        return Reply::success(__('Employee is added successfully.'));
    }

    /**
     * 詳細画面
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $this->abortNotFoundForMobile();
        $this->deniesForPCView(EmployeeBase::class);
        $employeeBase=EmployeeBase::find($id);
        $employeeDependentRelation = EmployeeDependentRelation::where('employee_id',$id)->orderBy('relationship_type', 'ASC')->get();
        $user=User::find($employeeBase->user_id);
        $bank_account_types = BankAccountType::all();
        $departments = Department::all();
        $hireType = HireType::all();
        $positionType = PositionType::all();
        $retireType = RetireType::all();
        $residenceType = ResidenceType::all();
        $currency = AdminSetting::first()->currency_symbol;
        $hrSetting = HrSetting::all()->first();
        return view('admin.employees.show', compact('employeeBase','employeeDependentRelation','user','bank_account_types','hireType','positionType','departments','retireType','residenceType','currency','hrSetting'));
    }

    /**
     * 扶養家族情報
     * @param $id
     * @return Application|Factory|View
     */
    public function relationInfo($id){
//        $this->deniesForPCView(EmployeeBase::class);
        $employeeDependentRelation = EmployeeDependentRelation::where('employee_id',$id)->orderBy('relationship_type', 'ASC')->get();
        return view('admin.employees.details.relation_card',compact('employeeDependentRelation'));
    }

    /**
     * 管理員修正中社員情報変更の有無判断
     * @param $id
     * @param $updateTime0
     * @param $updateTime1
     * @param $updateTime2
     * @param $updateTime3
     * @return bool
     */
    private function employeeIsModify($id,$updateTime0,$updateTime1,$updateTime2,$updateTime3){
        $baseUpdate = EmployeeBase::find($id)->updated_at;
        $bankUpdate = EmployeeBase::find($id)->EmployeeBank->updated_at;
        $contactsUpdate = EmployeeBase::find($id)->EmployeeContacts->updated_at;
        $stayUpdate = EmployeeBase::find($id)->EmployeeStay->updated_at;
        return ($updateTime0==$baseUpdate && $updateTime1==$bankUpdate && $updateTime2==$contactsUpdate && $updateTime3==$stayUpdate);
    }

    /**
     * 社員情報修正前処理（管理員）
     * @param Request $request
     * @return array|bool|bool[]|string[]
     */
    public function adminSubmitBefore(Request $request){
        $id = $request->id;
        $validator = $this->adminVerify($request);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $boo = $this->employeeIsModify($id,$request->updateTime0,$request->updateTime1,$request->updateTime2,$request->updateTime3);
        if ($boo){
            return $this->adminUpdate($id,$request);
        }else{
            return false;
        }
    }

    /**
     * 社員情報修正処理（管理員）
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function adminSubmit(Request $request){
        $id = $request->id;
        $validator = $this->adminVerify($request);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        return $this->adminUpdate($id,$request);
    }

    /**
     * 社員情報修正処理（管理員）
     * @param $id
     * @param Request $request
     * @return array|string[]
     */
    private function adminUpdate($id,Request $request){
//        $validator = $this->adminVerify($request);
//        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        DB::beginTransaction();
        //base
        $editEmployeeBase = EmployeeBase::find($id);
        $data_history = $request->only($editEmployeeBase->getFillable());
        $fileinfo = $request->file("icon");
        if (isset($fileinfo)) {
            if ($fileinfo->isValid()) {
                $ext = $fileinfo->getClientOriginalExtension();
                $name ="1icon".$editEmployeeBase->id.'.'.$ext;
                $realPath = $fileinfo->getRealPath();
                Storage::disk('icon')->put('/temp/'.$name,file_get_contents($realPath));
                $oldPath = storage_path('app/public/icon/temp/').$name;
                $newPath = storage_path('app/public/icon/').$name;
                $this->photoCut(file_get_contents($oldPath),$newPath);
                Storage::disk('thumbnail_employee')->put('/'.$name,$this->image_resize(file_get_contents($newPath),160,160));
                $data_history['icon']='/getImage/icon/'.$name.'?_='.time();
            }
        }else unset($data_history['icon']);
        $editEmployeeBase->update($data_history);
        //contents
        $editEmployeeContacts= EmployeeBase::find($id)->employeeContacts;
        $editEmployeeContacts->update($request->only($editEmployeeContacts->getFillable()));
        //bank
        $editEmployeeBank= EmployeeBase::find($id)->employeeBank;
        $editEmployeeBank->update($request->only($editEmployeeBank->getFillable()));
        //insurance
        $editEmployeeInsurance= EmployeeBase::find($id)->employeeInsurance;
        $editEmployeeInsurance->update($request->only($editEmployeeInsurance->getFillable()));
        //stay
        $editEmployeeStay= EmployeeBase::find($id)->employeeStay;
        $data_history = $request->only($editEmployeeStay->getFillable());
        $fileFront = $request->file("residence_card_front");
        if (isset($fileFront)) {
            if ($fileFront->isValid()) {
                $ext = $fileFront->getClientOriginalExtension();
                $name ="1front-".$editEmployeeBase->id.'.'.$ext;
                $realPath = $fileFront->getRealPath();
                Storage::disk('employeeCard')->put('/'.$editEmployeeBase->id.'/'.$name,file_get_contents($realPath));
                $data_history['residence_card_front']='/getImage/employeeCard/'.$editEmployeeBase->id.'/'.$name;
            }
        }else unset($data_history['residence_card_front']);
        $fileBack = $request->file("residence_card_back");
        if (isset($fileBack)) {
            if ($fileBack->isValid()) {
                $ext = $fileBack->getClientOriginalExtension();
                $name ="1back-".$editEmployeeBase->id.'.'.$ext;
                $realPath = $fileBack->getRealPath();
                Storage::disk('employeeCard')->put('/'.$editEmployeeBase->id.'/'.$name,file_get_contents($realPath));
                $data_history['residence_card_back']='/getImage/employeeCard/'.$editEmployeeBase->id.'/'.$name;
            }
        }else unset($data_history['residence_card_back']);
        $editEmployeeStay->update($data_history);
        //dependentRelation
        if(isset($request->relationship_type)){
            EmployeeDependentRelation::where('employee_id',$id)->whereNotIn('id', $request->relation_id)->delete();
            for($i=0;$i<count($request->relation_id);$i++){
                if($request->relation_id[$i]==''){
                    $employeeDependentRelation = new EmployeeDependentRelation();
                    $employeeDependentRelation->employee_id=$id;
                }else $employeeDependentRelation = EmployeeDependentRelation::find($request->relation_id[$i]);
                $employeeDependentRelation->dname=$request->dname[$i];
                $employeeDependentRelation->dependent_residence_card_num=$request->dependent_residence_card_num[$i];
                $employeeDependentRelation->relationship_type=$request->relationship_type[$i];
                $employeeDependentRelation->dependent_birthday=$request->dependent_birthday[$i];
                $employeeDependentRelation->relationship=$request->relationship[$i];
                $employeeDependentRelation->live_type=$request->live_type[$i];
                $employeeDependentRelation->dependent_address=$request->dependent_address[$i];
                $employeeDependentRelation->estimated=$request->estimated[$i];
                $employeeDependentRelation->save();
            }
        } else EmployeeDependentRelation::where('employee_id',$id)->delete();
        DB::commit();
        $updateTime =$this->historyAndModify($id,'true');
        $icon = $editEmployeeBase->data_history['icon'];
        return Reply::success(__('社員情報が正常に変更されました。'),['updateTime'=>$updateTime,'icon'=>$icon]);
    }

    /**
     * 社員情報修正バリデーション（管理員）
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function adminVerify(Request $request){
        $this->deniesForPCModify(EmployeeBase::class);
        $age =  date("Y-m-d", strtotime("+18 years", strtotime($request->birthday)));
        $today = date('Y-m-d');
        $retire_type_id = $request->retire_type_id;
        $date_hire= $request->date_hire;
        $validator = Validator::make($request->all(),array_merge($this->getValidatorArr(),[
            'date_hire' => ['bail','date','before_or_equal:'.$today,'after_or_equal:'.$age,],
            'employee_code' =>['bail','numeric', 'regex:/^\d{4}$/'],
            'retire_type_id' =>['bail','required', 'exists:retire_type,id'],
            'date_retire' => ['bail',
                function ($attribute, $value, $fail) use ($retire_type_id,$date_hire){
                    if ($value=='') {
                        if($retire_type_id==2){
                            $fail('「在職区分」に退職を選択された場合、「退職日」を入力する必要があります。');
                        }
                    }else{
                        if($retire_type_id!=2){
                            $fail('「退職日」を入力された場合、「在職区分」に退職を選択する必要があります。');
                        }else{
                            if($value<$date_hire){
                                $fail('退職日には、入社日より後の日付を指定してください。');
                            }
                        }
                    }
                }],

            'account_type' => ['bail','numeric','exists:bank_account_types,id'],
            'residence_type' => ['bail','numeric','exists:residence_type,id'],
            'residence_deadline' => ['bail','nullable','date'],
            'social_insurance' => ['bail','numeric','in:0,1'],
            'employment_insurance' => ['bail','numeric','in:0,1'],
            'national_health_insurance' => ['bail','numeric','in:0,1'],
            'national_pension_insurance' => ['bail','numeric','in:0,1'],
            'social_start_date' => ['bail','nullable','date'],
//            'base_amount' => ['bail','nullable',new Amount('基準額',true)],
            'social_end_date' => ['bail','nullable','date'],
            'employment_start_date' => ['bail','nullable','date'],
            'employment_end_date' => ['bail','nullable','date'],
            'relationship_type.*' => ['bail','numeric', 'between:1,4'],
        ]),$this->getValidatorMessage());
        $date_hire = $request->date_hire;
        $validator->sometimes('date_retire', ['bail','after_or_equal:'.$date_hire], function ($request) {
            if($request->date_retire != '') return $request->retire_type_id==2;
        });
        return $validator;
    }

    /**
     * 管理員承認
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function adminConfirm(Request $request){
        $this->deniesForManage(EmployeeBase::class);
        $id = $request->id;
        $type = $request->type;
        $boo = $this->employeeIsModify($id,$request->updateTime[0],$request->updateTime[1],$request->updateTime[2],$request->updateTime[3]);
        if($boo){
            $this->historyAndModify($id,$type);
            $action = RedisKey::NOTIFY_DELETED;
            if($type=='true') $action = RedisKey::NOTIFY_CONFIRMED;
            $msg = '編集した社員情報';
            $this->createNotification(EmployeeBase::find($id)->user->id,$msg,$action);
            return Reply::success(__('社員情報が正常に変更されました。'));
        }
        return Reply::fail(__('社員から新しい情報修正が発生したため、画面をリフレッシュしてください！'));
    }

    /**
     * 管理員承認処理
     * @param $id
     * @param $type
     * @return array
     */
    private function historyAndModify($id,$type){
        DB::beginTransaction();
        $editEmployeeBase = EmployeeBase::find($id);
        $editEmployeeBase->modified_type=0;
        $editEmployeeBase->save();
        $editEmployeeContacts = EmployeeBase::find($id)->employeeContacts;
        $editEmployeeBank = EmployeeBase::find($id)->employeeBank;
        $editEmployeeStay= EmployeeBase::find($id)->employeeStay;
        if($type=='true'){
            $this->updateHistory($editEmployeeBase);
            $this->updateHistory($editEmployeeContacts);
            $this->updateHistory($editEmployeeBank);
            $this->updateHistory($editEmployeeStay);
        }else{
            $updateInfo = $editEmployeeBase->data_history;
            $editEmployeeBase->update($updateInfo);
            $updateInfo = $editEmployeeContacts->data_history;
            $editEmployeeContacts->update($updateInfo);
            $updateInfo = $editEmployeeBank->data_history;
            $editEmployeeBank->update($updateInfo);
            $updateInfo = $editEmployeeStay->data_history;
            $editEmployeeStay->update($updateInfo);
        }
        DB::commit();
        return [date('Y-m-d H:i:s',strtotime($editEmployeeBase->updated_at)),date('Y-m-d H:i:s',strtotime($editEmployeeBank->updated_at)),date('Y-m-d H:i:s',strtotime( $editEmployeeContacts->updated_at)),date('Y-m-d H:i:s', strtotime($editEmployeeStay->updated_at))];
    }

    /**
     * 社員情報削除
     * @param $id
     * @return array|bool[]|string[]
     */
    public function destroy($id)
    {
        $this->deniesForPCModify(EmployeeBase::class);
        $employeeBase=EmployeeBase::find($id);
        $index=$employeeBase->Leave->count();
        $index+=$employeeBase->attendance->count();
        if($index==0){
            if ($employeeBase->delete()) return Reply::success(__('Employee is deleted successfully.'));
            else return Reply::fail(__('Employee delete failed, please try again.'));
        }
        return Reply::fail(__('当該社員は勤務情報或いは休暇情報が存在しています。削除はできません。'));
    }

    /**
     * 社員情報履歴更新
     * @param $employeeInfo
     */
    private function updateHistory($employeeInfo){
        $data_history = $employeeInfo->only($employeeInfo->getFillable());
        $employeeInfo->data_history = $data_history;
        $employeeInfo->save();
    }

    /**
     * 社員情報　データテーブル用
     * @param Request $request
     * @return Application|Factory|View
     * @throws \Exception
     */
    public function getEmployeesList(Request $request){
        $this->deniesForPCView(EmployeeBase::class);
        $builder = EmployeeBase::with('user:id,name,email','employeeContacts:employee_id,phone,nearest_station','hireType:id,hire_type','positionType:id,position_type')
            ->when($request->employee_retire,function($query) use ($request){
                $condition="employee_base.retire_type_id!= 2";
                $query->whereRaw($condition);
            })->when($request->id, function($query) use ($request){
                $condition="employee_code like '%{$request->id}%'";
                $query->whereRaw($condition);
            })->orderBy('employee_code','asc')->get();
        $index=sizeof($builder);
        if(isset($request->employee_name)){
            for ($i=0;$i<$index;$i++){
                $values=$builder[$i]->user->name;
                $keyword=$request->employee_name;
                if (strpos( $values , $keyword ) === false ) unset($builder[$i]);
            }
        }
        if(isset($request->type)) return view('admin.employees.card', compact( 'builder'));
        else{
            return Datatables::of($builder)
//                ->addColumn(
//                    'action',
//                    function($row) {
//                        $string = '<div class="dropdown dropdown-action">
//                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
//                    <div class="dropdown-menu dropdown-menu-right">';
//                        // Show Button
//                        if(Gate::allows(EmployeeBase::PC_MODIFY)){
//                            $string .= '<a class="dropdown-item edit_user" href="javascript:;" onclick="showDetail('.$row->id.')">
//                                    <i class="fa fa-eye m-r-5"></i> '.__('View or edit user detail info').'</a>';
//                            $string .= '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_user"  onclick="deleteAlert('.$row->id.')"    >
//                                    <i class="fa fa-trash-o m-r-5"></i> '.__('Delete').'</a>';
//                        }else{
//                            $string .= '<a class="dropdown-item edit_user" href="javascript:;" onclick="showDetail('.$row->id.')">
//                                    <i class="fa fa-eye m-r-5"></i> '.__('表示').'</a>';
//                        }
//                        $string .= '</div></div>';
//                        return $string;
//                    }
//                )
                ->addColumn('id', function ($row) {
                    return $this->getIdInput($row->id);
                })
                ->editColumn(
                    'employee_code',
                    function ($row) {
                        $inputHtml = "<input class='employee_code_input' value='$row->employee_code' type='text'/>";
                        if($row->modified_type==1) return '<span class="employee_code_span" data-code="'.$row->employee_code.'" data-id="'.$row->id.'" style="color: red">'.$row->employee_code.'</span>'.$inputHtml;
                        else return '<span class="employee_code_span" data-code="'.$row->employee_code.'" data-id="'.$row->id.'">'.$row->employee_code.'</span>'.$inputHtml;
                    }
                )
                ->editColumn(
                    'name',
                    function ($row) {
                        if($row->retire_type_id=='2') return '<span class="blueFont" onclick="showDetail('.$row->id.')">'.$row->user->name.'（退職）</span>';
                        else return '<span class="blueFont" onclick="showDetail('.$row->id.')">'.$row->user->name.'</span>';
                    }
                )
                ->editColumn(
                    'sex',
                    function ($row) {
                        if($row->sex=='0') return '男';
                        else if($row->sex=='1') return '女';
                        else return 'ユニセックス';
                    }
                )
                ->editColumn(
                    'age',
                    function ($row) {
                        list($by,$bm,$bd)=explode('-',$row->birthday);
                        $cm=date('n');
                        $cd=date('j');
                        $age=date('Y')-$by-1;
                        if ($cm>$bm || $cm==$bm && $cd>$bd) $age++;
                        return $age;
                    }
                )
                ->editColumn(
                    'work_year',
                    function ($row) {
                        return $this->calculateWorkYears($row->date_hire,$row->date_retire);
                    }
                )
                ->editColumn(
                    'hire_type',
                    function ($row) {
                        return $row->hireType->hire_type;
                    }
                )
                ->editColumn(
                    'position',
                    function ($row) {
                        return $row->positionType->position_type;
                    }
                )
                ->editColumn(
                    'nearest_station',
                    function ($row) {
                        return $row->employeeContacts->nearest_station;
                    }
                )
                ->editColumn(
                    'mail',
                    function ($row) {
                        return $row->user->email;
                    }
                )
                ->editColumn(
                    'tel',
                    function ($row) {
                        return $row->employeeContacts->phone;
                    }
                )
                ->escapeColumns([])
                ->rawColumns([])
                ->make(true);
        }
    }

    /**
     * ステップ2
     * @param Request $request
     * @return Application|Factory|View
     */
    public function step2(Request $request)
    {
        $this->deniesForPCModify(EmployeeBase::class);
        $type_id =$request->input('type_id');
        $notemployees =User::select('id','name','email')->doesntHave('employee')->whereHas('roles',function ($query){
            $query->whereNotIn('roles.id',[1]);
        })->get();
        return view('admin.employees.step2',compact('notemployees','type_id'));
    }

    /**
     * ステップ3
     * @param Request $request
     * @return Application|Factory|View
     */
    public function step3(Request $request)
    {
        $this->deniesForPCModify(EmployeeBase::class);
        $user_name = $request->name;
        $departments = Department::all();
        $hireType = HireType::all();
        $positionType = PositionType::all();
        return view('admin.employees.step3',compact('user_name','departments','hireType','positionType'));
    }

    /**
     * 社員コード 重複チェック
     * @param Request $request
     * @return bool
     */
    public function employeeCode(Request $request){
        $employeeBases = EmployeeBase::where('employee_code',$request->employeeCode)->count('*');
        return $employeeBases>0;
    }

    /**
     * 新しい社員コード取得
     * @return string
     */
    private function newEmployeeCode()
    {
        $code = EmployeeBase::select('employee_code')->max('employee_code');
        if($code==null) $code=0;
        $num = EmployeeBase::count('*');
        if($num==0) $num=1;
        return $this->employeeCodeCalculation($code,$num);
    }

    /**
     * 新しい社員コード計算
     * @param $code
     * @param $num
     * @return string
     */
    private function employeeCodeCalculation($code,$num){
        if(($code-$num)<20){
            $code1 = $num;
            $code2 = $num;
            while(true){
                $count1 = EmployeeBase::where('employee_code','=',str_pad($code1,4,'0',STR_PAD_LEFT))->count('*');
                $count2 = EmployeeBase::where('employee_code','=',str_pad($code2,4,'0',STR_PAD_LEFT))->count('*');
                if($count1==1 && $count2==1){
                    $code1++;
                    $code2--;
                    if($code2<1) $code2=1;
                }else{
                    if($count1 ==1) $num = $code2;
                    else $num = $code1;
                    break;
                }
            }
            return str_pad($num,4,'0',STR_PAD_LEFT);
        }
        $count1 = EmployeeBase::where('employee_code','<=',$num)->count('*');
        $count2 = EmployeeBase::where('employee_code','<=',$code)->count('*');
        if($num==$count1 && $code != $count2) return $this->employeeCodeCalculation((int)(($code+$num)/2),$num);
        else if($code == $count2) return $this->employeeCodeCalculation((int)($code*2-$num),$code);
        else return $this->employeeCodeCalculation($num,(int)($num/2));
    }

    /**
     * 携帯側用個人情報
     * @param $type
     * @param Request $request
     * @return Application|Factory|View
     */
    public function mobileGetInfo($type,Request $request){
        $this->deniesForMobileModify(EmployeeBase::class);
        $user = Auth::user();
        switch ($request->type){
            case "idCard":
                $base = EmployeeBase::with('departmentType')->where('user_id',$user->id)->first();
                $companyInfo=AdminSetting::all()->first();
                return view('mobile.personal.employee.id_card',compact('base','companyInfo'));
            case "base":
                $base = EmployeeBase::where('user_id',$user->id)->select("*")->first();
                return view('mobile.personal.employee.base',compact('base'));
            case "contact":
                $contact= EmployeeBase::where('user_id',$user->id)->first()->employeeContacts;
                return view('mobile.personal.employee.details.contacts',compact('contact'));
            case "bank":
                $bank = EmployeeBase::where('user_id',$user->id)->first()->employeeBank;
                $account_types = BankAccountType::all();
                return view('mobile.personal.employee.details.bank',compact('bank','account_types'));
            case "stay":
                $stay = EmployeeBase::where('user_id',$user->id)->first()->employeeStay;
                $residence_types=ResidenceType::all();
                return view('mobile.personal.employee.details.stay',compact('stay','residence_types'));
            case "user":
                $base = EmployeeBase::where('user_id',$user->id)->first();
                return view('mobile.personal.user.index',compact('base'));
            case "dependentRelations":
                return EmployeeBase::where('user_id',$user->id)->first()->employeeDependentRelation;
        }
    }

    /**
     * 携帯側　個人情報変更
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function mobileSaveInfo(Request $request){
        $this->deniesForMobileModify(EmployeeBase::class);
        $user = Auth::user();
        $editEmployeeBase = EmployeeBase::where('user_id',$user->id)->first();
        $validator = Validator::make($request->all(),[
            'name_phonetic' => ['sometimes','nullable','required',"regex:/^[ア-ン゛゜ァ-ォャ-ョー　 ]+$/u"],
            'name_roman' => ['sometimes','nullable','required','regex:/^[a-zA-Z　 ]+$/'],
        ]);
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        switch ($request->type){
            case "base":
                $employeeBase =new EmployeeBase();
                $data_history = $editEmployeeBase->only($employeeBase->getFillable());
                $data = $request->only($employeeBase->getFillable());
                if(!isset($editEmployeeBase->data_history)) $editEmployeeBase->data_history = $data_history;
                $editEmployeeBase->update($data);
                break;
            case "contact":
                $employeeContact =new EmployeeContact();
                $editEmployeeContact = $editEmployeeBase->employeeContacts;
                $data_history = $editEmployeeContact->only($employeeContact->getFillable());
                $data = $request->only($editEmployeeContact->getFillable());
                if(!isset($editEmployeeContact->data_history)) $editEmployeeContact->data_history = $data_history;
                $editEmployeeContact->update($data);
                break;
            case "bank":
                $employeeBank =new EmployeeBank();
                $editEmployeeBank = $editEmployeeBase->employeeBank;
                $data_history = $editEmployeeBank->only($employeeBank->getFillable());
                $data = $request->only($editEmployeeBank->getFillable());
                if(!isset($editEmployeeBank->data_history)) $editEmployeeBank->data_history = $data_history;
                $editEmployeeBank->update($data);
                break;
            case "stay":
                $employeeStay = new EmployeeStay();
                $editEmployeeStay = $editEmployeeBase->employeeStay;
                $data_history = $editEmployeeStay->only($employeeStay->getFillable());
                $data = $request->only($editEmployeeStay->getFillable());
                if(!isset($editEmployeeStay->data_history)) $editEmployeeStay->data_history = $data_history;
                $editEmployeeStay->update($data);
                break;
        }
        $this->mobileIsModify();
        return Reply::success(__('当該情報が正常に追加されました。'));
    }

    /**
     * 携帯側　画像アップロード
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function mobilePhotoSave(Request $request){
        $this->deniesForMobileModify(EmployeeBase::class);
        $validator = Validator::make($request->all(),[
                "icon"=> ['sometimes','nullable','mimes:jpeg,bmp,png','max_mb:10'],
                "residence_card_front"=> ['sometimes','nullable','mimes:jpeg,bmp,png','max_mb:10'],
                "residence_card_back"=> ['sometimes','nullable','mimes:jpeg,bmp,png','max_mb:10'],
        ],$this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $type = $request->type;
        $user = Auth::user();
        DB::beginTransaction();
        $base = EmployeeBase::where('user_id',$user->id)->first();
        switch ($type){
        case 'icon':
           $data_history = $base->only($base->getFillable());
           if(!isset($base->data_history)) $base->data_history = $data_history;
           $photoFile = $request->file("icon");
           $icon =$base->data_history['icon'];
           $boo = strstr($icon,'1icon');
           $name ="2icon-".$base->id.'.';
           if($boo==false) $name ="1icon-".$base->id.'.';
           if (isset($photoFile)) {
               if ($photoFile->isValid()) {
                   $ext = $photoFile->getClientOriginalExtension();
                   $name =$name.$ext;
                   $realPath = $photoFile->getRealPath();
                   Storage::disk('icon')->put('/temp/'.$name,file_get_contents($realPath));
                   $base->icon='/getImage/icon/'.$name.'?_='.time();
                   $base->save();
                   $oldPath = storage_path('app/public/icon/temp/').$name;
                   $newPath = storage_path('app/public/icon/').$name;
                   $this->photoCut(file_get_contents($oldPath),$newPath);
                   Storage::disk('thumbnail_employee')->put('/'.$name,$this->image_resize(file_get_contents($newPath),160,160));
               }
           }
           break;
        case 'stay':
           $stay = $base->employeeStay;
           $data_history = $stay->only($stay->getFillable());
           if(!isset($stay->data_history)) $stay->data_history = $data_history;
           $frontPhotoFile = $request->file("residence_card_front");
           $backPhotoFile = $request->file("residence_card_back");
           if (isset($frontPhotoFile)) {
               $path = $stay->data_history['residence_card_front'];
               $boo = strstr($path,'1front');
               $name ="2front-".$base->id.'.';
               if($boo==false) $name ="1front-".$base->id.'.';
               if ($frontPhotoFile->isValid()) {
                   $ext = $frontPhotoFile->getClientOriginalExtension();
                   $name =$name.$ext;
                   $realPath = $frontPhotoFile->getRealPath();
                   Storage::disk('employeeCard')->put('/'.$base->id.'/'.$name,file_get_contents($realPath));
                   $stay->residence_card_front='/getImage/employeeCard/'.$base->id.'/'.$name;
                   $stay->save();
               }
           }
           if (isset($backPhotoFile)) {
               $path = $stay->data_history['residence_card_back'];
               $boo = strstr($path,'1back');
               $name ="2back-".$base->id.'.';
               if($boo==false) $name ="1back-".$base->id.'.';
               if ($backPhotoFile->isValid()) {
                   $ext = $backPhotoFile->getClientOriginalExtension();
                   $name =$name.$ext;
                   $realPath = $backPhotoFile->getRealPath();
                   Storage::disk('employeeCard')->put('/'.$base->id.'/'.$name,file_get_contents($realPath));
                   $stay->residence_card_back='/getImage/employeeCard/'.$base->id.'/'.$name;
                   $stay->save();
               }
           }
           break;
        }
        $this->mobileIsModify();
        DB::commit();
        return Reply::success(__(''),['icon'=>$base->icon]);
    }

    /**
     * 変更有無の判断
     * @return bool
     */
    private function mobileIsModify(){
        $user = Auth::user();
        $base = EmployeeBase::where('user_id',$user->id)->first();
        $employeeModify = $base->getMobileModify();
        if(!$this->mobileIsModifyUse($employeeModify,$base)) return false;
        $bank = $base->employeeBank;
        $employeeModify = $bank->getMobileModify();
        if(!$this->mobileIsModifyUse($employeeModify,$bank)) return false;
        $contact = $base->employeeContacts;
        $employeeModify = $contact->getMobileModify();
        if(!$this->mobileIsModifyUse($employeeModify,$contact)) return false;
        $stay = $base->employeeStay;
        $employeeModify = $stay->getMobileModify();
        if(!$this->mobileIsModifyUse($employeeModify,$stay)) return false;
        $base->modified_type=0;
        $base->save();
        return true;
    }

    /**
     * 変更有無の判断
     * @param $employeeModify
     * @param $employeeInfo
     * @return bool
     */
    private function mobileIsModifyUse($employeeModify,$employeeInfo){
        foreach ($employeeModify as $key){
            if(!($employeeInfo->data_history[$key]==$employeeInfo->$key)){
                $user = Auth::user();
                $base = EmployeeBase::where('user_id',$user->id)->first();
                $base->modified_type=1;
                $base->save();
                return false;
            }
        }
        return true;
    }

    /**
     * 勤続年数の計算
     * @param $firstYear
     * @param $lastYear
     * @return string
     */
    private function calculateWorkYears($firstYear,$lastYear){
        $hrSetting = HrSetting::all()->first();
        list($by,$bm,$bd)=explode('-',$firstYear);
        list($cy,$cm,$cd)=explode('-',date("Y-m-d"));
        if(isset($lastYear)) list($cy, $cm, $cd) = explode('-', $lastYear);
        $year=$cy-$by;
        $month=$cm-$bm;
        $day=$cd-$bd;
        if($hrSetting->calculate_work_months==0){
            if($day>=0) $month++;
        } else if($day<0) $month--;
        if($month<0){
            $year--;
            $month=$month+12;
        }
        if($hrSetting->calculate_work_years==0) return sprintf("%.2f",$year+round($month/12,2)).'年';
        else{
            $month=str_pad($month,2,"0",STR_PAD_LEFT);
            return $year.'年'.$month.'月';
        }
    }

    /**
     * 携帯側　審査画面
     * @return Application|Factory|View
     */
    public function mobileAuditGetInfo(){
        $this->deniesForMobileAudit(EmployeeBase::class);
        $employees = EmployeeBase::with("user:id,name,email",'hireType','departmentType')->where("modified_type","1")->get();
        return view('mobile.examination.employee.index',compact('employees'));
    }

    /**
     * 携帯側　審査画面　詳細情報
     * @param $id
     * @return Application|Factory|View
     */
    public function mobileAuditGetInfoById($id){
        $this->deniesForMobileAudit(EmployeeBase::class);
        $employee = EmployeeBase::find($id);
        $residenceTypes = ResidenceType::all();
        $accountTypes = BankAccountType::all();
        return view('mobile.examination.employee.editInfo',compact('employee','residenceTypes','accountTypes'));
    }

    private function photoCut($oldPath,$newPath){
        $imgData  = json_decode($_POST['imgData']);
        $rate = $imgData->rate;
        $cropX  = (int)(($imgData->left)/$rate);
        $cropY  = (int)(($imgData->top)/$rate);
        $cropW  = (int)(($imgData->width)/$rate);
        $cropH  = (int)(($imgData->height)/$rate);

        // 使用图片处理类进行裁剪操作
        $img = Image::make($oldPath)
//            ->rotate(-$rotate)
            ->crop($cropW, $cropH, $cropX, $cropY)
//                ->resize(200,200)
            ->save($newPath);

        // 生成裁剪图片失败
        if(!$img) return ['ServerNo' => 500, 'ResultData' => '图片保存失败'];
        // 删除旧图片
        @unlink($oldPath);
        return ['ServerNo' => 200, 'ResultData' => $newPath];
    }

    public function employeeCodeSave(Request $request){
        $this->deniesForPCModify(EmployeeBase::class);
        $validator = Validator::make($request->all(),[
            "codeArr.*"=> ['regex:/^\d{4}$/'],
        ],$this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $idArr = $request->idArr;
        $codeArr = $request->codeArr;
        DB::beginTransaction();
        foreach ($idArr as $key=>$id){
            $employee = EmployeeBase::find($id);
            $employee->employee_code=$codeArr[$key];
            $employee->save();
        }
        DB::commit();
        return Reply::success(__('社員番号が正常に保存されました。'));
    }
    public function employeeDeleteCheck(Request $request){
        $this->deniesForPCModify(EmployeeBase::class);
        $idArr = $request->idArr;
        foreach ($idArr as $key=>$id){
            $employee = EmployeeBase::find($id);
            $index=$employee->Leave->count();
            $index+=$employee->attendance->count();
            if($index>0){
                return Reply::fail(__('選択した社員は勤務情報或いは休暇情報が存在しています。削除はできません。'));
            }
        }
        if (EmployeeBase::whereIn('id', $idArr)->delete()) return Reply::success(__('Employee is deleted successfully.'));
        else return Reply::fail(__('Employee delete failed, please try again.'));
    }
}
