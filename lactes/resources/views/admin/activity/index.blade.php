<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header mb-3">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">{{ __('Activity Logs') }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <table class="table table-striped custom-table mb-0 datatable" id="activityLogs">
                    <thead>
                    <tr>
                        <th></th>
                        <th>{{__('Log Time')}}</th>
                        <th class="text-center" style="padding-right: 8px!important;">{{__('Description')}}</th>
                        <th>{{__('Subject Type')}}</th>
                        <th>{{__('Causer Id')}}</th>
                        @can($__env->yieldContent('permission_modify'))
                        <th style="width: 50px;" class="text-right no-sort">{{__('Action')}}</th>
                        @endcan
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
<div class="modal custom-modal fade" id="delete_activityLog_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ __('Delete Activity Log') }}</h3>
                    <p>{{ __('Are you sure want to delete?') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" id="delete_activityLog_btn" class="btn btn-primary continue-btn">{{ __('Delete') }}</a>
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
