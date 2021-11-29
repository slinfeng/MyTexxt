<!-- User Modal -->
<div id="employee_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="employee_modal_content" >

        </div>
    </div>
</div>
<!-- /User Modal -->

<!-- Delete User Modal -->
<div class="modal custom-modal fade" id="delete_employee_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ __('Delete User') }}</h3>
                    <p>{{ __('Are you sure want to delete?') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" id="delete_employee_btn" class="btn btn-primary continue-btn">{{ __('Delete') }}</a>
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
<!-- /Delete User Modal -->
<!-- Change User Role Modal -->
<div class="modal custom-modal fade" id="change_employee_priority_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ __('Change User Role') }}</h3>
                    <p>{{ __('Are you sure want to change priority?') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" id="change_employee_priority_btn" class="btn btn-primary continue-btn">{{ __('Change') }}</a>
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
<!-- /Change User Status Modal -->

<!-- Modal -->
<div class="modal custom-modal fade" id="employee_add_modal" data-keyboard="false" data-backdrop="static" role="dialog">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container">
                    <h1 class="modal-title d-block w-100 text-center">{{ __('Add User') }}</h1>
                    <div class="w-100 text-center">
                        <div class='step'></div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class='wizard-tab' stepname='step1'>
                    <div class="w-100 text-center p-1">
                        <h4>{{ __('') }}</h4>
                    </div>
                    <div class="form-group form-check"  >
                        <div class="form-check">
                            <input type="radio" checked name="employee_user_type" id="employee_user_type_radio_0" class="form-check-input" value="0">
                            <label class="form-check-label" for="employee_user_type_radio_0">
                                {{ __("The user already registered this system.") }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="employee_user_type" id="employee_user_type_radio_1" class="form-check-input" value="1">
                            <label class="form-check-label" for="employee_user_type_radio_1">
                                {{ __("The user hasn't registered this system.") }}
                            </label>
                            {{--                                <div class="invalid-feedback">{{ __("You must select one to proceed.") }}</div>--}}
                        </div>
                    </div>
                </div>
                <div class='wizard-tab' stepname='step2' id='step2' style="min-height: 112px">
                </div>
                <div class='wizard-tab' stepname='step3' id='step3' style="min-height: 473px">
                </div>
            </div>
            <div class="modal-footer">
                <div class="content-full col-12" style="z-index: 0">
                    <div class="col-6 text-right float-right">
                        <button class='btn btn-primary btn-prev'>{{ __("Prev") }}</button>
                        <button class='btn btn-primary btn-next'>{{ __("Next") }}</button>
                        <button type="submit" id="employee-create-btn" class='btn btn-primary btn-end'>{{ __("Finalize") }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
