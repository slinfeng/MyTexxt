Array.prototype.indexOf = function(val) {
    for (let i = 0; i < this.length; i++) {
        if (this[i] === val) return i;
    }
    return -1;
};
Array.prototype.remove = function(val){
    let index = this.indexOf(val);
    if(index>-1)
        this.splice(index,1);
}

/**
 * データテーブルセッティングの初期化
 */
function initTableSettingInfo(order=1,order_method='desc') {
    datatableErrorHandle();
    return {
        dom:"rtip",
        scrollCollapse: true,
        // scrollY:$("body").innerHeight()*0.65,
        paging: false,
        searching: true,
        serverSide: true,
        bAutoWidth: false,
        orderFixed: {
            "post": [[ columns.length, 'asc' ]]
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
            url: $(table_selector).data('route'),
            type: "post",
            dataType: "json",
            data: data_datatable,
            complete: function () {
                datatableComplete();
            }
        },
        columns:columns,
        columnDefs:columnDefs,
        order: [
            [order, order_method]
        ],
    };
}
function datatableErrorHandle() {
    $.fn.dataTable.ext.errMode = function( settings, tn, msg){
        printErrorMsg('未知のエラーです。画面をリフレッシュしてください！');
    }
}
function unSizeForFixedHeader() {
    $.fn.DataTable.FixedHeader.prototype._unsize = function () {
        console.log('un resize');
    };
}
function batchSelectOnDatatable() {
    tableSettingInfo.select = {
        style: 'multi',
        selector: 'td:first-child',
    };
    let shift_flag = false;
    let last_tr_index = 0;
    $(document).on("keydown",function (e) {
        if(e.keyCode===16){
            shift_flag = true;
        }
    });
    $(document).on("keyup",function (e) {
        if(e.keyCode===16){
            shift_flag = false;
        }
    });
    $(table_selector).on("click", "tr td.select-checkbox", function (e) {
        const obj = e.target;
        if(!shift_flag){
            last_tr_index = $(obj).parent().index();
        } else{
            let tr_index = $(obj).parent().index();
            let start = last_tr_index;
            let end = tr_index;
            if(tr_index<last_tr_index){
                start = end;
                end = last_tr_index;
            }
            $('tr.selected').each(function () {
                const index = $(this).index();
                if(index<start || index>end){
                    if(typeof whenNotSelected === "function") {
                        whenNotSelected(index,shift_flag);
                    }
                    $(table_selector).DataTable().row(index).deselect();
                }
            });
            for(let i=start;i<=end;i++){
                if(typeof whenSelected === "function") whenSelected(i,shift_flag);
                if(i!==tr_index)
                    $(table_selector).DataTable().row(i).select().data();
                else
                    $(table_selector).DataTable().row(i).deselect();
            }
        }
        whenClicked(obj,shift_flag);
    });
}
function allSelectOnDatatable(){
    $(table_selector+' thead th').first().attr('onclick','selectAll(this)');
}
function selectAll(e) {
    const tr = $(e).parent();
    let rows = $(table_selector).DataTable().rows();
    if(!tr.hasClass('selected')){
        if($(table_selector+' tbody tr[role=row]').length>0){
            if(typeof whenAllSelected === "function") {
                rows.select().data();
                tr.addClass('selected');
                whenAllSelected();
            }
        }
    }else{
        if(typeof whenAllNotSelected === "function"){
            rows.deselect();
            tr.removeClass('selected');
            whenAllNotSelected();
        }

    }
}
function createdHandleWhenFile(nTd,oData) {
    if(oData.file_id!==null) {
        $(nTd).find('a').data('file-id',oData.file.id).data('file-type',oData.file.type).data('file-path',oData.file.path);
    }
}
function createdHandleWhenEditLink(nTd,oData,route) {
    route = route.replace(':id',oData.id);
    $(nTd).find('a').data('href',route);
}
/**
 * 操作項目を表示
 * @param e
 * @param able
 */
function showOptions(e,able=false) {
    $("#options").removeClass("hide");
    if ($("tbody tr.selected").length === 1 && $(e).parent().hasClass('selected')) {
        $("#options span").addClass("invisible");
        $('thead tr').removeClass('selected');
    } else {
        const span = $("#options span");
        if (span.hasClass("invisible")) {
            span.removeClass("invisible");
        }
    }
    if(able){
        ableInput(e);
    }
}
/**
 * バッチアクション
 * @param selector
 * @param method
 */
