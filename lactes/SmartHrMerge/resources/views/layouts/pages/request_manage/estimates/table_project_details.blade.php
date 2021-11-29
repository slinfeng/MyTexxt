<table class="bolder-border text-center">
    <tr>
        <td class="w-th-title font-weight-bold">作業場所</td>
        <td class="input-able w-content-b"><label>
                <input name="work_place" type="text"
                       value="@yield('work_place')"></label></td>
        <td class="input-able w-th-title"><label>
                <input class="text-center font-weight-bold" name="title_a" type="text"
                       value="@yield('title_a')"/></label></td>
        <td class="input-able"><label>
                <input class="text-left" name="content_a" type="text"
                       value="@yield('content_a')"/></label></td>
    </tr>
    <tr>
        <td class="font-weight-bold">作業期間</td>
        <td class="input-able"><label>
                <input name="period" autocomplete="off" type="text" @yield('date_month')
                       value="@yield('period')"></label></td>
        <td class="font-weight-bold">納品場所</td>
        <td class="input-able"><label>
                <input name="acceptance_place" type="text"
                       value="@yield('acceptance_place')"></label></td>
    </tr>
</table>
<br>
<table id="projectTable" class="bolder-border">
    <thead>
    <tr class="bolder-border font-weight-bold text-center">
        <td class="w-th-title">{{__("作業者名")}}</td>
        <td class="w-content-b">{{__("作業明細")}}</td>
        <td class="w-th-title">{{__("数量")}}</td>
        <td style="width: 7%">{{__("単位")}}</td>
        <td>{{__("単金")}}</td>
        <td style="width: 16%">{{__("金額")}}</td>
    </tr>
    </thead>
    <tbody>
    @if(isset($data))
        @foreach($data->accounts_estimate_detail as $v)
        @include('layouts.pages.request_manage.estimates.tr_employees')
        @endforeach
    @else
        @include('layouts.pages.request_manage.estimates.tr_employees')
    @endif
    <tr>
        <td></td>
        <td class="text-center font-weight-bold">{{__("小　計")}}</td>
        <td></td><td></td><td></td>
        <td class="text-right"><input class="text-right w-100" name="subtotal" type="text" value="@yield('amount')" readonly></td>
    </tr>
    <tr>
        <td></td>
        <td class="text-center font-weight-bold">{{__("消費税")}}</td>
        <td class="text-center"><input type="text" class="w-100 text-center" readonly name="tax_rate"
                                       value="@yield('tax_rate')"></td>
        <td class="text-center">%</td>
        <td></td>
        <td class="text-right"><input class="text-right w-100" name="totalTax" type="text" value="" readonly></td>
    </tr>
    <tr>
        <td></td>
        <td class="text-center font-weight-bold">{{__("合　計")}}</td>
        <td></td><td></td><td></td>
        <td class="text-right"><input class="text-right w-100" name="estimate_total" type="text"
                                      value="@yield('estimate_total')" readonly></td>
    </tr>
    </tbody>
</table>
<br>
