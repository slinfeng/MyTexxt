<input hidden name="init_val" data-currency-symbol="{{$requestSettingExtra['currency']}}"
       data-print-font="{{$requestSettingExtra->font_family_type->font_family}}"
       data-re-client-id="{{isset($_GET['re_client_id'])?$_GET['re_client_id']:''}}"
       data-re-period="{{isset($_GET['re_period'])?$_GET['re_period']:''}}"
       data-re-position="{{isset($_GET['re_position'])?$_GET['re_position']:''}}"
       data-client-sort-type="{{$requestSettingExtra['client_sort_type']}}"
       data-re-href="@yield('route_index')" data-save-ipaddr="{{$requestSettingExtra['local_ip_addr']}}">

