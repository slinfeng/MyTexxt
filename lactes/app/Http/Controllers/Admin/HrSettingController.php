<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\EmployeeAnnualLeave;
use App\Models\EmployeeBase;
use App\Models\HireType;
use App\Models\HrSetting;
use App\Models\LeaveAnnualConnect;
use App\Models\PositionType;
use App\Models\ResidenceType;
use App\Models\RetireType;
use App\Traits\PCGateTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\Reply;


class HrSettingController extends Controller
{
    use PCGateTrait;
    /**
     * インデックス画面
     * @return Factory|View
     */
    public function index()
    {
        $this->deniesForView(HrSetting::class);
        $departments = Department::all();
        $hireTypes = HireType::all();
        $positionTypes = PositionType::all();
        $retireTypes = RetireType::all();
        $residenceTypes = ResidenceType::all();
        $hrSetting = HrSetting::all()->first();
        $employees=EmployeeBase::all();
        return view('admin/HrSetting/index',compact('departments','hireTypes','positionTypes','retireTypes','residenceTypes','hrSetting','employees'));
    }

    /**
     *　編集保存
     * @param $id
     * @param Request $request
     * @return array
     */
    public function update($id,Request $request){
        $this->deniesForModify(HrSetting::class);
        switch ($id){
            case 0:
                $validator =  $this->commonEdit($request);
                break;
            case 1:
                $validator =  $this->employeeEdit($request);
                break;
            case 2:
                $validator =  $this->attendanceEdit($request);
                break;
            case 3:
                $validator =  $this->leavesEdit($request);
                break;
            case 4:
                $validator =  $this->employeeAnnualLeaveEdit($request);
                break;
        }
        $errMsg = $validator->errors()->first();
        if($errMsg!='') return Reply::fail($errMsg);
        return Reply::success(__('設定が正常に変更されました。'));
    }


    private function commonEdit($request){
        $temp = $request->all();
        $validator = Validator::make($temp, []);
        $hrSetting = HrSetting::first();
        $hrSetting->update($temp);
        return $validator;
    }

    /**
     * 社員設定編集　保存
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function employeeEdit($request){
        $temp = $request->all();
        $validator = Validator::make($temp, []);
        $hrSetting = HrSetting::first();
        $hrSetting->update($temp);
        return $validator;
    }

    /**
     * 勤務設定編集　保存
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function attendanceEdit($request){
        $temp = $request->all();
        $validator = Validator::make($temp, []);
        $hrSetting = HrSetting::first();
        $hrSetting->update($temp);
        return $validator;
    }

    /**
     * 休暇設定編集　保存
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function leavesEdit($request){
        $temp = $request->all();
        $validator = Validator::make($temp, [
            'first_year_leave'=>['bail','required','numeric','between:0,365'],
            'cumulative_years'=>['bail','required','numeric'],
            'grow_leave'=>['bail','required','numeric','between:0,365'],
            'max_annual_leave'=>['bail','required','numeric','between:0,365'],
        ]);
        if ($validator->fails()) return $validator;
        $hrSetting = HrSetting::first();
        $hrSetting->update($temp);
        return $validator;
    }

    /**
     *
     * 年休設定編集　保存
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function employeeAnnualLeaveEdit($request){
        $validator = Validator::make($request->all(), [
            'days.*'=>['bail','nullable','numeric','between:0,365'],
        ]);
        if ($validator->passes()){
            $temp = $request->all();
            DB::beginTransaction();
            $idEmployeeIdYearArr=$temp['idEmployeeIdYear'];
            $daysArr=$temp['days'];
            $hasDaysArr=$temp['has_days'];
            $employeeBaseIdArr=$temp['employee_base_id'];
            $annual_leave_typeArr=$request['annual_leave_type'];
            if(count($employeeBaseIdArr)>0){
                for ($i=0;$i<count($employeeBaseIdArr);$i++){
                    if(!isset($annual_leave_typeArr[$i])){
                        $annual_leave_typeArr[$i]=0;
                    }
                    $employee=EmployeeBase::find($employeeBaseIdArr[$i]);
                    $employee->update(['annual_leave_type'=>$annual_leave_typeArr[$i]]);
                }
            }
            for($i=0;$i<count($idEmployeeIdYearArr);$i++){
                $idEmployeeIdYear=explode('_',$idEmployeeIdYearArr[$i]);
                $updateData=[
                    'id'=>$idEmployeeIdYear[0],
                    'employee_base_id'=>$idEmployeeIdYear[1],
                    'year'=>$idEmployeeIdYear[2],
                    'days'=>$daysArr[$i],
                    'has_days'=>$hasDaysArr[$i],
                ];
                if($updateData['id']!=''){
                    $employeeAnnualLeave=EmployeeAnnualLeave::find($updateData['id']);
                    $employeeAnnualLeave->update($updateData);
                }else EmployeeAnnualLeave::create($updateData);
            }
            DB::commit();
        }
        return $validator;
    }

}
