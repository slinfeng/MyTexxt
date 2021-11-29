const TABLE_SELECTOR_LETTER = '#letteroftransmittal-table';
const TABLE_SELECTOR_INVOICE = '#invoices-table';
const TABLE_SELECTOR_CLIENT = '#clients';
let table_no_selector = ['#clients','#order-confirmations-table'];
const SELECTOR_CONTRACT_OTHER_REMARK = 'input[name=contract_type_other_remark]';
const SELECTOR_COMPANY_INFO = 'textarea[name=company_info]';
const SELECTOR_REMARK_START = 'textarea[name=remark_start]';
const SELECTOR_REMARK_END = 'textarea[name=remark_end]';
const SELECTOR_CLIENT_ID = 'select[name=client_id]';
const SELECTOR_CLIENT_NAME = 'span[name=cname]';
const SELECTOR_LAST_CLIENT_A = 'input[name=last_client_a]';
const SELECTOR_LAST_CLIENT_B = 'input[name=last_client_b]';
const SELECTOR_LAST_FORMAT = 'input[name=last_format]';

let other = $(SELECTOR_CONTRACT_OTHER_REMARK).val();
let textarea = $(SELECTOR_COMPANY_INFO);
let textarea_a = $(SELECTOR_REMARK_START);
let textarea_b = $(SELECTOR_REMARK_END);

const CLIENT_SORT_TYPE = $(SELECTOR_INIT_VAL).data('client-sort-type');
const COMPANY_INFO_INIT = textarea.data('initial');
const REMARK_START = textarea_a.data('initial');
const REMARK_END = textarea_b.data('initial');
const TAX_RATE = $('input[name=tax_rate]').val();

const INPUT_ABLE_CONTRACT_TYPE = "6";
const FORMAT_IN = "0";
const FORMAT_OUT = "1";
const POSITION_A = "1";
const POSITION_B = "2";
const FONT_FAMILY = init_val.data('print-font');
const OUT_USER = init_val.data('out-user');
const CLOUD_REQUEST_PERIOD = init_val.data('cloud-request-period');
const CREATE = 'create';
const EDIT = 'edit';
const SEARCH_MODE_MONTH = 1;
const CLIENT_ID_HAS_NOT = '0';
const CLIENT_NAME_WITHOUT_ID = '　&nbsp　&nbsp　&nbsp　&nbsp　&nbsp　';
const SAVE_PATH='request';

let ajaxLock = false;
let company_info_last = textarea.val();
let remark_start = textarea_a.val();
let remark_end = textarea_b.val();
let tableSettingInfo = '';
let columnDefs = [];
let lastClientID = init_val.data('re-client-id');
let trMap = new Map();
let action = window.location.href.indexOf(EDIT)>=0?EDIT:CREATE;
let data_datatable = function (d){
        let allDate = $("#startAndEndDate").val();
        d.client_id=lastClientID;
        if(allDate!==undefined&&allDate!==''){
            d.startDate=moment(allDate.substr(0,10), DATE_FORMAT_A).format(DATE_FORMAT_A);
            d.endDate=moment(allDate.substr(11,21), DATE_FORMAT_A).format(DATE_FORMAT_A);
        }
        d.our_position_type=$("select[name=position] option:selected").val();
        d.search_msg = $('#search_msg').val();
}

/**
 * ストリングにメソッドを追加
 * @returns {number}
 * @private
 */
String.prototype._getByteLength = function () {
    let str = this;
    let len = str.length;
    let reLen = 0;
    for (let i = 0; i < len; i++) {
        if (str.charCodeAt(i) < 27 || str.charCodeAt(i) > 126) {
            // 全角
            reLen += 2;
        } else {
            reLen++;
        }
    }
    return reLen;
}
String.prototype._cutString = function (len) {
    let str = this;
    let l = str.length;
    let rel = [];
    let tl = 0;
    for (let i = 0; i < l && tl < len; i++) {
        if (str.charCodeAt(i) < 27 || str.charCodeAt(i) > 126) {
            tl += 2;
        } else {
            tl++;
        }
        if (tl <= len) rel[i] = str[i];
    }
    return rel.join("");
}

/**
 * 取引先id、我社立場、ファイルフォーマットタイプのインプットを作成して、セレクターの指定フォームに追加する。
 * @param selector
 */