function batchAction(selector,method='post') {
    const obj = lockAjax();
    let idArr = getIdArr();
    $.ajax({
        url: $(selector).data('route'),
        type: method,
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
}
let Istrue=false;
function key() {
    document.onkeydown = function (e) {
        if (Istrue) {
            if (e && e.keyCode === 13) {
                window.event.returnValue = false;
            }
            if (e && e.keyCode === 32) {
                window.event.returnValue = false;
            }
        }
    };
}
let index=0;
let indexSum=0;
let per = 0.0;
let sum = 0;
let start = 0;
let unit = 0.0;
let exclude_lock = false;
let leave;
let lastLength;
window.pdf_creator=undefined;
window.zip = undefined;
let printTransferListType = false;
function batchExclude() {
    printTransferListType=$('input[name=pdf_print]:eq(1)').is(":checked")

    if($('input[name=pdf_print]:eq(0)').is(":checked")){
        $('.point-p').show();
        if(window.zip==undefined){
            window.zip = new JSZip();
        }
        Istrue=true;
        if(!exclude_lock){
            $('iframe').remove();
            exclude_lock = true;
            $('.continue-btn').css('cursor','wait');
            setTimeout(function () {
                let idArr = getIdArr();
                leave = idArr.map((x) => x);
                if(index==0){
                    sum = idArr.length;
                    lastLength = sum;
                    start = 0;
                    unit = 100 / sum / 4;
                    per = 0.0;
                }
                const modal = $('#exclude');
                const exclude_route = EDIT_ROUTE + '?toPdf=true';
                const prog = modal.find('.progress-bar');
                let i=index+10;
                for (index;index < i;index++) {
                    if(idArr[index]!==undefined){
                        changeIframeSrc(idArr[index], exclude_route, prog);
                    }
                }
            },500);
        }
    }else if(printTransferListType){
        if(window.zip==undefined){
            window.zip = new JSZip();
        }
        let transferList = getPrintTransferList()
        let sheet = XLSX.utils.aoa_to_sheet(transferList);
        sheet['!merges'] = cnameArr;
        sheet["A5"].s = {
            font: {
                sz: 13,
                bold: true,
                color: {
                    rgb: "FFFFAA00"
                }
            },
            alignment: {
                horizontal: "center",
                vertical: "center",
                wrap_text: true
            }
        }

        sheet["!cols"] = [{
            wpx: 70
        }, {
            wpx: 200
        }, {
            wpx: 70
        }, {
            wpx: 500
        }];
        window.zip.file("振込一覧.xlsx", sheet2blob(sheet),{base64:true});
        window.zip.generateAsync({type:"blob"}).then(function(content) {
            saveAs(content, "出力結果.zip");
        });
        $('#exclude').modal('hide');
    }
}

let excludeListener = async function() {
    if(leave.length>0){
        await judgeExcludeStatus();
    }
}


function getFormatDate(){
    let nowDate = new Date();
    let year = nowDate.getFullYear();
    let month = nowDate.getMonth() + 1 < 10 ? "0" + (nowDate.getMonth() + 1) : nowDate.getMonth() + 1;
    let date = nowDate.getDate() < 10 ? "0" + nowDate.getDate() : nowDate.getDate();
    let hour = nowDate.getHours()< 10 ? "0" + nowDate.getHours() : nowDate.getHours();
    let minute = nowDate.getMinutes()< 10 ? "0" + nowDate.getMinutes() : nowDate.getMinutes();
    // let second = nowDate.getSeconds()< 10 ? "0" + nowDate.getSeconds() : nowDate.getSeconds();
    return year + "年" + month + "月" + date+"日　"+hour+":"+minute;
}

function getPrintListText() {
    let listText = [
        ['出力結果報告書',null,null,null],
        [getFormatDate(),null,null,null],
        ['選択された資料の出力結果は以下となります。未作成分は別途ダウロード、管理してください。',null,null,null],
        ['＜作成分＞',null,null,null],
        ['No.','番号','業務名/ファイル名','取引先'],
    ]
    let printHtml = [];
    let notPrintHtml = [];
    let index = 1;
    let indexNot = 1
    $('tbody tr.selected').each(function () {
        if($(this).find('td:nth-of-type('+printTd[1]+')').find('a').length>0){
            let manage_code=$(this).find('td:nth-of-type('+printTd[0]+')').find('a').html();
            let name = $(this).find('td:nth-of-type('+printTd[1]+')').find('a').html();
            let cname=$(this).find('td:nth-of-type('+printTd[2]+')').find('a').html();
            name=name.replace(/&amp;/g,'&');
            cname=cname.replace(/&amp;/g,'&');
            notPrintHtml.push([indexNot,manage_code,name,cname])
            indexNot++;
        }else{
            let manage_code=$(this).find('td:nth-of-type('+printTd[0]+')').find('a').html();
            let name = $(this).find('td:nth-of-type('+printTd[1]+')').html();
            let cname=$(this).find('td:nth-of-type('+printTd[2]+')').find('a').html();
            name=name.replace(/&amp;/g,'&');
            cname=cname.replace(/&amp;/g,'&');
            printHtml.push([indexNot,manage_code,name,cname])
            index++;
        }
    })
    listText=listText.concat(printHtml);
    listText.push(['＜未作成分＞',null,null,null],);
    listText.push(['No.','番号','業務名/ファイル名','取引先']);
    listText=listText.concat(notPrintHtml);
    return listText;
}
let cnameArr=[{ s: {r: 0, c: 0}, e: {r: 0, c: 3} }];
function getPrintTransferList() {
    let transferList = [
        ['振込一覧',null,null,null],
        ['振込日','会社名','金額','口座情報']
    ]
    let last_cname = ''
    let begin_index=1;
    let end_index = 1;
    $('tbody tr.selected').each(function () {
        let pay_deadline = $(this).find('td:eq(6)').html();
        let clint_name = $(this).find('td:eq(4) a').html();
        let invoice_total = $(this).find('td:eq(5) span').html();
        let bank_info = $(this).find('td:eq(7) input').data('bank');
        clint_name=clint_name.replace(/&amp;/g,'&');
        invoice_total=invoice_total.replace(/&amp;/g,'&');
        bank_info=bank_info.replace(/&amp;/g,'&');

        if(last_cname==clint_name){
            end_index++
        }else{
            last_cname=clint_name;
            cnameArr.push({ s: {r: begin_index, c: 1}, e: {r: end_index, c: 1} });
            end_index++
            begin_index=end_index;
        }
        transferList.push([pay_deadline,clint_name,invoice_total,bank_info]);
    });
    cnameArr.push({ s: {r: begin_index, c: 1}, e: {r: end_index, c: 1} });

    return transferList;
}

function judgeExcludeStatus() {
    return new Promise((resolve) => {
        setTimeout(function () {
            if(leave.length!==0 && lastLength===leave.length){
                const iframe = $('#iframe-exclude'+leave[0])[0];
                const iframeLast = $('#iframe-exclude'+leave[leave.length])[0];
                excludeListener().then(r => {});
                if(iframe!== undefined && iframe.contentWindow.document.head.innerHTML.indexOf('500')>=0
                && iframeLast.contentWindow.document.head.innerHTML.indexOf('500')>=0){
                    reloadExclude(leave);
                }
            }else if(leave.length===0){
                resolve();
            }else{
                excludeListener().then(r => {});
            }
            lastLength = leave.length;
        },10000);
    });
}

function reloadExclude(idArr) {
    for(let i=0;i<idArr.length;i++){
        const modal = $('#exclude');
        const exclude_route = EDIT_ROUTE+'?toPdf=true';
        const prog = modal.find('.progress-bar');
        changeIframeSrc(idArr[i],exclude_route,prog);
    }
}

function changeIframeSrc(id,exclude_route,prog) {
    if($('input[name=id][value='+id+']').parent().parent().find('td').eq(3).find('a').length>0){
        leave.remove(id);
        updateProgForLoadCanvasToPdf();
    }else{
        const iframe = createIframe(id);
        $(iframe).attr('src',exclude_route.replace(':id',id)+'&_='+new Date().getTime());
        if(iframe.attachEvent){
            iframe.attachEvent("onreadystatechange", function() {
                updateProgForLoaded(id,prog,iframe);
            });
        }else{
            iframe.addEventListener("load", function() {
                updateProgForLoaded(id,prog,iframe);
            }, false);
        }
    }
}
function updateProgForLoaded(id,prog,iframe) {
    if(iframe.contentWindow.document.head.getElementsByTagName('title')[0].innerHTML.indexOf('500')>=0){
        iframe.contentWindow.location.reload();
    }else{
        leave.remove(id);
        per += unit;
        prog.width(per+'%').attr('data-original-title',per+'%');
    }
}
function updateProgForLoadCanvasToPdf(pdf_creator,pdf_name) {

    const modal = $('#exclude');
    const prog = modal.find('.progress-bar');
    if(pdf_creator===undefined){
        pdf_creator = window.pdf_creator;
    }else{
        let test1 = pdf_creator.output('blob','pdfName');
        window.zip.file(pdf_name+".pdf", test1, {binary: true})
        window.pdf_creator=pdf_creator;
    }
    indexSum++;
    if(indexSum==10){
        indexSum=0;
        exclude_lock = false;
        batchExclude();
    }
    per += 3*unit;
    start += 1;
    prog.width(per+'%').attr('data-original-title',per+'%');
    if(start===sum){
        setTimeout(function (){
            if(pdf_creator===undefined) clearExclude(modal,prog);
            else{
                printList();
                per = 100;
            }
        },1500)
    }
}
function sheet2blob(sheet, sheetName) {
    sheetName = sheetName || 'sheet1';
    var workbook = {
        SheetNames: [sheetName],
        Sheets: {}
    };
    workbook.Sheets[sheetName] = sheet;
    // 生成excel的配置项
    var wopts = {
        bookType: 'xlsx', // 要生成的文件类型
        bookSST: false, // 是否生成Shared String Table，官方解释是，如果开启生成速度会下降，但在低版本IOS设备上有更好的兼容性
        type: 'binary'
    };
    var wbout = XLSX.write(workbook, wopts);
    var blob = new Blob([s2ab(wbout)], {type:"application/octet-stream"});
    // 字符串转ArrayBuffer
    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i=0; i!=s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
        return buf;
    }
    return blob;
}
function printList() {
    let printText = getPrintListText();
    let printTextSheet = XLSX.utils.aoa_to_sheet(printText);
    printTextSheet['!merges'] = [
        {
            s: {r: 0, c: 0}, e: {r: 0, c: 4},
        }
    ];
    printTextSheet["!cols"] = [{
        wpx: 40
    }, {
        wpx: 100
    }, {
        wpx: 200
    }, {
        wpx: 200
    }];

    window.zip.file("出力結果報告書.xlsx", sheet2blob(printTextSheet),{base64:true});
    if(printTransferListType){
        let transferList = getPrintTransferList()
        let sheet = XLSX.utils.aoa_to_sheet(transferList);
        sheet['!merges'] = cnameArr;
        sheet["!cols"] = [{
            wpx: 70
        }, {
            wpx: 200
        }, {
            wpx: 70
        }, {
            wpx: 500
        }];
        window.zip.file("振込一覧.xlsx", sheet2blob(sheet),{base64:true});
    }
    window.zip.generateAsync({type:"blob"}).then(function(content) {
            saveAs(content, "出力結果.zip");
        });

    $('#printList').remove();
    const modal = $('#exclude');
    const prog = modal.find('.progress-bar');
    prog.width(per+'%').attr('data-original-title',per+'%');
    setTimeout(function () {
        window.zip = undefined;
        indexSum=0;
        index=0;
        $('.point-p').hide();
        clearExclude(modal,prog);
    },1500);
}
function clearExclude(modal,prog){
    modal.modal('hide');
    window.pdf_creator = undefined;
    $(table_selector).DataTable().rows().deselect();
    exclude_lock = false;
    $('.continue-btn').css('cursor','pointer');
    prog.width('0%').data('original-title','0%');
    $('thead tr.selected').removeClass('selected');
    $("#options span").addClass("invisible");
    if(typeof hideInput === "function") hideInput();
    Istrue=false;
}

function pdfCancel() {
    const modal = $('#exclude');
    const prog = modal.find('.progress-bar');
    exclude_lock = false;
    $('.continue-btn').css('cursor','pointer');
    $('iframe').remove();
    prog.width('0%').data('original-title','0%');
    window.zip = undefined;
    indexSum=0;
    index=0;
    $('.point-p').hide();
    Istrue=false;
    throw false;
}
function getIdArr(){
    let idArr = [];
    $('tbody tr.selected').find("input[name='id']").each(function () {
        idArr.push($(this).val());
    });
    return idArr;
}
function createIframe(id) {
    const iframeID = 'iframe-exclude'+id;
    const iframeStyle = 'border:0;position:absolute;width:0;height:0;right:0px;top:0px;';
    let iframe;
    try
    {
        iframe = document.createElement('iframe');
        document.body.appendChild(iframe);
        $(iframe).attr({ style: iframeStyle, id: iframeID});
        iframe.doc = null;
        iframe.doc = iframe.contentDocument ? iframe.contentDocument : ( iframe.contentWindow ? iframe.contentWindow.document : iframe.document);
    }
    catch( e ) { throw e + "ブラウザにアイフレームのサポーターが必要です。"; }
    return iframe;
}

/**
 * サイドバーが狭まる時とブラウザがリサイズするときに
 テーブルヘッダーの処理
 */
function adjustSidebarForFixedHeader() {
    $('#toggle_btn').on('click', function () {
        adjustHeader();
    });
    window.onresize = function () {
        adjustHeader();
    };
    let hasHeader = false;
    $(window).scroll(function(event){
        if($('table.fixedHeader-floating').length>0){
            if(!hasHeader){
                hasHeader = true;
                matchWidth();
            }
        }else{
            if(hasHeader){
                hasHeader=false;
            }
        }
    });
}
/**
 * テーブルヘッダー部を調整
 */
function adjustHeader() {
    setTimeout(function () {
        matchWidth();
    }, 600);
}
function matchWidth() {
    $(table_selector).DataTable().fixedHeader.adjust();
    $('table.fixedHeader-floating th').each(function (index) {
        $(this).width(widthArr[index]);
    });
    $(table_selector + ' th').each(function (index) {
        $(this).width(widthArr[index]);
    });
}


/*
 date range picker
 */
const DATE_FORMAT_A = 'YYYY-MM-DD';
/**
 * 日付セレクターの初期化
 * @param e
 * @param handler
 * @param format
 * @param initial
 */
function initSingleDatePicker(e, handler=function () {},format = DATE_FORMAT_A, initial = false,isToday = true) {
    let pickerInfo = {
        singleDatePicker: true,
        autoUpdateInput: false,
        showDropdowns: true,
        drops:'auto',
        locale: {
            format: format,
            daysOfWeek: ['日', '月', '火', '水', '木', '金', '土'],
            monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
                '七月', '八月', '九月', '十月', '十一月', '十二月'],
            cancelLabel: 'キャンセル',
            applyLabel: '設定',
        }
    };
    appendedFuncOnSingleDatepicker(e,pickerInfo,initial,format,isToday);
    $(e).daterangepicker(pickerInfo,function (start) {
        $(e).val(start.toString());
    }).on('hide.daterangepicker', function (ev, picker) {
        if($(this).val().trim()!=='')
            $(this).val(picker.startDate.format(format));
        handler();
    }).on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format(format));
    });
}
function appendedFuncOnSingleDatepicker(e,pickerInfo,initial) {}
function getSettingWhenIsPeriodPicker() {
    return {
        opens: 'right',
        autoUpdateInput: false,
        showDropdowns: true,
        drops:'auto',
        locale: {
            format: "YYYY-MM-DD",
            separator: "～",
            cancelLabel: "キャンセル",
            applyLabel: "設定",
            customRangeLabel: "カスタム範囲",
            daysOfWeek: ['日', '月', '火', '水', '木', '金', '土'],
            monthNames: ['一月', '二月', '三月', '四月', '五月',
                '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        },
        alwaysShowCalendars: true,
        linkedCalendars: false,
        ranges: {
            '前々月': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
            '前月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            '当月': [moment().startOf('month'), moment().endOf('month')],
            '翌月': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
        },
    };
}
/**
 * テキストエリアをクリックすると、日付セレクターを表示
 * @param e
 */
function showPicker(e) {
    $(e).parent().next('input').trigger('click');
}
function whenLinkedWithTextarea(e) {
    let isEmpty = true;
    let isOutsideClick = false;
    $(e).prev().find('textarea').on('blur', function (event) {
        if (isOutsideClick) {
            let input = $(event.target).parent().next('input');
            input.data('daterangepicker').updateElement();
            input.data('daterangepicker').hide();
            isOutsideClick = false;
        }
    });
    $(e).data('daterangepicker').hide = function () {
        if (!$(e).prev().find('textarea').is(':focus')) {
            this.isShowing && (this.endDate || (this.startDate = this.oldStartDate.clone(), this.endDate = this.oldEndDate.clone()), this.startDate.isSame(this.oldStartDate) && this.endDate.isSame(this.oldEndDate) || this.callback(this.startDate.clone(), this.endDate.clone(), this.chosenLabel), this.updateElement(), $(document).off(".daterangepicker"), $(window).off(".daterangepicker"), this.container.hide(), this.element.trigger("hide.daterangepicker", this), this.isShowing = !1)
        }
    };
    $(e).on('hide.daterangepicker', function (ev, picker) {
        let textarea = $(this).prev().find('textarea');
        if (!isEmpty) {
            textarea.val(picker.startDate.format(DATE_FORMAT_A) + '～' + picker.endDate.format(DATE_FORMAT_A));
            $(this).val(picker.startDate.format(DATE_FORMAT_A) + '～' + picker.endDate.format(DATE_FORMAT_A));
        }
        init_val.data('re-period',$(this).val());
    });
    $(e).data('daterangepicker').clickDate = function () {
        isEmpty = false;
    };
    let outsideClickFunc = $(e).data('daterangepicker').outsideClick;
    $(e).data('daterangepicker').outsideClick = function (event) {
        let el = $(event.target);
        let changeCalendar = el.parents('div.daterangepicker').length > 0;
        if (changeCalendar) isEmpty = false;
        if (!el.is($(e).prev().find('textarea')) && !changeCalendar
            && $(e).prev().find('textarea').is(':focus')) isOutsideClick = true;
        outsideClickFunc.call($(e).data('daterangepicker'), event);
    };
    $(e).data('daterangepicker').container.find('.drp-calendar').on('mousedown.daterangepicker', "td.available", $.proxy($(e).data('daterangepicker').clickDate, $(e).data('daterangepicker')));
    $(document).on('input', 'textarea[name=employee_period_picker]', function (event) {
        let input = $(event.target).parent().next('input');
        let period = $(this).val();
        input.val(period);
        input.trigger('keyup');
        isEmpty = period.trim() === "";
    });
}


/*
 file preview
 */
const FILE_OPEN_ABLE = ['png', 'jpg', 'jpeg', 'pdf','svg'];
/**
 * ファイルのプレビュー処理
 * @param id
 * @param type
 */
function openFile(id, type) {
    if (FILE_OPEN_ABLE.includes(type)) {
        window.open(init_val.data('file-preview').replace(':file_id', id));
    } else {
        getFileStatus(id);
    }
}

function getFileStatus(id){
    $.ajax({
        url:init_val.data('file-download').replace(':file_id', id),
        data:{request:'exist'},
        success:function (res){
            if(res==='notfound'){
                printErrorMsg('ファイルが見つかりませんでした。');
            }else if(res==='existed'){
                window.location.href = init_val.data('file-download').replace(':file_id', id);
            }else{
                testForLocal();
                getFileFromLocal(res,id);
            }
        },error:function (jqXHR,testStatus,error){
            ajaxErrorAction(jqXHR,testStatus,error);
        }
    });
}

function getFileFromLocal(path,id){
    let action = 'http://'+ init_val.data('save-ipaddr')+'/exist/index.php';
    $.ajax({
        url: action,
        type: "post",
        data: {path:path},
        success: function (res) {
            if (res==='existed'){
                window.location.href = init_val.data('file-download').replace(':file_id', id);
            }else if(res==='notfound'){
                printErrorMsg('ファイルが見つかりませんでした。');
                throw false;
            }
        },
        error: function (jqXHR, testStatus, error) {
            if (jqXHR.status === 0) {
                printErrorMsg('ローカルサーバーへの接続が失敗しました！');
                localAccessFlag = false;
            }
        },
    });
}

function openFileFromData(e) {
    const obj = $(e);
    openFile(obj.data('file-id'),obj.data('file-type'));
}


/*
 ajax submit and check
 */
/**
 * AjaxのToken検証の初期化
 */
function initAjaxSetup() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

let localAccessFlag = false;
/**
 * Ajaxでフォームをサブミット
 * @param selector
 * @param successHandle
 * @param hasFile
 */
function ajaxSubmitForm(selector, successHandle, hasFile = false) {
    const obj = lockAjax();
    if(hasFile && CLOUD_REQUEST_PERIOD==0) testForLocal(obj);
    setTimeout(function (){
        if(hasFile && CLOUD_REQUEST_PERIOD==0){
            if(localAccessFlag===false) {
                removeCommonInput(selector);
                throw false;
            }
        }
        let action = $(selector).attr('action');
        let ajaxSetting = {
            url: action,
            type: "post",
            success: function (res) {
                ajaxSuccessAction(res,function (res) {
                    if(hasFile && !ajaxSubmitFile(ajaxSetting.data)){
                        setTimeout(function (){
                            successHandle()
                        },2000);
                    }else{
                        successHandle();
                    }
                });
            },
            error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            },complete:function (event) {
                removeCommonInput(selector);
                unlockAjaxWhenFail(obj,event);
            }
        };
        if (hasFile) {
            let formData = new FormData($(selector).get(0));
            ajaxSetting.data = formData;
            ajaxSetting.contentType = false;
            ajaxSetting.processData = false;
        } else {
            ajaxSetting.data = $(selector).serialize();
        }
        $.ajax(ajaxSetting);
    },800);
}

function testForLocal(obj){
    localAccessFlag = false;
    let action = 'http://'+init_val.data('save-ipaddr')+'/access/index.php';
    $.ajax({
        url: action,
        type: "get",
        success: function (res) {
            localAccessFlag = true;
        },
        error: function (jqXHR, testStatus, error) {
            if (jqXHR.status === 0) {
                printErrorMsg('ローカルサーバーへの接続が失敗しました！');
                unlockAjax(obj);
                localAccessFlag = false;
            }
        },
        timeout: 200
    });
}

/**
 * Ajaxでファイルをサブミット
 * @param formData
 */
function ajaxSubmitFile(formData) {
    return true;
}

/**
 * jQueryセレクターによって、空文字チェック
 * @param selector
 * @param msg
 */
function checkEmpty(selector, msg) {
    if ($(selector).val().trim() === "") {
        $.notify(msg, {
            'type': 'danger',
        });
        throw false;
    }
}
/**
 * 金額入力欄がゼロかのチェック
 * @param selector
 * @param msg
 */
function checkZeroOnAmount(selector, msg) {
    if ($(selector).val() === CURRENCY+'0') {
        $.notify(msg, {
            'type': 'danger',
        });
        throw false;
    }
}


/*
 other
 */
/**
 * ブラウザを一定の程度に狭める時にヒントの表示
 */
function changeBrowserSize() {
    let bw = window.outerWidth;
    // let bh = window.outerHeight;
    if (bw < 1024) {
        let adjuster = $('#screen_size_adjust');
        let modal = adjuster.clone();
        let body = $('body');
        body.empty();
        body.append(modal);
        body.css('overflow', 'hidden');
        modal.modal('show');
    }else {
        if ($('div.content').length === 0 && $('body.print').length === 0) location.reload();
    }
    setTimeout(function () {
        initTextarea();
    },100);
}
/**
 * 全テキストエリアの高さを調整する
 */
function initTextarea() {
    $("textarea").each(function () {
        changeLength(this);
    });
}
/**
 * テキストエリアを入力ながら、高さを自動更新
 * @param e
 */
function changeLength(e) {
    $(e).height('auto');
    $(e).height(e.scrollHeight - 4);
}
/**
 * ファイル選択ダイアログを表示
 * @param e
 */
function uploadFile(e) {
    $(e).next('input').click();
}
/**
 * 選択されたファイル名を表示
 * @param e
 */
function showFileName(e) {
    $('span.files-info').html($(e)[0].files[0].name);
}

/**
 * アップロードされたファイルを読みだし
 * @param event
 */
function showImg(event) {
    let rd = new FileReader();//创建文件读取对象
    let files = event.files[0];//获取file组件中的文件
    rd.readAsDataURL(files);//文件读取装换为base64类型
    rd.onloadend = function(e) {
        //加载完毕之后获取结果赋值给img
        $(event).parents('td').find('img').attr('src',this.result);
    }
}

/*
 dialog model
 */
/**
 * ダイアログ画面表示
 * @param el
 */
function showModal(el) {
    let url = $(el).data('href');
    $.ajax({
        url: url,
        beforeSend: function () {
            $('#loader').show();
        },
        success: function (result) {
            $('#dialog-modal').modal("show");
            $('#dialog-modal-content').html(result).show();
        },
        complete: function () {
            $('#loader').hide();
            completeHandle(url);
        },
        error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        },
        timeout: 8000
    })
}
/**
 * 削除のダイアログ画面を表示
 * @param id
 */
