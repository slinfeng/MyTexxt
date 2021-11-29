{{--<div style="width: 480px;">--}}
@cannot($__env->yieldContent('self_modify'))
<div class="search">
    <div class="form-group form-focus select-focus">
        <select name="position" class="select floating" onchange="initTable()">
            <option value="@yield('position_a_val')" @if($position==1)selected @endif>@yield('position_a_html')</option>
            <option value="@yield('position_b_val')" @if($position==2)selected @endif>@yield('position_b_html')</option>
        </select>
        <label class="focus-label">{{ __('我社立場') }}</label>
    </div>
</div>
@endcannot
@include('layouts.pages.searchs.period')
{{--@include('layouts.pages.sections.requestmanage.options_c')--}}
@can($__env->yieldContent('permission_modify'))
    @include('layouts.pages.options')
@endcan
