<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\PCGateTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class
RoleController extends Controller
{
    use PCGateTrait;
    /**
     * インデックス画面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */

    public function index()
    {
        $this->deniesForView(AdminSetting::class);
        $roles = Role::with(['permissions:id,title'])->get();
        $allpermissions =Permission::select('title','id')->get()->toArray();
        return view('admin.roles.index', compact('roles','allpermissions'));
    }
    /**
     * 新規追加画面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
        $this->deniesForModify(AdminSetting::class);
        $validator = Validator::make($request->all(),[
            'title'=>'bail|required|max:50|unique:roles',
        ],['title.required'=>'役割名称を入力してください！',
            'title.unique'=>'役割名称を重ねることはできません！',
            'title.max'=>'役割名称は50桁以内に抑えてください！']);
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        $role = Role::create($request->all());
        $perms = array("1","6","11","16","21","27","32","37","42","47","52","57","60","65","70","75","80");
        $role->permissions()->sync($perms);
        return Reply::success('役割名称を追加しました！',['data'=>$role]);
    }

    /**
     * 編集画面
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Role $role)
    {
        $this->deniesForModify(AdminSetting::class);
        $permissions = Permission::all();
        $role->load('permissions');
        $result = [
            'role'        => $role,
            'permissions'        => $permissions
        ];
        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 編集画面保存
     * @param Request $request
     * @param Role $role
     * @return array
     */
    public function update(Request $request, Role $role)
    {
        $this->deniesForModify(AdminSetting::class);
        $validator = Validator::make($request->all(),[
            'title'=>['bail','required','max:50',Rule::unique('roles','title')->whereNot('id',$role->id)],
        ],['title.required'=>'役割名称を入力してください！',
            'title.max'=>'役割名称は50桁以内に抑えてください！',
            'title.unique'=>'役割名称を重ねることはできません！']);
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        $role->update(['title'=>$request->title]);
        return Reply::success('役割名称を変更しました！',['data'=>$role]);
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return view('admin.roles.show', compact('role'));
    }

    /**
     * 削除
     * @param Role $role
     * @return array
     */
    public function destroy(Role $role)
    {
        $this->deniesForModify(AdminSetting::class);
        if($role->id<=8) return Reply::fail($role->title.'の削除はできません。');
        $role->delete();
        return Reply::success('削除しました！');
    }

    /**
     * 権限者の権限変更
     * @param Request $request
     * @param $id
     * @return array
     */
    public function updateRolePermissions(Request $request, $id)
    {
        $this->deniesForModify(AdminSetting::class);
        $home_show = $request->home_show;
        if($id<=8){
            $fixedAuthority=array(
                1=>[1,6,11,16,21,27,32,37,42,47,52,57,60,65,70,77,82,85],
                2=>[3,8,13,18,23,28,34,39,44,49,54,58,62,66,72,77,81,85,86],
                3=>[3,8,13,18,23,28,34,38,43,48,53,57,61,67,71,75,80,85],
                4=>[2,7,12,17,22,28,33,39,44,48,54,58,61,67,71,75,80,85,86],
                5=>[1,6,11,16,21,27,32,37,42,47,52,57,62,66,72,75,80,85],
                6=>[3,7,12,17,22,29,33,38,43,48,53,57,61,67,71,75,80,85],
                7=>[1,6,11,16,21,27,32,37,42,47,52,59,60,65,70,75,80,86],
                8=>[1,6,16,24,27,39,42,47,52,57,60,65,70,75,80,85]
            );
            $perms = $fixedAuthority[$id];
            if(isset($home_show)){
                $perms = array_merge($perms,$home_show);
            }
        }else{
            $validator = Validator::make($request->all(),[
                'permission.*'=>['bail','nullable','numeric'],
            ],['permission.*.numeric'=>'権限を指定してください！']);
            if($validator->fails()) return Reply::fail($validator->errors()->first());
            $perms=$request->input('permission', []);
            $perms = array_unique($perms);
            asort($perms);
            $pc_login = array("2","3","7","8", "12","13","17","18","22","23","28","29","33","34","38","39","43","44","48","49","53","54","61","62","66","67","71","72","76","77","81");
            $mobile_login = array("58","59");
            if(sizeof(array_intersect($pc_login,$perms))>0) array_push($perms,'85');
            if(sizeof(array_intersect($mobile_login,$perms))>0) array_push($perms,'86');
        }
        $role = Role::find($id);
        $role->permissions()->sync($perms);

        return Reply::success(__('権限を変更しました！'));
    }
}
