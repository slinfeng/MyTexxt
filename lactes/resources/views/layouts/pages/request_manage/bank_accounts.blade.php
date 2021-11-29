<table class="w-100 h-100 bolder-border" id="bank-accounts" @if(isset($status) && $status==1) @else  data-bank="{{$bankAccounts}}" data-bankuse="{{$bankAccountsUse}}" @endif>
    <input name="bank_account_id" type="hidden">
    <tr class="bolder-border">
        <th colspan="2">振　込　先　口　座</th>
    </tr>
    <tr>
        <th>銀行名</th>
        <td class="text-center">
                {{$bankAccountsUse['bank_name'].' '.$bankAccountsUse['branch_name'].'  ('.$bankAccountsUse['branch_code'].')  '}}
{{--            <select name="bank_account_id" class="select-focus w-100 h-100 select-align-center" data-route="{{route('bankAccount.show',':id')}}">--}}
{{--                @foreach($bankAccounts as $bankAccount)--}}
{{--                    <option class="text-center" {{$bankAccount->id == $__env->yieldContent('bank_account_id')?'selected':''}}--}}
{{--                    value="{{$bankAccount->id}}">{{$bankAccount->bank_name}}　{{$bankAccount->branch_name}}（{{$bankAccount->branch_code}}）</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
        </td>
    </tr>
    <tr>
        <th>口座番号</th><td class="text-center">

                {{$bankAccountsUse['account_type_name'].'　'.$bankAccountsUse['account_num']}}

{{--            @foreach($bankAccounts as $bankAccount)--}}
{{--                {{$bankAccount->id == $__env->yieldContent('bank_account_id')?($bankAccount->BankAccountType->account_type_name.'　'.$bankAccount->account_num):''}}--}}
{{--            @endforeach--}}
        </td>
    </tr>
    <tr>
        <th>口座名義</th>
        <td class="text-center">

            {{$bankAccountsUse['account_name']}}

{{--            @foreach($bankAccounts as $bankAccount)--}}
{{--                {{$bankAccount->id == $__env->yieldContent('bank_account_id')?$bankAccount->account_name:''}}--}}
{{--            @endforeach--}}
        </td>
    </tr>
    @yield('bank_accounts_append')
</table>
