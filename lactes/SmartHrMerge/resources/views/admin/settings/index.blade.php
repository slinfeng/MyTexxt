@extends('layouts.backend')
@section('title', __('Settings').' | '.config('app.name', 'ダッシュボード'))
@section('page_title', __('Settings'))
@section('permission_modify','setting_modify')
@section('css_append')
    <!-- bootstrap fileinput -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" type="text/css" >
    <!-- bootstrap switch -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" type="text/css" >
    <style>
        #menu-list li{
            float: left;
        }
        #myTab2Content label{
            margin: 0;
        }
        #activityLogs_length label,#sessions_length label,#emailTemplate_length label{
            width: 10em;
        }
        #activityLogs_length select,#sessions_length select,#emailTemplate_length select{
            width: calc( 100% - 4em );
        }
        .datatable th{
            text-align: center;
        }
        .permission-title{
            background-color: #dee2e6;
        }
        #role-settings input[type=checkbox]{
            width: 15px;
            margin-left: 20px;
            vertical-align: middle;
        }
        .ip-input{
            display: initial;
            width: calc(100% - 30px);
        }
        #IPAddress tr td:nth-of-type(1),#IPAddress tr:nth-of-type(1) th:nth-of-type(1){
            width: 100px;
        }
        #IPAddress tr td:nth-of-type(2),#IPAddress tr:nth-of-type(1) th:nth-of-type(2){
            width: 30%;
        }
        .back-color-white{
            background-color: white!important;
        }
        .visibility-hidden {
            visibility: hidden;
        }
        .role-table>table th{
            background-color:#dee2e6;
            border: white 1px solid;
        }
        .action-circle{
            line-height: 22px;
        }
        input[type=radio]{
            transform: scale(1.5);
            cursor: pointer;
        }
        .width-14{
            width: 14%!important;
        }
        .width-28{
            width: 28%!important;
        }
        .width-42{
            width: 42%!important;
        }
        .table{
            box-shadow:0 0 5px 0 lightgrey;
        }
        .vertical-middle{
            vertical-align: middle!important;
        }
        .role-table{
            padding: 5px;
        }
        h4{
            margin-bottom: 0;
        }
        .linePoint{
            font-size: 12px;
            font-weight: 800;
        }
    </style>
@endsection
@section('content')
    <div class="content container-fluid">

    <div class="page-menu">
    <div class="row">
        <div class="col-md-12">
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
                    <ul class="nav nav-tabs nav-tabs-bottom" id="menu-list" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="contact-tab4" data-toggle="tab" href="#contact-settings" role="tab" aria-controls="contact" aria-selected="false">会社情報</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="role-tab4" data-toggle="tab" href="#role-settings" role="tab" aria-controls="contact" onclick="rolesShow()" aria-selected="false">役割</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="email-tab4" data-toggle="tab" href="#email-settings" role="tab" aria-controls="email" aria-selected="false">メールサーバー設定</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="mailTemplate-tab4" data-toggle="tab" href="#mailTemplate-settings" role="tab" aria-controls="contact" aria-selected="false">メールテンプレート</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="ipAddress-tab4" data-toggle="tab" href="#ipAddress-settings" role="tab" aria-controls="contact" aria-selected="false">IPアクセス制限</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="activity-tab4" data-toggle="tab" href="#activity-settings" role="tab" aria-controls="contact" aria-selected="false">ログ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="session-tab4" data-toggle="tab" href="#session-settings" role="tab" aria-controls="contact" aria-selected="false">セッション</a>
                        </li>
                    </ul>
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
    </div>
{{--    <div class="row">--}}
{{--        <div class="col-md-12">--}}
            <div class="tab-content" id="myTab2Content">
                <div class="tab-pane fade show active" id="contact-settings" role="tabpanel" aria-labelledby="contact-tab4">
                    @include('admin.settings.contact_us')
                </div>
                <div class="tab-pane fade" id="role-settings" role="tabpanel" aria-labelledby="role-tab4">
                    @include('admin.roles.index')
                </div>
                <div class="tab-pane fade" id="email-settings" role="tabpanel" aria-labelledby="email-tab4">
                    @include('admin.settings.email')
                </div>
                <div class="tab-pane fade" id="mailTemplate-settings" role="tabpanel" aria-labelledby="mailTemplate-tab4">
                    @include('admin.email-templates.index')
                </div>
                <div class="tab-pane fade" id="ipAddress-settings" role="tabpanel" aria-labelledby="ipAddress-tab4">
                    @include('admin.ipaddress.index')
                </div>
                <div class="tab-pane fade" id="activity-settings" role="tabpanel" aria-labelledby="activity-tab4">
                    @include('admin.activity.index')
                </div>
                <div class="tab-pane fade" id="session-settings" role="tabpanel" aria-labelledby="session-tab4">
                    @include('admin.sessions.index')
                </div>
            </div>
            <div id="result"></div>
{{--        </div>--}}
{{--    </div>--}}
    </div>

