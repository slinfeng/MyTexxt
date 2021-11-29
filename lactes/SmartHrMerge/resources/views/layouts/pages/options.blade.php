<div id="options" class="hide">
    <span class="invisible float-right">
        @include('layouts.pages.request_manage.option_exclude')
        @yield('option_append')
        <button class="btn-option btn btn-sm btn-danger" data-toggle="modal"
                data-target="#delete"> {{ __('削除') }}</button>
    </span>
</div>
