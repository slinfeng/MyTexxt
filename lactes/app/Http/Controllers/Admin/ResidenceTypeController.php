<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPermissionRequest;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\EmployeeBase;
use App\Models\EmployeeStay;
use App\Models\HrSetting;
use App\Models\Permission;
use App\Models\ResidenceType;
use App\Traits\PCGateTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ResidenceTypeController extends Controller
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
            'residence_type.required' => __('在留資格種類名を入力してください！'),
            'residence_type.unique' => __('当該在留資格種類名はまだ存在しました！'),
        ];
        $validator = Validator::make($request->all(), [
            'residence_type' => 'bail|required|unique:residence_type|max:200',
        ],$messages);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $reqData = $request->all();
        return Reply::success('在留資格種類を追加しました。',['newData'=>ResidenceType::create($reqData)]);
    }

    /**
     * 編集保存
     * @param Request $request
     * @param ResidenceType $residenceType
     * @return array
     */
    public function update(Request $request,ResidenceType $residenceType)
    {
        $this->deniesForModify(HrSetting::class);
        $messages = [
            'residence_type.required' => __('在留資格種類名を入力してください！'),
        ];
        $validator = Validator::make($request->all(), [
            'residence_type' => ['required','max:200',
                function ($attribute, $value, $fail) use ($residenceType){
                    if((ResidenceType::where('id','!=',$residenceType->id)->where('residence_type',$value)->count())>0){
                        $fail(__('当該在留資格種類名はまだ存在しました！'));
                    }
                },
            ],
        ],$messages);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $reqData = $request->all();
        $residenceType->update($reqData);
        return Reply::success(__('当該在留資格種類が正常に変更されました。'));
    }

    /**
     * 削除
     * @param ResidenceType $residenceType
     * @return array
     */
    public function destroy(ResidenceType $residenceType)
    {
        $this->deniesForModify(HrSetting::class);
        if((EmployeeStay::where('residence_type',$residenceType->id)->count())>0) return Reply::fail(__('当該在留資格種類は使用中であり、削除できません'));
        else if((ResidenceType::count())==1) return Reply::fail(__('最後の在留資格種類は削除できません'));
        else $residenceType->delete();
        return Reply::success(__('当該在留資格種類を削除しました。'));
    }

}
