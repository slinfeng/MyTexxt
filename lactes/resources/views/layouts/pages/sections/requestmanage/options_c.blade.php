@section('option_append')
    <button class="btn-option btn btn-sm btn-primary" data-toggle="modal"
            data-target="#copy">
        {{ __('コピーして作成') }}</button>
    <button class="btn-option btn btn-sm btn-primary" onclick="expensePost(this);" id="expenseHacchuuCheck"
            data-route="{{route('expense.hacchuu')}}"> {{ __('発注') }}</button>
@endsection