function showDelModel(id) {
    let delete_tag = $('#delete');
    let route = delete_tag.data('route').replace(/_id/g,id);
    delete_tag.append('<span id="temp-for-route" hidden>'+route+'</span>');
    delete_tag.data('route',);
    delete_tag.data('restore-id',id);
    delete_tag.modal('show');
}
/**
 * エラーメッセージを表示
 * @param msg
 */
function printErrorMsg(msg) {
    $.notify({
        message: msg
    }, {
        type: 'danger'
    });
}

function ajaxErrorAction(jqXHR, testStatus, error) {
    if (jqXHR.status === 419)
        printErrorMsg('画面はタイムアウトしました。リフレッシュしてください！');
    else if (jqXHR.status === 201) {
        printErrorMsg('未知のエラーです。画面をリフレッシュしてください！');
    } else if (jqXHR.status === 200) {
        printErrorMsg('未知のエラーです。画面をリフレッシュしてください！');
    } else if (jqXHR.status === 403) {
        printErrorMsg('権限不足です。アクセスが禁じられています！');
    } else if (jqXHR.status === 413) {
        printErrorMsg('アップロードされたファイルのサイズは4M以下に制限してください！');
    } else {
        printErrorMsg('未知のエラーです。画面をリフレッシュしてください！');
    }
}

