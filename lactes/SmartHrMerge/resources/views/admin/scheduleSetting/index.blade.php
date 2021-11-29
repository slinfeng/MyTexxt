@extends('layouts.backend')
@section('title', __('初期設定').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('初期設定画面'))
@section('permission_modify','scheduleSetting_modify')
@section('css_append')
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select.dataTables.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/schedule_setting.css') }}">


@endsection
@section('content')

    <div class="content container-fluid">

        <!-- Page Tab -->
        <div class="page-menu">
            <div class="row">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab_comment">全般</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab_member" onclick="changeTableName('#member-table')">予約アイテム</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab_group" onclick="initGroup();">サブグループ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab_color" onclick="changeTableName('#color-table')">色と種別</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Tab -->

        <!-- Tab Content -->
        <div class="tab-content" id="schedule_setting">
            <!-- common Tab -->
            <div class="tab-pane show active" id="tab_comment">
                @include('admin.scheduleSetting.details.comment')
            </div>
            <!-- common Tab -->

            <!-- employee Tab -->
            <div class="tab-pane" id="tab_member">
                @include('admin.scheduleSetting.details.member')
            </div>
            <!-- /employee Tab -->

            <!-- attendances Tab -->
            <div class="tab-pane" id="tab_group">
{{--                @include('admin.scheduleSetting.details.group')--}}
            </div>
            <!-- /attendances Tab -->

            <!-- leaves Tab -->
            <div class="tab-pane" id="tab_color">
                @include('admin.scheduleSetting.details.color')
            </div>
            <!-- /leaves Tab -->

        </div>
        <!-- Tab Content -->
    </div>
    @include('layouts.pages.models.model_delete')
