<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleColorSetting;
use App\Models\ScheduleColorType;
use App\Models\ScheduleGroup;
use App\Models\ScheduleMemberSetting;
use App\Models\User;
use App\Models\ScheduleSetting;
use App\Traits\PCGateTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\Reply;
use Yajra\DataTables\DataTables;


class ScheduleSettingController extends Controller
{
    use PCGateTrait;
    /**
     * インデックス画面
     * @return Factory|View
     */
    public function index()
    {
//        $users=User::all();
        $users =User::select('id','name')->doesntHave('schedule_member_setting')->get();
        $scheduleMemberSettings=ScheduleMemberSetting::all();
        $scheduleSetting = ScheduleSetting::first();
        $scheduleGroups=ScheduleGroup::all();
        $scheduleColorSettings=ScheduleColorSetting::all();
        $scheduleColorTypes=ScheduleColorType::all();
        return view('admin/scheduleSetting/index',compact('scheduleSetting','scheduleMemberSettings','users','scheduleGroups','scheduleColorSettings','scheduleColorTypes'));
    }

    /**
     *　編集保存
     * @param $id
     * @param Request $request
     * @return array
     */
    public function update($id,Request $request){
//        $this->deniesForModify(HrSetting::class);
        switch ($id){
            case 0:
                $validator =  $this->commonEdit($request);
                break;
            case 3:
                $validator =  $this->colorEdit($request);
                break;
        }
        $errMsg = $validator->errors()->first();
        if($errMsg!='') return Reply::fail($errMsg);
        return Reply::success(__('設定が正常に変更されました。'));
    }

    private function commonEdit($request){
        $display_time_start = $request->display_time_start;
        $display_time_end = $request->display_time_end;
        $request['display_time'] = $display_time_start.'～'.$display_time_end;
        $temp = $request->all();
        $validator = Validator::make($temp, [
            'anonymous_type' =>['bail', 'in:0,1'],
            'display_reservation_type' =>['bail', 'in:0,1'],
            'duplicate_reservation_type' =>['bail','in:0,1'],
            'display_time_start' =>['bail','numeric', 'between:0,24'],
            'display_time_end' =>['bail','numeric', 'between:0,24',
                function ($attribute, $value, $fail) use ($request){
                if($request->display_time_start>=$value){
                    $fail(__('適切な表示時間帯を入力してください。'));
                }
            }],
            'drag_accuracy_type' =>['bail', 'in:0,1,2,3'],
        ],[
            'reservation_restrictions_type.in'=>'「予約制限」がありを選択された場合、「匿名予約の許可」使用できません。',
        ]);
        $validator->sometimes('reservation_restrictions_type', ['in:0'], function ($request) {
            return $request->anonymous_type==1;
        });
        if ($validator->passes()){
            $scheduleSetting = ScheduleSetting::first();
            $scheduleSetting->update($temp);
        }
        return $validator;
    }

    public function colorEdit($request){

        $validator = Validator::make($request->all(), [
            'palette_type' =>['bail','in:0,1,2'],
            'color_id' =>['bail', 'exists:schedule_color_type,id'],
        ]);
        if ($validator->passes()){
            $scheduleSetting = ScheduleSetting::first();
            $scheduleSetting->update($request->all());
        }
        return $validator;
    }

    public function getScheduleColorSettings(Request $request)
    {
        $builder = ScheduleColorSetting::select('id','name','color_id','order_num')->orderBy('order_num');
        return Datatables::of($builder->get())
            ->editColumn('select', function ($row){
                return '<input type="hidden" name="id" value="'.$row->id.'"/>';
            })
            ->editColumn('name', function ($row){
                return '<a href="javascript:void(0)" onclick="toEdit(this)"><input type="hidden" name="color_id" value="'.$row->color_id.'"/><input type="hidden" name="name" value="'.$row->name.'"/><button class="btn" style="background-color: '.$row->ScheduleColorType->css_name.';"></button>'.$row->name.'</a>';
            })
            ->editColumn('order_num', function ($row){
                return '<input type="text" name="order_num" class="table_input" value="'.$row->order_num.'"/>';
            })
            ->escapeColumns([])
            ->make(true);
    }




}
