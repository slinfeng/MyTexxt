<div id="add_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="modalTitle"></span>{{ __('を追加') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="add_modal_form">
                    @csrf
                    <input id="edit_modal_id" name="id" type="hidden">
                    <div class="form-group">
                        <label for="validationDefault01"><span class="modalTitle"></span>{{ __('名') }}<span class="text-danger">*</span></label>
                        <input class="form-control modal_name1" type="text" name="" autofocus required >
                    </div>
                    <div class="form-group position-modal display-none">
                        <label for="validationDefault01">{{ __('表示名称') }}<span class="text-danger">*</span></label>
                        <input class="form-control modal_name2" type="text" name="position_type_name" autofocus required >
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary" type="button" onclick="tableInfoAdd(this)">{{ __('Add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Department Modal -->

<!-- Edit Department Modal -->
<div id="edit_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="modalTitle"></span>{{ __('を編集') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="edit_modal_form">
                    @csrf
                    @method('PUT')
                    <input id="edit_modal_id" name="id" type="hidden">
                    <div class="form-group">
                        <label for="validationDefault01"><span class="modalTitle"></span>{{ __('名') }}<span class="text-danger">*</span></label>
                        <input class="form-control modal_name1" type="text" id="edit_modal_name1" name="" autofocus required >
                    </div>
                    <div class="form-group position-modal display-none">
                        <label for="validationDefault01">{{ __('表示名称') }}<span class="text-danger">*</span></label>
                        <input class="form-control modal_name2" type="text" id="edit_modal_name2" name="position_type_name" autofocus required >
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary" type="button" onclick="tableInfoEdit(this)">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Edit Department Modal -->

<!-- Delete Department Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3><span class="modalTitle"></span>{{ __('を削除') }}</h3>
                    <p>{{ __('Are you sure want to delete?') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <input id="delete_modal_id" name="id" type="hidden">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" id="delete_modal_btn" class="btn btn-primary continue-btn">{{ __('Delete') }}</a>
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
