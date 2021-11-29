<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\BankAccountType;
use App\Models\Client;
use App\Http\Controllers\Controller;
use App\Models\OurPositionType;
use App\Models\RequestSettingClient;
use App\Models\RequestSettingExtra;
use App\Models\RequestSettingGlobal;
use App\Models\User;
use App\Rules\ClientName;
use App\Traits\PCGateTrait;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Facades\Agent;
use Symfony\Component\HttpFoundation\Response;
use yajra\Datatables\Datatables;

class ClientController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->abortNotFoundForMobile();
    }
    use PCGateTrait;

    /**
     * インデックス
     * @return Application|Factory|View
     */
    public function index()
    {
        $this->deniesForView(Client::class);
        $ourRole = OurPositionType::select('id','our_position_type_abbr_name')->get();
        $searchMode = RequestSettingGlobal::find(Client::SETTING_ID)->calendar_search_unit;
        return view('admin.clients.index',compact('ourRole','searchMode'));
    }

    /**
     * 新規
     * @return Application|Factory|View
     */
    public function create()
    {
        $this->deniesForModify(Client::class);
        $ourpositiontypes = OurPositionType::orderBy('id', 'asc')->get();
        $our_position_type = RequestSettingGlobal::first()->position_type;
        $nextID = Client::withTrashed()->max('id')+1;
        $boo=0;
        return view('admin.clients.create',compact(  'ourpositiontypes','nextID','our_position_type','boo'));
    }

    /**
     * 検証
     * @return array
     */
    public function getValidatorArr(){
        return ['client_abbreviation' => 'max:50',
            'cooperation_start'=>['bail','required','date'],
            'url'=>['bail','nullable','url'],
            'mail'=>['bail','nullable','email'],
            'tel'=>['bail','nullable','regex:/^[0-9]{6,14}$/'],
            'fax'=>['bail','nullable','regex:/^[0-9]{6,14}$/'],
            'post_code'=>['bail','nullable','regex:/^〒[0-9]{3}-[0-9]{4}$/'],
        ];
    }

    /**
     * 取引先を登録
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function store(Request $request)
    {
        $this->deniesForModify(Client::class);
        $validator = Validator::make($request->all(), array_merge(
            $this->getValidatorArr(),
            [
                'client_name'=>['bail','required','max:200',function($attribute, $value, $fail){
                    $boo = preg_match('/\*|<|>|\\\\|\||"|\?|:|\//',$value);
                    if ($boo==1) {
                        $fail('会社名には次の文字は使えません：¥\/:*?"<>|');
                    }
                },new ClientName],
                'our_role'=>['bail','required','in:1,2']
                ]
        ));
        if($validator->fails()) return Reply::fail($validator->errors()->first());

        $reqData = $request->all();
        $client=Client::create($reqData);

        $requestSettingClient = RequestSettingClient::create(['client_id'=>$client->id]);

        $global = RequestSettingGlobal::find(5);
        $extra = RequestSettingExtra::first();
        $requestSettingClient->use_init_val = $global->use_init_val;
        $requestSettingClient->company_info = $global->company_info;
        $requestSettingClient->remark_start = $global->remark_start;
        $requestSettingClient->remark_end = $global->remark_end;
        $requestSettingClient->project_name = $global->project_name;
        $requestSettingClient->contract_type = $extra->contract_type;
        $requestSettingClient->contract_type_other_remark = $extra->contract_type_other_remark;
        $requestSettingClient->create_month = $global->create_month;
        $requestSettingClient->create_day = $global->create_day;
        $requestSettingClient->period = $global->period;
        $requestSettingClient->work_place = $global->work_place;
        $requestSettingClient->payment_contract = $global->payment_contract;
        $requestSettingClient->request_pay_month = $extra->request_pay_month;
        $requestSettingClient->request_pay_date = $extra->request_pay_date;
        $requestSettingClient->save();
        return Reply::success(__('Client is added successfully.'));
    }

    /**
     * 取引先を編集
     * @param Client $client
     * @return Application|Factory|View
     */
    public function edit(Client $client)
    {
        $this->deniesForView(Client::class);
        $ourpositiontypes = OurPositionType::orderBy('id', 'asc')->get();
        $boo = User::where('client_id',$client->id)->count();
        return view('admin.clients.edit', compact('client', 'ourpositiontypes','boo'));
    }

    /**
     * 取引先を更新
     * @param Request $request
     * @param Client $client
     * @return array|bool[]|string[]
     */
    public function update(Request $request, Client $client)
    {
        $this->deniesForModify(Client::class);
        $validator = Validator::make($request->all(), array_merge(
            $this->getValidatorArr(),
            ['our_role'=>[function ($attribute, $value, $fail) use ($client){
                if ($this->updateDisable($client)) {
                    if($value != $client->our_role){
                        $fail(__('当該取引先と契約を結んでいます。我社立場の変更は出来ません。'));
                    }
                }
            },], 'client_name'=>['required',function($attribute, $value, $fail){
                $boo = preg_match('/\*|<|>|\\\\|\||"|\?|:|\//',$value);
                if ($boo==1) {
                    $fail('会社名には次の文字は使えません：¥\/:*?"<>|');
                }
            },new ClientName($client->id)]]
        ));
        $validator->sometimes('document_format', ['required'], function ($client) {
            return (User::where('client_id',$client->id)->count('client_id'))>0;
        });
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        if(isset($request->document_format)){
            $document_format = $request->document_format;
            if(count((array)$document_format)>1){
                $request['document_format']=2;
            }else if($document_format[0]==1){
                $request['document_format']=1;
            }else{
                $request['document_format']=0;
            }
        }
        $reqData = $request->all();
        $client->update($reqData);
        return Reply::success(__('Client is updated successfully.'));
    }

    /**
     * 取引先を削除
     * @param Client $client
     * @return array|bool[]|string[]
     */
    public function destroy(Client $client)
    {
        $this->deniesForModify(Client::class);
        if($this->updateDisable($client)){
            return Reply::fail(__('当該取引先と契約を結んでいます。削除は出来ません。'));
        }
        if ($client->delete()) {
            return Reply::success(__('Client is deleted successfully.'));
        } else {
            return Reply::fail(__('Client delete failed, please try again.'));
        }
    }

    /**
     * 更新可能性の判定
     * @param $client
     * @return bool
     */
    private function updateDisable($client){
        return sizeof($client->accounts_invoices)>0 || sizeof($client->accounts_estimate)>0
        || sizeof($client->AccountsOrder)>0 || sizeof($client->accountsOrderContirmation)>0
        || sizeof($client->LetterOfTransmittals)>0 || sizeof($client->Receipts)>0 || sizeof($client->user)>0;
    }

    /**
     * インデックス表示のために、取引先を取得
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function getClientsList(Request $request)
    {
        $this->deniesForView(Client::class);
        $id=$request->id;
        $wildcard=$request->client_name;
        $position = $request->position;
        $period = $request->period;
        $builder = Client::select('id','cooperation_start','client_name','url','our_role','priority','client_abbreviation','client_address','tel','memo')
            ->selectRaw("LPAD(id,4,0) as cid")
            ->with('OurPositionType:id,our_position_type_abbr_name')
            ->when($id, function($query) use ($id){
                $condition="RIGHT(CONCAT('000',id),4) like '%{$id}%'";
                $query->whereRaw($condition);
            })
            ->when($wildcard, function($query) use ($wildcard){
                $condition="(clients.client_abbreviation like concat('%{$wildcard}%','')";
                $condition.=" or clients.client_name like concat('%{$wildcard}%','')";
                $condition.=" or clients.client_address like concat('%{$wildcard}%','')";
                $condition.=" or clients.tel like concat('%{$wildcard}%','')";
                $condition.=" or clients.memo like concat('%{$wildcard}%',''))";
                $query->whereRaw($condition);
            })
            ->when($position, function($query) use ($position){
                $query->where('our_role',$position);
            })
            ->when($period, function($query) use ($period){
                $startDate = substr($period,0,10);
                $endDate = substr($period,-10,10);
                $query->where('clients.cooperation_start', '>=', $startDate)->where('clients.cooperation_start', '<=', $endDate);
            });
        return Datatables::of($builder->get())
            ->escapeColumns([])
            ->rawColumns(['cooperation_start', 'priority'])
            ->make(true);
    }

    /**
     * 取引先を取得
     * @param Request $request
     * @return array|string
     */
    public function getClients(Request $request){
        if($this->none(Client::class)){
            return '{}';
        }
        $client_sort_type = RequestSettingExtra::first()->client_sort_type;
        $position = $request->position;
        $client_info = Client::select('id', 'client_name','client_abbreviation')
            ->where('priority',0)
            ->when($position,function($query) use ($position) {
                return $query->where('our_role',$position);
            })->get();
        if($client_sort_type==Client::SORT_BY_ID){
            $clients = $client_info->sortByDesc('id')->values();
        }else{
            $clients = $this->sortArray($client_info,'client_abbreviation');
        }
        return $clients;
    }

    /**
     * 排列
     * @param $list
     * @param $callback
     * @return array
     */
    private function sortArray($list,$callback){
        $res_arr = [];
        $res_letter = [];
        $res_Kana = [];
        $res_empty = [];
        $res_other = [];
        foreach ($list as $key => $value) {
            $value = $value->getAttribute($callback);
            $value = mb_convert_kana($value,'K');
            $value = mb_convert_kana($value,'aHcV');
            switch (true){
                case $value=='':
                    $res_empty[$key] = $value;
                    break;
                case preg_match('/^[あ-ん].+$/',$value):
                    $res_Kana[$key] = $value;
                    break;
                case preg_match('/^[a-zA-Z].+$/',$value):
                    $res_letter[$key] = $value;
                    break;
                default:
                    $res_other[$key] = $value;
                    break;
            }
        }
        asort($res_letter);
        asort($res_Kana);
        asort($res_empty);
        asort($res_other);
        $index = 0;
        foreach (array_keys($res_letter) as $key) {
            $res_arr[$index] = $list[$key];
            $index++;
        }
        foreach (array_keys($res_Kana) as $key) {
            $res_arr[$index] = $list[$key];
            $index++;
        }
        foreach (array_keys($res_other) as $key) {
            $res_arr[$index] = $list[$key];
            $index++;
        }
        foreach (array_keys($res_empty) as $key) {
            $res_arr[$index] = $list[$key];
            $index++;
        }
        return $res_arr;
    }

    /**
     * 取引先の優先度を変更
     * @param $id
     * @return array|string[]
     */
    public function changePriority($id)
    {
        $this->deniesForModify(Client::class);
        $data = Client::find($id);
        $data->priority = $data->priority === 1 ? 0 : 1;
        $data->update();
        return Reply::success(__('優先度の変更が完了しました！'));
    }

    /**
     * 特定の取引先を取得
     * @param $id
     * @return array|string[]
     */
    public function getOneClient($id)
    {
        $this->deniesForView(Client::class);
        return Reply::success('success',['client'=>Client::find($id)]);
    }

    public function getBankInfo(Request $request){
        $requestSettingClient = RequestSettingClient::where('client_id',$request->id)->first();
        $bank_account_types = BankAccountType::all();
        return view('admin.clients.bankInfo', compact('requestSettingClient', 'bank_account_types'));
    }

    public function saveBankInfo(Request $request){
        $this->deniesForModify(Client::class);
        $validator = Validator::make($request->all(),[
            'bank_name' => 'required',
            'branch_name' => 'required',
            'branch_code' => ['bail','required','numeric'],
            'account_type' => ['bail','required','exists:bank_account_types,id'],
            'account_name' => ['required'],
            'account_num' => ['bail','required','numeric'],
        ],);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $requestSettingClient = RequestSettingClient::find($request->id);
        $requestSettingClient->update($request->all());
        return Reply::success(__('口座情報が正常に変更されました！'));
    }
}
