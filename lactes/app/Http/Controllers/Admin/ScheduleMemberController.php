<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\Permission;
use App\Models\Role;
use App\Models\ScheduleMemberSetting;
use App\Traits\PCGateTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class ScheduleMemberController extends Controller
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
     *
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
            'user_id'=>['bail','numeric'],
            'order_num'=>['bail','required','numeric'],
            'name'=>['bail','required','unique:schedule_member_setting'],
            'display_name'=>['bail','required','unique:schedule_member_setting'],
            'reserve_type'=>['bail','nullable','numeric','in:0,1'],
            'reserve_name_type'=>['bail','nullable','numeric','in:0,1'],
            'constraint_type'=>['bail','nullable','numeric','in:0,1'],
        ],[
            'name.required'=>'アイテム登録は必ず指定してください。',
            'name.unique'=>'当該アイテム登録は既に存在しました！',
            ]);
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        if(!isset($request['reserve_type'])){
            $request['reserve_type']=0;
        }
        if(!isset($request['reserve_name_type'])){
            $request['reserve_name_type']=0;
        }
        ScheduleMemberSetting::create($request->all());
        return Reply::success('アイテムを追加しました！');
    }

    /**
     * 編集画面
     * @param $id
     * @return JsonResponse
     */
    public function edit($id)
    {
       $data=ScheduleMemberSetting::find($id);
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
        $scheduleMemberSetting=ScheduleMemberSetting::find($id);
        $validator = Validator::make($request->all(),[
            'user_id'=>['bail','nullable','numeric'],
            'order_num'=>['bail','required','numeric'],
            'name'=>['bail','required',
                function ($attribute, $value, $fail) use ($scheduleMemberSetting){
                    if((ScheduleMemberSetting::where('id','!=',$scheduleMemberSetting->id)->where('name',$value)->count())>0){
                        $fail(__('当該アイテム登録は既に存在しました！'));
                    }
                }],
            'display_name'=>['bail','required',
                function ($attribute, $value, $fail) use ($scheduleMemberSetting){
                    if((ScheduleMemberSetting::where('id','!=',$scheduleMemberSetting->id)->where('display_name',$value)->count())>0){
                        $fail(__('当該表示名は既に存在しました！'));
                    }
                }],
            'reserve_type'=>['bail','nullable','numeric','in:0,1'],
            'reserve_name_type'=>['bail','nullable','numeric','in:0,1'],
            'constraint_type'=>['bail','nullable','numeric','in:0,1'],
        ],[
            'name.required'=>'アイテム登録は必ず指定してください。',
            ]);
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        if(!isset($request['reserve_type'])){
            $request['reserve_type']=0;
        }
        if(!isset($request['reserve_name_type'])){
            $request['reserve_name_type']=0;
        }

        $scheduleMemberSetting->update($request->all());
        return Reply::success('当該アイテムを変更しました！');
    }


    public function getScheduleMemberSettings(Request $request)
    {
        $builder = ScheduleMemberSetting::orderBy('order_num');
        return Datatables::of($builder->get())
            ->editColumn('select', function ($row){
                return '<input type="hidden" name="id" value="'.$row->id.'"/>';
            })
            ->editColumn('order_num', function ($row){
                return '<input type="text" name="order_num" class="table_input order-input number" value="'.$row->order_num.'"/><span class="order-span" data-code="'.$row->order_num.'">'.$row->order_num.'</span>';
            })
            ->editColumn('name', function ($row){
                return '<div>'.$row->name.'</div>';
            })
            ->editColumn('display_name', function ($row){
                return '<div>'.$row->display_name.'</div>';
            })
            ->editColumn('reserve_type', function ($row){
                return '<input type="checkbox" '.($row->reserve_type==1?'checked':'').' onclick="return false;">';
            })
            ->editColumn('reserve_name_type', function ($row){
                return '<input type="checkbox" '.($row->reserve_name_type==1?'checked':'').' onclick="return false;">';
            })
            ->editColumn('constraint_type', function ($row){
                return $row->constraint_type==0?'自由予約':'管理者以外予約不可';
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
            $scheduleMemberSetting=ScheduleMemberSetting::find($updateData[0]);
            if(isset($scheduleMemberSetting)){
                $scheduleMemberSetting->update($update);
            }
        }
        DB::commit();
        return Reply::success(__('選択されたアイテムの表示順を変更しました！'));
    }

    public function deleteMembers(Request $request){
        $deleteArr=$request->deleteArr;
        DB::beginTransaction();
        foreach ($deleteArr as $deleteData){
            $scheduleMemberSetting=ScheduleMemberSetting::find($deleteData);
            if(isset($scheduleMemberSetting)){
                $scheduleMemberSetting->delete();
            }
        }
        DB::commit();
        return Reply::success(__('選択されたアイテムを削除しました！'));
    }
}
