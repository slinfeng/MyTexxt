<div class="modal custom-modal fade" id="exclude" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>一括出力 </h3>
                    <p>{{ __('選択された項目をPDFに生成します。よろしいでしょうか?') }}</p>
                    <div class="invoice-print text-left" style="padding:10px 0 0 25px;display: none">
                        <p style="color: #333!important;"><label><input type="checkbox" value="0" name="pdf_print" checked> 請求書一括出力（pdf）</label></p>
                        <p style="color: #333!important;"><label><input type="checkbox" value="1" name="pdf_print"> 振込一覧（xlsx）</label></p>
                    </div>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row" style="margin-bottom: 10px;margin-top: -20px;">
                        <div class="progress progress-xs mb-0 w-75 p-0 m-auto" style="height: 0.8em">
                            <div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="" style="width: 0%" data-original-title="0%"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" onclick="batchExclude()"
                               class="btn btn-primary continue-btn">{{ __('一括出力') }}</a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-dismiss="modal" onclick="pdfCancel()"
                               class="btn btn-primary cancel-btn">{{__('Cancel')}}</a>
                        </div>
                    </div>
                    <p></p>
                    <p class="point-p m-0" style="color:#1F8FEF;font-size:12px;display:none">プログレスバーが30秒以内に変化しない場合は、もう一度やり直してください。</p>
                    <p class="point-p m-0" style="color:#1F8FEF;font-size:12px;display:none">一括出力が多くの失敗した試み、選択した書類の数を減らしてください。</p>
                    <p class="point-p m-0" style="color:#1F8FEF;font-size:12px;display:none">プログレスバーが完成したら、しばらくお待ちください。</p>
                </div>
            </div>
        </div>
    </div>
</div>
