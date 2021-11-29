const today2 = moment();
const currMonth = today2.format('M');
const currDay = today2.format('D');
const currYear = parseInt(today2.format('YYYY'));
let iconClickBoo = false;
initAjax();
// const yearArr = [];
function dateArr(i) {
    const year = {};
    year.value = i + '年';
    year.childs = [];
    for (let j = 0; j <= 11; j++) {
        today2.year(i);
        today2.month(j);
        const month = {};
        month.value = today2.format('MM') + '月';
        month.childs = [];
        const days = parseInt(today2.endOf('month').format('D'));
        for (let k = 1; k <= days; k++) {
            today2.date(k);
            const day = {};
            day.value = today2.format('DD') + '日';
            month.childs.push(day);
        }
        year.childs.push(month);
    }
    return year;
}
let dateTimeArr =[];
for (let i = currYear; i >= currYear - 100; i--) {
    dateTimeArr.push(dateArr(i));
}
for (let i = 0; i <dateTimeArr.length; i++) {
    let year =dateTimeArr[i];
    for (let j = 0; j < year.childs.length; j++) {
        let month =year.childs[j];
        for (let k = 0; k < month.childs.length; k++) {
            let day =month.childs[k];
            day.childs = [];
            for (let h = 9; h <= 18; h++){
                today2.hour(h);
                const hour = {};
                hour.value = today2.format('HH') + ':00';
                day.childs.push(hour);
            }
            dateTimeArr[i].childs[j].childs[k]=day;
        }
    }
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
    $('.file-name a').html($(e)[0].files[0].name).click(function () {
        previewFile(e);
    });
}
function previewFile(e) {
    const pre = $('#preview');
    var reads= new FileReader();
    f=$(e)[0].files[0];
    reads.readAsDataURL(f);
    reads.onload=function (e) {
        $('#preview img').attr('src',this.result);
        pre.modal('show');
    };
}

$('img').click(function (e) {
    const obj = e.target;
    const pre = $('#preview');
    if(pre.hasClass('show')){
        pre.modal('hide');
    }else{
        $('#preview img').attr('src',obj.src);
        pre.modal('show');
    }
});
if($('.attendance-selector').length>0){
    const today = moment();
    const itemArr = [];
    for (let i=0;i<=2;i++){
        itemArr.push(today.format('YYYY年MM月'));
        today.add(-1,'month');
    }
    const selectorInAttendance = new MobileSelect({
        trigger:'.attendance-selector',
        title:'勤務月選択',
        wheels:[{data:itemArr}],
        position:[1],
        transitionEnd:function (indexArr,data) {
            console.log(data+'aaa');
        },
        callback:function (indexArr,data) {
            $('.attendance-selector').next().val(data);
        },
    });
}

function dateSelector() {
    if($('.date-selector').length>0) {
        const yearArr = [];
        let selectDate = [];
        let dateVal = $('.date-selector').html().trim();
        if(dateVal!=""){
            let dateArr = dateVal.split('-');
            selectDate[0] = currYear-dateArr[0];
            selectDate[1] = dateArr[1]-1;
            selectDate[2] = dateArr[2]-1;
        }else{
            selectDate[0] = 0;
            selectDate[1] = currMonth-1;
            selectDate[2] = currDay-1;
        }
        // currYear-
        for (let i = currYear; i >= currYear - 100; i--) {
            yearArr.push(dateArr(i));
        }
        const selectorOnDate = new MobileSelect({
            trigger: '.date-selector',
            title: '日付選択',
            wheels: [{
                data: yearArr,
            }],
            position:[selectDate[0],selectDate[1],selectDate[2]],
            transitionEnd: function (indexArr, data) {
                console.log(data);
            },
            callback: function (indexArr, data) {
                let year = data[0].value.substring(0,data[0].value.length-1);
                let month = data[1].value.substring(0,data[1].value.length-1);
                let day = data[2].value.substring(0,data[2].value.length-1);
                let date = year+'-'+month+'-'+day;
                $('.date-selector').html(date);
                modalDateSelector(date,".date-selector");
            },
        });
    }
}