function ajaxSuccessAction(response,handle=function () {}) {
    if(response.status==='success'){
        if(response.message!=''){
            handle(response);
            $.notify(response.message);
        }
    }else{
        printErrorMsg(response.message);
    }
}

/**
 * 新規と編集の保存
 * @param hasFile
 */
function saveAddOrEdit(hasFile=false) {
    const obj = lockAjax();
    if(hasFile && CLOUD_REQUEST_PERIOD==0) testForLocal(obj);
    setTimeout(function (){
        if(localAccessFlag===false && hasFile) throw false;
        let form = $('#dialog-modal').find('form');
        let url = form.data('route');
        let ajaxSetting = {
            url: url,
            type: "POST",
            data: form.serialize(),
            success: function (response) {
                ajaxSuccessAction(response,function (response) {
                    if(hasFile) ajaxSubmitFile(ajaxSetting.data);
                    $('#dialog-modal').modal('hide');
                    $(table_selector).DataTable().draw();
                    successHandleWhenModify();
                });
            },
            error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            },complete:function () {
                unlockAjax(obj);
            }
        };
        if (hasFile) {
            if(window.location.href.indexOf('confirmations')>0){
                let input_folder = $('<input name="folder" type="text" hidden value="'+FOLDER+'">');
                form.append(input_folder);
                const temp = $('select[name=client_id] option:selected').html();
                $('input[name=cname]').val(temp.substr(temp.indexOf('）')+1,temp.indexOf('（',temp.indexOf('）'))-temp.indexOf('）')-1));
                let input_init_code = $('<input name="init_code" type="text" hidden value="'+init_val_manage_code+'">');
                form.append(input_init_code);
            }
            const formData = new FormData(form.get(0));
            ajaxSetting.data = formData;
            ajaxSetting.contentType = false;
            ajaxSetting.processData = false;
        }else{
            ajaxSetting.data = form.serialize();
        }
        $.ajax(ajaxSetting);
    },300);
}
function successHandleWhenModify() {}
/**
 * 削除処理
 */
