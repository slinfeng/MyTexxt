<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\EmployeeBase;
use App\Models\HireType;
use App\Models\HrSetting;
use App\Traits\PCGateTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class HireTypeController extends Controller
{
    use PCGateTrait;

    /**
     * 登録
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function store(Request $request)
    {
        $this->deniesForModify(HrSetting::class);
        $messages = [
            'hire_type.required' => __('契約形態名を入力してください！'),
            'hire_type.unique' => __('当該契約形態名は既に存在しました！'),
        ];
        $validator = Validator::make($request->all(), [
            'hire_type' => 'bail|required|unique:hire_types|max:200',
        ],$messages);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $reqData = $request->all();
        $newData = HireType::create($reqData);
        return Reply::success('契約形態を追加しました。',['newData'=>$newData]);
    }

    /**
     * 更新
     * @param Request $request
     * @param HireType $hireType
     * @return array|bool[]|string[]
     */
    public function update(Request $request, HireType $hireType)
    {
        $this->deniesForModify(HrSetting::class);
        $messages = [
            'hire_type.required' => __('契約形態名を入力してください！'),
        ];
        $validator = Validator::make($request->all(), [
            'hire_type' => ['required','max:200',
                function ($attribute, $value, $fail) use ($hireType){
                    if((HireType::where('id','!=',$hireType->id)->where('hire_type',$value)->count())>0){
                        $fail(__('当該契約形態名は既に存在しました！'));
                    }
                },
            ]
        ],$messages);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $reqData = $request->all();
        $hireType->update($reqData);
        return Reply::success(__('当該契約形態は正常に変更されました。'));
    }

    /**
     * 削除
     * @param HireType $hireType
     * @return array|bool[]|string[]
     */
    public function destroy(HireType $hireType)
    {
        $this->deniesForModify(HrSetting::class);
        if((EmployeeBase::where('hire_type_id',$hireType->id)->count())>0) return Reply::fail(__('当該契約形態は使用中であり、削除できません'));
        else if((HireType::count())==1) return Reply::fail(__('最後の契約形態は削除できません'));
        else $hireType->delete();
        return Reply::success(__('当該契約形態を削除しました。'));
    }
}
