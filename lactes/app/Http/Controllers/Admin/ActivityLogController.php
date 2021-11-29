<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;

class ActivityLogController extends Controller
{
    /**
     * index
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('admin.activity.index');
    }

    /**
     * 削除
     * @param $id
     * @return array|string[]
     */
    public function destroy($id)
    {
        Activity::where('id', $id)->delete();
        return Reply::success(__('Activity Log is deleted successfully.'));
    }

    /**
     * datatable用
     * @return mixed
     * @throws Exception
     */
    public function activityLog()
    {
        $activities = ActivityLog::with('user:id,name')->select('id', 'log_name',
            'description',
            'subject_type',
            'subject_id',
            'causer_type',
            'causer_id',
            'created_at')
            ->latest()->limit(100)->get();
        return Datatables::of($activities)
            ->editColumn('created_at',
                function ($row) {
                    return Carbon::parse($row->created_at);
                }
            )
            ->editColumn('causer_id',
                function ($row) {
                    try{
                        return $row->user->name;
                    }catch(\Exception $e){
                        return DB::table('activity_log')->select('causer_id')->find($row->id)->causer_id;
                    }
                }
            )
            ->addColumn(
                'action',
                function ($row) {
                    return '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_user"  onclick="deleteAlert(\'' . $row->id . '\')"    >
                                    <i class="fa fa-trash-o m-r-5"></i> ' . __('Delete') . '</a>';
                }
            )->addIndexColumn()
            ->escapeColumns([])
            ->rawColumns(['description'])
            ->make(true);
    }
}