function modelDelFunc() {
    const obj = lockAjax();
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
}
function successHandleWhenDel() {}


/*
 when special input is initiated
 */
const SELECTOR_INIT_VAL = 'input[name=init_val]';
let init_val = $(SELECTOR_INIT_VAL);
const CURRENCY = init_val.data('currency-symbol');
const REG = new RegExp('[,'+CURRENCY+']','g');
const REG_TO_NUMBER = /[^0-9]/g;
const REG_TO_NUMBER_WITH_MINUS = /[^0-9-]/g;
//$n有值时才会发生替换
//通配正则，将满足条件的值缓存替换掉整值
const REG_TO_MINUS_NUMBER = /^([^\d-])*((?:-)?\d*).*$/g;
const FLOAT_REG = /^\D*(\d*(?:\.\d{0,2})?).*$/g;
function initInputSpecial(){
    /**
     * 金額入力欄初期化
     */
    $(document).on('focus', 'input.amount:not([readonly])', function () {
        onFocusAmount(this);
    });
    $(document).on('input', 'input.amount:not([readonly]):not(.minus)', function () {
        this.value = this.value.replace(REG_TO_NUMBER, '');
    });
    $(document).on('input', 'input.minus:not([readonly])', function () {
        this.value = this.value.replace(REG_TO_MINUS_NUMBER, '$2');
    });
    $(document).on('blur', 'input.amount', function () {
        markCheckOnAmount(this);
    });
    /**
     * 小数入力欄初期化
     */
    $(document).on('focus', 'input.float:not([readonly])', function () {
        onFocusFloat(this);
    });
    $(document).on('input', 'input.float:not([readonly])', function () {
        this.value = this.value.replace(FLOAT_REG, '$1');
    });
    $(document).on('blur', 'input.float', function () {
        this.value = this.value ===''?'':parseFloat(this.value);
    });
    /**
     * 数字入力欄
     */
    $(document).on('input', 'input.number', function () {
        this.value = this.value.replace(REG_TO_NUMBER, '');
    });
}
/**
 * 金額の入力欄で自動的にカンマと¥を追加する
 * @param e
 */
