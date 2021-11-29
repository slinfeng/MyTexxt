<?php

namespace App\Traits;

use App\Models\AccountsInvoice;
use App\Models\AccountsOrderConfirmation;
use App\Models\BankAccount;
use App\Models\ContractType;
use App\Models\File;
use App\Models\OurPositionType;
use App\Models\RequestSetting;
use App\Models\RequestSettingExtra;
use App\Models\RequestSettingGlobal;
use App\Models\Session;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait ShowDatatable{
    /**
     * IDの隠す欄を取得
     * @param $id
     * @return string
     */
    public function getIdInput($id){
        return '<input type="hidden" name="id" value="'.$id.'"/>';
    }

    public function getEditLink($content){
        $content=empty($content)?'未入力':$content;
        return '<a href="javascript:void(0)" onclick="toEdit(this)">'.$content.'</a>';
    }

    public function getFileLink($file_id,$project_name_or_file_name){
        return $file_id==''?$project_name_or_file_name:'<a href="javascript:(0)" onclick="openFileFromData(this)">'.$project_name_or_file_name.'</a>';
    }

    public function showAmount($amount){
        return '<span data-sort="'.preg_replace('/[^0-9]/','',$amount).'">'.$amount.'</span>';
    }
}
