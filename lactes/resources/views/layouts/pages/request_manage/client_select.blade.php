<h4 style="width: 80%;display: inline-block">{{ __('取引先') }}
    <select data-route="{{route('client.getClients')}}" data-get-one-client="{{route('client.getOneClient',':id')}}" name="client_id" onchange="changeNum(this)">
        <option value="0">{{ __('選択してください') }}</option>
    </select>
</h4>

