<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Models\AdminSetting;
use App\Models\Client;
use App\Models\EmailTemplate;
use App\Models\EmployeeBase;
use App\Models\Role;
use App\Models\User;
use App\Models\UserIpAddress;
use App\Traits\PCGateTrait;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Facades\Agent;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class UsersController extends Controller
{
    use PCGateTrait;

    /**
     *
     * インデックス画面
     * @return Factory|View
     */
    public function index()
    {
        $this->deniesForView(User::class);
        $id = User::has('employee')->pluck('id');
        $users = User::with(['roles:id,title'])->whereNotIn('id',  $id)->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * 新規追加画面
     * @return Factory|View
     */
    public function create()
    {
        $this->deniesForModify(User::class);
        $roles = Role::where('id','>',1)->get();
        $clients=Client::where('our_role',1)->get();
        return view('admin.users.create', compact('roles','clients'));
    }

    /**
     * 新規追加保存
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies(User::MODIFY), Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(), $this->getValidatorArr(),$this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        if(in_array(8,$request->input('roles', [])) && ($request->client_id==0)){
            return Reply::fail(__('社外請求担当権限を付与すると、取引先を本社にするのはできません。'));
        }
        if(sizeof($request->input('roles', []))>1 && in_array(8,$request->input('roles', []))){
            return Reply::fail(__('社外請求担当権限と他の権限を同時に付与することはできません'));
        }
        if(!in_array(8,$request->input('roles', []))){
            $request['client_id']=0;
        }
        DB::beginTransaction();
        $user = User::create($request->all());
        if(in_array(8,$request->input('roles', []))) $this->sendAccountInfoMailToUser($user,$request->password);
        $user->roles()->sync($request->input('roles', []));
        $result=[
            'user_id' => $user->id,
            'user_name' => $user->name
        ];
        DB::commit();
        return Reply::success(__('User is added successfully.'),$result);
    }

    /**
     * お客にアカウント情報をメールで送信する
     * @throws Exception
     */
    function sendAccountInfoMailToUser($user,$password){
        $emailId = 'USER_CREATE';
        $emailInfoArr = [$user->email,$user->name];
        $fieldValues = ['COMPANYNAME'=>AdminSetting::first()->company_short_name,
                'CLIENTNAME'=>$user->client->client_name,
                'FROM'=>Auth::user()->name,
                'TO'=>$user->name,
                'EMAIL'=>$user->email,
                'PASSWORD'=>$password,
            ];
        EmailTemplate::prepareAndSendEmail($emailId, $emailInfoArr, $fieldValues,true);
    }

    /**
     * 共通バリデーション取得
     * @return string[]
     */
    public function getCommonValidator(){
        return [
            'name' => 'bail|required|max:200',
        ];
    }
    /**
     * バリデーション取得
     * @return array
     */
    public function getValidatorArr(){
        return array_merge($this->getCommonValidator(),[
            'email' => 'bail|required|email|unique:users',
            'password' => ['bail','required', 'min:6', 'max:16', 'confirmed', 'different:old_password'],
            'password_confirmation' => ['bail','required', 'min:6', 'max:16'],
        ]);
    }
    /**
     * バリデーションメッセージ取得
     * @return array
     */
    public function getValidatorMessage(){
        return [
            'email.required' => __('validation.required', ['attribute' => 'メールアドレス']),
            'email.email' => __('validation.email', ['attribute' => 'メールアドレス']),
            'email.unique' => __('validation.unique', ['attribute' => 'メールアドレス']),
        ];
    }

    /**
     * 社員情報を新規する時に関連ユーザー情報はまだ登録していませんすれば、新しいユーザーのバリデーション。
     * @param Request $request
     * @return array
     */
    public function verify(Request $request){
        $validator = Validator::make($request->all(), $this->getValidatorArr(),$this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        return Reply::success(__(''));
    }

    /**
     * 編集画面
     * @param User $user
     * @return Factory|View
     */
    public function edit(User $user)
    {
        $roles = Role::where('id','>',1)->get();
        $user->load('roles');
        $clients=Client::where('our_role',1)->get();
        return view('admin.users.edit', compact('roles', 'user','clients'));
    }
    /**
     * ユーザー情報編集保存
     * @param Request $request
     * @param User $user
     * @return array|string[]
     */
    public function update(Request $request, User $user)
    {
        $this->deniesForModify(User::class);
        $validator = Validator::make($request->all(), array_merge($this->getCommonValidator(),[
            'email' => ['bail','required',Rule::unique('users','email')->whereNot('id',$user->id)],
            'roles.*' => ['bail','nullable','exists:roles,id'],
        ]),$this->getValidatorMessage());
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        if(in_array(8,$request->input('roles', []))&&($request->client_id==0)){
            return Reply::fail(__('社外請求担当権限を付与すると、取引先が本社を選択できません。'));
        }
        if(sizeof($request->input('roles', []))>1 && in_array(8,$request->input('roles', []))){
            if(!(sizeof($request->input('roles', []))==2 && in_array(7,$request->input('roles', [])))){
                return Reply::fail(__('社外請求担当権限が社員以外権限を同時に付与することはできません'));
            }
        }
        if(!in_array(8,$request->input('roles', []))){
            $request['client_id']=0;
        }
        DB::beginTransaction();
        $user->update($request->all());
        if(Auth::user()->id!=$user->id){
            $user->roles()->sync($request->input('roles', []));
        }
        DB::commit();
        return Reply::success(__('User is updated successfully.'));

    }

    public function show(User $user)
    {
        $this->deniesForView(User::class);
        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * ユーザー情報削除
     * @param User $user
     * @return array
     */
    public function destroy(User $user)
    {
        $this->deniesForView(User::class);
        $employee=$user->Employee;
        if(!isset($employee)){
            $user->activity_log()->update(['causer_id'=>$user->email.'（削除済）']);
            $user->delete();
            return Reply::success(__('User is deleted successfully.'));
        }
        return Reply::fail(__('当該ユーザーは社員情報と関連しています。削除はできません。'));
    }

    public function updateProfile(Request $request)
    {
        auth()->user()->update(['name' => $request->name]);
        return Reply::success(__('Profile is updated successfully.'));
    }

    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => $request->get('password')]);
        return Reply::success(__('Password successfully updated.'));
    }

    /**
     * ユーザー情報　データテーブル用
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getUsersList(Request $request)
    {
        $this->deniesForView(User::class);
        $builder = User::with('client:id,client_name')->when($request->id, function($query) use ($request){
            $condition="RIGHT(CONCAT('000',id),4) like '%{$request->id}%'";
            $query->whereRaw($condition);
        })->when(Auth::user()->id!=1,function ($query){
            $query->where('id','>',1);
        });
        $list = $builder->get();

        return Datatables::of($list)
            ->editColumn(
                'action',
                function($row) {
                    $string = '';
                    if(Gate::allows(User::MODIFY) && $row->roles[0]->id>1 || Auth::user()->roles[0]->id==1){
                        $string = '<div class="dropdown dropdown-action">
                        <a href="javascript:void(0)" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">';
                        $string .= '<a class="dropdown-item edit_user" href="javascript:void(0)" data-toggle="modal" onclick="editModal(' . $row->id . ')"  >
                                <i class="fa fa-pencil m-r-5"></i> ' . __('Edit') . '</a>';
                        if(Auth::user()->id !== $row->id) {
                            $string .= '<a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#delete_user"  onclick="deleteAlert('.$row->id.')"    >
                                <i class="fa fa-trash-o m-r-5"></i> '.__('Delete').'</a>';
                        }
                        $string .= '</div></div>';
                    }
                    return $string;
                }
            )
            ->addColumn(
                'registered_date',
                function ($row) {
                    if(isset($row->created_at)){
                        return $row->created_at->format("Y-m-d");
                    }
                    return "";
                }
            )
            ->addColumn(
                'roles',
                function ($row) {
                    $string = '';
                    if(isset($row->roles)){
                        foreach ($row->roles as $role){
                            $string   .= '<span class="badge   badge-primary  m-1">'.$role->title.'</span>';
                        }
                        return $string;
                    }
                    return '<span class="badge   badge-warning  m-1"> '.__('No Data').'</span>';
                }
            )
            ->addColumn(
                'client_name',
                function ($row) {
                    if(isset($row->client)){
                        return $row->client->client_name;
                    }
                    return '本社';
                }
            )
            ->editColumn(
                'id',
                function ($row) {
                    return str_pad($row->id,4,'0',STR_PAD_LEFT);
                }
            )
            ->escapeColumns([])
            ->make(true);
    }


    public function showResetForm()
    {
        if(Agent::isMobile()) return view('mobile.personal.user.reset');
        else return view('auth.passwords.update');
    }

    public function passwordUpdate(PasswordRequest $request)
    {
        auth()->user()->update(['password' => $request->get('password')]);
        if(Agent::isMobile()){
            $user=Auth::user();
            return view('mobile.personal.user.index', compact( 'user'));
        }else return redirect('home');
    }

    public function showInfo()
    {
        $this->deniesForView(User::class);
        $user=Auth::user();
        return view('mobile.user.info', compact( 'user'));
    }

    public function infoUpdate(Request $request)
    {
        $this->deniesForModify(User::class);
        auth()->user()->update(['name' => $request->get('name')]);
        $user=Auth::user();
        return view('mobile.user.index', compact( 'user'));
    }
    public function setChartType(Request $request){
        $index = $request->index;
        $val=$request->val;
        $user = Auth::user();
        $home_chart_type = $user->home_chart_type;
        $home_chart_type[$index]=$val;
        $user->home_chart_type=$home_chart_type;
        $user->save();
        return;
    }
}
