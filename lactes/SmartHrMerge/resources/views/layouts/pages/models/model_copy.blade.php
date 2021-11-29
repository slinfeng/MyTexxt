<div class="modal custom-modal fade" id="copy" role="dialog" data-route="@yield('route_copy')">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>@yield('title_copy')のコピー</h3>
                    <p>{{ __('コピーしてもよろしいでしょうか?')}}</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" onclick="@yield('function_copy')"
                               class="btn btn-primary continue-btn">{{ __('コピー')}}</a>
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