@endsection
@section('footer_append')
    @include('layouts.footers.requestmanage.index_a')
    <!-- Select2 JS -->
    <script src="{{ asset('assets/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.searchableSelect.js') }}"></script>
    <script type="text/javascript" defer>
        let table_selector='';
        let display_reservation_type = '{{$scheduleSetting->display_reservation_type==1}}';
        let duplicate_reservation_type = '{{$scheduleSetting->duplicate_reservation_type==1}}';
        $(document).ready(function() {
            // $('.make-switch').bootstrapSwitch();
            $('#color-table').DataTable({
                dom:"rtip",
                scrollCollapse: true,
                paging: false,
                searching: true,
                serverSide: true,
                bAutoWidth: false,
                orderFixed: {
                    "post": [[ 2, 'asc' ]]
                },
                fixedHeader: {
                    header: true,
                    headerOffset: 145,
                },
                oLanguage: {
                    sInfoEmpty: '総件数:0 件',
                    sInfo: '総件数:_TOTAL_ 件',
                    sInfoFiltered: '',
                    sZeroRecords: "表示するデータがありません",
                    select: {
                        rows: {
                            _: "%d行を選択しました",
                            0: ""
                        }
                    },
                },
                ajax: {
                    url: '{{route('scheduleColor.get-scheduleColorSettings')}}',
                    type: "get",
                    dataType: "json",
                },
                columns:[
                    {data:'select',name: 'select',className: 'position-relative select-checkbox',width:'2.5em'},
                    {data: 'order_num', name: 'order_num',className: 'text-center'},
                    {data: 'name', name: 'name',className: ''},
                ],
                columnDefs:[{
                    orderable:false,
                    className:'select-checkbox',
                    targets:0
                }],
                order: [
                    [1, 'asc']
                ],
                select:{
                    selector:'td:first-child'
                },
            });


            $('#member-table').DataTable({
                dom:"rtip",
                scrollCollapse: true,
                paging: false,
                searching: true,
                serverSide: true,
                bAutoWidth: false,
                orderFixed: {
                    "post": [[ 2, 'asc' ]]
                },
                fixedHeader: {
                    header: true,
                    headerOffset: 145,
                },
                oLanguage: {
                    sInfoEmpty: '総件数:0 件',
                    sInfo: '総件数:_TOTAL_ 件',
                    sInfoFiltered: '',
                    sZeroRecords: "表示するデータがありません",
                    select: {
                        rows: {
                            _: "%d行を選択しました",
                            0: ""
                        }
                    },
                },
                ajax: {
                    url: '{{route('scheduleMember.get-scheduleMemberSettings')}}',
                    type: "get",
                    dataType: "json",
                },
                columns:[
                    {data:'select',name: 'select',className: 'position-relative select-checkbox',width:'2.5em'},
                    {data: 'order_num', name: 'order_num',className: 'text-left',width:'7em'},
                    {data: 'name', name: 'name',className: 'text-left'},
                    {data: 'display_name', name: 'display_name',className: 'text-left'},
                    {data: 'reserve_type', name: 'reserve_type',className: 'text-center'},
                    {data: 'reserve_name_type', name: 'reserve_name_type',className: 'text-center'},
                    {data: 'constraint_type', name: 'constraint_type',className: 'text-center',width: "12em"},
                ],
                columnDefs:[{
                    orderable:false,
                    className:'select-checkbox',
                    targets:0
                }],
                order: [
                    [1, 'asc']
                ],
                select:{
                    selector:'td:first-child'
                },
            });

            $(document).on('click','tr',function () {
                trSelected(this);
            });
            $(table_selector).on('order.dt',function () {
                whenAllNotSelected()
            });

            $('#member_add_modal').on('show.bs.modal',function () {
                $(this).find('input[name=order_num]').val(10);
                $(this).find('input[name=user_id]').val(0);
                $(this).find('input[name=name]').val('');
                $(this).find('select').val(0)
                $(this).find('input[name=display_name]').val('');
                if(duplicate_reservation_type){
                    $(this).find('input[name=reserve_type]').prop('checked',true);
                }else{
                    $(this).find('input[name=reserve_type]').prop('checked',false);
                }
                if(display_reservation_type){
                    $(this).find('input[name=reserve_name_type]').prop('checked',true);
                }else{
                    $(this).find('input[name=reserve_name_type]').prop('checked',false);
                }
                $(this).find('input[name=constraint_type]').first().prop('checked',true);
            });
            $('#color_add_modal').on('show.bs.modal',function () {
                $(this).find('input[name=order_num]').val(10);
                $(this).find('input[name=color_id]').val(1);
                $(this).find('input[name=name]').val('');
                $(this).find('.color-show').css('background-color','#f13b09');
                $(this).find('.color-name').html('#f13b09');
            });

            initGroup();
        });

        function initGroup() {
            $.ajax({
                url:"{{route('scheduleGroup.index')}}",
                type:"get",
                success: function (response) {
                    $('#tab_group').html(response);
                },
                error: function (jqXHR, testStatus, error) {

                }
            });
        }

        function scheduleSubmit(e) {
            let form = $(e).parents('form');
            let data = form.serialize();
            let url = form.attr('action');
            $.ajax({
                url:url,
                type:"post",
                data: data,
                success: function (response) {
                    ajaxSuccessAction(response,function () {
                        duplicate_reservation_type = $('input[name=duplicate_reservation_type]').last().is(':checked');
                        display_reservation_type = $('input[name=display_reservation_type]').last().is(':checked');
                    });
                },
                error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });
        }

        function addOrUpdateSelector(form_selector,modal_selector,table_selector) {
            const form = $(form_selector);
            const obj = lockAjax();
            // var postMethod='<input type="hidden" name="_method" value="PUT">';
            if(form_selector.indexOf('update')>0){
                form.find('input[name=_method]').remove();
                form.append('<input type="hidden" name="_method" value="PUT">');
            }
            $.ajax({
                url:form.attr('action'),
                type:'post',
                data:form.serialize(),
                dataType:'json',
                success:function (data) {
                    ajaxSuccessAction(data,function () {
                        $(modal_selector).modal('hide');
                        if(form_selector.indexOf('add')>0){
                            $(form_selector+' input[type=text]').val('');
                        }
                        $(table_selector).DataTable().draw();
                    });
                },error:function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                },complete:function () {
                    unlockAjax(obj);
                }
            })
        }

        function editMember() {
            let memberId=$('#member-table .selected input[name=id]').val();
            let scheduleMemberEditRoute='{{ route('scheduleMember.edit', ':id') }}';
            scheduleMemberEditRoute=scheduleMemberEditRoute.replace(':id',memberId);
            let scheduleMemberUpdateRoute=$('#member_update_modal').data('update-url');
            scheduleMemberUpdateRoute=scheduleMemberUpdateRoute.replace(':id',memberId);
            $.get(scheduleMemberEditRoute, function (data) {
                $('#member_update_form input[name=order_num]').val(data.order_num);
                $('#member_update_form input[name=user_id]').val(data.user_id);
                $('#member_update_form input[name=name]').val(data.name);
                $('#member_update_form input[name=display_name]').val(data.display_name);
                if(data.reserve_type==1){
                    $('#member_update_form input[name=reserve_type]').prop('checked',true);
                }else{
                    $('#member_update_form input[name=reserve_type]').prop('checked',false);
                }
                if(data.reserve_name_type==1){
                    $('#member_update_form input[name=reserve_name_type]').prop('checked',true);
                }else{
                    $('#member_update_form input[name=reserve_name_type]').prop('checked',false);
                }
                $('#member_update_form input[name=constraint_type][value='+data.constraint_type+']').prop('checked',true);
                $('#member_update_form').attr('action',scheduleMemberUpdateRoute);
                $('#member_update_modal').modal('show');
            });
        }

        function editColor() {
            var colorId=$('#color-table .selected input[name=id]')[0].value;
            var scheduleColorEditRoute='{{ route('scheduleColor.edit', ':id') }}';
            scheduleColorEditRoute=scheduleColorEditRoute.replace(':id',colorId);
            var scheduleColorUpdateRoute=$('#color_update_modal').data('update-url');
            scheduleColorUpdateRoute=scheduleColorUpdateRoute.replace(':id',colorId);
            $.get(scheduleColorEditRoute, function (data) {
                $('#color_update_form input[name=order_num]').val(data.order_num);
                $('#color_update_form input[name=name]').val(data.name);
                $('#color_update_form .btn[data-id='+data.color_id+']').click();
                $('#color_update_form').attr('action',scheduleColorUpdateRoute);
                $('#color_update_modal').modal('show');
            });
        }

        function deleteMethod(type) {
            let deleteArr=[];
            var deleteRoute=$('#'+type+'_delete_modal').data('route');
            var selectors=$('#'+type+'-table .selected');
            selectors.each(function () {
                var id=$(this).find('input[name=id]')[0].value;
                deleteArr.push(id);
            });
            $('#'+type+'_delete_btn').on('click',function () {
                const obj = lockAjax();
                $.ajax({
                    url:deleteRoute,
                    type:'post',
                    data:{'deleteArr':deleteArr},
                    success:function (data) {
                        ajaxSuccessAction(data,function () {
                            $('#'+type+'_delete_modal').modal('hide');
                            $('#'+type+'-table').DataTable().draw();
                            $(table_selector+' tr').first().removeClass('selected');
                            whenAllNotSelected();
                        });
                    },error:function (jqXHR, testStatus, error) {
                        ajaxErrorAction(jqXHR, testStatus, error);
                    },complete:function () {
                        unlockAjax(obj);
                    }
                });
            });
        }

        function userChange(e,tagType) {
            let userName='';
            if(tagType==1){
                userName=$(e).val();
                $('input[name=user_id]').val(0);
                var select=$(e).closest('form').find('select').first();
                select.select2('destroy');
                select.children('option').first().attr('selected',true);
                select.select2({
                    minimumResultsForSearch: Infinity
                });
            }else{
                userName=$(e).children("option:selected").text();
                $('input[name=user_id]').val($(e).children("option:selected").val());
            }
            $('input[name=name]').val(userName);
        }

        function orderNumChange() {
            let updateArr=[];
            let selectors=$(table_selector+' .selected');
            selectors.each(function () {
                    let id=$(this).find('input[name=id]').val();
                    let orderNum=$(this).find('input[name=order_num]').val();
                    let memberArr=[id,orderNum];
                    updateArr.push(memberArr);
                });
            let url='';
            if(table_selector=='#member-table'){
                url="{{route('scheduleMember.updateOrderNum')}}";
            }else{
                url="{{route('scheduleColor.updateOrderNum')}}";
            }
            const obj = lockAjax();
            $.ajax({
                url:url,
                type:'post',
                data:{'updateArr':updateArr},
                dataType:'json',
                success:function (data) {
                    ajaxSuccessAction(data,function () {
                        $(table_selector).DataTable().draw();
                        $(table_selector+' tr').first().removeClass('selected');
                        whenAllNotSelected();
                    });
                },error:function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                },complete:function () {
                    unlockAjax(obj);
                }
            });
        }

        function changeColor(e) {
            let id = $(e).data('id');
            let color = $(e).data('color');
            let parentForm = $(e).closest('form');
            parentForm.find('input[name=color_id]').val(id);
            parentForm.find('.color-show').css('background-color',color);
            parentForm.find('.color-name').html(color);
        }
        function changeTableName(name) {
            table_selector = name;
        }
        function trSelected(e) {
            if($(table_selector+' .selected').length==0){
                whenAllNotSelected();
            }else{
                if(table_selector=="#member-table"){
                    $("#member_options").show();
                }else{
                    $("#color_options").show();
                }
            }
            if($(table_selector+' .selected').length<$(table_selector+' tr').length && $(table_selector+' tr').first().hasClass('selected')){
                $(table_selector+' tr').first().removeClass('selected');
            }else if($(table_selector+' .selected').length==$(table_selector+' tr').length-1 && !$(table_selector+' tr').first().hasClass('selected')){
                $(table_selector+' tr').first().addClass('selected');
            }
            if($(table_selector+' .selected').length==1){
                if(table_selector=="#member-table"){
                    $('.member-edit').show();
                }else{
                    $('.color-edit').show();
                }

            }else{
                if(table_selector=="#member-table"){
                    $('.member-edit').hide();
                }else{
                    $('.color-edit').hide();
                }
            }
            $(table_selector+' .selected').find('.order-input').show();
            $(table_selector+' .selected').find('.order-span').hide();
            $(table_selector+' tr:not(.selected)').each(function () {
                $(this).find('.order-input').hide();
                $(this).find('.order-span').show();
                let order_code = $(this).find('.order-span').data('code');
                $(this).find('.order-input').val(order_code);
            })
        }
        function whenAllSelected() {
            if(table_selector=="#member-table"){
                $('.member-edit').hide();
            }else{
                $('.color-edit').hide();
            }

        }
        function whenAllNotSelected() {
            if(table_selector=="#member-table"){
                $("#member_options").hide();
            }else{
                $("#color_options").hide();
            }
            $(table_selector+' tr').first().removeClass('selected');
        }
    </script>
@endsection

