let deleteTr ="";
let editId = 0;
let editVal1 = "";
let editVal2 = "";
let type = "";
$(document).ready(function() {
    // add
    $(document).on('click','.add-button',function(){
        type = $(this).data('type');
        let url = $(this).data('url');
        $('#add_modal_form').attr('action',url);
        $('#add_modal_form input').val("");
        modalShowBefore();
        $('#add_modal').modal('show');
    });
    // edit
    $(document).on('click','.edit_icon',function(){
        type = $(this).data('type');
        modalShowBefore();
        let update_action_url=$(this).data('update');
        editId = $(this).parents('tr').find('.field1').data('id');
        $('#edit_modal_id').val(editId);
        $('#edit_modal_name1').val($(this).parents('tr').find('.field1').html());

        if($(this).parents('tr').find('.field2').length>0){
            $('#edit_modal_name2').val($(this).parents('tr').find('.field2').html());
        }
        $('#edit_modal_form').attr('action',update_action_url);
        $('#edit_mdoal').modal('show');
    });
    // delete
    $(document).on('click','.delete_icon',function(){
        type = $(this).data('type');
        modalShowBefore();
        $('#delete_modal').modal('show');
    });
    let modalDelete = $("#delete_modal");
    modalDelete.on("show.bs.modal", function(e) {
        let btn = $(e.relatedTarget);
        var form = btn.closest('form');
        $(this).find('#delete_modal_btn').data('form', form);
        deleteTr = btn.closest('tr')
    });
    modalDelete.find('#delete_modal_btn').on('click', function(){
        tableInfoDelete($(this).data('form').attr('action'));
    });

});

function modalShowBefore() {
    let modalTitle="";
    let modal_name1="";
    let boo=false;
    switch (type) {
        case "department":
            modalTitle = "部門";
            modal_name1 = "department_name";
            break;
        case "hire":
            modalTitle = "契約形態";
            modal_name1 = "hire_type";
            break;
        case "position":
            modalTitle = "役職";
            modal_name1 = "position_type";
            boo=true;
            break;
        case "retire":
            modalTitle = "在職区分";
            modal_name1 = "retire_type";
            break;
        case "residence":
            modalTitle = "在留資格種類";
            modal_name1 = "residence_type";
            break;
    }
    $(".modalTitle").html(modalTitle);
    $(".modal_name1").attr('name',modal_name1);
    if(boo) $(".position-modal").show();
    else $(".position-modal").hide();
}
function tableInfoAdd(e) {
    let url=$(e).parents('form').attr('action');
    const obj = lockAjax();
    $.ajax({
        url:url,
        data:$(e).parents('form').serialize(),
        type:"post",
        success:function(response){
            ajaxSuccessAction(response,function () {
                switch (type) {
                    case "department":
                        addDepartmentAfter(response);
                        break;
                    case "hire":
                        addHireTypeAfter(response);
                        break;
                    case "position":
                        addPositionTypeAfter(response);
                        break;
                    case "retire":
                        addRetireTypeAfter(response);
                        break;
                    case "residence":
                        addResidenceTypeAfter(response);
                        break;
                }
                $('#add_modal').modal('hide');
            });
        },
        error: function(jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        },complete:function () {
            unlockAjax(obj);
        }
    });
}
function tableInfoEdit(e) {
    editVal1 = $('#edit_modal_name1').val();
    editVal2 = $('#edit_modal_name2').val();
    let url=$(e).parents('form').attr('action');
    $.ajax({
        url:url,
        data:$(e).parents('form').serialize(),
        type:"put",
        success:function(response){
            ajaxSuccessAction(response,function () {
                $('#edit_modal').modal('hide');
                let table="";
                switch (type) {
                    case "department":
                        table="#departmentTable td";
                        break;
                    case "hire":
                        table="#hireTypeTable td";
                        break;
                    case "position":
                        table="#positionTypeTable td";
                        break;
                    case "retire":
                        table="#retireTypeTable td";
                        break;
                    case "residence":
                        table="#residenceTypeTable td";
                        break;
                }
                $(table).each(function () {
                    if($(this).data('id')==editId){
                        $(this).html(editVal1);
                        if($(this).parents('tr').find('.field2').length>0) $(this).parents('tr').find('.field2').html(editVal2);
                    }
                });
            })
        },
        error: function(jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }
    });
}
function tableInfoDelete(url) {
    $.ajax({
        url:url,
        type:"delete",
        success:function(response){
            ajaxSuccessAction(response,function () {
                $('#delete_modal').modal('hide');
                deleteTr.remove();
                $("table").each(function (){
                    $(this).find('.numberId').each(function (index) {
                        $(this).html(index+1);
                    })
                });
            });
        },
        error: function(jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }
    });
}

