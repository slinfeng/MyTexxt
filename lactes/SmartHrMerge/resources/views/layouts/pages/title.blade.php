<div class="card-header mb-3">
    @yield('init_val')
    <div class="row align-items-center">
        <div class="col-9">
            <h3 class="mb-0">
                <a name="index" class="pointer-events-none" onclick="returnInit()">@yield('view_title')</a>
                <span name="showed-by-client" class="invisible"></span>
            </h3>
        </div>
        @yield('title_append')
        @can($__env->yieldContent('permission_modify'))
        <div  class="col-auto float-right ml-auto">
            <a href="javascript:void(0)" data-href="@yield('route_create')" class="btn add-btn"
               onclick="@yield('function_create')">
                <i class="fa fa-plus"></i> {{ __('新規作成') }}
            </a>
        </div>
        @endcan
        @if($__env->yieldContent('view_title')=='請求書管理画面')
            @can($__env->yieldContent('self_modify'))
                <div  class="col-auto float-right ml-auto">
                    <a href="javascript:void(0)" data-href="@yield('route_create')" class="btn add-btn"
                       onclick="@yield('function_create')">
                        <i class="fa fa-plus"></i> {{ __('新規作成') }}
                    </a>
                </div>
            @endcan
        @endif
    </div>
</div>
