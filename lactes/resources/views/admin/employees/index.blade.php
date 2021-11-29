@section('permission_modify','employee_modify')
<div class="card-header mb-3">
    <div class="row align-items-center">
        <div class="col-4">
            <h3 class="mb-0">{{ __('Employees View') }}</h3>
        </div>
        <div class="col-auto float-right ml-auto row" style="margin-right: 0">
            <div class="view-icons">
                <a href="{{'employees/list'}}" class="grid-view btn btn-link @yield('view-list') "><i class="fa fa-bars"></i></a>
                <a href="{{'employees/card'}}" class="list-view btn btn-link @yield('view-card') "><i class="fa fa-th"></i></a>
            </div>
            <div class="row col-auto float-right ml-auto" style="padding:5px 20px 0 20px;margin: 0">
                <div class="row col-auto float-right ml-auto" style="width: 210px">
                    <span style="padding-top: 3px;font-size: 16px;">退職者表示　</span>
                    <div class="status-toggle" style="padding-top: 5px;">
                        <input type="checkbox" id="switch_annual" value="1" name="employeeType" class="check" onclick="employeeRetire()">
                        <label for="switch_annual" class="checktoggle">checkbox</label>
                    </div>
                </div>
            </div>
            @can($__env->yieldContent('permission_modify'))
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal"
                       data-target="#employee_add_modal" data-url="{{ route('employees.create') }}" title="{{ __('Add user base info') }}">
                        <i class="fa fa-plus"></i> {{ __('新規作成') }}
                    </a>
                </div>
            @endcan
        </div>
    </div>
</div>

<!-- Search Filter -->
    <div class="col-12 row filter-row">
        <div class="col-sm-6 col-md-2">
            <div class="form-group form-focus">
                <input type="text" class="form-control floating" name="id" id="employee_search_id" >
                <label class="focus-label" for="id" >{{__('Employee ID')}}</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-2">
            <div class="form-group form-focus">
                <input type="text" class="form-control floating" name="employee_name" id="employee_search_employee_name"  >
                <label class="focus-label" for="employee_name">{{__('Employee Name')}}</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <a href="javascript:void(0)" class="btn btn-success" id="employee_search_btn" > {{__('Search')}} </a>
            <a href="javascript:void(0)" class="btn btn-secondary" id="reset_search_btn" > {{__('Reset')}} </a>
        </div>
        <div id="options" class="col-md-5 text-right" style="display: none">
            <button class="btn-option btn btn-sm btn-primary" onclick="saveCheck()" id="save_employee_code"> 保存</button>
            <button class="btn-option btn btn-sm btn-danger" onclick="deleteCheck()" data-toggle="modal" data-target="#delete_employee_modal"> 削除</button>
        </div>
    </div>

<!-- Search Filter -->
<input type="hidden" id="hide_val" data-user-view="{{$user_view}}">

