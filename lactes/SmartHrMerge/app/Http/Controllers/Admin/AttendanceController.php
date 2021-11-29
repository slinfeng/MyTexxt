<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Constants\RedisKey;
use App\Models\Attendance;
use App\Http\Controllers\Controller;
use App\Models\EmployeeBase;
use App\Models\File;
use App\Models\HrSetting;
use App\Models\RequestSettingExtra;
use App\Rules\Amount;
use App\Rules\FileMimeType;
use App\Rules\Period;
use App\Traits\NotifyTrait;
use App\Traits\PCAndMobileGateTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Facades\Agent;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller
{
    use NotifyTrait,PCAndMobileGateTrait;

    /**
     * index
     * @return Application|Factory|View
     */
    public function index()
    {
        if(Agent::isMobile()){
            $this->deniesForMobileModify(Attendance::class);
            $date = Carbon::today()->startOfMonth()->addMonth(-2)->format('Ym');
            $employee_id = Auth::user()->Employee->id;
            $currency = RequestSettingExtra::select('currency')->first()->currency;
            $attendances = Attendance::where('employee_id',$employee_id)->where('year_and_month','>=',$date)->orderBy('year_and_month','desc')->get();
            return view('mobile.personal.attendance.index',compact('attendances','currency'));
        } else{
            $this->deniesForPCView(Attendance::class);
            $employees = EmployeeBase::where('retire_type_id', '!=', 2)->get();
            $requestSettingExtra = RequestSettingExtra::select('local_ip_addr','currency')->first();
            return view('admin.attendances.index',compact('employees','requestSettingExtra'));
        }
    }

    /**
     * update
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function update(Request $request)
    {
        $this->deniesForPCModify(Attendance::class);
        $rules = [
            'idArr.*' => 'exists:attendance,id',
            'workingTimeArr.*' => ['bail','required','numeric','max:999'],
            'expenseArr.*' => ['bail','required',new Amount('精算費用')],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return Reply::fail($validator->errors()->first());
        }
        $idArr = $request->idArr;
        $workingTimeArr = $request->workingTimeArr;
        $expenseArr = $request->expenseArr;
        DB::beginTransaction();
        for ($i = 0; $i < count($idArr); $i++) {
            $attendance = Attendance::find($idArr[$i]);
            if($workingTimeArr[$i]!=0){
                $attendance->working_time = $workingTimeArr[$i];
            }
            if($expenseArr[$i]!=-1){
                $attendance->transportation_expense = $expenseArr[$i];
            }
            $attendance->save();
        }
        DB::commit();
        return Reply::success('保存しました。',['user' => Auth::user()->name]);
    }

    /**
     * 削除
     * @param Request $request
     * @return array|string[]
     */
    public function destroy(Request $request)
    {
        $this->deniesForPCModify(Attendance::class);
        $this->iteratorForId($request->idArr,function ($id){
            $attendance = Attendance::find($id);
            if(Storage::disk('local')->exists($attendance->file->path)) Storage::disk('local')->delete($attendance->file->path);
            Attendance::destroy($id);
        });
        return Reply::success(__('Attendance is deleted successfully.'));
    }

    /**
     * 拒否
     * @param Request $request
     * @return array|string[]
     */
    public function rejection(Request $request){
        $this->deniesForMobileAudit(Attendance::class);
        $this->iteratorForId($request->idArr,function ($id){
            $attendance = Attendance::find($id);
            $attendance->status = RedisKey::ATTENDANCE_REJECTED;
            $attendance->save();
            if($attendance->employee!=0){
                $msg = $this->transYearMonth($attendance->year_and_month).'の勤務表';
                $this->createNotification($attendance->employee->user->id,$msg,RedisKey::NOTIFY_DELETED);
            }

        });
        return Reply::success('勤務表を拒否しました。');
    }

    /**
     * 繰り返し
     * @param $idArr
     * @param $handle
     */
    private function iteratorForId($idArr,$handle){
        DB::beginTransaction();
        foreach ($idArr as $id){
            $handle($id);
        }
        DB::commit();
    }

    /**
     * YYYYMMからYYYY年MM月に変換
     * @param $year_and_month
     * @return string
     */
    private function transYearMonth($year_and_month){
        return substr($year_and_month,0,4).'年'.substr($year_and_month,-2).'月';
    }

    /**
     * datatable用
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getTableInfo(Request $request)
    {
        $this->deniesForPCView(Attendance::class);
        Auth::user()->update(['user_view_attendance'=>0]);
        $year_and_month = $request->year_and_month;
        $file_name = $request->file_name;
        $currency = RequestSettingExtra::select('currency')->first()->currency;
        $list = Attendance::whereHas('file', function ($query) use ($file_name){
            $query->where('basename','like','%'.$file_name.'%');
        })->where('year_and_month',$year_and_month)->where('status','!=',RedisKey::ATTENDANCE_REJECTED)->get();
        return Datatables::of($list)
            ->editColumn('id', '<input type="hidden" name="id" value="{{$id}}"/>')
            ->editColumn('file_name', function ($row) {
                return '<a href="javascript:(0)" data-sort="'.$row->file->basename.'" onclick="openFile(\''.$row->file_id.'\',\''.$row->file->type.'\')">'.$row->file->basename.'</a>';
            })
            ->editColumn('working_time', function ($row) {
                if($row->working_time==0)
                    $val = '未確認';
                else
                    $val = $row->working_time . '時間';
                return '<input class="float disable-input" name="working_time" value="'.$val.'" size="6" maxlength="6">';
            })
            ->editColumn('status', function ($row) {
                return $row->status==RedisKey::ATTENDANCE_WAIT?'<span class="color-red">未確認</span>':'確認済';
            })
            ->editColumn('transportation_expense', function ($row) use ($currency) {
                $val =  !empty($row->transportation_expense)?$row->transportation_expense:$currency.'0';
                return '<input class="amount disable-input" name="transportation_expense" value="'.$val.'" size="6" maxlength="6">';
            })
            ->editColumn('update_user', function ($row) {
                return $row->file->user->name;
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * カード表示
     * @param Request $request
     * @return Builder[]|Collection
     */
    public function getCardInfo(Request $request){
        $this->deniesForPCView(Attendance::class);
        $year_and_month = $request->year_and_month;
        $file_name = $request->file_name;
        Auth::user()->update(['user_view_attendance'=>1]);
        return Attendance::with('file','file.user')->whereHas('file', function ($query) use ($file_name){
            $query->where('basename','like','%'.$file_name.'%');
        })->where('year_and_month',$year_and_month)->where('status','!=',RedisKey::ATTENDANCE_REJECTED)->get();
    }

    /**
     * 勤務表新規
     * @param Request $request
     * @return array|bool[]|Application|RedirectResponse|Redirector|string[]
     */
    function uploadFile(Request $request){
        if(Agent::isMobile()){
            $this->deniesForMobileModify(Attendance::class);
            $request['employee_id']=Auth::user()->Employee->id;
            $messages['file.required'] = '勤務表写真を選択してください！';
        }else{
            $this->deniesForPCModify(Attendance::class);
        }
        $messages = [
//            'employee_id.required' => '社員を選択してください！',
            'year_and_month.required' =>'年月を選択してください！',
            'working_time.required' =>'勤務時間数を入力してください！',
            'file.required' => '勤務表ファイルを選択してください！',
        ];
        $rules = [
            'year_and_month' => ['bail','required','regex:/^[0-9]{4}年[0-9]{2}月$/'],
            'employee_id' => ['bail','sometimes','nullable','exists:employee_base,id'],
            'working_time' => ['bail','required','numeric','max:999'],
            'file' =>['bail','required','max_mb:10',new FileMimeType],
        ];
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()){
            if(Agent::isMobile()) {
                return back()->withErrors($validator)->withInput($_POST);
            }
            return Reply::fail($validator->errors()->first());
        } else {

            $fileinfo = $request->file('file');
            $year_and_month = $request->year_and_month;
            $temp['year_and_month'] = str_replace(['年','月'],'',$year_and_month);
            $temp['employee_id'] = 0;
            $temp['working_time'] = $request['working_time'];
            $temp['transportation_expense'] = $request['transportation_expense'];
            $temp['status'] = RedisKey::ATTENDANCE_WAIT;
            DB::beginTransaction();
            if (isset($fileinfo)) {
                if ($fileinfo->isValid()) {
                    //扩展名
                    $ext = $fileinfo->getClientOriginalExtension();
                    if(isset($request['employee_id'])){
                        $employee_name = EmployeeBase::find($request['employee_id'])->User->name;
                        $employee_code = str_pad(EmployeeBase::find($request['employee_id'])->employee_code,4,'0',STR_PAD_LEFT);
                        $temp['employee_id'] = $request['employee_id'];
                        $filename = $year_and_month . '_勤務表(' . $employee_name . '[' . $employee_code .']).' . $ext;
                    }else{
                        $filename = $fileinfo->getClientOriginalName();
                    }


                    $filepath = file_get_contents($fileinfo->getRealPath());
                    $path = 'attendance/' . $temp['year_and_month'] . '/' . $filename;
                    $attendance = Attendance::where('year_and_month', $temp['year_and_month'])->where('employee_id',$request['employee_id']);
                    if ($attendance->count() > 0) {
                        if($attendance->first()->status==RedisKey::ATTENDANCE_CONFIRMED && Agent::isMobile()){
                            return back()->withErrors(['confirm'=>'当該勤務表は既に担当者に確認されました。変更が必要である場合は担当者までご連絡ください。'])->withInput($_POST);
                        }
                        $file = File::find($attendance->first()->file_id);
                        $delFile = $file->path;
                        $delFlag = $filename != $file->basename;
                    }else{
                        $file = new File;
                    }
                        $bool = Storage::disk('attendance')->put('/' . $temp['year_and_month'] . '/' . $filename, $filepath);
                        if ($bool) {
                            $file->user_id = Auth::user()->id;
                            $file->path = $path;
                            $num = mb_strrpos($filename, '.');
                            $file->name = mb_substr($filename, 0, $num);
                            $file->basename = $filename;
                            $file->mimetype = $fileinfo->getClientMimeType();
                            $file->filesize = "" . $fileinfo->getSize();
                            $file->type = mb_substr($filename, $num + 1);
                            $file->is_in_local = File::IN_CLOUD;
                            $file->save();
                            $temp['file_id'] = $file->id;
                            if ($attendance->count() > 0){
                                $attendance->first()->update($temp);
                                if($delFlag) {
                                    if(Storage::disk('local')->exists($delFile)) Storage::disk('local')->delete($delFile);
                                }
                            } else
                                Attendance::create($temp);
                        } else{
                            return Reply::fail('アップロードが失敗しました！');
                        }

                }
            }
            DB::commit();
            if(Agent::isMobile()){
                return redirect(route('attendances.index'));
            }
            return Reply::success(__('勤務表をアップロードしました。'));
        }
    }

    /**
     * 勤務時間数確認（PC側）
     * @param Request $request
     * @return array|string[]
     */
    function confirmWorkingTime(Request $request){
        $this->deniesForPCModify(Attendance::class);
        $idArr = $request->idArr;
//        $timeArr = $request->timeArr;
//        $expenseArr = $request->expenseArr;
        for ($i = 0; $i < count($idArr); $i++) {
            $attendance = Attendance::find($idArr[$i]);
//            if($timeArr[$i]!=-1){
//                $attendance->working_time = $timeArr[$i];
//                $attendance->transportation_expense = $expenseArr[$i];
//            }
            $attendance->status = RedisKey::ATTENDANCE_CONFIRMED;
            $attendance->save();
            if(isset($attendance->employee->user)){
                $msg = $this->transYearMonth($attendance->year_and_month).'の勤務表';
                $this->createNotification($attendance->employee->user->id,$msg,RedisKey::NOTIFY_CONFIRMED);
            }
        }
        return Reply::success('勤務表を承認しました。',['idArr'=>$idArr]);
    }

    /**
     * 勤務時間数確認（携帯側）
     * @param Request $request
     * @return array|bool[]|string[]
     */
    function confirmWhenMobile(Request $request){
        $this->deniesForMobileAudit(Attendance::class);
        $rules = [
            'timeArr.*' => ['bail','required','numeric','max:999'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return Reply::fail($validator->errors()->first());
        }
        $idArr = $request->idArr;
        $timeArr = $request->timeArr;
        $expenseArr = $request->expenseArr;
        DB::beginTransaction();
        for ($i = 0; $i < count($idArr); $i++) {
            $attendance = Attendance::find($idArr[$i]);
            $attendance->working_time = $timeArr[$i];
            $attendance->transportation_expense = $expenseArr[$i];
            $attendance->status = RedisKey::ATTENDANCE_CONFIRMED;
            $attendance->save();
            if(isset($attendance->employee->user)){
                $msg = $this->transYearMonth($attendance->year_and_month).'の勤務表';
                $this->createNotification($attendance->employee->user->id,$msg,RedisKey::NOTIFY_CONFIRMED);
            }
        }
        DB::commit();
        return Reply::success('勤務表を承認しました。');
    }

    /**
     * ローカルサーバー用、勤務表ファイルをクラウドからローカルサーバーへ移行する
     * @return Application|Factory|View
     */
    function changeFilePath(){
        $cloud_attendance_period=HrSetting::select('id','cloud_attendance_period')->first()->cloud_attendance_period;
        $deadline = new Carbon('-'.($cloud_attendance_period-1).' month');
        $attendances = Attendance::where('year_and_month','<',$deadline->format('Ym'))->get();
        DB::beginTransaction();
        foreach ($attendances as $attendance){
            $file = $attendance->file()->first();
            $file->update(['is_in_local'=>File::IN_LOCAL]);
            if(Storage::disk('local')->exists($file->path)){
                Storage::disk('local')->delete($file->path);
            }
        }
        DB::commit();
        return view('admin.attendances.closeTab');
    }

    /**
     * 勤務表年月ごとに合計の取得
     * @param Request $request
     * @return array|string[]
     */
    function getAttendanceCountInMonth(Request $request){
        $this->deniesForPCView(Attendance::class);
        $startDate=Carbon::parse($request->startDate)->addMonth(-1);
        $endDate=Carbon::parse($request->endDate);
        $resArr=[];
        for($endDate;$endDate>$startDate;$endDate->addMonth(-1)){
           $selDate= $endDate->format('Ym');
            $countInMonth = Attendance::where('year_and_month',$selDate)->where('status','!=',RedisKey::ATTENDANCE_REJECTED)->count();
            $flag = Attendance::where('year_and_month',$selDate)->where('status',RedisKey::ATTENDANCE_WAIT)->exists();
            if($flag){
                $selDate = '<span class="color-red">'.$selDate.'('.$countInMonth.')</span>';
            }else{
                $selDate =$selDate.'('.$countInMonth.')';
            }
            array_push($resArr,$selDate);
        }
        return Reply::success(__('success'),$resArr);
    }

    /**
     * 携帯管理側
     * @return Application|Factory|View
     */
    function getAttendanceForManage(){
        $this->deniesForMobileAudit(Attendance::class);
        $datas = Attendance::where('status',RedisKey::ATTENDANCE_WAIT)->get();
        $currency = RequestSettingExtra::select('currency')->first()->currency;
        return view('mobile.examination.attendance.index',compact('datas','currency'));
    }
}
