@cannot($__env->yieldContent('self_modify'))
    @include('layouts.pages.sections.requestmanage.format_in')
    @include('layouts.pages.sections.requestmanage.format_out')
@else
    @if($__env->yieldContent('document_format')==1)
        @include('layouts.pages.sections.requestmanage.format_out')
    @elseif($__env->yieldContent('document_format')==0)
        @include('layouts.pages.sections.requestmanage.format_in')
    @else
        @include('layouts.pages.sections.requestmanage.format_in')
        @include('layouts.pages.sections.requestmanage.format_out')
    @endif
@endcannot
