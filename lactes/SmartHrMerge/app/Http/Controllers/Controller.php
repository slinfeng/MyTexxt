<?php

namespace App\Http\Controllers;

use App\Incs\GlobalConfig;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $l_sidebar_setting=false;
    public function __construct()
    {
        //view()->share('l_sidebar_setting', $this->l_sidebar_setting);
        //声明全局模板
        //view()->share('gcon', $this->GlobalConfigs());
//       if(Session::get('l_sidebar_setting')!=null){
//           view()->share('l_sidebar_setting', $this->l_sidebar_setting);
//       } else{
//
//       }

    }

    /**
     * 获取配置文件
     */
    public function GlobalConfigs()
    {
        // **************************************************************************************************
        // Global Config OBJECT
        // **************************************************************************************************

        //                               : Name, version and assets folder's name
        $gcon                             = new GlobalConfig('Lactes', '1.1', '');


        // **************************************************************************************************
        // GLOBAL META & OPEN GRAPH DATA
        // **************************************************************************************************

        //                               : The data is added in the <head> section of the page
        $gcon->author                     = 'Drtech';
        $gcon->title                      = 'Lactes';
        $gcon->description                = 'Lactes';

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


        return $gcon;
    }
}
