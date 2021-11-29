@include('layouts.headers.head_start')
@yield('css_append')
@include('layouts.headers.head_end')

@include('layouts.pages.page_header')

@if(!Agent::isMobile())
@include('layouts.sidebars.sidebar')
@endif

@include('layouts.pages.page_content_start')
@yield('content')
@include('layouts.pages.page_content_end')

@include('layouts.pages.page_footer')
@include('layouts.pages.models.model_adjust_browser')
{{--@yield('page_end_append')--}}
@include('layouts.footers.footer_start')
@yield('footer_append')
@include('layouts.footers.footer_end')
