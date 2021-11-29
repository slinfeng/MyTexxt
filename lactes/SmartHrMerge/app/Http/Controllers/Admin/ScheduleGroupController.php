<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\Permission;
use App\Models\Role;
use App\Models\ScheduleGroup;
use App\Models\ScheduleMemberSetting;
use App\Traits\PCGateTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ScheduleGroupController extends Controller
{
    use PCGateTrait;

    /**
     * インデックス画面
     * @return Factory|View
     */
    public function index()
    {
        $scheduleGroups=ScheduleGroup::all();
        $scheduleMemberSettings=ScheduleMemberSetting::all()->sortBy('order_num');
        return view('admin.scheduleSetting.details.group', compact('scheduleGroups','scheduleMemberSettings'));
    }
    /**
     * 新規追加画面
     * @return Factory|View
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * 新規追加保存
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name'=>'bail|required|unique:schedule_group',
        ],['name.required'=>'サブグループ名称を入力してください！',
            'name.unique'=>'当該サブグループ名は既に存在しました！',
            'name.max'=>'サブグループ名称は全角10桁以内に抑えてください！']);
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        $scheduleGroup=ScheduleGroup::create($request->all());
        return Reply::success('サブグループ名称を追加しました！',['data'=>$scheduleGroup]);
    }

    /**
     * 編集画面
     * @param $id
     * @return JsonResponse
     */
    public function edit($id)
    {
       return ScheduleGroup::find($id);
    }

    /**
     * 編集画面保存
     * @param Request $request
     * @param Role $role
     * @return array
     */
    public function update(Request $request, $id)
    {
        $group=ScheduleGroup::find($id);
        $validator = Validator::make($request->all(),[
            'name'=>['bail','required',
                function ($attribute, $value, $fail) use ($group){
                    if((ScheduleGroup::where('id','!=',$group->id)->where('name',$value)->count())>0){
                        $fail(__('当該サブグループ名は既に存在しました！'));
                    }
                }],
        ],['name.required'=>'サブグループ名称を入力してください！',
            'name.max'=>'サブグループ名称は全角10桁以内に抑えてください！',
        ]);
        if($validator->fails()) return Reply::fail($validator->errors()->first());

        $group->update($request->all());
        return Reply::success('当該サブグループ名称を変更しました！',['data'=>$group]);
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return view('admin.roles.show', compact('role'));
    }

    /**
     * 削除
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        $group=ScheduleGroup::find($id);
        $group->delete();
        return Reply::success('当該サブグループを削除しました！');
    }

    /**
     * サブグループの予約アイテム変更
     * @param Request $request
     * @return array
     */
    public function updateGroupMembers(Request $request)
    {
        $members = $request->members;
        $group=ScheduleGroup::find($request->id);
        $group->ScheduleMemberSetting()->sync($members);
        return Reply::success('当該サブグループに、予約アイテムを変更しました！');
    }
}
