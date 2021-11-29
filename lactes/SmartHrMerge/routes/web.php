<?php

use App\Models\File;
use App\Models\RequestSettingExtra;
use Jenssegers\Agent\Facades\Agent;
use League\Flysystem\FileNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    if(Auth::check()) return redirect()->route('home');
    if (Agent::isMobile()) {
        return view('mobile.welcome');
    }else {
        return view('welcome');
    }
});
Route::get('/chartType',[App\Http\Controllers\Admin\UsersController::class, 'setChartType'])->name('setChartType');
//Route::get('/test',function (){
//    $requestSettingClient = \App\Models\RequestSettingClient::all();
//    $global = \App\Models\RequestSettingGlobal::find(5);
//    $extra = \App\Models\RequestSettingExtra::first();
//    foreach ($requestSettingClient as $client){
//        $client->use_init_val = $global->use_init_val;
//        $client->company_info = $global->company_info;
//        $client->remark_start = $global->remark_start;
//        $client->remark_end = $global->remark_end;
//        $client->project_name = $global->project_name;
//        $client->contract_type = $extra->contract_type;
//        $client->contract_type_other_remark = $extra->contract_type_other_remark;
//        $client->create_month = $global->create_month;
//        $client->create_day = $global->create_day;
//        $client->period = $global->period;
//        $client->work_place = $global->work_place;
//        $client->payment_contract = $global->payment_contract;
//        $client->request_pay_month = $extra->request_pay_month;
//        $client->request_pay_date = $extra->request_pay_date;
//        $client->save();
//    }
//});
Route::get('/showInfo', [App\Http\Controllers\Admin\ShowInfoController::class, 'index'])->name('showInfo');;
//Route::get('/showInfo',function (){
//    return view('showInfo');
//})->name('showInfo');
Route::get('attendances/schedule', [App\Http\Controllers\Admin\AttendanceController::class, 'changeFilePath']);
Route::get('request/schedule', [App\Http\Controllers\Admin\RequestSettingController::class, 'changeFilePath']);
Route::middleware(['auth','agent.mobile'])->group(function () {
//    Route::get('/employee/id_card', function (){return view('mobile.personal.employee.index');});
    Route::get('/employee/base', function (){return view('mobile.personal.employee.base');});
    Route::get('/employee/contacts', function (){return view('mobile.personal.employee.details.contacts');})->name('mobileContacts');
    Route::get('/employee/bank', function (){return view('mobile.personal.employee.details.bank');})->name('mobileBank');
    Route::get('/employee/stay', function (){return view('mobile.personal.employee.details.stay');})->name('mobileStay');
    Route::get('/employee/getEmployeeInfo/{type?}', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'mobileGetInfo'])->name('getEmployeeInfo');
    Route::post('/employee/saveEmployeeInfo', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'mobileSaveInfo']);
    Route::get('/employee/dependentRelationEdit/{id}', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'mobileDependentEdit']);
    Route::post('/employee/photoSave/{type?}', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'mobilePhotoSave'])->name('employee.photoSave');
    Route::get('/user/mobileIndex', function (){return view('mobile.personal.user.index');});
    Route::get('/getAttendance',function(){
        $path = $_GET['url'];
        if(!str_starts_with($path,'attendance')) abort(403);
        $fileType = mb_substr($path,mb_strrpos($path,'.'));
        switch ($fileType){
            case 'pdf':
                header('Content-type: application/pdf;charset=UTF-8');
                break;
            case 'png':
            case 'jpeg':
            case 'jpg':
                header('Content-type: image/jpeg');
                break;
            case 'xlsx':
                header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                break;
        }
        try {
            return Storage::disk('local')->download($path);
        } catch (FileNotFoundException $e){
            abort(404,'ファイルは見つかりませんでした。');
        }
    })->name('getAttendance');
});
Route::middleware(['auth','agent.mobile'])->group(function () {
    Route::get('/audit/home', [App\Http\Controllers\HomeController::class, 'mobileAudit'])->name('audit.home');
    Route::get('/audit/employees', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'mobileAuditGetInfo'])->name('audit.employees');
    Route::get('/audit/employee/{id}', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'mobileAuditGetInfoById'])->name('audit.employee');
    Route::get('/audit/attendance', [App\Http\Controllers\Admin\AttendanceController::class,'getAttendanceForManage'])->name('audit.attendance');
    Route::get('/audit/leaves', [App\Http\Controllers\Admin\LeaveController::class,'auditIndex'])->name('audit.leaves');
    Route::get('/audit/user', function (){return view('mobile.examination.user.index');})->name('audit.user');
});
Auth::routes();
Route::get('login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::get('password/request',[App\Http\Controllers\AuthController::class,'showEmailForm'])->name('password.request');
//Route::get('register',function (){
//    echo '申し訳ございませんが、登録機能はまだサポートされていないです。';
//})->name('register');
//Route::post('register',function (){
//    echo '申し訳ございませんが、登録機能はまだサポートされていないです。';
//})->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');
Route::middleware(['auth','ip.limit'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home/getStatistical', [App\Http\Controllers\HomeController::class, 'getStatistical'])->name('home.getStatistical');
    Route::get('/home/coinGraph/{id?}', [App\Http\Controllers\HomeController::class, 'getCoinYearData'])->name('home.yearCoins');
    Route::get('/home/coins', [App\Http\Controllers\HomeController::class, 'getCoinInfo'])->name('home.coins');
    Route::get('/home/stocks', [App\Http\Controllers\HomeController::class, 'getStockInfo'])->name('home.stocks');
});
Route::middleware(['auth','ip.limit'])->group(function () {
    Route::post('client/get-clients', [App\Http\Controllers\Admin\ClientController::class, 'getClientsList'])->name('clients.get-clients');
    Route::post('client/priority/{id}', [App\Http\Controllers\Admin\ClientController::class, 'changePriority'])->name('clients.priorityChange');
    Route::post('/client/getClients', [App\Http\Controllers\Admin\ClientController::class, 'getClients'])->name('client.getClients');
    Route::post('client/getOneClient/{id}', [App\Http\Controllers\Admin\ClientController::class, 'getOneClient'])->name('client.getOneClient');
    Route::get('client/getBankInfo', [App\Http\Controllers\Admin\ClientController::class, 'getBankInfo'])->name('client.getBankInfo');
    Route::post('client/saveBankInfo', [App\Http\Controllers\Admin\ClientController::class, 'saveBankInfo'])->name('client.saveBankInfo');

    Route::post('/estimates/getEstimates', [App\Http\Controllers\Admin\EstimatesController::class, 'information'])->name('estimates.getEstimates');
    Route::post('/estimates/getPJNO', [App\Http\Controllers\Admin\EstimatesController::class, 'accountsGetPJNO'])->name('estimates.getPJNO');
    Route::post('/estimates/delete', [App\Http\Controllers\Admin\EstimatesController::class, 'accountsDelete'])->name('estimates.delete');
    Route::post('/estimates/copyToCreate', [App\Http\Controllers\Admin\EstimatesController::class, 'accountsCopy'])->name('estimates.copyToCreate');
    Route::post('/estimates/upload', [App\Http\Controllers\Admin\EstimatesController::class, 'upload'])->name('estimates.upload');

    Route::post('/expense/getExpense', [App\Http\Controllers\Admin\ExpenseController::class, 'information'])->name('expense.getExpense');
    Route::post('/expense/getPJNO', [App\Http\Controllers\Admin\ExpenseController::class, 'accountsGetPJNO'])->name('expense.getPJNO');
    Route::post('/expense/delete', [App\Http\Controllers\Admin\ExpenseController::class, 'accountsDelete'])->name('expense.delete');
    Route::post('/expense/copy', [App\Http\Controllers\Admin\ExpenseController::class, 'accountsCopy'])->name('expense.copy');
    Route::post('/expense/upload', [App\Http\Controllers\Admin\ExpenseController::class, 'upload'])->name('expense.upload');
    Route::post('/expense/hacchuu', [App\Http\Controllers\Admin\ExpenseController::class, 'hacchuu'])->name('expense.hacchuu');


    Route::post('confirmation/getNum', [App\Http\Controllers\Admin\OrderConfirmationsController::class, 'accountsGetPJNO'])->name('confirmation.getNum');
    Route::post('confirmations/search', [App\Http\Controllers\Admin\OrderConfirmationsController::class, 'search'])->name('confirmations.search');

    Route::post('/invoice/getInvoices', [App\Http\Controllers\Admin\InvoicesController::class, 'getInvoices'])->name('invoice.getInvoices');
    Route::post('/invoice/delete', [App\Http\Controllers\Admin\InvoicesController::class, 'delInvoice'])->name('invoice.delete');
    Route::post('/invoice/fillInvoice', [App\Http\Controllers\Admin\InvoicesController::class, 'fillInvoice'])->name('invoice.fillInvoice');
    Route::post('/invoice/copyToCreate', [App\Http\Controllers\Admin\InvoicesController::class, 'copyToCreate'])->name('invoice.copyToCreate');
    Route::post('/invoice/getInvoiceNum', [App\Http\Controllers\Admin\InvoicesController::class, 'accountsGetPJNO'])->name('invoice.getInvoiceNum');
    Route::post('/invoice/approveRequest/{id}', [App\Http\Controllers\Admin\InvoicesController::class, 'approveRequest'])->name('invoice.approveRequest');
    Route::post('/invoice/requestCallBack/{id}', [App\Http\Controllers\Admin\InvoicesController::class, 'requestCallBack'])->name('invoice.requestCallBack');
    Route::post('/invoice/delInvoiceClient/{id}', [App\Http\Controllers\Admin\InvoicesController::class, 'delInvoiceClient'])->name('invoice.delInvoiceClient');

    Route::post('/letteroftransmittal/getLetterOfTransmittal', [App\Http\Controllers\Admin\LetterOfTransmittalController::class, 'getLetterOfTransmittal'])->name('letteroftransmittal.getLetterOfTransmittal');
    Route::post('/letteroftransmittal/delete', [App\Http\Controllers\Admin\LetterOfTransmittalController::class, 'accountsDelete'])->name('letteroftransmittal.delete');
    Route::post('/letteroftransmittal/copy', [App\Http\Controllers\Admin\LetterOfTransmittalController::class, 'accountsCopy'])->name('letteroftransmittal.copy');

    Route::post('requestSetting/bankInfoAddOrEdit', [App\Http\Controllers\Admin\RequestSettingController::class, 'bankInfoAddOrEdit'])->name('requestSetting.bankInfoAddOrEdit');
    Route::get('requestSetting/bankInfoGet', [App\Http\Controllers\Admin\RequestSettingController::class, 'bankInfoGet'])->name('requestSetting.bankInfoGet');
    Route::post('/email-templates/requestMailUpdate', [App\Http\Controllers\Admin\EmailTemplateController::class, 'requestMailUpdate'])->name('mail.requestMailUpdate');
    Route::post('/requestSetting/receiveMailAddressChange', [App\Http\Controllers\Admin\AdminSettingsController::class, 'receiveMailAddressChange'])->name('adminSetting.receiveMailAddressChange');
});
Route::middleware(['auth','ip.limit'])->group(function () {
    Route::get('employees/list', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'indexList'])->name('employees.list');
    Route::get('employees/card', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'indexCard'])->name('employees.card');
    Route::get('employees/employeeCode', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'employeeCode'])->name('employees.employeeCode');
    Route::get('/employees/EmployeeRelationInfo/{id}', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'relationInfo']);
    Route::get('employee/adminConfirm', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'adminConfirm'])->name('employees.adminConfirm');
    Route::get('employee/get-employees', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'getEmployeesList'])->name('employees.get-employees');
    Route::post('employee/step2', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'step2'])->name('employees.step2');
    Route::post('employee/step3', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'step3'])->name('employees.step3');
    Route::post('employee/adminSubmitBefore', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'adminSubmitBefore'])->name('employees.adminSubmitBefore');
    Route::post('employee/adminSubmit/{id}', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'adminSubmit'])->name('employees.adminSubmit');
    Route::post('employee/userVerify', [App\Http\Controllers\Admin\UsersController::class, 'verify'])->name('employees.userVerify');
    Route::post('employee/employeeCodeSave', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'employeeCodeSave'])->name('employees.codeSave');
    Route::post('employee/employeeDeleteCheck', [App\Http\Controllers\Admin\EmployeeInfoController::class, 'employeeDeleteCheck'])->name('employees.deleteCheck');

    Route::post('attendance/getTableInfo', [App\Http\Controllers\Admin\AttendanceController::class, 'getTableInfo'])->name('attendances.getTableInfo');
    Route::get('attendance/getCardInfo', [App\Http\Controllers\Admin\AttendanceController::class, 'getCardInfo'])->name('attendances.getCardInfo');
    Route::post('attendance/uploadFile', [App\Http\Controllers\Admin\AttendanceController::class, 'uploadFile'])->name('attendances.uploadFile');
    Route::post('attendance/confirmWorkingTime', [App\Http\Controllers\Admin\AttendanceController::class, 'confirmWorkingTime'])->name('attendances.confirmWorkingTime');
    Route::post('attendance/confirmWhenMobile', [App\Http\Controllers\Admin\AttendanceController::class, 'confirmWhenMobile'])->name('attendances.confirmWhenMobile');
    Route::post('attendances/search', [App\Http\Controllers\Admin\AttendanceController::class, 'search'])->name('attendances.search');
    Route::put('attendances/update', [App\Http\Controllers\Admin\AttendanceController::class, 'update'])->name('attendances.modify');
    Route::delete('attendances/destroy', [App\Http\Controllers\Admin\AttendanceController::class, 'destroy'])->name('attendances.delete');
    Route::post('attendances/rejection', [App\Http\Controllers\Admin\AttendanceController::class, 'rejection'])->name('attendances.rejection');
    Route::post('attendances/getAttendanceCountInMonth', [App\Http\Controllers\Admin\AttendanceController::class, 'getAttendanceCountInMonth'])->name('attendances.getAttendanceCountInMonth');

    Route::get('leave/get-LeaveList', [App\Http\Controllers\Admin\LeaveController::class, 'getEmployeeList'])->name('leaves.getLeaves');
    Route::get('leave/get-editList', [App\Http\Controllers\Admin\LeaveController::class, 'getEmployeeLeaves'])->name('leaves.getEmployeeLeaves');
    Route::get('leave/getAnnualLeaveHasDays', [App\Http\Controllers\Admin\LeaveController::class, 'getAnnualLeaveHasDays'])->name('leaves.getAnnualLeaveHasDays');
    Route::post('leave/status/{id}', [App\Http\Controllers\Admin\LeaveController::class, 'changeStatus'])->name('leaves.statusChange');
    Route::post('leave/leaveDateValidate', [App\Http\Controllers\Admin\LeaveController::class, 'leaveDateValidate'])->name('leaves.leaveDateValidate');
    Route::post('leave/getLeaveOne/{id}', [App\Http\Controllers\Admin\LeaveController::class, 'getLeaveOne'])->name('leaves.getLeaveOne');
    Route::post('leave/setLastDaysOfAnnual', [App\Http\Controllers\Admin\LeaveController::class, 'setLastDaysOfAnnual'])->name('leaves.setLastDaysOfAnnual');
});
Route::middleware(['auth','ip.limit'])->group(function () {
    Route::get('/companyasset/getMaxNum',[App\Http\Controllers\Admin\CompanyAssetsController::class, 'getMaxNum'])->name('companyasset.getMaxNum');
    Route::get('/companyassets/loan/{id}', [App\Http\Controllers\Admin\CompanyAssetsController::class, 'loan'])->name('companyassets.loan');
    Route::post('/companyassets/rental/{id}', [App\Http\Controllers\Admin\CompanyAssetsController::class, 'rental'])->name('companyassets.rental');
    Route::get('/companyassets/delRental/{id}', [App\Http\Controllers\Admin\CompanyAssetsController::class, 'delRental'])->name('companyassets.delRental');
    Route::get('/companyassets/restore/{id}', [App\Http\Controllers\Admin\CompanyAssetsController::class, 'restore'])->name('companyassets.restore');
    Route::get('/companyassets/showAsset', [App\Http\Controllers\Admin\CompanyAssetsController::class, 'showAsset'])->name('companyassets.showAsset');
    Route::post('/companyassets/getAssetInfo', [App\Http\Controllers\Admin\CompanyAssetsController::class, 'getAssetInfo'])->name('asset.getAssetInfo');
    Route::post('/companyassets/getAssetRentalLogs', [App\Http\Controllers\Admin\CompanyAssetsController::class, 'getAssetRentalLogs'])->name('asset.getAssetRentalLogs');
    Route::get('/companyassets/editRental/{id}', [App\Http\Controllers\Admin\CompanyAssetsController::class, 'editRental'])->name('asset.editRental');
    Route::post('/companyassets/updateRental/{id}', [App\Http\Controllers\Admin\CompanyAssetsController::class, 'updateRental'])->name('asset.updateRental');

    Route::post('/receipt/getReceipt', [App\Http\Controllers\Admin\ReceiptController::class, 'getReceipt'])->name('receipt.getReceipt');
    Route::post('/receipt/delete', [App\Http\Controllers\Admin\ReceiptController::class, 'accountsDelete'])->name('receipt.delete');
    Route::post('/receipt/copy', [App\Http\Controllers\Admin\ReceiptController::class, 'accountsCopy'])->name('receipt.copy');
});
Route::middleware(['auth','ip.limit'])->group(function () {
    Route::get('schedule/getSchedules', [App\Http\Controllers\Admin\ScheduleController::class, 'getSchedules'])->name('schedule.getSchedules');
    Route::get('schedules/group/{id}', [App\Http\Controllers\Admin\ScheduleController::class, 'getSchedulesGroup'])->name('schedules.group');
    Route::get('schedules/calendar', [App\Http\Controllers\Admin\ScheduleController::class, 'getSchedulesCalendar'])->name('schedules.calendar');
    Route::post('schedules/saveSchedule', [App\Http\Controllers\Admin\ScheduleController::class, 'saveSchedule'])->name('schedules.saveSchedule');
    Route::post('schedules/deleteSchedule', [App\Http\Controllers\Admin\ScheduleController::class, 'deleteSchedule'])->name('schedules.deleteSchedule');


    Route::post('user/get-users', [App\Http\Controllers\Admin\UsersController::class, 'getUsersList'])->name('users.get-users');
    Route::get('password/reset',[App\Http\Controllers\Admin\UsersController::class,'showResetForm'])->name('user.resetPassword');
    Route::post('password/update',[App\Http\Controllers\Admin\UsersController::class,'passwordUpdate'])->name('user.passwordUpdate');
    Route::get('user/info',[App\Http\Controllers\Admin\UsersController::class,'showInfo'])->name('user.info');
    Route::post('info/update',[App\Http\Controllers\Admin\UsersController::class,'infoUpdate'])->name('user.infoUpdate');

    Route::post('adminsetting/IPAddressSave', [App\Http\Controllers\Admin\AdminSettingsController::class, 'IPAddressSave'])->name('adminsetting.IPAddressSave');
    Route::post('adminsetting/addIPAddress', [App\Http\Controllers\Admin\AdminSettingsController::class, 'addIPAddress'])->name('adminsetting.addIPAddress');
    Route::post('role/permissions/{id}', [App\Http\Controllers\Admin\RoleController::class, 'updateRolePermissions'])->name('role.updateRolePermissions');
    Route::get('session/get-sessions', [App\Http\Controllers\Admin\SessionController::class, 'getSessions'])->name("sessions.get-sessions");
    Route::get('get-email-template', [App\Http\Controllers\Admin\EmailTemplateController::class, 'getEmailTemplate'])->name("get-email-template");;
    Route::get('activity',[App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name("activity");
    Route::delete('activity/{id}',[App\Http\Controllers\Admin\ActivityLogController::class, 'destroy'])->name("activity.destroy");
    Route::get('ajax_activity', [App\Http\Controllers\Admin\ActivityLogController::class, 'activityLog'])->name("ajax.activity");

    Route::get('scheduleColor/get-scheduleColorSettings', [App\Http\Controllers\Admin\ScheduleColorController::class, 'getScheduleColorSettings'])->name("scheduleColor.get-scheduleColorSettings");
    Route::post('scheduleColor/updateOrderNum', [App\Http\Controllers\Admin\ScheduleColorController::class, 'updateOrderNum'])->name('scheduleColor.updateOrderNum');
    Route::post('scheduleColor/deleteColors', [App\Http\Controllers\Admin\ScheduleColorController::class, 'deleteColors'])->name('scheduleColor.deleteColors');

    Route::get('scheduleMember/get-scheduleMemberSettings', [App\Http\Controllers\Admin\ScheduleMemberController::class, 'getScheduleMemberSettings'])->name("scheduleMember.get-scheduleMemberSettings");
    Route::post('scheduleMember/updateOrderNum', [App\Http\Controllers\Admin\ScheduleMemberController::class, 'updateOrderNum'])->name('scheduleMember.updateOrderNum');
    Route::post('scheduleMember/deleteMembers', [App\Http\Controllers\Admin\ScheduleMemberController::class, 'deleteMembers'])->name('scheduleMember.deleteMembers');

    Route::post('scheduleGroup/updateGroupMembers', [App\Http\Controllers\Admin\ScheduleGroupController::class, 'updateGroupMembers'])->name('scheduleGroup.updateGroupMembers');


    Route::get('/downloadfile/{file_id}/a', function ($file_id) {
        $file = File::find($file_id);
        $path = $file->path;
        $type = $file->type;
        if($file->is_in_local==1){
            if(array_key_exists('request',$_GET) && $_GET['request']=='exist'){
                return $file->path;
            }else {
                $ipAddr = RequestSettingExtra::select('local_ip_addr')->first()->local_ip_addr;
                $path = 'http://'.$ipAddr.'/getfile?path='.$path.'&type='.$type.'&_='.time();
                return redirect()->away($path);
            }
        }else{
            if(array_key_exists('request',$_GET) && $_GET['request']=='exist'){
                if(Storage::disk('local')->exists($path)){
                    return 'existed';
                }else{
                    return 'notfound';
                }
            }else{
                return Storage::disk('local')->download($path);
            }
        }
    })->name('downloadfile');
    Route::get('/getImage/{folder}/{subFolder?}/{path?}',function(){
        $realpath = str_replace('getImage','/app/public',Request::path());
        $path = storage_path().$realpath;
        if(!file_exists($path)){
            abort(\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }
        header('Content-type: image/jpg');
        echo file_get_contents($path);
        exit;
    });
    Route::get('/getFileSource',function(){
        $path = 'app/'.$_GET['path'];
        $fileType = mb_substr($path,mb_strrpos($path,'.'));
        switch ($fileType){
            case 'pdf':
                header('Content-type: application/pdf;charset=UTF-8');
                break;
            case 'png':
            case 'jpeg':
            case 'jpg':
                header('Content-type: image/jpeg');
                break;
            case 'xlsx':
                header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                break;
        }
        if(file_exists(storage_path($path))){
            return response()->file(storage_path($path));
        }else{
            abort(\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }
    })->name('getFileSource');
    Route::get('/preview/{file_id}',function ($file_id){
        $file = File::find($file_id);
        $find = $file->path;
        $type = $file->type;
        $ipAddr = RequestSettingExtra::select('local_ip_addr')->first()->local_ip_addr;
        $time=time();
        if($file->is_in_local==1){
//            $find=str_replace(['&'],['%26'],$find);
//            $newFind=str_replace(['+','/','?','%','#','&'],['%2B','%2F','%3F','%25','%23','%26'],$find);
            $path = 'http://'.$ipAddr.'/getfile?path='.$find.'&type='.$type.'&_='.time();
//            $path=str_replace(['+'],['%2B'],$path);
//            Log::debug($find);
//            Log::debug($path);
//            $path=str_replace(['+','/','?','%','#','&'],['%2B','%2F','%3F','%25','%23','%26'],$path);
            return view('preview',compact('type','ipAddr','find','time'));
        }else{
            $path = route('getFileSource').'?path='.$find.'&_='.time();
            $path=str_replace('+','%2B',$path);
            if(!\Illuminate\Support\Facades\Storage::disk('local')->exists($find)){
                $status = '404';
                return view('preview',compact('path','type','status'));
            }
            return view('preview',compact('path','ipAddr','type','find','time'));
        }
//        $path = route('getFileSource').'?path='.$path.'&_='.time();
    })->name('preview');

    Route::get('/getVersion',function (){
        echo phpinfo();
    });
    Route::get('/mailtemp', function () {
        return view('admin.email.layout');
    });
});
Route::middleware(['auth','ip.limit'])->group(function () {
    Route::resources([
        'clients' => App\Http\Controllers\Admin\ClientController::class,
        'estimates' => App\Http\Controllers\Admin\EstimatesController::class,
        'expense' => App\Http\Controllers\Admin\ExpenseController::class,
        'confirmations' => App\Http\Controllers\Admin\OrderConfirmationsController::class,
        'invoice' => App\Http\Controllers\Admin\InvoicesController::class,
        'letteroftransmittal' => App\Http\Controllers\Admin\LetterOfTransmittalController::class,
        'requestSetting' => App\Http\Controllers\Admin\RequestSettingController::class,
        'bankAccount' => App\Http\Controllers\Admin\BankAccountController::class,

        'employees' => App\Http\Controllers\Admin\EmployeeInfoController::class,
        'attendances' => App\Http\Controllers\Admin\AttendanceController::class,
        'leaves' => App\Http\Controllers\Admin\LeaveController::class,
        'HrSetting' => App\Http\Controllers\Admin\HrSettingController::class,
        'scheduleColor' => App\Http\Controllers\Admin\ScheduleColorController::class,
        'scheduleGroup' => App\Http\Controllers\Admin\ScheduleGroupController::class,
        'scheduleMember' => App\Http\Controllers\Admin\ScheduleMemberController::class,
        'scheduleSetting' => App\Http\Controllers\Admin\ScheduleSettingController::class,
        'departments' => App\Http\Controllers\Admin\DepartmentController::class,
        'hireType' => App\Http\Controllers\Admin\HireTypeController::class,
        'positionType' => App\Http\Controllers\Admin\PositionTypeController::class,
        'retireType' => App\Http\Controllers\Admin\RetireTypeController::class,
        'residenceType' => App\Http\Controllers\Admin\ResidenceTypeController::class,

        'companyassets' => App\Http\Controllers\Admin\CompanyAssetsController::class,
        'receipt' => App\Http\Controllers\Admin\ReceiptController::class,
        'assetSetting' => App\Http\Controllers\Admin\AssetSettingController::class,
        'assettypes' => App\Http\Controllers\Admin\AssetTypeController::class,

        'schedules' => App\Http\Controllers\Admin\ScheduleController::class,

        'users' => App\Http\Controllers\Admin\UsersController::class,

        'settings' => App\Http\Controllers\Admin\AdminSettingsController::class,
        'roles' => App\Http\Controllers\Admin\RoleController::class,
        'sessions' => App\Http\Controllers\Admin\SessionController::class,
        'email-templates' => App\Http\Controllers\Admin\EmailTemplateController::class,
//        'register' => App\Http\Controllers\Auth\RegisterController::class,
//        'contracttypes' => App\Http\Controllers\Admin\ContractTypeController::class,
//        'ourpositiontypes' => App\Http\Controllers\Admin\OurPositionTypeController::class,
    ]);
});
Route::middleware(['auth','ip.limit'])->group(function () {
    Route::resource('user', App\Http\Controllers\UserController::class)->except(['show']);
});
//Route::fallback(function () {
//    abort(426);
//});
