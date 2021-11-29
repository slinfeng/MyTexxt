<div class="search">
    <div class="form-group form-focus">
        <div class="cal-icon">
            <input autocomplete="off" class="form-control floating" data-search-mode="{{$searchMode}}" id="startAndEndDate" type="text" value="@yield('search_period')"/>
        </div>
        <label class="focus-label">
            {{ __('自') }}<span class="invisible">****-**-** ～</span>{{ __('至') }}</label>
    </div>
</div>
