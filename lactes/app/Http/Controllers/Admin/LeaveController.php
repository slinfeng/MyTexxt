<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Constants\RedisKey;
use App\Models\EmployeeAnnualLeave;
use App\Models\EmployeeBase;
use App\Http\Controllers\Controller;
use App\Models\HrSetting;
use App\Traits\NotifyTrait;
use App\Traits\PCAndMobileGateTrait;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Leave;
use Carbon\Carbon;
use Jenssegers\Agent\Facades\Agent;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class LeaveController extends Controller
{
    use NotifyTrait, PCAndMobileGateTrait;

    /**
     * インデックス画面
     * @return Application|Factory|View
     */
    public function index()
    {
        $loginId = Auth::id();
        $leaveDatesInfo = $this->getLeaveDaysInfo($loginId);
        $employeeBases = EmployeeBase::all();
        if (Agent::isMobile()) {
            $this->deniesForMobileModify(Leave::class);
            $employee_id = EmployeeBase::select('id')->where('user_id', '=', $loginId)->first()->id;
            $leaves = Leave::select('id', 'leave_from', 'leave_to', 'reason', 'status')->where('employee_base_id', '=', $employee_id)->orderBy('status', 'asc')->get();
            return view('mobile.personal.leaves.index', compact('leaves'));
        } else {
            $this->deniesForPCView(Leave::class);
            return view('admin.leaves.index', compact('leaveDatesInfo', 'employeeBases'));
        }

    }

    /**
     * インデックス画面　スマホ側
     * @return Application|Factory|View
     */
    public function auditIndex()
    {
        $this->deniesForMobileAudit(Leave::class);
        $leaves = Leave::with('EmployeeBase')->where('status', 0)->get();
        return view('mobile.examination.leaves.index', compact('leaves'));
    }

    /**
     * 休暇を変更権限に判定
     * @param $model
     */
    private function deniesForModify($model)
    {
        abort_if(Gate::denies($model::PC_MODIFY) && Gate::denies($model::MOBILE_MODIFY), Response::HTTP_FORBIDDEN);
    }

    /**
     * 休暇を新規登録
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->deniesForModify(Leave::class);
            $employees = EmployeeBase::all();
            $userId = Auth::id();
            $employee = EmployeeBase::get()->where('user_id', '=', $userId)->first();
            $sumDaysOfLeave = 0;
            if ($employee !== null) {
                $sumDaysOfLeave = $this->getLeaveDaysInfo($employee->id);
                $annualLeaveHasDays = $this->annualLeaveHasDays($employee->id);
            }
            if (Agent::isMobile()) return view('mobile.personal.leaves.create', compact('employee', 'sumDaysOfLeave', 'annualLeaveHasDays'));
            else return view('admin.leaves.create', compact('employees'));
    }

    /**
     * 休暇をデータベースに登録
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function store(Request $request)
    {
        $this->deniesForModify(Leave::class);
        $messages = [
            'reason.required' => __('休暇理由を入力してください！'),
            'employee_base_id.required' => __('社員が指定してください！'),
        ];
        $validator = Validator::make($request->all(), [
            'employee_base_id' => 'required',
            'reason' => 'required'
        ], $messages);
        if ($validator->fails()){
            return Reply::fail($validator->errors()->first());
        }
        $reqData = $request->all();


        if ($reqData['status'] == 1) $reqData['approved_by_user_id'] = Auth::id();
        $reqData['days_of_leave'] = (float)$reqData['days_of_leave'];
        $leave = Leave::create($reqData);
        if ($reqData['status'] == 1) $this->annualLeaveDaysChange($leave->id, $reqData['employee_base_id'], $reqData['days_of_leave']);
        return Reply::success(__('Leave is saved successfully.'));
    }

    /**
     * 社員の年内休暇総日数を取得
     * @param $employeeBaseId
     * @return mixed
     */
    private function getLeaveDaysInfo($employeeBaseId)
    {
        return Leave::where('employee_base_id', $employeeBaseId)
            ->where('leave_from', '>', date('Y') . '-01-01')
            ->where('leave_from', '<', ((int)date('Y') + 1) . '-01-01')
            ->where('status', 1)
            ->sum('days_of_leave');
    }

    /**
     * 休暇を編集
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        if (Agent::isMobile()) {
            $this->deniesForMobileModify(Leave::class);
            $leave = Leave::find($id);
            $userId = Auth::id();
            $employee = EmployeeBase::get()->where('user_id', '=', $userId)->first();
            $sumDaysOfLeave = 0;
            if (isset($employee)) {
                $sumDaysOfLeave = $this->getLeaveDaysInfo($employee->id);
                $annualLeaveHasDays = $this->annualLeaveHasDays($employee->id);
            }
            return view('mobile.personal.leaves.create', compact('leave', 'employee', 'sumDaysOfLeave', 'annualLeaveHasDays'));
        } else {
            $this->deniesForPCView(Leave::class);
            $leave = $this->statusHas($id);
            $employee = EmployeeBase::find($id);
            $annualLeaveHasDays = $this->annualLeaveHasDays($id);
            $sumDaysOfLeave = $this->getLeaveDaysInfo($id);
            return view('admin.leaves.edit', compact('leave', 'employee', 'sumDaysOfLeave', 'annualLeaveHasDays'));
        }
    }

    /**
     * 休暇をデータベースに更新
     * @param Request $request
     * @param $id
     * @return array|bool[]|string[]
     */
    public function update(Request $request, $id)
    {
        $this->deniesForModify(Leave::class);
        $messages = [
            'reason.required' => __('休暇理由を入力してください！'),
            'employee_base_id.required' => __('社員が指定してください！'),
        ];
        $validator = Validator::make($request->all(), [
            'reason' => 'required',
        ], $messages);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $employeeBaseId = $request->employee_base_id;
        if ($request['status'] == 1) {
            $startDate = $request->leave_from;
            $endDate = $request->leave_to;
            $result = Leave::where('employee_base_id', $employeeBaseId)
                ->whereBetween('leave_from', [$startDate, $endDate])
                ->whereIn('status', [0,1])
                ->when($id != ''&&$id != null, function ($q) use ($id) {
                    return $q->where('id', '!=', $id);
                })
                ->orWhereBetween('leave_to', [$startDate, $endDate])
                ->where('employee_base_id', $employeeBaseId)
                ->whereIn('status', [0,1])
                ->when($id != ''&&$id != null, function ($q) use ($id) {
                    return $q->where('id', '!=', $id);
                })
                ->orwhere('leave_to', '>', $endDate)
                ->where('leave_from', '<', $startDate)
                ->whereIn('status', [0,1])
                ->where('employee_base_id', $employeeBaseId)
                ->when($id != ''&&$id != null, function ($q) use ($id) {
                    return $q->where('id', '!=', $id);
                })
                ->count();
            if ($result > 0) return Reply::fail(__('leave date is conflicted.'));
        }
        $leave = Leave::find($id);
        if(!isset($leave)){
            return Reply::fail(__('当該休暇は削除されました。'));
        }
        if($leave->status!=0){
            return Reply::fail(__('当該休暇は削除されました。'));
        }
        $userId = Auth::id();
        $reqData = $request->all();
        $reqData['approved_by_user_id'] = $userId;
        $reqData['days_of_leave'] = (float)$request->days_of_leave;
        $leave->update($reqData);
        if ($request['status'] == 1) {
            $this->annualLeaveDaysChange($leave->id, $leave->employee_base_id, $leave->days_of_leave);
            $msg = $this->getMsg($leave);
            $this->createNotification($leave->EmployeeBase->user->id, $msg, RedisKey::NOTIFY_CONFIRMED);
        }
        if ($request['status'] == 2) {
            $msg = $leave->leave_from . 'の休暇';
            $redisKey = RedisKey::NOTIFY_DELETED;
            $this->createNotification($leave->EmployeeBase->user->id, $msg, $redisKey);
        }
        $annualLeaveHasDays = $this->annualLeaveHasDays($employeeBaseId);
        return Reply::success(__('Leave is updated successfully.'), ['annualLeaveHasDays' => $annualLeaveHasDays]);
    }

    /**
     * 時間をフォマード
     * @param $time
     * @return false|string
     */
    private function transTime($time)
    {
        return substr($time, 0, strlen($time) - 3);
    }

    /**
     * 休暇申し込みメッセージ
     * @param $leave
     * @return string:
     */
    private function getMsg($leave)
    {
        return $this->transTime($leave->leave_from) . 'から' . $this->transTime($leave->leave_to) . 'までの休暇申し込み';
    }

    /**
     * 休暇をデータベースに削除
     * @param $id
     * @return array|bool[]|string[]
     */
    public function destroy($id)
    {
        $this->deniesForModify(Leave::class);
        $leave = Leave::find($id);
        if ($leave->delete()) {
            $leaveDatesInfo = $this->getLeaveDaysInfo(Auth::id());
            return Reply::success(__('Leave is deleted successfully.'), $leaveDatesInfo);
        } else return Reply::fail(__('Leave delete failed, please try again.'));
    }

    /**
     *　社員全員休暇情報リストを表示
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getEmployeeList(Request $request)
    {
        $this->deniesForPCView(Leave::class);
        $builder = EmployeeBase::with('user:id,name')->when($request->employeeType == "false", function ($query) {
            $query->where("retire_type_id", '!=', 2);
        });
        return Datatables::of($builder->get())
            ->addColumn(
                'employee_id',
                function ($row) {
                    return str_pad($row->employee_code, 4, '0', STR_PAD_LEFT);
                })
            ->addColumn(
                'status',
                function ($row) {
                    return $this->contactHas($row->id) ? '<span style="color: red;">連絡中</span>' : '<span>未連絡</span>';
                })
            ->addColumn(
                'apply_user',
                function ($row) {
                    return $row->retire_type_id == 2 ? $row->user->name . " (退職)" : $row->user->name;
                })
            ->addColumn(
                'action',
                function ($row) {
                    $string = '<div class="dropdown dropdown-action">
                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">';
                    $string .= '<a class="dropdown-item edit_leave" href="javascript:void(0)" onclick="editLeave(' . $row->id . ')">';
                    if (!Gate::denies(Leave::PC_MODIFY)) {
                        $string .= '<i class="fa fa-pencil m-r-5"></i> ' . __('編集・承認');
                    } else {
                        $string .= '<i class="fa fa-eye m-r-5"></i> ' . __('表示');
                    }
                    $string .= '</a></div></div>';
                    return $string;
                }
            )
            ->addColumn(
                'has_days',
                function ($row) {
                    return $this->annualLeaveHasDays($row->id) . '日';
                })
            ->editColumn(
                'work_year',
                function ($row) {
                    list($by, $bm, $bd) = explode('-', $row->date_hire);
                    if (isset($row->date_retire)) {
                        list($cy, $cm, $cd) = explode('-', $row->date_retire);
                        return sprintf("%.2f", $cy - $by + round(($cm - $bm) / 12, 2));
                    }
                    return sprintf("%.2f", date('Y') - $by + round((date('n') - $bm) / 12, 2));
                })
            ->addColumn(
                'annual_leave_days',
                function ($row) {
//                    return $row->annual_leave_type==1?$this->annualLeaveDays($row->id).'日':'';
                    return $this->annualLeaveDays($row->id) . '日';
                }
            )
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * 社員個人休暇情報リストを表示
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getEmployeeLeaves(Request $request)
    {
        $this->deniesForPCView(Leave::class);
        $builder = Leave::with('EmployeeBase')->where('employee_base_id', $request->id);
        return Datatables::of($builder->get())
            ->addColumn(
                'action',
                function ($row) {
                    $string='';
                    if (Gate::allows(Leave::PC_MODIFY)) {
                        $string = '<div class="dropdown dropdown-action">
                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">';
                        if (Agent::isMobile()) {
                            $string .= '<a href="' . route('leaves.edit', $row->id) . '" class="dropdown-item edit_leave" href="javascript:void(0)">
                                    <i class="fa fa-pencil m-r-5"></i> ' . __('編集') . '</a>';
                        } else {
                            $string .= '<a class="dropdown-item edit_leave" href="javascript:void(0)" onclick="editLeaveOne(' . $row->id . ')">
                                    <i class="fa fa-pencil m-r-5"></i> ' . __('編集・承認') . '</a>';
                        }
                        if (((($row->status == 0) && isset($row->EmployeeBase) ? ($row->EmployeeBase->user_id == Auth::id()) : false)) || (Gate::allows(Leave::PC_MODIFY))) {
                            $string .= '<a class="dropdown-item" href="#" onclick="deleteLeave(' . $row->id . ')"    >
                                    <i class="fa fa-trash-o m-r-5"></i> ' . __('Delete') . '</a>';
                        }
                        $string .= '</div></div>';
                    }
                    return $string;
                }
            )
            ->addColumn(
                'approved_date',
                function ($row) {
                    if ($row->status != 0) {
                        return date('Y-m-d H:i', strtotime($row->updated_at));
                    }
                    return "";
                })
            ->addColumn(
                'approved_by_user',
                function ($row) {
                    if ($row->status != 0) {
                        return isset($row->User) ? $row->User->name : "";
                    }
                }
            )
            ->addColumn(
                'leave_date',
                function ($row) {
                    return date('Y-m-d H:00', strtotime($row->leave_from)) . "～" . date('Y-m-d H:00', strtotime($row->leave_to));
                })
            ->addColumn(
                'status_action',
                function ($row) {
                    $statusArr = [
                        0 => "確認中",
                        1 => "承認済",
                        2 => "拒否",
                    ];
                    return $statusArr[$row->status];
                }
            )
            ->editColumn(
                'memo',
                function ($row) {
                    return mb_strlen($row->memo) > 8 ? mb_strcut($row->memo, 0, 8) . '...' : $row->memo;
                }
            )
            ->editColumn(
                'reason',
                function ($row) {
                    return mb_strlen($row->reason) > 8 ? mb_strcut($row->reason, 0, 5) . '...' : $row->reason;
                }
            )
            ->editColumn(
                'days_of_leave',
                function ($row) {
                    return $row->days_of_leave . '日';
                }
            )
            ->escapeColumns([])
            ->rawColumns(['status'])
            ->make(true);
    }

    /**
     * 最新休暇を取得
     * @param $id
     * @return mixed
     */
    private function statusHas($id)
    {
        $leave = Leave::where('employee_base_id', $id)->where('status', 0)->orderby('leave_from', 'desc')->first();
        return isset($leave) ? $leave : Leave::where('employee_base_id', $id)->orderby('created_at', 'desc')->first();
    }

    /**
     * 個人休暇申し込み状態を判定
     * @param $id
     * @return bool
     */
    private function contactHas($id)
    {
        $leave = Leave::where('employee_base_id', $id)->where('status', 0)->first();
        return isset($leave);
    }

    /**
     * 年間累計年休日数の統計
     * @param $id
     * @return mixed
     */
    private function annualLeaveDays($id)
    {
        $hrSetting = HrSetting::first();
        $cumulative_years = $hrSetting->cumulative_years;
        $endYear = Carbon::now()->year;
        $startYear = $endYear - $cumulative_years + 1;
        return EmployeeAnnualLeave::where('employee_base_id', $id)
            ->whereBetween('year', [$startYear, $endYear])
            ->sum('days');
    }

    /**
     * 年間累計年休残り日数の統計
     * @param $id
     * @return mixed
     */
    private function annualLeaveHasDays($id)
    {
        $hrSetting = HrSetting::first();
        $cumulative_years = $hrSetting->cumulative_years;
        $endYear = Carbon::now()->year;
        $startYear = $endYear - $cumulative_years + 1;
        $employee=EmployeeBase::find($id);
        $employeeAnnualLeave=EmployeeAnnualLeave::where('employee_base_id', $id)->where('year', $endYear)->get();
        if($employee->annual_leave_type==1 && sizeof($employeeAnnualLeave)==0){
            $endDate=Carbon::createFromDate($endYear,1,1);
            $startDate=Carbon::parse($employee->date_hire);
            $workYear=$endDate->diffInYears($startDate);
            $days=$hrSetting->first_year_leave+($hrSetting->grow_leave*$workYear);
            $days=$days<$hrSetting->max_annual_leave?$days:$hrSetting->max_annual_leave;
            EmployeeAnnualLeave::create([
                'employee_base_id'=>$id,
                'year'=>$endYear,
                'days'=>$days,
                'has_days'=>$days]);
        }
        return EmployeeAnnualLeave::where('employee_base_id', $id)
            ->whereBetween('year', [$startYear, $endYear])
            ->sum('has_days');
    }

    /**
     * 休暇申し込みを承認した後、年休を再計算
     * @param $id
     * @param $employeeId
     * @param $leaveDays
     */
    private function annualLeaveDaysChange($id, $employeeId, $leaveDays)
    {
        $hrSetting = HrSetting::first();
        $cumulative_years = $hrSetting->cumulative_years;
        $endYear = Carbon::now()->year;
        $startYear = $endYear - $cumulative_years + 1;
        $annualLeaveHasDays = $this->annualLeaveHasDays($employeeId);
        $remainDays = $annualLeaveHasDays - $leaveDays;
        if ($remainDays > 0) {
            $updateData = [
                'annual_leave_on' => $leaveDays,
            ];
        } else {
            $updateData = [
                'annual_leave_on' => $annualLeaveHasDays,
            ];
        }
        $leave = Leave::find($id);
        $leave->update($updateData);
        $employeeAnnualLeaves = EmployeeAnnualLeave::where('employee_base_id', $employeeId)->whereBetween('year', [$startYear, $endYear])->get();
        if (count($employeeAnnualLeaves) > 0) {
            foreach ($employeeAnnualLeaves as $employeeAnnualLeave) {
                $days = $employeeAnnualLeave->days;
                if ($remainDays <= 0) {
                    $employeeAnnualLeave->has_days = 0;
                    $employeeAnnualLeave->save();
                } else {
                    if ($remainDays > $days) {
                        $remainDays -= $days;
                    } else {
                        $employeeAnnualLeave->has_days = $remainDays;
                        $employeeAnnualLeave->save();
                        $remainDays = 0;
                    }
                }
            }
        }
    }

    /**
     * 指定休暇を取得
     * @param $id
     * @return mixed
     */
    public function getLeaveOne($id)
    {
        $this->deniesForPCView(Leave::class);
        return Leave::find($id);
    }

    /**
     * 携帯側の休暇を承認/拒否
     * @param Request $request
     * @param $id
     * @return array|string[]
     */
    public function changeStatus(Request $request, $id)
    {
        $this->deniesForManage(Leave::class);
        $leave = Leave::find($id);
        if(!isset($leave)){
            return Reply::fail(__('当該休暇は削除されました。'));
        }
        if($leave->status!=0){
            return Reply::fail(__('当該休暇は削除されました。'));
        }
        $user = Auth::user();
        $leave->status = $request->status;
        $leave->approved_by_user_id = $user->id;
        $leave->update();
        $leaveDatesInfo = $this->getLeaveDaysInfo($leave->EmployeeBase->id);
        $msg = $this->getMsg($leave);
        if ($leave->status == 1) {
            $this->annualLeaveDaysChange($leave->id, $leave->employee_base_id, $leave->days_of_leave);
            $action = RedisKey::NOTIFY_CONFIRMED;
        } else $action = RedisKey::NOTIFY_DELETED;
        $this->createNotification($leave->EmployeeBase->user->id, $msg, $action);
        return Reply::success(__('Status Is changed.'), $leaveDatesInfo);
    }

    /**
     * 休暇期間の検証
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function leaveDateValidate(Request $request)
    {
        $this->deniesForModify(Leave::class);
        $id = $request->id;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $employeeBaseId = $request->employee_base_id;
        $result = Leave::where('employee_base_id', $employeeBaseId)
            ->whereBetween('leave_from', [$startDate, $endDate])
            ->whereIn('status', [0,1])
            ->when($id != ''&&$id != null, function ($q) use ($id) {
                return $q->where('id', '!=', $id);
            })
            ->orWhereBetween('leave_to', [$startDate, $endDate])
            ->where('employee_base_id', $employeeBaseId)
            ->whereIn('status', [0,1])
            ->when($id != ''&&$id != null, function ($q) use ($id) {
                return $q->where('id', '!=', $id);
            })
            ->orwhere('leave_to', '>=', $endDate)
            ->where('leave_from', '<=', $startDate)
            ->whereIn('status', [0,1])
            ->where('employee_base_id', $employeeBaseId)
            ->when($id != ''&&$id != null, function ($q) use ($id) {
                return $q->where('id', '!=', $id);
            })
            ->count();
        if ($result > 0) return Reply::fail(__('leave date is conflicted.'));
        return Reply::success(__('success'));
    }

    /**
     * 累計年休残り総数と今年度休暇総数を取得
     * @param Request $request
     * @return mixed
     */
    public function getAnnualLeaveHasDays(Request $request)
    {
        abort_if(Gate::none([Leave::PC_VIEW,Leave::PC_MODIFY,Leave::MOBILE_AUDIT,Leave::MOBILE_MODIFY]),Response::HTTP_FORBIDDEN);
        $employee['annualLeaveHasDays'] = $this->annualLeaveHasDays($request->id);
        $employee["sumDaysOfLeave"] = $this->getLeaveDaysInfo($request->id);
        return $employee;
    }
}
