<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\AccountsEstimate;
use App\Models\AdminSetting;
use App\Models\AssetSetting;
use App\Models\AssetType;
use App\Models\BankAccount;
use App\Models\BankAccountType;
use App\Models\ContractType;
use App\Models\EmailTemplate;
use App\Models\File;
use App\Models\FontFamilyType;
use App\Models\RequestSettingExtra;
use App\Models\RequestSettingGlobal;
use App\Models\User;
use App\Traits\PCGateTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;


class AssetSettingController extends Controller
{
    use PCGateTrait;
    /**
     * index
     * @return Application|Factory|View
     */
    public function index()
    {
        $this->deniesForView(AssetSetting::class);
        $assettypes = AssetType::orderBy('asset_type_code')->get();
        $assetSetting = AssetSetting::first();
        return view('admin.assetSetting.index',compact('assettypes','assetSetting'));
    }

    /**
     * update
     * @param Request $request
     * @param AssetSetting $assetSetting
     * @return array|bool[]|string[]
     */
    public function update(Request $request,AssetSetting $assetSetting){
        $this->deniesForModify(AssetSetting::class);
        $messages = [
            'seal_file.dimensions' => __('正方形の画像をアップロードしてください。'),
        ];
        $validator = Validator::make($request->all(), [
            'search_mode' => ['bail','required','in:0,1'],
            'use_init_val' => ['bail','nullable','in:1'],
            'use_seal' => ['bail','nullable','in:1'],
            'seal_file'=>['dimensions:ratio=1/1']
        ],$messages);
        if ($validator->fails()) {
            return Reply::fail($validator->errors()->first());
        }
        $temp = $request->all();
        if(!isset($request->use_init_val)){
            $temp['use_init_val'] = 0;
        }
        if(!isset($request->use_seal)){
            $temp['use_seal'] = 0;
        }
        $fileinfo = $request->file("seal_file");
        if (isset($fileinfo)) {
            if ($fileinfo->isValid()) {
                $ext = $fileinfo->getClientOriginalExtension();
                $name ='receive_seal_file'.'.'.$ext;
                $realPath = $fileinfo->getRealPath();
                Storage::disk('electronicSeal')->put('/'.$name,file_get_contents($realPath));
                $temp['seal_file'] = '/electronicSeal/'.$name;
            }
        }
        $assetSetting->update($temp);
        return Reply::success('初期設定を変更しました!');
    }

}
