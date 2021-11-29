<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\Department;
use App\Http\Controllers\Controller;
use App\Models\EmployeeBase;
use App\Models\HrSetting;
use App\Traits\PCGateTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DepartmentController extends Controller
{
    use PCGateTrait;

    /**
     * 部門情報追加
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function store(Request $request)
    {
        $this->deniesForModify(HrSetting::class);
        $messages = [
            'department_name.required' => __('部門名を入力してください！'),
            'department_name.unique' => __('当該部門名は既に存在しています！'),
        ];
        $validator = Validator::make($request->all(), [
            'department_name' => 'bail|required|unique:departments|max:200',
        ],$messages);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $newData = Department::create($request->all());
        return Reply::success('部門を追加しました。',['newData'=>$newData]);
    }

    /**
     * 部門情報変更
     * @param Request $request
     * @param Department $department
     * @return array|bool[]|string[]
     */
    public function update(Request $request, Department $department)
    {
        $this->deniesForModify(HrSetting::class);
        $messages = [
            'department_name.required' => __('部門名を入力してください！'),
        ];
        $validator = Validator::make($request->all(), [
            'department_name' => ['required','max:200',
                function ($attribute, $value, $fail) use ($department){
                    if((Department::where('id','!=',$department->id)->where('department_name',$value)->count())>0){
                        $fail(__('当該部門名は既に存在しています！'));
                    }
                },
            ]
        ],$messages);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $reqData = $request->all();
        $department->update($reqData);
        return Reply::success(__('当該部門は正常に変更されました。'));
    }

    /**
     * 部門情報削除
     * @param Department $department
     * @return array|bool[]|string[]
     */
    public function destroy(Department $department)
    {
        $this->deniesForModify(HrSetting::class);
        if((EmployeeBase::where('department_type_id',$department->id)->count())>0){
            return Reply::fail(__('当該部門に属する社員がいます、削除できません'));
        }else if((Department::count())==1){
            return Reply::fail(__('部門は少なくとも一つを残してください。'));
        }else{
            $department->delete();
            return Reply::success(__('当該部門を削除しました。'));
        }
    }
}
