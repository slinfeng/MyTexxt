<?php

namespace App\Providers;

use App\Models\AccountsInvoice;
use App\Models\AccountsOrder;
use App\Models\AdminSetting;
use App\Models\Attendance;
use App\Models\Client;
use App\Models\EmployeeBase;
use App\Models\Leave;
use App\Models\ScheduleGroup;
use App\Services\SharingService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Incs\GlobalConfig;
use InvalidArgumentException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // **************************************************************************************************
        // Global Config OBJECT
        // **************************************************************************************************
        View::composer(['preview','layouts.headers.head_start','layouts.pages.page_header'], function ($view)
        {
        //                               : Name, version and assets folder's name
        $gcon                             = new GlobalConfig('SmartHr', '4.4', '');


        // **************************************************************************************************
        // GLOBAL META & OPEN GRAPH DATA
        // **************************************************************************************************

        //                               : The data is added in the <head> section of the page
        $gcon->author                     = 'Drtech';
        $gcon->title                      = 'SmartHr';
        $gcon->description                = 'SmartHr';

        // **************************************************************************************************
        // GLOBAL SIDEBAR & SIDE OVERLAY
        // **************************************************************************************************

        // true                          : Left Sidebar and right Side Overlay
        // false                         : Right Sidebar and left Side Overlay
        $gcon->l_sidebar_left             = true;

        // true                          : Mini hoverable Sidebar (screen width > 991px)
        // false                         : Normal mode
        $gcon->l_sidebar_mini             = false;

        // true                          : Visible Sidebar (screen width > 991px)
        // false                         : Hidden Sidebar (screen width > 991px)
        $gcon->l_sidebar_visible_desktop  = true;

        // true                          : Visible Sidebar (screen width < 992px)
        // false                         : Hidden Sidebar (screen width < 992px)
        $gcon->l_sidebar_visible_mobile   = false;

        // true                          : Show all setting sidebar menu
        // false                         : Hidden all setting sidebar menu
        $gcon->l_sidebar_setting          = false;


//        $this->app->singleton('gcon', function ($gcon) {
//            return $gcon;
//        });
//        View::share("gcon",app('gcon'));
        $gcon->company_name = AdminSetting::select('company_name')->first()->company_name;
        $user = Auth::user();
        if(isset($user)){
            if($user->client_id>0){
                $gcon->company_name = Client::find($user->client_id)->client_name;
            }
        }

            View::share("gcon",$gcon);
        });

//        $this->app->singleton('shared', function() {
//
//            $sharingService = new SharingService();
//
//            $sharingService->share('l_sidebar_setting', false);
//            // ☝️ you can set values here or in any place, since it's a public method
//
//            return $sharingService;
//        });

//        View::composer('*', function ($view) {
//            $view->share('l_sidebar_setting',false);
//        });
        Schema::defaultStringLength(191);
        // max_mb ファイルサイズの最大値をメガバイトで指定するvalidationルールを追加する
        Validator::extend('max_mb', function ($attribute, $value, $parameters, $validator) {
            // パラメータ数のチェック
            $this->requireParameterCount(1, $parameters, 'max_mb');

            // アップロードに成功しているかの判定
            if ($value instanceof UploadedFile && ! $value->isValid()) {
                return false;
            }

            // ファイルサイズをMBに変換
            $megaBytes = $value->getSize() / 1024 / 1024;

            // $parametersにルールで設定した引数より小さければ問題なし
            return $megaBytes <= $parameters[0];
        });

        // validationメッセージ「The :attribute may not be greater than :max_mb megabytes.」の
        // 「:max_mb」部分をパラメータとして与えられた値と置き換える
        Validator::replacer('max_mb', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':max_mb', $parameters[0], $message);
        });
        View::composer('layouts.sidebars.sidebar', function ($view)
        {
            $groups = ScheduleGroup::all();
            $invoice = 0;
            $order = 0;
            $user = Auth::user();
            if(isset($user)){
                $client_id = $user->client_id;
                if($client_id>0){
                    $invoice = AccountsInvoice::where('client_id',$client_id)->where('new_notice',1)->count();
                    $order = AccountsOrder::where('client_id',$client_id)->where('new_notice',1)->count();
                }else{
                    $invoice = AccountsInvoice::where('new_notice',2)->count();
                    $order = AccountsOrder::where('new_notice',2)->count();
                }
            }
            $requestTotal = $invoice+$order;
            $employee = EmployeeBase::where("modified_type","1")->count();
            $attendance = Attendance::where('status',0)->count();
            $leave=Leave::where('status',0)->count();
            $HrTotal=$employee+$attendance+$leave;
            $info=[
                "requestTotal" => $requestTotal,
                "invoice" => $invoice,
                "order" => $order,
                "HrTotal" => $HrTotal,
                "employee" => $employee,
                "attendance" => $attendance,
                "leave" => $leave,
                "groups" => $groups,
            ];
            View::share("info",$info);
        });

    }

    /**
     * ルールに与えられるパラメータ数のチェックを行う
     *
     * @param integer $count
     * @param array $parameters
     * @param string $rule
     * @return void
     */
    protected function requireParameterCount($count, $parameters, $rule)
    {
        if (count($parameters) < $count) {
            throw new InvalidArgumentException("Validation rule $rule requires at least $count parameters.");
        }
    }
}