function addCommonInput(selector) {
    if(action===CREATE){
        let client_id = $('.common select[name=client_id]').val();
        let our_position_type = POSITION_B;
        const file_format_type = $('.common input[name=file_format_type]:checked').val();
        if(client_id!==undefined){
            client_id = client_id===CLIENT_ID_HAS_NOT?"":client_id;
            our_position_type = $('.common input[name=our_position_type]:checked').val();
        }else{
            client_id =-1;
        }
        let input_client = $('<input name="client_id" type="text" hidden value="'+client_id+'">');
        let input_position = $('<input name="our_position_type" type="text" hidden value="'+our_position_type+'">');
        let input_format = $('<input name="file_format_type" type="text" hidden value="'+file_format_type+'">');
        let cname = $('span[name=cname]').html();
        let input_cname = $('<input name="cname" type="text" hidden value="'+cname+'">');
        $(selector).append(input_cname);
        $(selector).append(input_client);
        $(selector).append(input_position);
        $(selector).append(input_format);
    }else{
        let input_init_code = $('<input name="init_code" type="text" hidden value="'+init_val_manage_code+'">');
        $(selector).append(input_init_code);
    }
    let input_folder = $('<input name="folder" type="text" hidden value="'+FOLDER+'">');
    $(selector).append(input_folder);
}

/**
 * 取引先id、我社立場、ファイルフォーマットタイプのインプットをリムーブする。
 * @param selector
 */
function removeCommonInput(selector) {
    if(action===CREATE) {
        $(selector).find('input[name=client_id]').remove();
        $(selector).find('input[name=our_position_type]').remove();
        $(selector).find('input[name=file_format_type]').remove();
        $(selector).find('input[name=cname]').remove();
    }else{
        $(selector).find('input[name=init_code]').remove();
    }
    $(selector).find('input[name=folder]').remove();
}

/**
 * 我社立場によって、取引先を取得
 * @param position
 * @param selectedId
 */
function changeClients(position) {
        let select = $(SELECTOR_CLIENT_ID);
        const url = select.data('route');
        if(url!==undefined){
            $.ajax({
                url: url,
                data: {position: position,},
                dataType: 'json',
                type: 'post',
                async: false,
                success: function (clients) {
                    let option = select.find('option').first().clone();
                    select.empty();
                    select.append(option);
                    for(let i=0;i<clients.length;i++){
                        var client=clients[i];
                        option = select.find('option').first().clone();
                        option.val(client.id);
                        if(client.client_abbreviation==null){
                            client.client_abbreviation='';
                        }
                        if(CLIENT_SORT_TYPE==0){
                            option.html('（' + fillZeroInClient(client.id) + '）' + client.client_name+'（' + client.client_abbreviation + '）');
                        }else{
                            option.html('（' + client.client_abbreviation + '）' + client.client_name+'（' + fillZeroInClient(client.id) + '）');
                        }
                        select.append(option);
                    }
                    changeNum();
                    const sS = select.data('searchableSelect');
                    if(sS!==undefined){
                        sS.items.empty();
                        sS.buildItems();
                    }else{
                        select.searchableSelect();
                    }
                }, error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                },
            });
        }
}

/**
 * 取引先idが4桁不足の場合に’0’を左側に追加する。
 * @returns {string}
 * @param client_id
 */
function fillZeroInClient(client_id) {
    return ('000'+client_id).substr(-4);
}

/**
 * 社内フォーマットと社外フォーマットの切替
 * @param e
 */
function disableFormat(e) {
    if(e!==undefined){
        const position = $(e).val();
        const inputFormat = $("input[name='file_format_type']");
        const isInvoice = window.location.href.indexOf('invoice')>=0;
        if(window.location.href.indexOf('confirmations')===-1){
            if (position !== POSITION_IN) {
                inputFormat.first().prop("disabled", true);
                inputFormat.last().prop("checked", true);
                if(isInvoice) $('.format_B tr').eq(1).find('th').html('入金予定日');
            } else {
                inputFormat.first().prop("disabled", false);
                const last_format = $(SELECTOR_LAST_FORMAT).val();
                inputFormat.eq(parseInt(last_format)).prop("checked", true);
                if(isInvoice) $('#bank-accounts tr').last().find('th').html('支払期限日');
            }
        }
        let last_client = position === POSITION_A?SELECTOR_LAST_CLIENT_A:SELECTOR_LAST_CLIENT_B;
        $(last_client).val($(SELECTOR_CLIENT_ID).val());
        changeFormat($("input[name=file_format_type]:checked"),false);
        changeClients(position);
        const client_id = position === POSITION_A?$(SELECTOR_LAST_CLIENT_B).val():$(SELECTOR_LAST_CLIENT_A).val();
        if($(SELECTOR_CLIENT_ID).length>0) $(SELECTOR_CLIENT_ID).data('searchableSelect').selectItem($('div.searchable-select-item[data-value='+client_id+']').first());
        $('input.searchable-select-input').val('');
        // changeNum();
    }else {
        changeClients();
    }
}

/**
 * 管理番号の更新
 */
