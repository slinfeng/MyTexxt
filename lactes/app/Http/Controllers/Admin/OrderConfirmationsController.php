<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Models\OurPositionType;
use App\Models\AccountsOrderConfirmation;
use App\Models\Client;
use App\Models\File;
use App\Http\Controllers\Controller;
use App\Models\RequestSettingExtra;
use App\Models\RequestSettingGlobal;
use App\Rules\Period;
use App\Traits\FillFileInfo;
use App\Traits\PCGateTrait;
use App\Traits\RequestManageTrait;
use App\Traits\ShowDatatable;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderConfirmationsController extends Controller
{
    use RequestManageTrait,FillFileInfo,ShowDatatable,PCGateTrait;
    /**
     * トップページに遷移
     * @return Application|Factory|View
     */
    public function index()
    {
        $this->deniesForView(AccountsOrderConfirmation::class);
        return view('admin.accountsOrder.index',$this->getIndexArray(AccountsOrderConfirmation::SETTING_ID));
    }

    public function create()
    {
        $this->deniesForModify(AccountsOrderConfirmation::class);
        $requestSettingGlobal = RequestSettingGlobal::find(AccountsOrderConfirmation::SETTING_ID);
        return view('admin.accountsOrder.create',compact('requestSettingGlobal'));
    }

    public function getCommonValidatorArr($id=0){
        return [
            'order_manage_code' => [Rule::unique('accounts_order_confirmations')->whereNull('deleted_at')->ignore($id)],
            'client_id' =>['bail','required','not_in:0','exists:clients,id'],
            'period' =>['bail','required',new Period],
        ];
    }

    public function saveFile($fileInfo,$file,$temp){
        $cloud_request_period=RequestSettingExtra::select('id','cloud_request_period')->first()->cloud_request_period;
        if($cloud_request_period>0) $this->fillFileInfoWhenCloud($fileInfo,$file);
        else $this->fillFileInfoWhenLocal($fileInfo,$file);
        $file->path = 'request/['.str_pad($temp['client_id'],4,'0',STR_PAD_LEFT).']'.$temp['cname'].'/注文請書/['.$temp['order_manage_code'].']'.$file->basename;
        if($cloud_request_period>0) Storage::disk('local')->put($file->path, file_get_contents($fileInfo->getRealPath()));
        $file->save();
    }

    public function store(Request $request)
    {
        $this->deniesForModify(AccountsOrderConfirmation::class);
        $validator = Validator::make($request->all(), array_merge(
                $this->getCommonValidatorArr(),
                ['project_name_or_file_name' =>['bail','required','max_mb:10']]
            ),['project_name_or_file_name.required' => __('注文請書のアップロードは必要となります。'),
            'project_name_or_file_name.max' => __('validation.max')]);
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $temp = $request->all();
        $fileInfo = $request->file("project_name_or_file_name");
        if (isset($fileInfo)) {
            if ($fileInfo->isValid()) {
                DB::beginTransaction();
                $file = new File;
                $this->saveFile($fileInfo,$file,$temp);
                $temp['file_id'] = $file->id;
                $temp['project_name_or_file_name'] = $file->basename;
                AccountsOrderConfirmation::create($temp);
                DB::commit();
            }
        }
        return Reply::success(__('注文請書が正常に追加されました。'));
    }

    public function getPJNO($client_id,$date,$position)
    {
        return $this->getManageCode(AccountsOrderConfirmation::class,$client_id,$date,$position);
    }

    public function accountsGetPJNO(){
        $this->deniesForModify(AccountsOrderConfirmation::class);
        return $this->getPJNO($_POST["client_id"],$_POST["date"],$_POST["our_position_type"]);
    }

    /**
     * Ajaxでデータを抽出用
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function search(Request $request)
    {
        $this->deniesForView(AccountsOrderConfirmation::class);
        $model = AccountsOrderConfirmation::class;
        $res = $this->getSearchResult($request,$model);
        $res->select('id','period','order_manage_code','project_name_or_file_name','client_id','cname','file_id');
        return datatables()->of($res->get())
            ->editColumn('period', '{{date("Y-m-d",strtotime(mb_substr($period, 0, 10)))}}')
            ->editColumn('project_name_or_file_name', function ($row) {
                return $this->getFileLink($row->file_id,$row->project_name_or_file_name);
            })
            ->editColumn('client_name', function ($row) {
                return $this->getClientLink($row->cname);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * 編集画面を戻す
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $this->deniesForView(AccountsOrderConfirmation::class);
        $data = AccountsOrderConfirmation::find($id);
        $clients = $data->client;
        $clientSortType = RequestSettingExtra::select('client_sort_type')->first()->client_sort_type;
        return view('admin.accountsOrder.edit', compact('data', "clients","clientSortType"));
    }

    /**
     * データベースに更新
     * @param $id
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function update($id, Request $request)
    {
        $this->deniesForModify(AccountsOrderConfirmation::class);
        $validator = Validator::make($request->all(), $this->getCommonValidatorArr($id));
        if ($validator->fails()) return Reply::fail($validator->errors()->first());
        $fileInfo = $request->file("project_name_or_file_name");
        $accountsOrderConfirmations = AccountsOrderConfirmation::find($id);
        $temp = $request->all();
        $file = $accountsOrderConfirmations->file;
        if (isset($fileInfo)) {
            $this->saveFile($fileInfo,$file,$temp);
            $temp['project_name_or_file_name'] = $file->basename;
        }
        $accountsOrderConfirmations->update($temp);
        return Reply::success(__('注文請書が正常に変更されました。'));
    }

    /**
     * 削除
     * @param $id
     * @return array|string[]
     */
    public function destroy($id)
    {
        $this->deniesForModify(AccountsOrderConfirmation::class);
        AccountsOrderConfirmation::destroy($id);
        return Reply::success('当該注文請書を削除しました。');
    }
}
