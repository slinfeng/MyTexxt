@section('permission_modify','asset_modify')
<div class="modal-header">
    <h5 class="modal-title">
        @can($__env->yieldContent('permission_modify'))
            {{ __('資産編集') }}
        @else
            {{ __('資産詳細情報') }}
        @endcan
    </h5>
</div>
<div class="modal-body">
    <form id="edit-asset" data-action="{{ route('companyassets.update', $assetInfo->id) }}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>管理番号</label>
                    <input class="form-control" type="text" name="manage_code" readonly="readonly" value="{{$assetInfo->manage_code}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>資産分類</label>
                    <select onchange="onTypeChange(this)" class="selectpicker form-control" name="asset_type_id" >
                        @foreach($assetTypes as $assetType)
                            <option value="{{$assetType->id}}"{{$assetType->id==$assetInfo->asset_type_id?'selected':''}}>{{$assetType->asset_type_name}}（{{$assetType->asset_type_code}}）</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>納品日</label>
                    <input class="form-control datetime" type="text" autocomplete="off" name="delivery_date" value="{{isset($assetInfo->delivery_date)?$assetInfo->delivery_date->toDateString():''}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>金額</label>
                    <input maxlength="9" class="form-control amount" type="text" name="amount" value="{{$assetInfo->amount}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>種類</label>
                    <input maxlength="20" class="form-control" type="text" name="type" value="{{$assetInfo->type}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>メーカー</label>
                    <input maxlength="20" class="form-control" type="text" name="maker" value="{{$assetInfo->maker}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>型番</label>
                    <input maxlength="50" class="form-control center" type="text" name="model" value="{{$assetInfo->model}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>シリアル番号</label>
                    <input maxlength="50" class="form-control" type="text" name="serial_number" value="{{$assetInfo->serial_number}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>保管場所</label>
                    <input maxlength="50" class="form-control" type="text" name="storage" value="{{$assetInfo->storage}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>他の情報</label>
                    <textarea maxlength="100" class="form-control" type="text" name="infos">{{$assetInfo->infos}}</textarea>
                </div>
            </div>
        </div>
        @can($__env->yieldContent('permission_modify'))
        <div class="submit-section">
            <button class="btn btn-primary" type="button" style="font-size:18px;width: 8em;height:40px;display: inline-block;" onclick="submitForm()">保存</button>
        </div>
        @endcan
    </form>
</div>
<script>
    $(document).ready(function(){
        $('input[name=amount]').blur();
        initSingleDatePicker('.datetime');
    });

    function onTypeChange(e) {
        const asset_type_id = $(e).val();
        if(asset_type_id=='{{$assetInfo->asset_type_id}}'){
            $('.modal-body input[name=manage_code]').val('{{$assetInfo->manage_code}}');
        }else{
            $.ajax({
                url:'{{route('companyasset.getMaxNum')}}',
                type:'get',
                async:false,
                data:{asset_type_id:asset_type_id},
                success:function (res) {
                    $('.modal-body input[name=manage_code]').val(res);
                },error:function (jaXHR,testStatus,error) {
                    ajaxErrorAction(jaXHR,testStatus,error)
                }
            });
        }
    }

    function submitForm() {
        const tag = $('#edit-asset');
        $.ajax({
            url:tag.data('action'),
            type:'post',
            data:tag.serialize(),
            success:function (res) {
                ajaxSuccessAction(res,function () {
                    $('#add-edit-asset').modal("hide");
                    table.draw();
                });
            },error:function (jaXHR,testStatus,error) {
                ajaxErrorAction(jaXHR,testStatus,error)
            }
        })
    }
</script>
