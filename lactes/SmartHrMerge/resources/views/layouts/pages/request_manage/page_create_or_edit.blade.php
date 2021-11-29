<div class="@yield('action')">
    @include('layouts.pages.initval.requestmanage.input_c')
    <div class="w-100 common">
        <h3>@yield('page_title')@yield('action_name'){{ __('画面') }}</h3>
{{--        @cannot($__env->yieldContent('self_modify'))--}}
        @yield('base_selector')
{{--        @endcannot--}}
        @include('layouts.pages.request_manage.gotoback')
    </div>
    @yield('format_in')
    @yield('format_out')
    @include('layouts.pages.models.model_adjust_browser')
{{--    @cannot($__env->yieldContent('self_modify'))--}}
    <div class="modal custom-modal fade" id="print_modal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>@yield('page_title')を印刷する</h3>
                    </div>
                    <div class="form-body">
                        <div class="row m-0">
                            <div class="col-4">
                                <h4><label><input value="A" type="checkbox" name="printCheck" @if(substr($__env->yieldContent('print_flag'),0,1)==1)checked @endif> @yield('page_title')
                                    </label></h4>
                            </div>
                            @if(strlen($__env->yieldContent('print_flag'))==2)
                                <div class="col-4"><h4><label><input value="B" type="checkbox" name="printCheck" @if(substr($__env->yieldContent('print_flag'),1,1)==1)checked @endif> {{ __('注文請書') }}
                                        </label></h4>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" onclick="preparePrint()" id="delete_client_btn"
                                   class="btn btn-primary continue-btn">{{ __('Print') }}</a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal"
                                   class="btn btn-primary cancel-btn">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    @endcannot--}}
</div>

