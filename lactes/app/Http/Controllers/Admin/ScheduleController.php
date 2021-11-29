<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\ScheduleColorType;
use App\Models\ScheduleDetail;
use App\Models\ScheduleGroup;
use App\Models\ScheduleGroupScheduleMemberSetting;
use App\Models\ScheduleMemberSetting;
use App\Models\ScheduleSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index(){
        return view('admin.schedule.index');
    }

    public function saveSchedule(Request $request)
    {

        DB::beginTransaction();
        $reqData=$request->all();

        $memberIdArr=explode(',',$reqData['memberIdArr']);
        $scheduleDateArr=explode(',',$reqData['schedule_date_arr']);
        $i=0;
        $j=0;
        foreach ($memberIdArr as $memberId){
            $i++;
            foreach ($scheduleDateArr as $scheduleDate){
                $j++;
                $reqData['member_id']=$memberId;
                $reqData['schedule_date']=$scheduleDate;
                if(isset($reqData['id']) && $reqData['id']>0 && $i==1 && $j==1){
                    $scheduleDetail=ScheduleDetail::find($reqData['id']);
                    $scheduleDetail->update($reqData);
                    $reqData['id']='';
                }else{
                    ScheduleDetail::create($reqData);
                }
            }
        }
        DB::commit();
        return Reply::success(__('スケジュールを追加しました.'));
    }

    public function getSchedules(Request $request){
        $id=$request['id'];
        $memberId=$request['memberId'];
        $date=$request['date'];
        $date=$date[0].'-'.$date[1].'-'.$date[2];
//        $items = User::select('id','name')->get();

        $data=ScheduleDetail::with('ScheduleColorType','ScheduleMemberSetting','User');
        $members=ScheduleGroup::find($id)->ScheduleMemberSetting;
        if(isset($memberId) && $memberId!=''){
            $items=[];
            $selectMonth=Carbon::parse($date);
            $foolMonth=Carbon::parse($date);

            for($i=0;$i<$foolMonth->daysInMonth;$i++){
                $pushItem=[
                    'id'=>$i,
                    'name'=>$selectMonth->format('Y-m-d'),
                    ];
                array_push($items,$pushItem);
                $selectMonth=$selectMonth->addDays(1);
            }
            $data=$data->where('member_id',$memberId)->where('schedule_date','like',Carbon::parse($date)->format('Y-m').'%')->get();
        }else{
            $items = $members;
            $data=$data->where('schedule_date',$date)->get();
        }

        return Reply::success('',['items'=>$items,'data'=>$data,'members'=>$members]);
    }

    public function getScheduleData($id){
//        $items = User::select('id','name')->get();
//        $members = ScheduleGroup::find($id)->ScheduleMemberSetting;
        $data=ScheduleDetail::all();
        return Reply::success('',['data'=>$data]);
    }

    public function getSchedulesGroup($id){
        $setting = ScheduleSetting::first();
        $members=ScheduleGroup::find($id)->ScheduleMemberSetting;
        $membersCount = $members->count();
        $scheduleColorTypes=ScheduleColorType::all();
        return view('admin.schedule.index',compact('id','setting','members','membersCount','scheduleColorTypes'));
    }
    function getSchedulesCalendar(Request $request){
        $time = $request->time;
        $date = $time[0].'-'.$time[1].'-%';
        $member_id = $request->member_id;
        return ScheduleDetail::where('member_id',$member_id)->where('schedule_date','like',$date)->where('anonymous_type',0)->orderBy('schedule_date')->orderBy('schedule_time')->get();
    }

    function deleteSchedule(Request $request){
        DB::beginTransaction();
        $scheduleDetail=ScheduleDetail::find($request->id);
        $scheduleDetail->delete();
        DB::commit();
        return Reply::success('選択されたスケジュールを削除しました。');
    }
}
