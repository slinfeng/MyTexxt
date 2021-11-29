<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header mb-3">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="mb-0">{{ __('Sessions') }}</h4>
                    </div>
                </div>
            </div>


            <div class="col-12">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>

            <div class="col-12">
                <table class="table table-striped custom-table mb-0 datatable" id="sessions">
                    <thead>
                    <tr>
{{--                            <th>{{__('Session ID')}}</th>--}}

                        <th style="width: 150px">{{__('Online User Name')}}</th>
                        <th>{{__('Ip Address')}}</th>
                        <th>{{__('User Agent')}}</th>
                        <th>{{__('Last Activity')}}</th>

{{--                            @can('session_delete')--}}
{{--                        @can($__env->yieldContent('permission_modify'))--}}
{{--                        <th style="width: 50px;" class="text-right no-sort">{{__('Action')}}</th>--}}
{{--                        @endcan--}}
{{--                            @endcan--}}
                    </tr>

                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    </div></div>

<!-- Delete Session Modal -->
<div class="modal custom-modal fade" id="delete_session_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ __('Delete Session') }}</h3>
                    <p>{{ __('Are you sure want to delete?') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" id="delete_session_btn" class="btn btn-primary continue-btn">{{ __('Delete') }}</a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">{{ __('Cancel') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Session Modal -->
