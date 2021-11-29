<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Classes\Reply;

use App\Models\AdminSetting;
use App\Models\Session;
use App\Traits\PCGateTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


use Yajra\DataTables\DataTables;
class SessionController extends Controller
{
    use PCGateTrait;

    /**
     * インデックス画面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.sessions.index');
    }
    /**
     * セッション情報　データテーブル用
     * @return mixed
     * @throws \Exception
     */
    public function getSessions()
    {
        $this->deniesForView(AdminSetting::class);
        $sessions = Session::with('user:id,name')->orderBy('last_activity','desc');
        return Datatables::of($sessions)
            ->editColumn(
                'last_activity',
                function ($row) {
                    return Carbon::createFromTimestamp($row->last_activity)->format('Y-m-d H:i:s');
                }
            )
            ->editColumn(
                'name',
                function ($row) {
                    if(isset($row->user)) return $row->user->name;
                    return '';
                }
            )
            ->removeColumn('id')
            ->make(true);
    }


    public function destroy($id)
    {
        $this->deniesForModify(AdminSetting::class);
        Session::where('id', $id)->delete();
        return Reply::success(__('Session is deleted successfully.'));
    }

}
