<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\Permission;
use App\Models\Role;
use App\Models\ScheduleColorSetting;
use App\Traits\PCGateTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class ScheduleColorController extends Controller
{
    use PCGateTrait;

    /**
     * インデックス画面
     *
     */
    public function index()
    {

    }
    /**
     * 新規追加画面
     */
    public function create()
    {

    }

    /**
     * 新規追加保存
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'color_id'=>['bail','required','numeric','exists:schedule_color_type,id'],
            'order_num'=>['bail','required','numeric'],
            'name'=>['bail','required','unique:schedule_color_setting'],
        ],[
            'name.required'=>'名称は必ず指定してください。',
            'name.unique'=>'当該名称は既に存在しました！',
        ]);
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        DB::beginTransaction();
        ScheduleColorSetting::create($request->all());
        DB::commit();
        return Reply::success('種別を追加しました！');
    }

    /**
     * 編集画面
     * @param $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        $data=ScheduleColorSetting::find($id);
        return $data;
    }

    /**
     * 編集画面保存
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $scheduleColorSetting=ScheduleColorSetting::find($id);
        $validator = Validator::make($request->all(),[
            'color_id'=>['bail','required','numeric','exists:schedule_color_type,id'],
            'order_num'=>['bail','required','numeric'],
            'name'=>['bail','required',function ($attribute, $value, $fail) use ($scheduleColorSetting){
                if((ScheduleColorSetting::where('id','!=',$scheduleColorSetting->id)->where('name',$value)->count())>0){
                    $fail(__('当該名称は既に存在しました！'));
                }
            }],
        ],['name.required'=>'名称は必ず指定してください。',]);
        if($validator->fails()) return Reply::fail($validator->errors()->first());

        $scheduleColorSetting->update($request->all());
        return Reply::success('種別を変更しました！');
    }

    public function show(Role $role)
    {

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


    public function getScheduleColorSettings(Request $request)
    {
        $builder = ScheduleColorSetting::select('id','name','color_id','order_num')->orderBy('order_num');
        return Datatables::of($builder->get())
            ->editColumn('select', function ($row){
                return '<input type="hidden" name="id" value="'.$row->id.'"/>';
            })
            ->editColumn('name', function ($row){
                return '<input type="hidden" name="color_id" value="'.$row->color_id.'"/><input type="hidden" name="name" value="'.$row->name.'"/><button onclick="return false;" class="btn color-show" style="cursor:default;background-color: '.$row->ScheduleColorType->css_name.';"></button>'.$row->name;
            })
            ->editColumn('order_num', function ($row){
                return '<input type="text" name="order_num" class="table_input order-input number text-center" value="'.$row->order_num.'"/><span class="order-span" data-code="'.$row->order_num.'">'.$row->order_num.'</span>';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function updateOrderNum(Request $request){
        $updateArr=$request['updateArr'];
        DB::beginTransaction();
        foreach ($updateArr as $updateData){
            $update=[
                'order_num'=>$updateData[1],
            ];
            $scheduleMemberSetting=ScheduleColorSetting::find($updateData[0]);
            if(isset($scheduleMemberSetting)){
                $scheduleMemberSetting->update($update);
            }
        }
        DB::commit();
        return Reply::success(__('選択されたアイテムの表示順を変更しました！'));
    }

    public function deleteColors(Request $request){
        $deleteArr=$request['deleteArr'];
        DB::beginTransaction();
        foreach ($deleteArr as $deleteData){
            $scheduleColorSetting=ScheduleColorSetting::find($deleteData[0]);
            if(isset($scheduleColorSetting)){
                $scheduleColorSetting->delete();
            }
        }
        DB::commit();
        return Reply::success(__('選択された種別を削除しました！'));
    }
}
