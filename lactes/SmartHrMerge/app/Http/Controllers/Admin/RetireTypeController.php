<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\EmployeeBase;
use App\Models\HireType;
use App\Models\HrSetting;
use App\Models\RetireType;
use App\Traits\PCGateTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class RetireTypeController extends Controller
{
    use PCGateTrait;

    /**
     * 新規追加保存
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->deniesForModify(HrSetting::class);
        $messages = [
            'retire_type.required' => __('在職区分名を入力してください！'),
            'retire_type.unique' => __('当該在職区分名はまだ存在しました！'),
        ];
        $validator = Validator::make($request->all(), [
            'retire_type' => 'bail|required|unique:retire_type|max:200',
        ],$messages);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $reqData = $request->all();
        $newData = RetireType::create($reqData);
        return Reply::success('在職区分を追加しました。',['newData'=>$newData]);
    }

    /**
     * 編集保存
     * @param Request $request
     * @param RetireType $retireType
     * @return array
     */
    public function update(Request $request,RetireType $retireType)
    {
        $this->deniesForModify(HrSetting::class);
        $messages = [
            'retire_type.required' => __('在職区分名を入力してください！'),
        ];
        $validator = Validator::make($request->all(), [
            'retire_type' => ['required','max:200',
                function ($attribute, $value, $fail) use ($retireType){
                    if((RetireType::where('id','!=',$retireType->id)->where('retire_type',$value)->count())>0){
                        $fail(__('当該在職区分名はまだ存在しました！'));
                    }
                },
            ],
        ],$messages);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        if(($retireType->id)<=2)return Reply::fail(__('在職と退職は基本的な在職区分であり、編集できません'));
        $reqData = $request->all();
        $retireType->update($reqData);
        return Reply::success(__('当該在職区分が正常に変更されました。'));
    }

    /**
     *　削除
     * @param RetireType $retireType
     * @return array
     */
    public function destroy(RetireType $retireType)
    {
        $this->deniesForModify(HrSetting::class);
        if((EmployeeBase::where('retire_type_id',$retireType->id)->count())>0) return Reply::fail(__('当該在職区分は使用中であり、削除できません'));
        else if(($retireType->id)<=2) return Reply::fail(__('在職と退職は基本的な在職区分であり、削除できません'));
        else if((RetireType::count())==1) return Reply::fail(__('最後の在職区分は削除できません'));
        else $retireType->delete();
        return Reply::success(__('当該在職区分を削除しました。'));
    }
}
