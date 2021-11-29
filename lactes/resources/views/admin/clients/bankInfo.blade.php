
<div class="modal-header">
    <div class="container">
        <h1 class="modal-title d-block w-100 text-center">{{ __('口座情報編集') }}</h1>
        <div class="w-100 text-center">
            <div class='step'></div>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
<div class="modal-body">
    <form id="client_bank_info_form" action="" method="post">
        <input name="id" value="{{$requestSettingClient->id}}" type="hidden">
        <div class="card-body" id="employee-address-container" >
            <table class="table-left-setting w-100">
                <tr>
                    <td>
                        {{ __('銀行名') }}
                    </td>
                    <td>
                        <input name="bank_name" value="{{$requestSettingClient->bank_name}}" type="text" class="form-control" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('支店名') }}
                    </td>
                    <td>
                        <input name="branch_name" value="{{$requestSettingClient->branch_name}}" type="text" class="form-control" required>

                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('支店番号') }}
                    </td>
                    <td>
                        <input name="branch_code" value="{{$requestSettingClient->branch_code}}" type="text" class="form-control number" required>

                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('預金種類') }}
                    </td>
                    <td>
                        <select class="select form-control" name="account_type">
                            @foreach($bank_account_types as $bankAccountType)
                                <option value="{{$bankAccountType->id}}" {{$requestSettingClient->account_type==$bankAccountType->id?'selected':''}}>{{$bankAccountType->account_type_name}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('口座番号') }}
                    </td>
                    <td>
                        <input name="account_num" value="{{$requestSettingClient->account_num}}" type="text" class="form-control number" required>

                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('口座名義') }}
                    </td>
                    <td>
                        <input name="account_name" value="{{$requestSettingClient->account_name}}" type="text" class="form-control" required>

                    </td>
                </tr>
            </table>

        </div>

    </form>
</div>
<div class="modal-footer">
    <div class="content-full col-12" style="z-index: 0">
        <div class="col-6 text-right float-right">
            <button class="btn btn-primary" type="button" onclick="bankInfoSave();">保存</button>
        </div>
    </div>
</div>