function changeNum() {
    let our_position_type = '';
    let date = '';
    let client_id = '';
    let client_name = '';
    let updateCode = false;
    if(action===CREATE){
        our_position_type = $("input[name=our_position_type]:checked").val();
        date = unityDate();
        client_id = $(SELECTOR_CLIENT_ID).val();
        if(client_id!==undefined){
            const temp = $("option[value='" + client_id + "']").html();
            const start = temp.indexOf('）');
            const end = temp.indexOf('（',start+1);
            client_name = temp.substr(start+1,end - start - 1);
        }
        updateCode = (client_id !== CLIENT_ID_HAS_NOT) && (client_id !== '');
            if (updateCode){
                if(OUT_USER==0){
                    $(SELECTOR_CLIENT_NAME).html(client_name);
                }
            } else {
                $(manage_code_selector).val("");
                $(SELECTOR_CLIENT_NAME).html(CLIENT_NAME_WITHOUT_ID);
            }
            if (date.trim() === '') {
                updateCode = false;
                $(manage_code_selector).val('');
            }

    }else if(action===EDIT){
        date = $(date_selector).val().substr(0,10);
        our_position_type = $("input[name=our_position_type]:checked").val();
        client_id = $("input[name=client_id]").val();
        const myDate = new Date(date);
        const year = myDate.getFullYear();
        const month = myDate.getMonth() + 1;
        updateCode = !(year === parseInt(init_val_created_date.substr(0, 4))
            && month === parseInt(init_val_created_date.substr(5, 2)));
        if(!updateCode){
            $(manage_code_selector).val(init_val_manage_code);
        } else if(date.trim()===''){
            updateCode = false;
            $(manage_code_selector).val("");
        }
    }
    if(our_position_type===undefined){
        our_position_type=POSITION_A;
    }
    if(client_id===undefined) {
        client_id='';
    }
    if (updateCode) {
        $.ajax({
            type: 'post',
            url: $(manage_code_selector).first().data('route'),
            dataType: 'json',
            async: false,
            data: {
                'client_id': client_id,
                'date': date.replace(/-/g, ''),
                'our_position_type': our_position_type,
                'last_manage_code':$(manage_code_selector).first().val(),
            },
            success: function (result) {
                $(manage_code_selector).val(result.code);
                if(typeof handleWhenClientChanged === 'function') handleWhenClientChanged(result);
            },
            error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            }
        });
    }
}

/**
 * 管理番号を影響してる日付または日付範囲の取得
 * @returns {jQuery}
 */
function unityDate() {
    return $(date_selector).val();
}

/**
 * 社内フォーマットと社外フォーマットの切替
 * @param e
 * @param changeLastVal
 */
function changeFormat(e,changeLastVal = true) {
    if ($(e).val() === FORMAT_IN) {
        $("div.syagai").addClass("display-none");
        $("div.syanai").removeClass("display-none");
        if(changeLastVal)
            $(SELECTOR_LAST_FORMAT).val(FORMAT_IN);
        initCompanyInfoAndRemark();
        $('textarea').each(function () {
            changeLength(this);
        })
    } else {
        $("div.syanai").addClass("display-none");
        $("div.syagai").removeClass("display-none");
        if(changeLastVal)
            $(SELECTOR_LAST_FORMAT).val(FORMAT_OUT);
    }
}

function periodToMonths(period) {
    if(period!==''){
        let time=period.split('～');
        let startTime = time[0].split('-');
        let endTime = time[1].split('-');
        if(startTime.length === 3 && endTime.length === 3){
            let day = Math.round(parseFloat(endTime[2])/7-parseFloat(startTime[2])/7);
            day =parseFloat(day)/4;
            period =(parseInt(endTime[0])-parseInt(startTime[0]))*12+parseInt(endTime[1])-parseInt(startTime[1])+day;
        }
    }
    return period;
}

/**
 * フォームのサブミット
 * @param selector
 * @param action
 * @param format
 * @param hasFile
 */
function submitForm(selector,action,format,hasFile=false) {
    addCommonInput(selector);
    ajaxSubmitForm(selector,function () {action===CREATE?goToBack():updateInitVal();},hasFile);
}

/**
 * バッチアクション
 * @param selector
 * @param method
 */
function batchDeleteForLocalServer(selector) {
    const obj = lockAjax();
    let pathArr = getFilePathArr();
    if(pathArr.length>0){
        testForLocal(obj);
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
                let idArr = getIdArr();
                $.ajax({
                    url: $(selector).data('route'),
                    type: 'post',
                    data: {
                        idArr: idArr,
                    },
                    success: function (res) {
                        ajaxSuccessAction(res,function (res) {
                            successWhenBatchAction();
                        });
                    }, error: function (jqXHR, testStatus, error) {
                        ajaxErrorAction(jqXHR, testStatus, error);
                    },complete:function () {
                        unlockAjax(obj);
                    }
                });
            }else{
                unlockAjax(obj);
            }
        },300);
    }else{
        let idArr = getIdArr();
        $.ajax({
            url: $(selector).data('route'),
            type: 'post',
            data: {
                idArr: idArr,
            },
            success: function (res) {
                ajaxSuccessAction(res,function (res) {
                    successWhenBatchAction();
                });
            },error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            },complete:function () {
                unlockAjax(obj);
            }
        });
    }
}

