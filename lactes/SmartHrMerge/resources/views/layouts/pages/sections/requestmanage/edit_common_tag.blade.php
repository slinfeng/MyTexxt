@include('layouts.pages.sections.requestmanage.base_selector')
@if($data->file_format_type==0)
    @include('layouts.pages.sections.requestmanage.useinit_setting')
    @include('layouts.pages.sections.requestmanage.edit_in')
    @yield('in-content')
    @include('layouts.pages.sections.requestmanage.format_in')
@else
    @include('layouts.pages.sections.requestmanage.file_append')
    @include('layouts.pages.sections.requestmanage.edit_out')
    @include('layouts.pages.sections.requestmanage.format_out')
@endif