function deadDateSelect() {
    if($('.dead-date-select').length>0) {
        const yearArr = [];
        let selectDate = [];
        let dateVal = $('.dead-date-select').html().trim();
        if(dateVal!=""){
            let dateArr = dateVal.split('-');
            selectDate[0] = currYear-dateArr[0];
            selectDate[1] = dateArr[1]-1;
            selectDate[2] = dateArr[2]-1;
        }else{
            selectDate[0] = 0;
            selectDate[1] = currMonth-1;
            selectDate[2] = currDay-1;
        }
        // currYear-
        for (let i = currYear; i <= currYear + 10; i++) {
            yearArr.push(dateArr(i));
        }
        const selectorOnDate = new MobileSelect({
            trigger: '.dead-date-select',
            title: '日付選択',
            wheels: [{
                data: yearArr,
            }],
            position:[selectDate[0],selectDate[1],selectDate[2]],
            transitionEnd: function (indexArr, data) {
                console.log(data);
            },
            callback: function (indexArr, data) {
                let year = data[0].value.substring(0,data[0].value.length-1);
                let month = data[1].value.substring(0,data[1].value.length-1);
                let day = data[2].value.substring(0,data[2].value.length-1);
                let date = year+'-'+month+'-'+day;
                $('.dead-date-select').html(date);
                modalDateSelector(date,".dead-date-select");
            },
        });
    }
}
function familyNumSelect() {
    if($('.family-num-select').length>0) {
        const numArr = [];
        let selectNum = [];
        let dateVal = $('.family-num-select').html().trim();
        if(dateVal!=""){
            selectNum[0] = dateVal.substring(0,dateVal.length-1);
        }else{
            selectNum[0] = 0;
        }
        for (let i = 0; i <= 20; i++) {
            numArr.push(i + '人');
        }
        const selectorOnDate = new MobileSelect({
            trigger: '.family-num-select',
            title: '扶養家族',
            wheels: [{
                data: numArr,
            }],
            position:[selectNum[0]],
            transitionEnd: function (indexArr, data) {
                console.log(data);
            },
            callback: function (indexArr, data) {
                let date = data[0].substring(0,data[0].length-1);
                $('.family-num-select').html(data[0].value);
                modalDateSelector(date,".family-num-select");
            },
        });
    }
}

let init_val = $('input[name=init_val]');
const CURRENCY = init_val.data('currency-symbol');
const REG = new RegExp('[,'+CURRENCY+']','g');

/**
 * 金額入力欄がフォーカスを取る時の処理
 * @param e
 */
function onFocusAmount(e) {
    e.value = e.value.replace(REG, "");
    e.setSelectionRange(0, e.value.length);
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
 * 金額入力欄初期化
 */
$(document).on('focus', 'input.amount:not([readonly])', function () {
    onFocusAmount(this);
});
$(document).on('input', 'input.amount:not([readonly]):not(.minus)', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
});
$(document).on('input', 'input.minus:not([readonly])', function () {
    this.value = this.value.replace(/^([^\d-])*((?:-)?\d*).*$/g, '$2');
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
    this.value = this.value.replace(/^\D*(\d*(?:\.\d{0,2})?).*$/g, '$1');
});
$(document).on('blur', 'input.float', function () {
    this.value = this.value ===''?'':parseFloat(this.value);
});
const REG_TO_NUMBER = /[^0-9]/g;
const FLOAT_REG = /^\D*(\d*(?:\.\d{0,2})?).*$/g;
/**
 * 数字入力欄
 */
$(document).on('input', 'input.number', function () {
    this.value = this.value.replace(REG_TO_NUMBER, '');
});

function clearErr(el) {
    $(el).parent().find('.err-msg').html('');
}
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
 * 小数入力欄がフォーカスを取る時の処理
 * @param e
 */
function onFocusFloat(e) {
    e.setSelectionRange(0, e.value.length);
}
/**
 * 社員 showModal 前 赋值
 */
let employeeEditName = '';

function showModalCenter(e) {
    let title = $(e).data('title');
    let val = $(e).html().trim();
    $('.error-message').html('');
    employeeEditName = $(e).attr('name');
    $('#base-model-center .modal-title').html(title);
    $('#base-model-center .modal-value input').val(val);
    $('#base-model-center').modal('show');
}

