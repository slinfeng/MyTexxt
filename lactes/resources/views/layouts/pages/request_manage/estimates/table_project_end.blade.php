<div class="layer-end">
    <table class="bolder-border">
        <tr>
            <td style="text-align: left;vertical-align: top" class="textarea-able">
                <p class="m-0 backcolor-E8F0FE" style="padding: 1px 5px;line-height: 20px;text-align: left">{{__("備考")}}：</p>
                <label>
                    <textarea oninput="changeLength(this)" rows="4" name="remark">@yield('remark')</textarea>
                </label>
            </td>
        </tr>
    </table>
    <br>
    <div class="w-100">
        <table class="table-border-none">
            <tr>
                <td class="p-0">
                    @include('layouts.pages.request_manage.bank_accounts')
                </td></tr>
        </table>
    </div>
    <p><textarea rows="1" hidden readonly name="remark_end" oninput="changeLength(this)" class="w-100"
                 data-initial="{{$requestSettingGlobal->remark_end}}">@yield('remark_end')</textarea></p>
</div>