<script type="text/javascript">
    let data;
    function initPageContent() {
        $('#employee_search_btn').click(function(){
            listOrCardDraw();
        });
        $(document).on('click', '#showUserModalBtn', function(event) {
            event.preventDefault();
            showModalForm($(this).attr('data-url'));
        });
        $('#employee_search_id').keydown(function () {
            const e = event || window.event;
            if(e.keyCode === 13){
                e.preventDefault();
                listOrCardDraw();
            }
        });
        $('#employee_search_employee_name').keydown(function () {
            const e = event || window.event;
            if(e.keyCode === 13){
                e.preventDefault();
                listOrCardDraw();
            }
        });
        $('#reset_search_btn').click(function(){
            $('#employee_search_id').val('');
            $('#employee_search_employee_name').val('');
            listOrCardDraw();
        });
    }

    function initAddModel() {
        let selected_employee_user_type=0;
        let selected_employee_user_id;
        let user_name;
        const employee_user_selector = 'input:radio[name="employee_user_type"]';
        let flag = true;
        let wizard=$('#employee_add_modal').wizard({
            onnext: function(stepname, i) {
                switch (stepname) {
                    case 'step1':
                        let val=$(employee_user_selector+':checked').val();
                        if(val==null){
                            $(employee_user_selector).addClass('is-invalid');
                            return false;
                        }
                        $(employee_user_selector).removeClass('is-invalid');
                        selected_employee_user_type=val;
                        $('#step2').html("");
                        if(selected_employee_user_type==0) getStepContent("step2","{{ route('employees.step2',array('type_id'=>0)) }}");
                        else if(selected_employee_user_type==1) getStepContent("step2","{{ route('employees.step2',array('type_id'=>1)) }}");
                        break;
                    case 'step2':
                        if(selected_employee_user_type==0){
                            selected_employee_user_id=$('select[name="employee_user_selected"]').val();
                            user_name=$('select[name="employee_user_selected"] option:selected').data('name');
                        }else if(selected_employee_user_type==1){
                            var name = $("#name").val();
                            var email = $("#email").val();
                            var pwd = $("#password").val();
                            var cfpwd = $("#password-confirm").val();
                            var flag=false;
                            if((name=="")||(email=="")||(pwd=="")||(cfpwd=="") ){
                                printErrorMsg("{{ __('Please fill all fields of register form.') }}");
                                return false;
                            }
                            if(pwd!=cfpwd){
                                printErrorMsg("{{ __('The confirmation password is not matched.') }}");
                                return false;
                            }
                            data=$('#register_form').serialize();
                            let temporaryName=$('#register_form').find('input[name=name]').val();
                            $.ajax({
                                url:"{{ route('employees.userVerify') }}",
                                type:"POST",
                                data: data,
                                async: false,
                                success:function(response){
                                    ajaxSuccessAction(response,function () {
                                        flag=true;
                                        selected_employee_user_id=0;
                                        user_name=temporaryName;
                                    });
                                    if (response.status !== "success") flag=false;
                                },
                                error: function(jqXHR, testStatus, error) {
                                    ajaxErrorAction(jqXHR, testStatus, error);
                                    flag=false;
                                }
                            });
                            if(!flag) return false;
                        }
                        break;
                }
                return true;
            },
            onstep: function(stepname) {
                switch (stepname) {
                    case 'step2':
                        break;
                    case 'step3':
                        $('#step3').html("");
                        let url = "{{ route('employees.step3') }}";
                        url=url+"?name="+user_name;
                        getStepContent("step3",url);
                        break;
                }
                return true;
            },
            onend: function() {
                if(flag){
                    flag = false;
                    $('#employee-create-btn').css('cursor','wait');
                    $.ajax({
                        url:"{{ route('employees.store') }}",
                        type:"POST",
                        data: $('#add_employee_base_info_form').serialize() + "&user_id="+selected_employee_user_id+"&"+data,
                        success:function(response){
                            ajaxSuccessAction(response,function () {
                                $('#employee_add_modal').modal('hide');
                                $('#add_employee_base_info_form')[0].reset();
                                $('#step2').html("");
                                $('#step3').html("");
                                listOrCardDraw();
                            })
                        },
                        error: function(jqXHR, testStatus, error) {
                            ajaxErrorAction(jqXHR, testStatus, error);
                        },complete: function () {
                            $('#employee-create-btn').css('cursor','pointer');
                            flag = true;
                        }
                    });
                    return true;
                }
            },
            onbegin: function(stepname, i) {
                $('input:radio[name="employee_user_type"]').prop('checked', false);
            }
        });
        wizard.on('show.bs.modal', function() {
            $('#employee_add_modal').wizard('begin');
        });
    }

    function employeeRetire() {
        listOrCardDraw();
    }

    function deleteAlert(id,event) {
        $('#delete_employee_modal').modal('show').find("#delete_employee_btn").off().click(function () {
            var url = "{{ route('employees.destroy',':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                url:url,
                type:"delete",
                success:function(response){
                    $('#delete_employee_modal').modal('hide');
                    ajaxSuccessAction(response,function () {
                        listOrCardDraw();
                    });
                },
                error: function(jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });

        });
    }

    function showDetail(id) {
        window.location.href = "{{route('employees.show',':id')}}".replace(':id',id);
    }

    function showModalForm(url){
        $.ajax({
            url: url,
            beforeSend: function() {
                $('#loader').show();
            },
            success: function(result) {
                $('#employee_modal').modal("show");
                $('#employee_modal_content').html(result).show();
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

    function listOrCardDraw() {
        var user_view=$('#hide_val').attr('data-user-view');
        if(user_view==='card') cardShow();
        else table.draw();
    }

    function getStepContent(step,url){
        $.ajax({
            url: url,
            type:"post",
            beforeSend: function() {
                $('#loader').show();
            },
            success: function(result) {
                if(step!='step3'){
                    $('#'+step).html(result).show();
                }else{
                    $('#'+step).html(result).show();
                }
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
</script>
