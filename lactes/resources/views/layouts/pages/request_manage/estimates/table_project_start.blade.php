<input type="hidden" name="calc_type" value="@yield('calc_type')">
<table class="border-0 w-pro-a">
    <tr class="border-0">
        <td colspan="2" class="border-0 p-0">
                <textarea rows="1" readonly name="remark_start" oninput="changeLength(this)" data-initial="{{$requestSettingGlobal->remark_start}}"
                >@yield('remark_start')</textarea><br>
        </td>
    </tr>
    <tr class="bolder-border">
        <td class="font-weight-bold text-center w-title-a">案件名</td>
        <td class="text-left textarea-able">
            <label><textarea name="project_name_or_file_name" oninput="changeLength(this)" rows="1"
                >@yield('project_name_or_file_name')</textarea></label></td>
    </tr>
</table>
<br>
<table class="bolder-0 w-pro-a">
    <tr class="bolder-border">
        <td class="font-weight-bold text-center w-title-a" style="height: 40px;">御見積金額<br/>（税抜）</td>
        <td class="text-right" style="font-size: 1.5rem"><input class="text-right font-weight-bold w-100" name="estimate_subtotal" type="text" value="@yield('amount')" readonly></td>
    </tr>
</table>
<br>
