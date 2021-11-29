<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\AccountsInvoice;
use App\Models\Permission;
use App\Models\RequestSetting;
use App\Models\RequestSettingGlobal;
use App\Models\Role;
use App\Models\UserIpAddress;
use App\Traits\PCGateTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Artisan;
use App\Models\AdminSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AdminSettingsController extends Controller
{
    use PCGateTrait;
    /**
     * index
     * @return Application|Factory|View
     */

    public function index()
    {
        $this->deniesForView(AdminSetting::class);
        $master = array();
        $data = AdminSetting::get()->first();
        $master['id'] = $data['id'];
        $master['company_name'] = $data['company_name'];
        $master['post_code'] = $data['post_code'];
        $master['address'] = $data['address'];
        $master['representative_name'] = $data['representative_name'];
        $master['phone_no'] = $data['phone_no'];
        $master['fax'] = $data['fax'];
        $master['closing_month']=$data['closing_month'];
        $master['email'] = $data['email'];
        $master['url'] = $data['url'];
        $master['mail_driver']=$data['mail_driver'];
        $master['mail_host']=$data['mail_host'];
        $master['mail_port']=$data['mail_port'];
        $master['mail_username']=$data['mail_username'];
        $master['mail_password']=$data['mail_password'];
        $master['mail_encryption']=$data['mail_encryption'];
        $master['mail_from_address']=$data['mail_from_address'];
        $master['mail_from_name']=$data['mail_from_name'];
        $master['company_short_name']=$data['company_short_name'];
        $master['logo']=$data['logo'];
        $ipAddressArr=[];
        $IPAddressArr=UserIpAddress::select('id','name','ip_address','sort_num')->orderBy('sort_num','asc')->get();
        foreach ($IPAddressArr as $ipAddress){
            $ipAddressId=$ipAddress->id;
            $ipAddressName=$ipAddress->name;
            $sortNum=$ipAddress->sort_num;
            $ipAddress=$ipAddress->ip_address;
            $ipAddress[0]=explode('.',$ipAddress[0]);
            $ipAddress[1]=explode('.',$ipAddress[1]);
            $ipAddressNew=[$ipAddress[0],$ipAddress[1],$ipAddressId,$sortNum,$ipAddressName];
            array_push($ipAddressArr,$ipAddressNew);
        }
        $roles = Role::with(['permissions:id,title'])->get();
        $allpermissions =Permission::select('title','id')->get()->toArray();
        return view('admin.settings.index', compact(['master'],'roles','allpermissions','ipAddressArr'));//,'l_sidebar_setting'
    }

    /**
     * update
     * @param $id
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function update($id,Request $request)
    {
        $this->deniesForModify(AdminSetting::class);
        $setting = AdminSetting::get()->first();
        $requestData=$request->all();
        if ($request->setting == 'email') {
            $validator = Validator::make($request->all(), [
                'mail_driver'=>['bail','required','max:200'],
                'mail_host'=>['bail','required','max:200'],
                'mail_port'=>['bail','required','numeric'],
                'mail_username'=>['bail','required','max:200'],
                'mail_password'=>['bail','required','max:200'],
                'mail_encryption'=>['bail','required','regex:/^(ssl|tls)$/'],
                'mail_from_address'=>['bail','required','max:200'],
                'mail_from_name'=>['bail','required','max:200'],
            ]);
            if ($validator->fails()) return Reply::fail($validator->errors()->first());
            $setting->update($requestData);
            return Reply::success(__('Mail setting is updated successfully.'));
        } elseif ($request->setting == 'contact') {
            $validator = Validator::make($request->all(), [
                'company_name'=>['bail','required','max:200'],
                'address'=>['bail','required'],
                'representative_name'=>['bail','required','max:50'],
                'phone_no'=>['bail','nullable','regex:/^[0-9]{6,14}$/'],
                'fax'=>['bail','nullable','regex:/^[0-9]{6,14}$/'],
                'closing_month'=>['bail','numeric','between:01,12'],
                'email'=>['bail','nullable','email'],
                'url'=>['bail','nullable','url'],
                'company_short_name'=>['bail','required'],
                'logo'=>['bail','dimensions:ratio=1/1'],
            ]);
            if ($validator->fails()) return Reply::fail($validator->errors()->first());
            $fileinfo = $request->file("logo");
            if (isset($fileinfo)) {
                if ($fileinfo->isValid()) {
                    $ext = $fileinfo->getClientOriginalExtension();
                    $name ='phoneCompanyLogo'.'.'.$ext;
                    $realPath = $fileinfo->getRealPath();
                    Storage::disk('logo')->put('/'.$name,file_get_contents($realPath));
                    $requestData['logo'] = '/logo/'.$name;
                }
            }
            $requestData['closing_month'] = str_pad($requestData['closing_month'],2,'0',STR_PAD_LEFT);
            $setting->update($requestData);
            return Reply::success(__('Contact setting is updated successfully.'));
        }
    }

    /**
     * 設定の読み込み
     * @return string[]
     */
    public function envRead()
    {
        $data = [
            'TWILIO_SID' => ' ',
            'TWILIO_AUTH_TOKEN' => ' ',
            'TWILIO_NUMBER' => ' ',
            'TEXT_LOCAL_API' => ' ',
            'STRIPE_SECRET' => ' ',
            'STRIPE_KEY' => ' ',
            'P_PRODUCTION_CLIENT_ID' => ' ',
            'P_SANDBOX_CLIENT_ID' => ' ',
            'RAZOR_ID' => ' ',
            'APP_ID' => ' ',
            'REST_API_KEY' => ' ',
            'USER_AUTH_KEY' => ' ',
            'PROJECT_NUMBER' => ' ',
        ];
        if (count($data) > 0) {
            if (is_writeable("../.env")) {
                $env = file_get_contents('../.env');
                $env = preg_split('/\s+/', $env);
                foreach ((array) $data as $key => $vaue) {
                    foreach ($env as $env_key => $env_value) {
                        $entry = explode("=", $env_value, 2);
                        if ($entry[0] == $key) {
                            $data[$key] = $entry[1];
                        }
                    }
                }
                return $data;
            } else {
                return $data;
            }
        }
    }

    /**
     * メール設定変更
     * @param Request $request
     * @return mixed
     */
    public function updateEmail(Request $request)
    {
        $data = [
            'MAIL_HOST' => $request->MAIL_HOST,
            'MAIL_PORT' => $request->MAIL_PORT,
            'MAIL_USERNAME' => $request->MAIL_USERNAME,
            'MAIL_PASSWORD' => $request->MAIL_PASSWORD,
            'MAIL_ENCRYPTION' => $request->MAIL_ENCRYPTION,
            'MAIL_DRIVER' => $request->MAIL_DRIVER,
        ];
        $this->updateENV($data);
        return redirect('setting')->withStatus(__('Email Configuration updated successfully.'));
    }

    /**
     * env設定変更
     * @param $data
     * @return bool|void
     */
    public function updateENV($data)
    {
        if (is_writeable("../.env")) {
            $env = file_get_contents('../.env');
            $env = preg_split('/\s+/', $env);
            foreach ((array) $data as $key => $value) {
                foreach ($env as $env_key => $env_value) {
                    $entry = explode("=", $env_value, 2);
                    if ($entry[0] == $key) {
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        $env[$env_key] = $env_value;
                    }
                }
            }
            $env = implode("\n", $env);
            file_put_contents('../.env', $env);
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            return true;
        } else {
            return abort(500, __('Don`t have write permission'));
        }
    }

    /**
     * Generate a new unique file name
     * @param $currentFileName
     * @return string new file name
     */
    public function generateNewFileName($currentFileName)
    {
        $ext     = strtolower(File::extension($currentFileName));
        $newName = md5(microtime());
        return $newName . '.' . $ext;
    }

    /**
     * メール送信先変更
     * @param Request $request
     * @return array|string[]
     */
    public function receiveMailAddressChange(Request $request){
        $this->deniesForModify(RequestSettingGlobal::class);
        $adminSetting=AdminSetting::first();
        $adminSetting->receiver_arr=$request->idArr;
        $adminSetting->save();
        return Reply::success(__('我社の送信先を更新しました。'));
    }


    public function addIPAddress(){
        $ipAddress=UserIpAddress::create([
            'name'=>'test',
            'ip_address'=>['0.0.0.0','0.0.0.0'],
        ]);
        return Reply::success(__('Ip Address is created successfully !'),['ipAddress'=>$ipAddress]);
    }

    /**
     * 社内サーバーIP変更
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function IPAddressSave(Request $request)
    {
        $this->deniesForModify(AdminSetting::class);
        $validator = Validator::make($request->all(), [
            'ipAddressName.*' => 'required',
            'IPAddress00.*' => ['bail','required','numeric','between:0,255'],
            'IPAddress01.*' => ['bail','required','numeric','between:0,255'],
            'IPAddress02.*' => ['bail','required','numeric','between:0,255'],
            'IPAddress03.*' => ['bail','required','numeric','between:0,255'],
            'IPAddress13.*' => ['bail','required','numeric','between:0,255'],
        ]);
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        DB::beginTransaction();
        $idArr=$request->id;
        $nameArr=$request->ipAddressName;
        $sortNumArr=$request->sort_num;
        $delIdArr=$request->delId;
        for($i=0;$i<count($idArr);$i++){
            $saveArr['sort_num']=$sortNumArr[$i];
            $saveArr['name']=$nameArr[$i];
            $saveArr['ip_address']=[
                $request->IPAddress00[$i].'.'.$request->IPAddress01[$i].'.'.$request->IPAddress02[$i].'.'.$request->IPAddress03[$i],
                $request->IPAddress00[$i].'.'.$request->IPAddress01[$i].'.'.$request->IPAddress02[$i].'.'.$request->IPAddress13[$i]
            ];
            if(isset($idArr[$i])){
                $ipAddress=UserIpAddress::find($idArr[$i]);
                $ipAddress->update($saveArr);
            }else{
                UserIpAddress::create($saveArr);
            }
        }
        if(isset($delIdArr)){
            for($i=0;$i<count($delIdArr);$i++){
                $userIpAddress=UserIpAddress::find($delIdArr[$i]);
                $userIpAddress->delete();
            }
        }
        DB::commit();
        $userIpAddressArr=UserIpAddress::orderBy('sort_num','asc')->get();
        return Reply::success(__('Ip Address is updated successfully !'),['userIpAddressArr'=>$userIpAddressArr]);
    }
}