function getFilePathArr(){
    let filePathArr = [];
    $('tbody tr.selected').find("td:nth-of-type("+file_index+")").each(function () {
        let a = $(this).find('a');
        if(a.length>0){
            const path = a.data('file-path');
            if(path !== undefined && path !== '') filePathArr.push(path);
        }
    });
    return filePathArr;
}

function updateInitVal() {
    init_val_created_date = $(date_selector).val();
    init_val_manage_code=$(manage_code_selector).val();
    const file_span=$('span.files-info');
    const file_input=$('input[name=file_name]');
    if(file_span.html()!=='' && file_span.html()!==undefined){
        file_input.val(file_span.html());
        file_span.html('');
        file_span.parent().find('a').html(file_input.val()).attr('onclick','openFile('+$('input[name=file_id]').val()+',\''+file_input.val().substr(file_input.val().indexOf('.')+1)+'\')');
    }
}

/**
 * Ajaxでファイルをサブミット
 * @param formData
 */
function ajaxSubmitFile(formData) {
    if(typeof outFlag!=="undefined") return true;
    if(CLOUD_REQUEST_PERIOD>0) return true;
    let action = 'http://'+init_val.data('save-ipaddr');
    let flag=false;
    $.ajax({
        url: action,
        type: "post",
        data:formData,
        async:false,
        contentType:false,
        processData:false,
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
    return flag;
}

function successWhenBatchAction() {
    initTable();
}

/**
 * 印刷する前のチェック
 */
function preparePrint() {
    $('#print_modal').modal('hide');
    //延时函数，使 弹出框 有时间 hide
    setTimeout(function () {
        let printCheckInput = $('input[name=printCheck]');
        doPrint(printCheckInput.eq(0).is(':checked'), printCheckInput.eq(1).is(':checked'));
    }, 500);
}

let bdhtml1 = '';
let title_pdf = '';
/**
 * プリント処理
 */
function doPrint(local=true,copy,toPdf=false) {
    title_pdf = getPdfName();
    saveValue();
    let print = $('.print-receipt');
    bdhtml1 = print.prop("outerHTML");//获取当前页的html代码 重置用
    $("textarea").each(function () {
        $(this).html($(this).val().replace(/:br-replace/g,'\r\n'));
    });
    $(SELECTOR_CLIENT_NAME).removeClass('overflowHide');
    if(copy===true){
        let print_a = print.clone();
        const div_img = $('<div class="div-img"></div>');
        const div_img_content = $('<div class="div-img-content"><span>収入</span><span>印紙</span></div>');
        div_img_content.appendTo(div_img);
        print_a.prepend(div_img);
        print_a.find('h1.text-center').html('御　注　文　請　書');
        print_a.find(SELECTOR_CLIENT_NAME).html($('input[name=company_name]').val());
        print_a.find('li.num:first span').html('作成日');
        print_a.find('li.num:first input').val('　　年　月　日').attr('value','　　年　月　日').removeAttr('size').addClass('print-tag').width(160);
        print_a.find('li.num span').width('3.2em');
        print_a.find('li.num:eq(1)').html('　');
        print_a.find('ul.manage-code').css('margin-right','2em');
        let textarea_company = print_a.find(SELECTOR_COMPANY_INFO);
        textarea_company.html('');
        textarea_company.attr('rows','6');
        textarea_company.addClass('print-tag');
        textarea_company.css('border','1px solid #e3e3e3');
        const remark_confirmation = $('textarea[name=remark_confirmation]').val();
        print_a.find('.company-info img').remove();
        print_a.find('div.clear').html(remark_confirmation.replace(/\n|\r|(\r\n)|(\u0085)|(\u2028)|(\u2029)/g,"<br>"));
        print_a.find('p').last().remove();
        let nextPage = $("<div></div>");
        nextPage.append(print_a.html());
        if(local === true){
            nextPage.css('padding-top','30px');
            nextPage.addClass('next-page');
        }else{
            print.empty();
        }
        print.append(nextPage);
    }
    tagAction();
    printHandle();
    print.printArea({toPdf:toPdf});
}

function getPdfName() {
    let cname = $('.client-name').html();
    if(cname!='' && cname!=undefined){
        cname=cname.trim();
        cname=cname.replace('取引先：','');
        cname=cname.replace(/&amp;/g,'&');
    }
    let employee_name = '(';
    let index = 1;
    $('input[name^=employee_name]').each(function () {
        let name=$(this).val();
        if(name!='' && name!=undefined){
            name = name.trim();
            name = name.replace(/　/,'')
            name = name.replace(/&amp;/g,'&');
        }
        if(name!=''){
            if(index==1){
                employee_name +=name+"";
            }else if(index==2){
                employee_name+="...";
            }
            index++;
        }
    });
    employee_name+=")";

    return $(manage_code_selector).val()+'_'+cname+employee_name;
}

/**
 * 各入力欄のバリューをセーブする。
 */
function saveValue() {
    $(".syanai input").each(function () {
        $(this).attr("value", $(this).val());
    });
    $('.syanai input[type=radio]:checked').attr('checked', true);
    $('.syanai input[type=radio]:not(:checked)').removeAttr('checked');
    $('.syanai input[type=checkbox]:checked').attr('checked', true);
    $('.syanai input[type=checkbox]:not(:checked)').removeAttr('checked');
    //更新select
    $(".syanai select option:selected").attr("selected", true);
    $("textarea").each(function () {
        $(this).html($(this).val().replace(/\r\n/g,':br-replace'));
        $(this).html($(this).val().replace(/\n/g,':br-replace'));
    });
}

/**
 * タグの処理
 */
function tagAction() {
    $('#cal-type').remove();
    $(".print-receipt select").each(function () {
        if ($(this).attr('name') === 'contract_type' && $(this).val()===INPUT_ABLE_CONTRACT_TYPE)
            $(this).replaceWith('');
        else
            $(this).replaceWith($(this).find('option:selected').html());
    });
    $("li.hide_label").css('visibility', 'hidden');
    $('.print-receipt .backcolor-E8F0FE').css('background-color', 'white');
    handleBeforePrint();
    $("[data-print='false']").remove();
    $(".print-receipt input[type=radio]").remove();
    //将input框去除
    $(".print-receipt input").each(function () {
        if (!$(this).is(":hidden")
            && !$(this).hasClass('print-tag') && $(this).attr('type')!=='checkbox') {
            if ($(this).attr('name') === 'contract_type_other_remark' && $(this).val().trim() !== '') {
                let v = $(this).val();
                $(this).replaceWith(v);
            } else {
                $(this).replaceWith($(this).val());
            }
        }
    });
}

/**
 * 印刷プレビュー前の各自処理
 */
function handleBeforePrint() {}

/**
 * 印刷プレビュー前に空白行の追加
 */
function addEmptyLine() {}

/**
 * 印刷プレビュー直前の処理
 */
function printHandle() {
    $(".print-receipt").addClass("print");
    $(".print").css("font-family",FONT_FAMILY);
    $(".print h1,.print h2,.print h3,.print h4,.print h5,.print h6").css("font-family",FONT_FAMILY);
    $('ul').css('list-style-type','none');
    if(window.location.href.indexOf('letteroftransmittal')>0){
        letterLayout();
    }
    $('textarea:not(:hidden)').each(function () {
        if(!$(this).hasClass('print-tag'))
            $(this).replaceWith($(this).val().replace(/\n|\r|(\r\n)|(\u0085)|(\u2028)|(\u2029)/g,"<br>"));
    }).css('margin-top','0');
    initTextarea();
    $(SELECTOR_CLIENT_NAME).removeClass('overflow-hide');
    addEmptyLine();
}

/**
 * 印刷後画面の回復
 * @param html
 */
function restoreHandle(html) {
    $('.print-receipt').replaceWith($(html));//还原界面
    $("textarea").each(function () {
        $(this).html($(this).val().replace(/:br-replace/g,'\r\n'));
    });
    handleAfterPrint();
    textarea = $('textarea[name=company_info]');
    textarea_a = $('textarea[name=remark_start]');
    textarea_b = $('textarea[name=remark_end]');
}

/**
 * 一覧画面に戻る
 */
function goToBack(href= init_val.data('re-href'),reClientId= lastClientID,rePeriod= init_val.data('re-period'),rePosition= init_val.data('re-position')) {
    var appendHtml='<form id="reForm" action="'+href+'" method="get">' +
        '<input hidden name="re_client_id" value="'+reClientId+'">\n' +
        '<input hidden name="re_period" value="'+rePeriod+'">\n' +
        '<input hidden name="re_position" value="'+rePosition+'"></form>';
    $('body').append(appendHtml);
    $('#reForm').submit();
}

/**
 * 我社情報と補充説明文の初期化
 * @param e
 */
function initCompanyInfoAndRemark(e = 'input[name=use_init_val]') {
    if ($(e).is(':checked')) {
        company_info_last = textarea.val();
        remark_start = textarea_a.val();
        remark_end = textarea_b.val();
        textarea.val(COMPANY_INFO_INIT);
        textarea_a.val(REMARK_START);
        textarea_b.val(REMARK_END);
        textarea.prop('readonly', true);
        textarea_a.prop('readonly', true);
        textarea_b.prop('readonly', true);
    } else {
        textarea.val(company_info_last);
        textarea_a.val(remark_start);
        textarea_b.val(remark_end);
        textarea.prop('readonly', false);
        textarea_a.prop('readonly', false);
        textarea_b.prop('readonly', false);
    }
    changeLength(textarea[0]);
    changeLength(textarea_a[0]);
    changeLength(textarea_b[0]);
}

function appendedFuncOnSingleDatepicker(e,pickerInfo,initial,format,isToday=true) {
    if (initial && typeof ($(e).data('month')) !== 'undefined') {
        const day = $(e).data('day');
        const month = $(e).data('month');
        let date = moment();
        switch (month) {
            case 2:
                date.add(1, 'month');
                break;
            case 3:
                date.add(2, 'month');
                break;
        }
        if(month!==0){
            day > date.daysInMonth() ? date.date(date.endOf('month').format('D')) : date.date(day);
        }
        if(isToday || month!==0){
            pickerInfo.startDate = date.format(format);
            $(e).val(date.format(format));
        }
    }
}

/**
 * 期間セレクター初期化
 * @param e
 * @param handler
 * @param initial
 */
function initDatePicker(e,handler=function () {},initial=false) {
    let isTextarea = $(e).attr('name') === 'employee_period[]';
    let pickerInfo = getSettingWhenIsPeriodPicker();
    if (initial && typeof ($(e).data('month')) !== 'undefined') {
        const month = $(e).data('month');
        if(month!==0){
            let startDate = '';
            let endDate = '';
            switch (month) {
                case 1:
                    startDate=moment().startOf('month').format(DATE_FORMAT_A);
                    endDate=moment().endOf('month').format(DATE_FORMAT_A);
                    break;
                case 2:
                    startDate=moment().add(1, 'month').startOf('month').format(DATE_FORMAT_A);
                    endDate=moment().add(1, 'month').endOf('month').format(DATE_FORMAT_A);
                    break;
            }
            pickerInfo.startDate = startDate;
            pickerInfo.endDate = endDate;
            $(e).val(startDate + '～' + endDate);
        }
    }
    $(e).daterangepicker(pickerInfo, function (start, end) {
        $(e).val(start.toString() + '～' + end.toString());
    });
    if (isTextarea) {
        whenLinkedWithTextarea(e);
    } else {
        let searchMode = $(e).data('search-mode');
        $(e).on('apply.daterangepicker', function (ev, picker) {
            applyDate(this,picker,searchMode)
        }).on('hide.daterangepicker', function (ev, picker) {
            if ($(this).val().trim() !== ''){
                applyDate(this,picker,searchMode)
            }
            handler();
            $(e).focus().blur();
        });
    }
}

/**
 * 日付範囲の適用
 * @param el
 * @param picker
 * @param searchMode
 */
function applyDate(el,picker,searchMode) {
    if(searchMode!==SEARCH_MODE_MONTH){
        $(el).val(picker.startDate.format(DATE_FORMAT_A) + '～' + picker.endDate.format(DATE_FORMAT_A));
    }else{
        $(el).val(picker.startDate.startOf('month').format(DATE_FORMAT_A) + '～' + picker.endDate.endOf('month').format(DATE_FORMAT_A));
    }
}

let sort_id_name = 'id';
/**
 * データテーブルの初期化
 */
function initDataTable(order) {
    unSizeForFixedHeader();
    // let fixedHeader = $.fn.DataTable.FixedHeader.prototype._update;
    // $.fn.DataTable.FixedHeader.prototype._update = function(){
    //     fixedHeader.call($(table_selector).DataTable().settings()[0]._fixedHeader);
    // }
    tableSettingInfo = initTableSettingInfo(order);
    tableSettingInfo.columns.push({data: "id", name: sort_id_name,orderable:true,visible:false});
    if(!table_no_selector.includes(table_selector))
        batchSelectOnDatatable();
    appendedFuncOnDatatable();
    return initTable(lastClientID);
}
function whenClicked(obj) {
    showOptions(obj);
}
function appendedFuncOnDatatable() {}

/**
 * 選択される場合はテーブルに修正できる項目を入力可能にする
 * 保存する場合はテーブルに修正できる項目を入力不能にする
 * @param e
 * @param shift_flag
 * @param clear_flag
 * @param status
 */
function ableInput(e,shift_flag,clear_flag=false) {
    let tr = $(e).parent();
    const index = tr.index();
    if (tr.hasClass('selected') && (!shift_flag || clear_flag)) {
        recoveryWith(tr,trMap.get(index));
        trMap.delete(index);
        tr.find('input').addClass('disable-input');
    } else if(!tr.hasClass('selected')) {
        trMap.set(index,tr.clone());
        tr.find('input').removeClass('disable-input');
    }
    if(tr.find('td:nth-of-type(9)').text()==='承認待'){
        tr.find('input').addClass('disable-input');
    }
}

/**
 * データテーブルのAjax完成処理
 */
function datatableComplete() {
    ajaxLock = false;
    $("#options").addClass("hide");
    $("#options span").addClass("invisible");
    $('#delete').modal("hide");
    $('#copy').modal("hide");
    $(".hidden").hide();
    if(lastClientID!==undefined && lastClientID !==''){
        ableIndexLink(lastClientID);
        // init_val.data('re-client-id','');
    }else{
        disableIndexLink();
    }
    if(table_selector===TABLE_SELECTOR_INVOICE){
        $('.date-modify').each(function () {
            initSingleDatePicker($(this));
        });
        $('thead tr').removeClass('selected');
        calcSum($(table_selector).DataTable());
        changeTitle($("select[name=position] option:selected").val());
    }
}

/**
 * テーブルの初期化
 * @param client_id
 */
function initTable(client_id) {
    if(!ajaxLock){
        if(client_id!==undefined && client_id !== ''){
            lastClientID = client_id;
        }
        ajaxLock = true;
        if($('div.dataTables_info').length===0){
            return $(table_selector).DataTable(tableSettingInfo);
        }else{
            $(table_selector).DataTable().draw();
        }
    }
}

function searchClient(e){
    const client_id = $(e).parent().data('id');
    initTable(client_id);
}

function initFontFamily() {
    if(FONT_FAMILY!==undefined){
        $('textarea').each(function () {
            $(this).css('font-family',FONT_FAMILY);
        });
    }
}

/**
 * 新規画面へ
 * @param e
 */
function toCreate(e) {
    const type= $('select[name=position]').val();
    const rePeriod= $('#startAndEndDate').val();
    const reClientId= lastClientID;
    let href = $(e).data('href');
    goToBack(href,reClientId,rePeriod,type);
}

function toEdit(e) {
    toCreate(e);
}

function createdHandleWhenClient(nTd,oData) {
    $(nTd).attr('id','client-'+oData.client_id).data('id',oData.client_id);
}

function maxBytesCanInput(e, max) {
    let val = $(e).val();
    if (val._getByteLength() > max) {
        $(e).val(val._cutString(max));
    }
}

/**
 * チェックボックスを選択された/されてなかった状態にする
 * @param el
 */
function checkedBox(el) {
    let span = $(el).next();
    if($(el).is(':checked')){
        span.removeClass('backcolor-white');
    }else{
        span.addClass('backcolor-white');
    }
}
/**
 * ラジオボックスを選択された/されてなかった状態にする
 * @param el
 */
function checkedRadio(el) {
    $(el).parent().find('span.rect').removeClass('backcolor-white');
    $(el).parent().parent().find('input[type=radio]').each(function () {
        if(this!==el){
            $(this).parent().find('span.rect').addClass('backcolor-white');
        }
    });
}

/**
 * 電子印鑑を表示
 * @param e
 */
function showSeal(e = 'input[name=use_seal]') {
    if ($(e).is(':checked')) $('img.electronicSeal').show();
    else $('img.electronicSeal').hide();
}

/**
 * 全表示リンク可能にする
 * @param client_id
 */
function ableIndexLink(client_id) {
    let span = $("span[name=showed-by-client]");
    var clientName= $('#client-' + client_id).find('a').html();
    if(clientName==undefined){
        clientName=init_val.data('re-client-name');
    }else{
        init_val.data('re-client-name',clientName);
    }
    span.html('/' + clientName+'<button class="btn-option btn btn-sm btn-success" style="margin-left: 3px;" onclick="returnInit();">戻る</button>');
    span.removeClass("invisible");
    $("a[name=index]").removeClass("pointer-events-none");
    lastClientID=client_id;
}

/**
 * 全表示リンク不能にする
 */
function disableIndexLink() {
    $("span[name=showed-by-client]").addClass("invisible");
    $("a[name=index]").addClass("pointer-events-none");
    lastClientID='';
}

/**
 * 口座情報表示初期化
 */
function initBankAccounts() {
    let select = 'select[name=bank_account_id]';
    $(document).on('change',select,function (ev) {
        let el = $(ev.target);
        if(el.val()==0){
            let bankAccounts = $('#bank-accounts');
            bankAccounts.find('tr').eq(2).find('td').html(ACCOUNT_NUM);
            bankAccounts.find('tr').eq(3).find('td').html(ACCOUNT_NAME);
        }else{
            $.ajax({
                url:el.data('route').replace(':id',el.val()),
                success:function (data) {
                    data = JSON.parse(data);
                    let bankAccounts = $('#bank-accounts');
                    bankAccounts.find('tr').eq(2).find('td').html(data.bank_account_type.account_type_name + '　' + data.account_num);
                    bankAccounts.find('tr').eq(3).find('td').html(data.account_name);
                },error:function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });
        }

    });
    $(select).trigger('change');
}

function bankInfoToArray(bankData){
    let bankArray={};
    $.each(bankData,function (key,value){
        let bankName = value.bank_name + '　' + value.branch_name + '　（' + value.branch_code + '）';
        let accountNum = value.bank_account_type.account_type_name + '　' +value.account_num;
        let accountName = value.account_name;
        let id = value.id;
        let temporaryMap = {};
        let temporaryMap2 = {};
        if(bankArray.hasOwnProperty(bankName)){
            if(bankArray[bankName].hasOwnProperty(accountNum)){
                temporaryMap = bankArray[bankName][accountNum];
                temporaryMap[accountName]=id;
                bankArray[bankName][accountNum]=temporaryMap;
            }else{
                temporaryMap2[accountName]=id;
                temporaryMap=bankArray[bankName];
                temporaryMap[accountNum]=temporaryMap2;
                bankArray[bankName]=temporaryMap;
            }
        }else{
            temporaryMap2[accountName]=id;
            temporaryMap[accountNum]=temporaryMap2;
            bankArray[bankName]=temporaryMap;
        }
    });
    return bankArray;
}

function bankInfoToSelect(bankArray,type,bankName="",accountNum="",accountName="",bankUse="") {
    let bankNameUse="";
    let accountNumUse="";
    let accountNameUse="";
    let selectHtml = '';
    let temporaryArray = [];
    let id = 0;
    if(bankUse!=""){
        bankNameUse = bankUse.bank_name + '　' + bankUse.branch_name + '　（' + bankUse.branch_code + '）';
        accountNumUse = bankUse.bank_account_type.account_type_name + '　' +bankUse.account_num;
        accountNameUse = bankUse.account_name;
        id = bankUse.id;
    }
    switch (type) {
        case 0:
            temporaryArray = arrayToSelect(bankArray,1,bankNameUse);
            bankName =temporaryArray[1];
            if(temporaryArray[2]>1){
                selectHtml = temporaryArray[0];
            }else{
                selectHtml = bankName;
            }
            $("#bank-accounts").find('td:eq(0)').html(selectHtml);
        case 1:
            temporaryArray = arrayToSelect(bankArray[bankName],2,accountNumUse);
            accountNum =temporaryArray[1];
            if(temporaryArray[2]>1){
                selectHtml = temporaryArray[0];
            }else {
                selectHtml = accountNum;
            }
            $("#bank-accounts").find('td:eq(1)').html(selectHtml);
        case 2:
            temporaryArray = arrayToSelect(bankArray[bankName][accountNum],3,accountNameUse);
            accountName = temporaryArray[1];

            if(temporaryArray[2]>1){
                selectHtml = temporaryArray[0];
            }else {
                selectHtml = accountName;
            }
            $("#bank-accounts").find('td:eq(2)').html(selectHtml);
        case 3:
            if(id==0){
                id = bankArray[bankName][accountNum][accountName];
            }
    }
    $("input[name=bank_account_id]").val(id);
}

function arrayToSelect(array,type,selectType) {
    let selectHtml = "<select class='select-focus w-100 h-100 select-align-center' onchange='bankInfoReplace("+type+")'>";
    let boo=true;
    let firstKey = '';
    let index = 0;
    for(let key in array){
        index++;
        if(boo){
            firstKey=key;
            boo=false;
        }
        if(selectType==key){
            selectHtml += "<option class='text-center' selected>"+key+"</option>";
            firstKey=key;
        }else{
            selectHtml += "<option class='text-center'>"+key+"</option>";
        }

    }

    selectHtml += "</select>";
    return [selectHtml,firstKey,index];
}
function bankInfoReplace(type) {
    let bankName="";
    let accountNum="";
    let accountName="";
    switch (type) {
        case 3:
            accountName = $("#bank-accounts").find('td:eq(2)').find('option:selected').html();
        case 2:
            if($("#bank-accounts td:eq(1)").find('select').length>0){
                accountNum = $("#bank-accounts").find('td:eq(1)').find('option:selected').html();
            }else{
                accountNum = $("#bank-accounts").find('td:eq(1)').html();
            }
        case 1:
            bankName = $("#bank-accounts").find('td:eq(0)').find('option:selected').html();
    }
    bankInfoToSelect(bankData,type,bankName,accountNum,accountName,'');
}

/**
 * 契約形態がその他の場合は入力可能にする
 * @param e
 */
function ableOther(e) {
    let input_other_remark = $(e).parents('.input-group').find('input');
    if ($(e).val() === INPUT_ABLE_CONTRACT_TYPE) {
        input_other_remark.prop("readonly", false);
        input_other_remark.val(other);
    } else {
        input_other_remark.prop("readonly", true);
        const temp = input_other_remark.val() === "";
        if (!temp)
            other = input_other_remark.val();
        input_other_remark.val("");
    }
}

/**
 * 新規と編集のダイアログ画面のロードが完成後の処理
 * @param href
 */
function completeHandle(href) {
    initSingleDatePicker(".datepicker");
}

/**
 * 請求書と見積書で行追加の説明文を表示　隐す
 * @param event
 */
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

function returnInit() {
    lastClientID='';
    initTable();
}
