<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header mb-3">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h4 class="mb-0">{{ __('Email Templates') }}</h4>
                    </div>
{{--                        @can('email_template_create')--}}
{{--                        <div class="col-4 text-right">--}}
{{--                            <a href="#" class="btn btn-primary btn-block" data-toggle="modal" id="showEmailTemplatesModalBtn"--}}
{{--                               data-target="#client_modal" data-url="{{ route('email-templates.create') }}" title="{{ __('Add Email Templates') }}">--}}
{{--                                <i class="fa fa-plus"></i> {{ __('Add Email Templates') }}--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                        @endcan--}}
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
                <table class="table table-striped custom-table mb-0 datatable" id="emailTemplate" >
                    <thead>
                    <tr>
{{--                        <th>{{__('Email ID')}}</th>--}}
                        <th>{{__('Subject')}}</th>
                        <th>{{__('Text')}}</th>

                        @can($__env->yieldContent('permission_modify'))
                        <th style="width: 50px;" class="no-sort">{{__('Action')}}</th>
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

<!-- Email Template Modal -->
<div id="email_template_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="email_template_modal_content" >

        </div>
    </div>
</div>
<!-- /Email Template Modal -->
