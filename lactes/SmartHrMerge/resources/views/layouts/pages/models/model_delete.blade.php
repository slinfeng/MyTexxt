<div class="modal custom-modal fade" id="delete" role="dialog" data-route="@yield('route_delete')">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>@yield('title_delete'){{ __('の削除') }} </h3>
                    <p>{{ __('削除してもよろしいでしょうか?') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" onclick="@yield('function_delete')"
                               class="btn btn-primary continue-btn">{{ __('削除') }}</a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-dismiss="modal"
                               class="btn btn-primary cancel-btn">{{__('Cancel')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
