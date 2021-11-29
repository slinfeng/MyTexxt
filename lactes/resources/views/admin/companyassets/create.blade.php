@section('permission_modify','asset_modify')
            <div class="modal-header">
                <h5 class="modal-title">{{ __('新規追加') }}</h5>
            </div>
            <div class="modal-body">
                <form action="{{ route('companyassets.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>管理番号<span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="manage_code" readonly="readonly" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>資産分類<span class="text-danger">*</span></label>
                                <select onchange="onTypeChange(this)" class="selectpicker form-control" name="asset_type_id" >
                                    @foreach($assetTypes as $assetType)
                                    <option value="{{$assetType->id}}">{{$assetType->asset_type_name}}（{{$assetType->asset_type_code}}）</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>納品日<span class="text-danger">*</span></label>
                                <input class="form-control datetime" type="text" name="delivery_date" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>金額</label>
                                <input maxlength="9" class="form-control amount" type="text" name="amount" value="¥0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>種類</label>
                                <input maxlength="20" class="form-control" type="text" name="type">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>メーカー</label>
                                <input maxlength="20" class="form-control" type="text" name="maker"></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>型番</label>
                                <input maxlength="50" class="form-control center" type="text" name="model">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>シリアル番号</label>
                                <input maxlength="50" class="form-control" type="text" name="serial_number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>保管場所</label>
                                <input maxlength="50" class="form-control" type="text" name="storage">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>他の情報</label>
                                <textarea maxlength="100" class="form-control" type="text" name="infos"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary" type="button" style="font-size:18px;width: 8em;height:40px;display: inline-block;" onclick="formSubmit(this);">保存</button>
                    </div>
                </form>
            </div>
    <script>
        $(document).ready(function(){
            $('select[name=asset_type_id]').trigger('change');
            initSingleDatePicker('.datetime');
        });

        function onTypeChange(e) {
            const asset_type_id = $(e).val();
            $.ajax({
                url:'{{route('companyasset.getMaxNum')}}',
                type:'get',
                data:{asset_type_id:asset_type_id},
                async:false,
                success:function (res) {
                    $('.modal-body input[name=manage_code]').val(res);
                },error:function (jqXHR,testStatus,error) {
                    ajaxErrorAction(jqXHR,testStatus,error);
                }
            });
        }
    </script>
