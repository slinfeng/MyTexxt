<?php

namespace App\Http\Controllers\Admin;

use App\Models\HrSetting;
use App\Traits\PCGateTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Reply;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class EmailTemplateController extends Controller
{
    use PCGateTrait;
    public function index()
    {
        return view('admin.email-templates.index');
    }

    /**
     * データテーブル用
     * @return mixed
     * @throws \Exception
     */
    public function getEmailTemplate()
    {
        $emailTemplate = EmailTemplate::select('id', 'email_id', 'subject', 'body');
        return Datatables::of($emailTemplate)
            ->addColumn(
                'action',
                function($row) {
                    return '<a class="dropdown-item " href="#" data-toggle="modal" data-target="#email_template_modal" onclick="editModal('.$row->id.')"  >
                                    <i class="fa fa-pencil m-r-5"></i> '.__('Edit').'</a>';
                }
            )
            ->removeColumn('id')
            ->make(true);
    }

    /**
     * 新規画面
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.email-templates.create');
    }

    /**
     * 編集画面
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $editTemplate = EmailTemplate::find($id);
        $emailVariable = json_decode($editTemplate->variables);
        $emailVariables = isset($emailVariable) ? implode(', ', $emailVariable) : __('No Variables used');
        return view('admin.email-templates.edit', compact('editTemplate','emailVariables'));
    }

    /**
     * 変更
     * @param Request $request
     * @param $id
     * @return array|bool[]|string[]
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'body' => 'required',
        ]);
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        $emailTemplate = EmailTemplate::find($id);
        $emailTemplate->body = $request->body;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->save();
        return Reply::success(__('Email template is updated successfully.'));
    }

    /**
     * 登録
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_id' => 'bail|required|unique:email_templates|max:200',
            'subject' => 'required',
            'body' => 'required',
        ]);
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        $reqData = $request->all();
        EmailTemplate::create($reqData);
        return Reply::success(__('Email Template is added successfully.'));
    }

    /**
     * 請求管理用変更
     * @param Request $request
     * @return array|bool[]|string[]
     */
    public function requestMailUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'body' => 'required',
        ]);
        if($validator->fails()) return Reply::fail($validator->errors()->first());
        $reqData=$request->all();
        $id=$reqData['id'];
        $emailTemplate = EmailTemplate::find($id);
        $emailTemplate->update($reqData);
        return Reply::success(__('メールは正常に更新されました。'));
    }

}