function showModalSelect(e) {
    let title = $(e).data('title');
    let val =$(e).html().trim();
    let selectHtml = '';
    employeeEditName = $(e).attr('name');
    modalSelect.forEach(function (element,index) {
        if(val==element){
            selectHtml+='<span class="select-span" data-id="'+modalSelectId[index]+'" onclick="modalSelectSpan(this)">'+element+'</span>';
        }else{
            selectHtml += '<span data-id="'+modalSelectId[index]+'" onclick="modalSelectSpan(this)">'+element+'</span>';
        }
    });
    $('#base-model-select .modal-value').html(selectHtml);
    $('#base-model-select').modal('show');
}
function modalCenterSubmit() {
    let val = $("#base-model-center").find('input').val();
    let data = employeeEditName+"="+val+"&type="+type;
    employeeEdit(data,'center');
}
function modalSelectSpan(e){
    $('.select-span').removeClass('select-span');
    $(e).addClass('select-span');
}
function modalSelectSubmit() {
    let val = $('.select-span').html();
    let id =  $('.select-span').data('id');

    let data = employeeEditName+"="+id+"&type="+type;
    employeeEdit(data,val);
}
function modalDateSelector(date,select) {
    employeeEditName = $(select).attr('name');
    let data = employeeEditName+"="+date+"&type="+type;
    employeeEdit(data,'');
}
function employeeEdit(data,typeOrVal) {
    $.ajax({
        url:'/employee/saveEmployeeInfo',
        data: data,
        type:"post",
        success: function (response) {
            ajaxSuccessAction(response,function () {
                afterEmployeeEdit(response,typeOrVal);
            });
        },
        error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }
    });
}
function afterEmployeeEdit(response,typeOrVal){
    if(typeOrVal=='center'){
        let val = $("#base-model-center").find('input').val();
        $('td[name='+employeeEditName+']').html(val);
        $("#base-model-center").modal('hide');
    }else if(typeOrVal==''){

    }else{
        $('td[name='+employeeEditName+']').html(typeOrVal);
        $("#base-model-select").modal('hide');
    }
    modifiedRedJudge('td[name='+employeeEditName+']')
}
function modifiedRed() {
    $(".modified").each(function () {
        modifiedRedJudge(this);
    })
}
function modifiedRedJudge(e) {
    let modified='';
    if($(e).html()!=undefined){
        modified = $(e).html().trim();
    }
    let history='';
    if($(e).data("history")!=undefined){
        history=(''+$(e).data("history")).trim();
    }
    if(modified==history){
        $(e).removeClass("color-red");
    }else{
        $(e).addClass("color-red");
    }
}
function iconIsClick(boo) {
    $('#iconImg').attr('src','');
    $('#icon-model').modal('hide');
    if(boo){
        let data = new FormData($("#iconInfo").get(0));
        let imgData=$('#iconImg').cropper('getCropBoxData');
        let temp=$('#iconImg').cropper('getImageData');
        let canvas=$('#iconImg').cropper('getCanvasData');
        imgData.rate = temp.width/temp.naturalWidth;
        imgData.left = imgData.left - canvas.left;
        imgData.top = imgData.top - canvas.top;
        data.append('imgData',JSON.stringify(imgData));
        photoUploadPublicAjax('icon',data,true);
    }

}
/**
 * 头像 photo edit
 */
function editIcon(e){
    let data = new FormData($("#iconInfo").get(0));
    photoUploadPublicAjax('icon',data,e);
}

/**
 * photo show
 * @param event
 */
function showImg(event) {
    $('.error-message').html('');
    let rd = new FileReader();
    let files = event.files[0];
    rd.readAsDataURL(files);
    rd.onloadend = function() {
        $(event).parents('td').find('img').attr('src',this.result);
    };
}

/**
 * 在留卡photo upload
 */
function uploadCardFile(boo) {
    if(boo){
        let data = new FormData($("#cardInfo").get(0));
        if($('input[name=residence_card_front]').val()!=''){
            let rd = new FileReader();
            let files = $('input[name=residence_card_front]')[0].files[0];
            rd.readAsDataURL(files);
            rd.onloadend = function() {
                frontPhotoModify=this.result;
                // $('.residenceCardFront').attr('src',this.result);
            };
        }
        photoUploadPublicAjax('stay',data);
    }else{
        $('#residenceCardFront').attr('src',frontPhotoHistory);
        $('#residenceCardBack').attr('src',backPhotoHistory);
        $('#card-model-upload').modal('hide');
    }
    // $('#card-model-upload').modal('hide');
}
function photoUploadPublicAjax(type,data,iconEvent) {
    let url = '/employee/photoSave/'+type;
    $.ajax({
        url:url,
        type:"POST",
        contentType: false,//リクエストヘッダーを不要にする
        processData: false,//前処理を取り消す
        data: data,
        success:function(response){
            ajaxSuccessAction(response,function () {
                $('input[type=file]').val('');
                $('#card-model-upload').modal('hide');
                if(iconEvent!=undefined){
                    $('#mugshot').attr('src',response.icon);

                }else if(frontPhotoModify!=undefined && frontPhotoModify!=''){
                    frontPhotoHistory=frontPhotoModify;
                    $('.residenceCardFront').attr('src',frontPhotoHistory);
                    frontPhotoModify='';
                }
            })
        },
        error: function(jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error)
        }
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
        printErrorMsg('アップロードされたファイルのサイズは10M以下に制限してください！');
    } else {
        printErrorMsg('未知のエラーです。画面をリフレッシュしてください！');
    }
}
function ajaxSuccessAction(response,handle=function () {}) {
    if(response.status==='success'){
        handle(response);
    }else{
        printErrorMsg(response.message);
    }
}
/**
 * エラーメッセージを表示
 * @param msg
 */
function printErrorMsg(msg) {
    $('.error-message').html(msg);
}
function initAjax() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}