function markCheckOnAmount(e) {
    let value = e.value.replace(REG, '');
    $(e).val(numberToAmount(value));
}
/**
 * 数字から金額に変換
 * @param val
 * @returns {string}
 */
function numberToAmount(val) {
    val = new Intl.NumberFormat().format(val);
    return CURRENCY + val;
}
/**
 * 数字に変換
 * @param val
 * @returns {string}
 */
function toNumber(val) {
    return val.replace(REG_TO_NUMBER, "");
}
/**
 * 数字に変換
 * @param val
 * @returns {string}
 */
function toNumberWithMinus(val) {
    return val.replace(REG_TO_NUMBER_WITH_MINUS, "");
}
/**
 * 金額入力欄がフォーカスを取る時の処理
 * @param e
 */
function onFocusAmount(e) {
    e.value = e.value.replace(REG, "");
    e.setSelectionRange(0, e.value.length);
}
/**
 * 小数入力欄がフォーカスを取る時の処理
 * @param e
 */
function onFocusFloat(e) {
    e.setSelectionRange(0, e.value.length);
}

function lockAjax() {
    const obj = event.target;
    $(obj).data('click',$(obj).attr('onclick'));
    $(obj).removeAttr('onclick');
    $(obj).css('cursor','wait');
    return obj;
}

