<div style="display: none" id="group-model">
    <div class="li-model">
        <li role="presentation" class="">
            <a class="tab-link" href="javascript:void(0)"  tab="top-tab:index">:name
                <span class="role-action">
                <span class="action-circle large edit_group" data-toggle="modal" data-target="#edit_group" data-id=":id">
                    <i class="material-icons">{{ __('edit') }}</i>
                </span>
                <span class="action-circle large delete-btn" data-toggle="modal" data-target="#delete_group" data-id=":id">
                    <i class="material-icons">{{ __('delete') }}</i>
                </span>
            </span>
            </a>
        </li>
    </div>
    <div class="tab-model">

    </div>
</div>
<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
        <div class="row">
            <div class="col-sm-3 col-md-3 col-lg-2 col-xl-3">
                <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#add_group"><i class="fa fa-plus"></i> {{ __('サブグループ設定') }}</a>
                <div class="roles-menu">
                    <ul class="" data-toggle="tabs" role="tablist">
                        @foreach ($scheduleGroups as $index=>$scheduleGroup)
                            <li role="presentation" class="{{$index==0 ? 'active' : ''}}">
                                <a class="tab-link" href="javascript:void(0)"  tab="top-tab{{$index}}">{{ $scheduleGroup->name}}
                                    <span class="role-action">
                                        <span class="action-circle large edit_group" data-toggle="modal" data-target="#edit_group"
                                                data-id="{{$scheduleGroup->id}}">
                                            <i class="material-icons">{{ __('edit') }}</i>
                                        </span>
                                        <span class="action-circle large delete-btn" data-toggle="modal" data-target="#delete_group" data-id="{{$scheduleGroup->id}}">
                                            <i class="material-icons">{{ __('delete') }}</i>
                                        </span>
                                    </span>
                                </a>
                            </li>
                            @push('tabs')
                                <div class="role-tab" role="tab-item" id="top-tab{{$index}}">
                                    <form autocomplete="off">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-9"><h4 class="role-title">{{$scheduleGroup->name}}{{ __('サブグループに、予約アイテムを追加、削除します。') }}</h4></div>
                                                    <div class="col-3">
                                                        <button class="btn btn-primary float-right roles-setting-btn" type="button"
                                                                data-id="{{$scheduleGroup->id}}" onclick="addEditUser({{$scheduleGroup->id}},this);">{{__('Submit')}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row" >
                                                    <div class="col-12">
                                                        <div class="form-group leave-duallist">
{{--                                                            <label>{{__('scheduleMemberSettings')}}</label>--}}
                                                            <div class="row">
                                                                <div class="col-lg-11 m-auto">
                                                                    <span>{{ __('▼選択されているアイテム')}}</span>
                                                                </div>
                                                                <div class="col-lg-5 col-sm-5 ml-auto">
                                                                    <select name="members[]" class="form-control" size="4" multiple="multiple">
                                                                        @foreach ($scheduleMemberSettings as $scheduleMemberSetting)
                                                                            <option value="{{ $scheduleMemberSetting->id }}"
                                                                                    @if(!$scheduleGroup->ScheduleMemberSetting->contains($scheduleMemberSetting->id))
                                                                                        style="display: none"
                                                                                    @endif
                                                                            >{{ $scheduleMemberSetting->name }}</option>

                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="multiselect-controls col-lg-1 col-sm-1">
                                                                    <button type="button" onclick="rolesSelectLeft(this)" class="btn btn-block btn-white"><i class="fa fa-chevron-left"></i></button>
                                                                    <button type="button" onclick="rolesSelectRight(this)" class="btn btn-block btn-white"><i class="fa fa-chevron-right"></i></button>
                                                                    <button type="button" onclick="rolesSelectRightAll(this)" class="btn btn-block btn-white"><i class="fa fa-forward"></i></button>
                                                                    {{--                    <button type="button" id="edit_roles_select_leftAll" class="btn btn-block btn-white"><i class="fa fa-backward"></i></button>--}}
                                                                </div>
                                                                <div class="col-lg-5 col-sm-5 mr-auto">
                                                                    <select name="" class="form-control" size="8" multiple="multiple">
                                                                        @foreach ($scheduleMemberSettings as $scheduleMemberSetting)
                                                                            <option value="{{ $scheduleMemberSetting->id }}"
                                                                                    @if($scheduleGroup->ScheduleMemberSetting->contains($scheduleMemberSetting->id))
                                                                                    style="display: none"
                                                                                @endif
                                                                            >{{ $scheduleMemberSetting->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div style="font-size: 12px">
                                                                <br>
                                                                <span class="text-info"> {{ __('Ctrlキーを同時に押して、複数を選択します。') }}</span>
                                                                <br>
                                                                <span class="text-info"> {{ __('Shiftキーを押しながら、終点のセルをクリックすると範囲選択されます。 ') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endpush
                        @endforeach
                            @push('tabs')
                                <div class="role-tab" role="tab-item" id="top-tab:index">
                                    <form autocomplete="off">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-9"><h4 class="role-title">:name{{ __('サブグループに、予約アイテムを追加、削除します。') }}</h4></div>
                                                    <div class="col-3">
                                                        <button class="btn btn-primary float-right roles-setting-btn" type="button" data-id=":id" onclick="addEditUser(':id',this);">{{__('Submit')}}</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row" >
                                                    <div class="col-12">
                                                        <div class="form-group leave-duallist">
                                                            <div class="row">
                                                                <div class="col-lg-11 m-auto">
                                                                    <span>{{ __('▼選択されているアイテム')}}</span>
                                                                </div>
                                                                <div class="col-lg-5 col-sm-5 ml-auto">
                                                                    <select name="members[]" class="form-control" size="4" multiple="multiple">
                                                                        @foreach ($scheduleMemberSettings as $scheduleMemberSetting)
                                                                            <option value="{{ $scheduleMemberSetting->id }}"
                                                                                    style="display: none">{{ $scheduleMemberSetting->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="multiselect-controls col-lg-1 col-sm-1">
                                                                    <button type="button" onclick="rolesSelectLeft(this)" class="btn btn-block btn-white"><i class="fa fa-chevron-left"></i></button>
                                                                    <button type="button" onclick="rolesSelectRight(this)" class="btn btn-block btn-white"><i class="fa fa-chevron-right"></i></button>
                                                                    <button type="button" onclick="rolesSelectRightAll(this)" class="btn btn-block btn-white"><i class="fa fa-forward"></i></button>
                                                                </div>
                                                                <div class="col-lg-5 col-sm-5 mr-auto">
                                                                    <select name="" class="form-control" size="8" multiple="multiple">
                                                                        @foreach ($scheduleMemberSettings as $scheduleMemberSetting)
                                                                            <option value="{{ $scheduleMemberSetting->id }}">{{ $scheduleMemberSetting->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div style="font-size: 12px">
                                                                <br>
                                                                <span class="text-info"> {{ __('Ctrlキーを同時に押して、複数を選択します。') }}</span>
                                                                <br>
                                                                <span class="text-info"> {{ __('Shiftキーを押しながら、終点のセルをクリックすると範囲選択されます。 ') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endpush
                    </ul>
                </div>
            </div>
            <div class="col-sm-9 col-md-9 col-lg-10 col-xl-9">
                <div class="m-b-30">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 tab-content">
                            @stack('tabs')
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<!-- Add Role Modal -->
<div id="add_group" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('サブグループの追加') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('scheduleGroup.store') }}" method="POST">
                    @csrf
                    <input id="edit_group_id" name="id" type="hidden">
                    <div class="form-group">
                        <label for="name">{{ __('サブグループ名') }}<span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="name">
                        @error('name')
                        <div class="invalid-div">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button" onclick="addGroup()">{{ __('追加') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Role Modal -->

<!-- Edit Role Modal -->
<div class="modal custom-modal fade" id="edit_group" role="dialog" data-edit="{{ route('scheduleGroup.edit', ':id') }}"
     data-update="{{ route("scheduleGroup.update", ':id') }}">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('サブグループの編集') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="edit_group_form">
                    @csrf
                    @method('PUT')
                    <input id="edit_group_id" name="id" type="hidden">
                    <div class="form-group">
                        <label>{{ __('サブグループ名') }}<span class="text-danger">*</span></label>
                        <input class="form-control" value="" type="text" name="name" id="edit_group_name">
                        @error('name')
                        <div class="invalid-div">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="button">{{ __('保存') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- /Edit Role Modal -->

<!-- Delete Role Modal -->
<div class="modal custom-modal fade" id="delete_group" role="dialog" data-route="{{ route('scheduleGroup.destroy', ':id') }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>{{ __('サブグループの削除') }}</h3>
                    <p>{{ __('削除してもよろしいですか？') }}</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="javascript:void(0);" id="delete_group_btn" class="btn btn-primary continue-btn">{{ __('削除') }}</a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">{{ __('キャンセル') }}</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete Role Modal -->
<script>
    $(function () {
        // tab init
        $("[role='tab-item']").hide();
        $("#top-tab0").show();
        // tab show
        $("[role='tablist']").on("click", "a", function (event) {
            //console.log(event.target.tagName);
            if (event.target.tagName!='A') return;
            let target = $(event.target);
            $("[role='tab-item']").hide();
            $("li[role='presentation']").removeClass("active");
            target.closest('li').addClass("active");
            $("#" + target.attr("tab")).fadeIn();
        });
        // ajax get edit data
        $(document).on('click','.edit_group',function(){
            const modal = $('#edit_group');
            const obj = $(this);
            const id=obj.data('id');
            let edit_action_url=modal.data('edit');
            edit_action_url=edit_action_url.replace(':id',id);
            let update_action_url=modal.data('update');
            update_action_url=update_action_url.replace(':id',id);
            $.get(edit_action_url, function (data) {
                $('#edit_group_id').val(id);
                $('#edit_group_name').val(data.name);
                const form = $('#edit_group_form');
                form.attr('action',update_action_url);
                form.find('button.submit-btn').unbind('click').on('click',function () {
                    $.post(update_action_url,form.serialize(),function (res) {
                        ajaxSuccessAction(res,function () {
                            const data = res.data;
                            const a = obj.closest('a');
                            const span = a.find('span').first().clone();
                            a.html(data.name);
                            span.appendTo(a);
                            var topTab=a.attr('tab');
                            $('#'+topTab+' .role-title').html(data.name+'サブグループに、予約アイテムを追加、削除します。');
                            // $('.role-tab:last .role-title').html(data.name+'サブグループに、予約アイテムを追加、削除します。');
                            $('#edit_group').modal('hide');
                        });
                    });
                });
                $('#edit_group').modal('show');
            })
        });

        // delete
        var modalDelete = $("#delete_group");
        modalDelete.on("show.bs.modal", function(e) {
            let route = modalDelete.data('route');
            route = route.replace(':id',$(e.relatedTarget).data('id'));
            modalDelete.find('#delete_group_btn').unbind('click').on('click', function(){
                $.ajax({
                    url:route,
                    type:'delete',
                    success:function (res) {
                        ajaxSuccessAction(res,function () {
                            sidebarReplace($(e.relatedTarget).data('id'),'','delete');
                            let id = "#"+$(e.relatedTarget).closest('li').find('.tab-link').attr('tab');
                            $(id).remove();
                            $(e.relatedTarget).closest('li').remove();
                            modalDelete.modal('hide');
                        });
                    }
                });
            });
        });
        const addGroupModal = $('#add_group');
        addGroupModal.on("show.bs.modal", function(e) {
            addGroupModal.find('form')[0].reset();
        });

    });

    function addGroupHtml(id,name) {
        let index=0;
        if($('.role-tab').length>1){
            index = Number($('.role-tab').last().attr('id').replace('top-tab',''))+1;
        }
        let li = $('#group-model .li-model').html();
        let tab = $('.role-tab').last().prop("outerHTML");
        li = li.replace(/:id/g,id);
        li = li.replace(/:name/g,name);
        li = li.replace(/:index/g,index);
        tab = tab.replace(/:id/g,id);
        tab = tab.replace(/:name/g,name);
        tab = tab.replace(/:index/g,index);
        $('.tab-content ul').append(li);
        $('.role-tab').last().before(tab);
    }

    function addGroup() {
        const modal = $('#add_group');
        const form = modal.find('form');
        adminObj = lockAjax();
        $.ajax({
            url:form.attr('action'),
            type:"post",
            data: form.serialize(),
            success: function (response) {
                ajaxSuccessAction(response,function () {
                    let data = response.data;
                    addGroupHtml(data.id,data.name);
                    sidebarReplace(data.id,data.name,'add');
                    $('div.roles-menu ul').find('.tab-link:last').click();
                    modal.modal('hide');
                });
            },
            error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            },complete:function () {
                unlockAjax(adminObj);
            }
        });
    }
    function sidebarReplace(id,name,type) {
        let liHtml = '<li class="{{(request()->is('*schedules*:id*'))?'active':''}} schedule-group" id="schedules:id"><a href="{{route('schedules.group',['id'=>':id'])}}"><span>:firstName</span><span>:name</span></a></li>'
        switch (type) {
            case 'add':
                let firstName = name.substr(0,1);
                liHtml = liHtml.replace(':firstName',firstName)
                liHtml = liHtml.replace(':name',name)
                liHtml = liHtml.replace(/:id/g,id)
                $('.schedule-group').last().after(liHtml);
                break;
            case 'delete':
                $('#schedules'+id).remove();
                break;
        }
    }
    // add or Update User Function ajax request
    function addEditUser(id,e) {
        var url  ="{{route('scheduleGroup.updateGroupMembers')}}";

        $(e).closest('form').find('input[name=id]').remove();
        $(e).closest('form').append('<input type="hidden" name="id" value="'+id+'">');

        var selector= $(e).closest('form').find('select').first();

        selector.find('option:selected').prop('selected',false);
        selector.find('option:visible').prop('selected',true);
        selector.find('option:hidden').prop('selected',false);

        // $('#edit_roles_select option:selected').prop('selected',false);
        // $("#edit_roles_select option:visible").prop('selected',true);
        // $("#edit_roles_select option:hidden").prop('selected',false);
        const obj = lockAjax();
        $.ajax({
            url:url,
            type:"post",
            data: $(e).closest('form').serialize(),
            success:function(response){
                ajaxSuccessAction(response,function () {

                });
            },
            error: function(jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            },complete:function () {
                unlockAjax(obj);
            }
        });

    }

    function rolesSelectRightAll(e) {
        var selector= $(e).closest('form').find('select');
        selector.first().find('option').hide();
        selector.last().find('option').show();
        // $('#edit_roles_select>*').hide();
        // $('#edit_roles_select_to>*').show();
        // roleCheck();
    }
    function rolesSelectRight(e) {
        var selector= $(e).closest('form').find('select');

        let val = selector.first().val();
        selector.first().find('option:selected').hide();
        // $('#edit_roles_select option:selected').hide();
        $.each(val, function(i, item){
            selector.last().find('option[value='+item+']').show();
            // $('#edit_roles_select_to option[value='+item+']').show();
        });
        // roleCheck();
    }
    function rolesSelectLeft(e) {
        var selector= $(e).closest('form').find('select');
        let val = selector.last().val();
        selector.last().find('option:selected').hide();
        // let val = $('#edit_roles_select_to').val();
        // $('#edit_roles_select_to option:selected').hide();
        $.each(val, function(i, item){
            selector.first().find('option[value='+item+']').show();
            // $('#edit_roles_select option[value='+item+']').show();
        });
        // roleCheck();
    }

    function roleCheck() {

    }
</script>
