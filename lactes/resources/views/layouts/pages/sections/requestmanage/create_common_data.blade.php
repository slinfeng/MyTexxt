@include('layouts.pages.sections.requestmanage.action_create')
@include('layouts.pages.sections.requestmanage.base_selector')
@include('layouts.pages.sections.requestmanage.client_name_null')
@include('layouts.pages.sections.requestmanage.amount_0')
@include('layouts.pages.sections.requestmanage.useinit_global')
@section('init_date_create')
    data-month="{{$requestSettingGlobal['create_month']}}" data-day="{{$requestSettingGlobal['create_day']}}"
@endsection
