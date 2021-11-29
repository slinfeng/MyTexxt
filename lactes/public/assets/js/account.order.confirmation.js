let manage_code_selector = "input[name=order_manage_code]";
let date_selector = "input[name=period]";
let init_val_created_date = '';
let init_val_manage_code = '';
const FOLDER='注文請書';
let table_selector = '#order-confirmations-table';
$(function () {
    showHeadButton();
    initDatePicker('#startAndEndDate',function () {
        $(table_selector).DataTable().draw();
    });
    initDataTable(0);
    adjustSidebarForFixedHeader();
});

function appendedFuncOnDatatable(){
    tableSettingInfo.order = [[0, 'desc'],[1,'asc']];
}

function completeHandle(href) {
    let boo = false;
    if(href.indexOf("create") > 0 ){
        action="create";
        let position = $("input:radio[name=our_position_type]:checked").val();
        changeClients(position);
        boo = true;
    }else{
        init_val_created_date = unityDate();
        init_val_manage_code = $(manage_code_selector).val();
        action="edit";
    }
    initDatePicker($("input[name=period]"),function () {
        changeNum();
    },boo);
}

/**
 * 削除のダイアログ画面を表示
 * @param id
 */
function showDelModel(id) {
    let delete_tag = $('#delete');
    let route = delete_tag.data('route').replace(/_id/g,id);
    let e = event.target;
    let path = $(e).parents('tr').first().find('td:nth-of-type(3)').find('a').data('file-path');
    delete_tag.append('<span id="temp-for-route" hidden>'+route+'</span>');
    delete_tag.append('<span id="temp-for-path" hidden>'+path+'</span>');
    delete_tag.data('route',);
    delete_tag.data('restore-id',id);
    delete_tag.modal('show');
}

/**
 * 削除処理
 */
function modelDelFunc() {
    const obj = lockAjax();
    testForLocal(obj);
    let pathArr = getFilePathArr();
    setTimeout(function (){
        if(localAccessFlag===false) throw false;
        let action = 'http://'+init_val.data('save-ipaddr')+'/delfile/index.php';
        let flag=false;
        $.ajax({
            url: action,
            type: "post",
            data: {'pathArr':pathArr},
            async:false,
            success: function (res) {
                const data = JSON.parse(res);
                if(data.status===0){
                    flag = true;
                }else{
                    printErrorMsg(data.message);
                    flag = false;
                }
            },
            error: function (jqXHR, testStatus, error) {
                flag = false;
                if (jqXHR.status === 0) {
                    printErrorMsg('ローカルサーバーへの接続が失敗しました！');
                    return;
                }
                ajaxErrorAction(jqXHR, testStatus, error);
            }
        });
        if(flag) {
            const delete_tag = $('#delete');
            const route_tag = delete_tag.find('#temp-for-route');
            const route = route_tag.html();
            route_tag.remove();
            $.ajax({
                url: route,
                type: "delete",
                success: function (response) {
                    ajaxSuccessAction(response,function (response) {
                        $(table_selector).DataTable().draw();
                        successHandleWhenDel();
                        delete_tag.modal('hide');
                    })
                },
                error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error)
                },complete:function () {
                    unlockAjax(obj);
                }
            });
        }else{
            unlockAjax(obj);
        }
    },300);
}

function getFilePathArr(){
    const delete_tag = $('#delete');
    const path_tag = delete_tag.find('#temp-for-path');
    const path = path_tag.html();
    path_tag.remove();
    return [path];
}
