@section('option_append')
    <button class="btn-option btn btn-sm btn-primary" data-toggle="modal"
            data-target="#copy">
        {{ __('翌月分をコピーして作成') }}</button>
    <button class="btn-option btn btn-sm btn-primary" onclick="saveApproveCheck()" id="approve_invoice"
            data-route="{{route('invoice.fillInvoice')}}"> {{ __('承認') }}</button>
    <button class="btn-option btn btn-sm btn-primary" onclick="saveCheck()" id="save_invoice"
            data-route="{{route('invoice.fillInvoice')}}"> {{ __('金額保存') }}</button>
@endsection