function annualLeaveTableInit() {
    var grow_leave=Number($('input[name=grow_leave]').val());
    var maxAnnualLeave=$('input[name=max_annual_leave]').val();
    $('#annual_leave_table tbody').find('tr').each(function () {
        var daysInput= $(this).find('td').find('input[name="days[]"]');
        var hasDaysInput= $(this).find('td').find('input[name="has_days[]"]');
        if($(this).find('td').find('input[type=checkbox]').is(':checked')){
            var preVal=0;
            if(daysInput.val()==''){
                var thisYearVal=Number($('input[name=first_year_leave]').val())+Number(grow_leave*calculateWorkYears($(this).find('td').find('input[name=date_hire]').first().val(),$(this).find('td').find('input[name=date_retire]').first().val()));
                thisYearVal=thisYearVal>maxAnnualLeave?maxAnnualLeave:thisYearVal;
                daysInput.val(thisYearVal);
                hasDaysInput.val(thisYearVal);
                preVal=thisYearVal+grow_leave;
                preVal=preVal>maxAnnualLeave?maxAnnualLeave:preVal;
                daysInput.parent('td').prev().html(preVal+'/'+preVal);
            }else{
                preVal=Number(daysInput.val())+grow_leave;
                preVal=preVal>maxAnnualLeave?maxAnnualLeave:preVal;
                daysInput.parent('td').prev().html(preVal+'/'+preVal);
            }
        }else {
            $(this).find('td').find('input[type=text]').val('');
            $(this).find('td').find('input[type=text]').attr('readonly',true);
        }
    });
}

function preValChange(event) {
    var maxAnnualLeave=$('input[name=max_annual_leave]').val();
    var preVal= Number($('input[name=grow_leave]').val())+Number($(event).val());
    if(preVal>maxAnnualLeave) preVal=maxAnnualLeave;
    $(event).parent('td').prev().html(preVal+'/'+preVal);
}

function employeeAnnualLeaveChange(event) {
    var nextAllTd= $(event).parents('td').nextAll();
    if($(event).is(':checked')){
        var preTd=$(event).parents('td').prev();
        var workYear=calculateWorkYears(preTd.find('input').first().val(),preTd.find('input').last().val());
        var first_year_leave=$('input[name=first_year_leave]').val();
        var grow_leave=$('input[name=grow_leave]').val();
        var maxAnnualLeave=$('input[name=max_annual_leave]').val();
        var annualLeaves=Number(first_year_leave)+Number(grow_leave*workYear);
        var nextAnnualLeave=annualLeaves+Number(grow_leave);
        if(annualLeaves>maxAnnualLeave) annualLeaves=maxAnnualLeave;
        if(nextAnnualLeave>maxAnnualLeave) nextAnnualLeave=maxAnnualLeave;
        nextAllTd.first().html(nextAnnualLeave+'/'+nextAnnualLeave);
        nextAllTd.find('input[name="days[]"]').val(annualLeaves);
        nextAllTd.find('input[name="has_days[]"]').val(annualLeaves);
        nextAllTd.find('input').attr('readonly',false);
    }else{
        nextAllTd.first().html('');
        nextAllTd.find('input[name="days[]"]').val('');
        nextAllTd.find('input[name="has_days[]"]').val('');
        nextAllTd.find('input').attr('readonly',true);
    }
}

function hrSettingSubmit(event) {
    let data = new FormData($(event).parents('form').get(0));
    let url = $(event).parents('form').attr('action');
    $.ajax({
        url:url,
        type:"post",
        contentType: false,//リクエストヘッダーを不要にする
        processData: false,//前処理を取り消す
        data: data,
        success: function (response) {
            ajaxSuccessAction(response);
        },
        error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }
    });
}


function calculateWorkYears(firstYear,lastYear) {
    firstYear=moment().format(firstYear);
    if(lastYear!=undefined && lastYear!=''){
        lastYear=moment().format('YYYY-01-01',lastYear);
    }else{
        lastYear=moment().format('YYYY-01-01');
    }
    return moment(lastYear).diff(moment(firstYear),'years');
}
