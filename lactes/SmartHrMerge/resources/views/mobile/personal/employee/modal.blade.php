<div class="modal custom-modal fade" id="base-model-center" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-btn delete-action">
                    <div class="modal-title">
                        title
                    </div>
                    <div class="modal-value">
                        <input type="text">
                    </div>
                    <p class="error-message"></p>
                    <div class="modal-button text-right">
                        <input type="button" value="キャンセル" onclick="$('#base-model-center').modal('hide');">
                        <input type="button" value="確認" onclick="modalCenterSubmit()">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal custom-modal fade" id="base-model-select" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-btn delete-action">
                    <div class="modal-value">
                    </div>
                    <div class="modal-button text-center">
                        <input type="button" value="キャンセル" onclick="$('#base-model-select').modal('hide');">
                        <input type="button" value="確認" onclick="modalSelectSubmit()">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