function unlockAjax(obj) {
    $(obj).each(function () {
        $(this).css('cursor','pointer');
        $(this).attr('onclick',$(this).data('click'));
    });
}

function unlockAjaxWhenFail(obj,event) {
    if(event.responseJSON.status !== 'success' || window.location.href.indexOf('edit')>=0){
        unlockAjax(obj);
    }
}

initAjaxSetup();
initInputSpecial();
changeBrowserSize();

/**
 * メッセージ初期化
 */
$.notifyDefaults({
    placement: {
        from: "top",
        align: "right"
    },
    animate: {
        enter: "animated fadeInUp",
        exit: "animated fadeOutDown"
    },
    offset: {
        x: 17,
        y: 60,
    },
    delay: 1000,
    timer: 1000,
});
$(window).resize(function() {
    changeBrowserSize();
});
function showHeadButton() {
    $(window).scroll(function(event){
        if($(document).scrollTop()>100){
            if($('.headButton').length==0){
                $('.filter-row').after($('.filter-row').clone());
                $('.filter-row:eq(0)').addClass('headButton');
            }
            let width = $(table_selector).innerWidth()+20
            $('.headButton').css({'width':width});
        }else{
            if($('.headButton').length>0){
                $('.filter-row').last().remove();
                // $('.fixedHeader-floating').remove();
                $('.headButton').removeClass('headButton');
            }
        }
    });
}
