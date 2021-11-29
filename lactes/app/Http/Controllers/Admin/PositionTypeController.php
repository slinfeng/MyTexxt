<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\EmployeeBase;
use App\Models\HireType;
use App\Models\HrSetting;
use App\Models\PositionType;
use App\Traits\PCGateTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PositionTypeController extends Controller
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
            'position_type.required' => __('役職名を入力してください！'),
            'position_type.unique' => __('当該役職名はまだ存在しました！'),
            'position_type_name.required' => __('表示名称を入力してください！'),
        ];
        $validator = Validator::make($request->all(), [
            'position_type' => 'bail|required|unique:position_types|max:200',
            'position_type_name' => 'bail|required|max:200',
        ],$messages);
        if ($validator->fails()){
            return Reply::fail($validator->errors()->first());
        } else if ($validator->passes()) {
            $reqData = $request->all();
            $newData = PositionType::create($reqData);
            return Reply::success('役職を追加しました。',['newData'=>$newData]);
        }
    }

    /**
     * 編集保存
     * @param Request $request
     * @param PositionType $positionType
     * @return array
     */
    public function update(Request $request, PositionType $positionType)
    {
        $this->deniesForModify(HrSetting::class);
        $messages = [
            'position_type.required' => __('役職名を入力してください！'),
            'position_type_name.required' => __('表示名称を入力してください！'),
        ];
        $validator = Validator::make($request->all(), [
            'position_type' => ['required','max:255',
                function ($attribute, $value, $fail) use ($positionType){
                    if((PositionType::where('id','!=',$positionType->id)->where('position_type',$value)->count())>0){
                        $fail(__('当該役職名はまだ存在しました！'));
                    }
                },
            ],
            'position_type_name' => 'bail|required|max:255',
        ],$messages);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $reqData = $request->all();
        $positionType->update($reqData);
        return Reply::success(__('当該役職が正常に変更されました。'));
    }

    /**
     * 削除
     * @param PositionType $positionType
     * @return array
     */
    public function destroy( PositionType $positionType)
    {
        $this->deniesForModify(HrSetting::class);
        if((EmployeeBase::where('position_type_id',$positionType->id)->count())>0) return Reply::fail(__('当該役職は使用中であり、削除できません'));
        else if((PositionType::count())==1) return Reply::fail(__('最後の役職は削除できません'));
        else{
            $positionType->delete();
            return Reply::success(__('当該役職を削除しました。'));
        }
    }

}