@endsection
@section('footer_append')
    <!-- Datatable JS -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable-language.jp.js') }}"></script>
    <!-- Select2 JS -->
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>
    <!-- bootstrap fileinput -->
    <script src="{{ asset('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}"></script>
    <!-- bootstrap switch -->
    <script src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

    <script src="{{ asset('assets/js/setting.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.js') }}"></script>
    <script type="text/javascript">
        /**
         * テキストエリアを入力ながら、高さを自動更新
         * @param e
         */
        function changeLength(e) {
            $(e).height('auto');
            $(e).height(e.scrollHeight - 4);
        }
        function EditEmail(id) {
            var url = "{{route('settings.update',':id')}}";
            url = url.replace(':id',id);
            $.ajax({
                url:url,
                type:"POST",
                data: $('#email-setting-form').serialize(),
                success:function(response){
                    ajaxSuccessAction(response);
                },
                error: function(jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });
        }
        function EditContact(id) {
            var url = "{{route('settings.update',':id')}}";
            url = url.replace(':id',id);
            let data = new FormData($('#contact-setting-form').get(0));
            $.ajax({
                url:url,
                type:"POST",
                contentType: false,
                processData: false,
                data: data,
                success:function(response){
                    ajaxSuccessAction(response);
                },
                error: function(jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });

        }
        function deleteAlert(id) {
            $('#delete_activityLog_modal').modal('show');
            $('#delete_activityLog_modal').find("#delete_activityLog_btn").off().click(function () {
                var url = "{{ route('activity.destroy',':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url:url,
                    type:"delete",
                    success:function(response){
                        ajaxSuccessAction(response,function () {
                            $('#delete_activityLog_modal').modal('hide');
                            $('#activityLogs').DataTable().draw();
                        });
                    },
                    error: function(jqXHR, testStatus, error) {
                        ajaxErrorAction(jqXHR, testStatus, error);
                    }
                });

            });
        }
        function deleteAlertWhenSession(id) {
            $('#delete_session_modal').modal('show');
            $('#delete_session_modal').find("#delete_session_btn").off().click(function () {
                var url = "{{ route('sessions.destroy',':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url:url,
                    type:"delete",
                    success:function(response){
                        ajaxSuccessAction(response,function () {
                            $('#delete_session_modal').modal('hide');
                            $('#sessions').DataTable().draw();
                        });
                    },
                    error: function(jqXHR, testStatus, error) {
                        ajaxErrorAction(jqXHR, testStatus, error);
                    }
                });

            });
        }
        function showModalForm(url){
            $.ajax({
                url: url,
                beforeSend: function() {
                    $('#loader').show();
                },
                success: function(result) {
                    $('#email_template_modal').modal("show");
                    $('#email_template_modal_content').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        }
        function editModal(id) {
            var url ="{{route('email-templates.edit',':id')}}";
            url = url.replace(':id',id);
            showModalForm(url);
        }
        function addEditTemplate(id) {
            let url;
            if(typeof id!='undefined'){
                url ="{{route('email-templates.update',':id')}}";
                url = url.replace(':id',id);
            }else{
                url = "{{ route('email-templates.store') }}";
            }
            $.ajax({
                url:url,
                type:"POST",
                data: $('#add_edit_form').serialize(),
                success:function(response){
                    ajaxSuccessAction(response,function () {
                        $('#email_template_modal').modal('hide');
                        $('#emailTemplate').DataTable().draw();
                    })
                },
                error: function(jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });

        }
        function EditRole(e) {
            let id = $(e).data('id');
            let url = "{{route('role.updateRolePermissions',':id')}}";
            url = url.replace(':id',id);
            $.ajax({
                url:url,
                type:"POST",
                data: $('.role-tab form:visible').serialize(),
                success:function(response){
                    ajaxSuccessAction(response);
                },
                error: function(jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });
        }

        function adminSelect(){
            // $('#presentation_1').attr('class','active');
            // $('#presentation_1').click();
            // $('#presentation_1').removeClass('active');
        }

        $(document).ready(function() {
            $('.make-switch').bootstrapSwitch();
            $('#activityLogs').DataTable({
                "autoWidth": false,
                "searching" : false,
                "responsive": true,
                "bAutoWidth": true,
                "serverSide": true,
                "order":false,
                'iDisplayLength': 10,
                "bLengthChange": true,
                "ajax": {
                    "url": "{{route('ajax.activity')}}",
                    "type": "GET",
                    "datatype": "json",
                },

                "columns": [
                    {data:'DT_RowIndex',name: 'DT_RowIndex' },
                    {data: 'created_at', name: 'created_at',className: 'text-center'},
                    {data: 'description', name: 'description',className: 'text-center'},
                    {data: 'subject_type', name: 'subject_type'},
                    {data: 'causer_id', name: 'causer_id'},
                    @can($__env->yieldContent('permission_modify'))
                    {data: 'action', name: 'action', orderable: false, searchable: false,className:'text-center'}
                    @endcan
                ],
                "oLanguage": {
                    "sProcessing": '<div class="overlay"><div class="cssload-speeding-wheel"></div></div>'
                },
            });
            $('#sessions').DataTable({
                "autoWidth": false,
                "searching" : false,
                "responsive": true,
                "bAutoWidth": true,
                "serverSide": true,
                "order": false,
                'iDisplayLength': 10,
                "bLengthChange": true,
                "ajax": {
                    "url": "{{route('sessions.get-sessions')}}",
                    "type": "GET",
                    "datatype": "json",
                },

                "columns": [
                    {data: 'name', name: 'name'},
                    {data: 'ip_address', name: 'ip_address'},
                    {data: 'user_agent', name: 'user_agent'},
                    {data: 'last_activity', name: 'last_activity'},
                ],
                "oLanguage": {
                    "sProcessing": '<div class="overlay"><div class="cssload-speeding-wheel"></div></div>'
                }
            });
            $('#emailTemplate').DataTable({
                "autoWidth": false,
                "searching" : false,
                "responsive": true,
                "bAutoWidth": true,
                "serverSide": true,
                "paging": false,
                "order": [
                    [0, "asc"]
                ],
                "ajax": {
                    "url": "{{route('get-email-template')}}",
                    "type": "GET",
                    "datatype": "json",
                },
                "columns": [
                    // {data: 'id',name: 'id' },
                    {data: 'subject', name: 'subject'},
                    {data: 'body', name: 'body'},
                    @can($__env->yieldContent('permission_modify'))
                    {data: 'action', name: 'action'}
                    @endcan
                    //操作（Action）
                ],
                "oLanguage": {
                    "sProcessing": '<div class="overlay"><div class="cssload-speeding-wheel"></div></div>'
                }
            });
            $(document).on('click', '#showEmailTemplatesModalBtn', function(event) {
                event.preventDefault();
                let url = $(this).attr('data-url');
                showModalForm(url);
            });
            // tab init
            $("[role='tab-item']").hide();
            $("#top-tab1").show();
            // tab show
            $("[role='tablist']").on("click", "a", function (event) {
                //console.log(event.target.tagName);
                if (event.target.tagName!='A') return;
                let target = $(event.target);
                if(event.target.getAttribute('href')=='#role-settings'){
                    $("#top-tab1").show();
                    // $("li[id='presentation_1']").addClass("active");
                }else{
                    $("[role='tab-item']").hide();
                    $("li[role='presentation']").removeClass("active");
                }
                target.closest('li').addClass("active");
                $("#" + target.attr("tab")).fadeIn();
            });
            // ajax get edit data
            $(document).on('click','.edit_role',function(){
                const modal = $('#edit_role');
                const obj = $(this);
                const id=obj.data('id');
                let edit_action_url=modal.data('edit');
                edit_action_url=edit_action_url.replace(':id',id);
                let update_action_url=modal.data('update');
                update_action_url=update_action_url.replace(':id',id);
                $.get(edit_action_url, function (data) {
                    $('#edit_role_id').val(id);
                    $('#edit_role_title').val(data.role.title);
                    const form = $('#edit_role_form');
                    form.attr('action',update_action_url);
                    form.find('button.submit-btn').unbind('click').on('click',function () {
                        $.post(update_action_url,form.serialize(),function (res) {
                            ajaxSuccessAction(res,function () {
                                const data = res.data;
                                const a = obj.closest('a');
                                const span = a.find('span').first().clone();
                                a.html(data.title);
                                span.appendTo(a);
                                $('#edit_role').modal('hide');
                            });
                        });
                    });
                    $('#edit_role').modal('show');
                })
            });

            // delete
            var modalDelete = $("#delete_role");
            modalDelete.on("show.bs.modal", function(e) {
                let route = modalDelete.data('route');
                route = route.replace(':id',$(e.relatedTarget).data('id'));
                modalDelete.find('#delete_role_btn').unbind('click').on('click', function(){
                    $.ajax({
                        url:route,
                        type:'delete',
                        success:function (res) {
                            ajaxSuccessAction(res,function () {
                                let id = "#"+$(e.relatedTarget).closest('li').find('.tab-link').attr('tab');
                                $(e.relatedTarget).closest('li').remove();
                                $(id).remove();
                                modalDelete.modal('hide');
                            });
                        }
                    });
                });
            });
            const addRoleModal = $('#add_role');
            addRoleModal.on("show.bs.modal", function(e) {
                addRoleModal.find('form')[0].reset();
            });
        });

        function addEditIPAddress() {
            var url  ="{{route('adminsetting.IPAddressSave')}}";
            $.ajax({
                url:url,
                type:"POST",
                data: $('#ipAddress_add_edit_form').serialize(),
                success:function(response){
                    if(response.status==='success'){
                        $.notify(response.message);
                        ipAddressInit(response.userIpAddressArr);
                    }else{
                        printErrorMsg(response.message);
                    }
                },
                error: function(jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });

        }

        function ipAddressInit(userIpAddressArr) {
            var trArr=$('#ipAddress_add_edit_form').find('tr');
            var i=0;
            $.each(userIpAddressArr,function () {
                var ipArr=this.ip_address[0].split('.');
                ipArr.push(this.ip_address[1].split('.')[3])
                var inputArr=$(trArr[i+3]).find('input');
                $(inputArr[0]).val(this.id);
                $(inputArr[1]).val(this.sort_num);
                $(inputArr[2]).val(this.name);
                $(inputArr[3]).val(ipArr[0]);
                $(inputArr[4]).val(ipArr[1]);
                $(inputArr[5]).val(ipArr[2]);
                $(inputArr[6]).val(ipArr[3]);
                $(inputArr[7]).val(ipArr[4]);
                i++;
            });
                $('#ipAddress_add_edit_form').find('input[name="delId[]"]').remove();
        }


        function addIPAddress(e) {
            const tr = $("#ipAddress_add_edit_form").find("tr:eq(3)").clone();
            tr.find("input").each(function () {
                $(this).val("");
            });
            $(e).parents('tr').first().after(tr);
            $(e).parents('tr').next().find('.linePoint').hide();
            trNumReset();
        }

        function delIPAddress(e) {
            if($(e).parents("tr").first().find("input").first().val().trim() !== ""){
               var delIdInput= $(".card-body").find('input[name="delId[]"]').first().clone();
                delIdInput.val($(e).parents("tr").first().find("input").first().val());
                $("#ipAddress_add_edit_form").prepend(delIdInput);
            }
            delIPAddressDiv(e);
        }

        function delIPAddressDiv(e) {
            if ($("#ipAddress_add_edit_form").find("tr").length > 4)
                $(e).parents("tr").first().remove();
            else{
                $("#ipAddress_add_edit_form").find("tr:eq(3)").find("input").each(function () {
                    $(this).val("");
                });
            }
            trNumReset();
        }

        function showLinePoint(event){
            var e =  window.event;
            let x = e.clientX;
            let y = e.clientY;
            x = Number(x)+Number(15)+"px";
            y = Number(y)+Number(5)+"px";
            $(event).next().show();
            $(event).next().css({'position':'fixed','top':y,'left':x});
        }

        function hideLinePoint(event){
            $(event).next().hide();
        }

        function inputCheck(e) {
            const reg_to_number = /[^0-9]/g;
            $(e).val($(e).val().replace(reg_to_number, ''));
            if(($(e).val()!='')&&($(e).val()>255)){
                $(e).val(255);
            }
        }

        function lastInputCheck(e) {
            if(Number($(e).parents("tr").first().find("input")[6].value)>Number($(e).parents("tr").first().find("input")[7].value)){
                $(e).parents("tr").first().find("input")[7].value=$(e).parents("tr").first().find("input")[6].value;
            }
        }

        function inputEmptyCheck(){
            var boo=true;
            $("#ipAddress_add_edit_form").find("input").each(function () {
                if($(this).attr('name')!='id[]'){
                    if($(this).val().trim() === ""){
                        return boo=false;
                    }
                }
            });
            return boo;
        }

        function trNumReset() {
            var trArr= $("#ipAddress_add_edit_form").find("tr");
            for(var i=1;i<trArr.length-2;i++){
                $(trArr[i+2]).find('input').eq(1).val(i);
                $(trArr[i+2]).find('td').eq(0).text(i);
            }
        }

        function rolesShow() {
            $('.roles-menu li').removeClass('active');
            $('.roles-menu li:first').addClass('active');
            $('.role-tab').hide();
            $('.role-tab:first').show();
            $('.role-table:lt(8)').find('input[type=radio]').attr("disabled","disabled");
        }

        function addPostMark(e) {
            const val = $(e).val();
            if (val.trim() !== ''){
                if(val.length>3) $(e).val('〒' + val.substr(0,3) +'-' + val.substr(3));
                else $(e).val('〒' + val);
            }
        }

        function onPostFocus(e) {
            e.value = e.value.replace(/[〒-]/g, "");
            e.setSelectionRange(0, e.value.length);
        }

        /**
         * アップロードされたファイルを読みだし
         * @param event
         */
        function showImg(event) {
            let rd = new FileReader();
            let files = event.files[0];
            rd.readAsDataURL(files);
            rd.onloadend = function(e) {
                $(event).parents('div').find('div[name=logo]').find('img').attr('src',this.result);
            }
        }

    </script>
@endsection
