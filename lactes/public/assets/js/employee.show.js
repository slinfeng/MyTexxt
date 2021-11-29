let modifyBoo = false;
let submitBoo = true;
let relationType = 0;
let iconSrc='';
let frontSrc='';
let backSrc='';
let insuranceCheckbox=[];
let adminObj = '';
let iconData = '';
let iconReCoverType = false;
let iconFileType = 1;
$(document).ready(function() {
    $(document).on('mouseover', '.adminButton', function () {
        let modifyHtml = $(this).data('over');
        $(this).html(modifyHtml);
        $(this).css('color','white')
    });
    $(document).on('mouseout', '.adminButton', function () {
        let modifyHtml = $(this).data('out');
        $(this).html(modifyHtml);
    });
    $('#delete').on('hide.bs.modal',function () {
        $('.alert-danger').css('right','0');
    });
    allDateCalculation();
    dateTimePicker();
    checkboxInsurance();
    if(modified_type==0){
        $("#adminConfirm").hide();
        $("#adminModify").show();
        $(".history").hide();
        $(".date-history").hide();
        $(".date-history+.date-japan").hide();
    }else{
        $("#adminConfirm").show();
        $("#adminModify").hide();
        showModify();
    }
    checkboxInsuranceHide();
    $('#adminConfirmCoverage').on('hide.bs.modal',function(){
        unlockAjax(adminObj);
    });
    $('.cutIconShow').height($('.cutIconShow').width());
    $('#icon-model').on('hide.bs.modal',function(){
        if(!iconReCoverType){
            $("#iconImg").cropper('clear')
            $("#iconImg").cropper('replace',iconSrc)
            $("input[name=icon]").val('');
        }
    });
});
window.onload=function () {
    tableHeightSet();
    iconSrc = $("#iconImg").attr('src');
    frontSrc = $("#residenceCardFront").attr('src');
    backSrc = $("#residenceCardBack").attr('src');
    $("#iconImg").cropper({
        preview:".cutIconShow",
        autoCrop:true,
        autoCropArea:100,
        aspectRatio:1,
        viewMode:1,
        minContainerWidth:440,
        minContainerHeight:440,
        naturalWidth:440,
        crop:function (e) {
            console.log(e)
        }
    });

};
/**
 * 時間を計算
 * 1.年齢
 * 2.勤続年数
 * 3.日本のカレンダー
 * 4.１６歳の人 生年月日
 */
function allDateCalculation() {
    let birthday = $('input[name=birthday]').val();
    $('.ageVal').html(ageCalculation(birthday));
    $('.date-western').each(function () {
        let dateVal = $(this).find('.date-val').html().trim();
        if(dateVal!=''){
            $(this).find('.date-japan').html(dateCalculation(dateVal));
        }else{
            $(this).find('.date-japan').html('');
        }
    });
    workYearCalculation();
    $('.sixteenAge').html("16歳は"+sixteenAge()+"以前生");
}
/**
 * 社員編集した情報を表示する
 */
function showModify() {
    $(".employee-update .modify").each(function(){
        if($(this).prevAll(".history").length>0){
            let modifyVal = $(this).html().trim();
            let historyVal = $(this).prev().html().trim();
            if(modifyVal=='' && historyVal==''){
                $(this).html('');
                $(this).prev().hide();
            }else if(modifyVal=='' && historyVal!=''){
                $(this).prev().addClass('orange-color');
            }else if(modifyVal!=historyVal){
                $(this).addClass('orange-color');
            }else{
                $(this).prev().hide();
            }
            if(historyVal==''){
                $(this).prev().hide();
            }
        }
    });
    $(".employee-update .date-modify").each(function () {
        if($(this).parents('tr').prev().find('.date-history').length>0){
            let modifyVal = $(this).html().trim();
            let historyVal = $(this).parents('tr').prev().find('.date-history').html().trim();
            if(modifyVal=='' && historyVal==''){
                $(this).html('');
                $(this).parents('tr').prev().find('.date-history').hide();
                $(this).parents('tr').prev().find('.date-japan').hide();
            }else if(modifyVal=='' && historyVal!=''){
                $(this).parents('tr').prev().find('.date-history').addClass('orange-color');
                $(this).parents('tr').prev().find('.date-history').next().addClass('orange-color');
            }else if(modifyVal!=historyVal){
                $(this).addClass('orange-color');
                $(this).next().addClass('orange-color');
            }else{
                $(this).parents('tr').prev().find('.date-history').hide();
                $(this).parents('tr').prev().find('.date-japan').hide();
            }
            if(historyVal==''){
                $(this).parents('tr').prev().find('.date-history').hide();
            }
        }
    })
}
/**
 * 日本のカレンダーを計算する
 * @param date
 * @returns {string}
 */
function dateCalculation(date){
    let Wareki='';
    let WarekiYear = 0;
    let text = '';
    let year = Number(date.substr(0,4));
    let month = Number(date.substr(5,2));
    let day = Number(date.substr(8,2));
    if(year == 1926){
        if(month==12){
            if(day>=25){
                Wareki = '昭和';
                WarekiYear = '元';
            }
        }
    }else if(year>1926 && year<1989){
        Wareki = '昭和';
        WarekiYear = year-1925;
    }else if(year==1989){
        if(month==1){
            if(day<=7){
                Wareki = '昭和';
                WarekiYear = year-1925;
            }else{
                Wareki = '平成';
                WarekiYear = '元';
            }
        }else{
            Wareki = '平成';
            WarekiYear = '元';
        }
    }else if(year>1989 && year<2019){
        Wareki = '平成';
        WarekiYear = year-1988;
    }else if(year==2019){
        if(month<=4){
            Wareki = '平成';
            WarekiYear = year-1988;
        }else{
            Wareki = '令和';
            WarekiYear = '元';
        }
    }else if(year>2019){
        Wareki = '令和';
        WarekiYear = year-2018;
    }

    if(Wareki!=''){
        text = "("+Wareki+WarekiYear+'年'+month+'月'+day+"日)";
    }else{
    }
    return text;
}
/**
 * 年齢を計算
 */
function ageCalculation(birthday) {
    let age=0;
    if(birthday!=''){
        let year = Number(birthday.substr(0,4));
        let month = Number(birthday.substr(5,2));
        let day = Number(birthday.substr(8,2));
        let myDate = new Date();
        let nowMonth = myDate.getMonth()+1;
        let nowDate = myDate.getDate();
        age=myDate.getFullYear()-year-1;
        if (nowMonth>month || nowMonth==month && nowDate>day) {
            age++;
        }
    }
    $('.age').find('span').html(age);
    return age+'歳'
}
/**
 * 勤続年数を計算
 */
function workYearCalculation(){
    let dateHire = $('input[name=date_hire]').val();
    let dateRetire = $('input[name=date_retire]').val();
    let by = Number(dateHire.substr(0,4));
    let bm = Number(dateHire.substr(5,2));
    let bd = Number(dateHire.substr(8,2));

    let myDate = new Date();
    let cy=myDate.getFullYear();
    let cm = myDate.getMonth()+1;
    let cd = myDate.getDate();
    let html='';
    if(dateRetire!=''){
        cy=Number(dateRetire.substr(0,4));
        cm=Number(dateRetire.substr(5,2));
        cd=Number(dateRetire.substr(8,2));
    }
    let year=cy-by;
    let month=cm-bm;
    let day=cd-bd;
    if(calculateWorkMonths==0){
        if(day>=0){
            month++;
        }
    }else{
        if(day<0){
            month--;
        }
    }
    if(month<0){
        year--;
        month=month+12;
    }
    if(calculateWorkYears==0){
        html= (Number(year)+Number((month/12))).toFixed(2)+'年';
    }else{
        month=(month+'').padStart(2, '0');
        html= year+'年'+month+'月';
    }
    $('.workYear').html(html);
}
/**
 * １６歳の人  生年月日を計算
 * @returns {string}
 */
function sixteenAge(){
    let date = new Date();
    let seperator = "-";
    let year = date.getFullYear() - 16;
    let month = date.getMonth() + 1;
    let strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    let time = year + seperator + month + seperator + strDate;
    return dateCalculation(time);
}
/**
 * 编辑ボタンをクリック
 */
function adminModify() {
    $('.adminModify').hide();
    $('.adminSave').show();
    $('.cancelButton').attr('data-over','取消').data('over','取消');
    $('.modify').hide();
    $('.date-modify').hide();
    $('.date-modify+.date-japan').hide();
    $('.enter').show();
    $('.adminModifyAfter').show();
    $('.adminModifyBefore').hide();
    $("textarea").each(function (){
        changeLength(this)
    });
    $("form select").each(function () {
        $(this).select2({
            minimumResultsForSearch: Infinity
        });
    });
    modifyBoo=true;
    tableHeightSet();
    //保険・年金情報 checkbox 状态保存
    $('#employee-insurance').find('input[type=checkbox]').each(function (index) {
        if($(this).is(':checked')){
            insuranceCheckbox[index]=true;
            $(this).prop('checked',true);
        }else{
            insuranceCheckbox[index]=false;
            $(this).prop('checked',false);
        }
    });
    checkboxInsurance();
    relationCardAdd();
}
/**
 * 保存ボタンをクリック
 */
function adminSubmit() {
    $("#relationInfo .relationCard:hidden").each(function () {
        $(this).remove();
    });
    $('#adminConfirmCoverage').modal('hide');
    let data = new FormData($('#employee_all_form').get(0));
    data.append('imgData',iconData);
    if(submitBoo){
        $.ajax({
            url:adminSubmitUrl,
            type:"post",
            contentType: false,//リクエストヘッダーを不要にする
            processData: false,//前処理を取り消す
            data: data,
            success:function(response){
                ajaxSuccessAction(response,function () {
                    adminSubmitAfter(response)
                });
                if(response.status!=='success'){
                    unlockAjax(adminObj);
                }
            },
            error: function(jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            }
        });
    }else {
        printErrorMsg('「社員番号」を確認してください！');
        submitBoo=false;
    }
}

/**
 *
 * 保存前　社員から新しい情報修正が発生したため、確認します。
 */
function adminSubmitBefore() {
    adminObj = lockAjax();
    $("#relationInfo .relationCard:hidden").each(function () {
        $(this).remove();
    });
    $('#adminConfirmCoverage').modal('hide');
    let data = new FormData($('#employee_all_form').get(0));
    data.append('id',id);
    data.append('updateTime0',updateTime[0]);
    data.append('updateTime1',updateTime[1]);
    data.append('updateTime2',updateTime[2]);
    data.append('updateTime3',updateTime[3]);
    data.append('imgData',iconData);
    $.ajax({
        url:adminSubmitBeforeUrl,
        type:"POST",
        dataType:"JSON",
        contentType: false,//リクエストヘッダーを不要にする
        processData: false,//前処理を取り消す
        data: data,
        success:function(response){
            ajaxSuccessAction(response,function () {
                adminSubmitAfter(response)
            });
            if(response==false){
                $('#adminConfirmCoverage').modal('show');
            }
        },
        error: function(jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }
    });
}

function adminSubmitAfter(response){
    $('.adminModify').show();
    $('.adminSave').hide();
    $('.cancelButton').attr('data-over','戻る').data('over','戻る');
    $('.modify').show();
    $('.enter').hide();
    $('.date-modify').show();
    $('.date-modify+.date-japan').show();
    $('.adminModifyAfter').hide();
    $('.adminModifyBefore').show();
    enterToModify();
    allDateCalculation();
    checkboxInsuranceHide();
    modifyBoo=false;
    relationCardReplace(adminObj);
    updateTime = response.updateTime;
    iconSrc=response.icon;
    $("#iconImg").cropper('clear');
    $("#iconImg").cropper('replace',iconSrc);
}
function adminSubmitCancel() {
    $('#adminConfirmCoverage').modal('hide');
    unlockAjax(adminObj);
}
/**
 * 扶養カード replace
 */
function relationCardReplace(obj){
    $.ajax({
        url:"/employees/EmployeeRelationInfo/"+id,
        type:"GET",
        success:function(response){
            $(".relationCard").remove();
            if($(".relationCard").length>0){
                $("body")[0].removeChild($(".relationCard")[0])
            }
            $("#relationInfo").append(response);
            tableHeightSet();
            dateTimePicker();
            $('#relationInfo .date-western').each(function () {
                let dateVal = $(this).find('.date-val').html().trim();
                if(dateVal!=''){
                    $(this).find('.date-japan').html(dateCalculation(dateVal));
                }else{
                    $(this).find('.date-japan').html('');
                }
            });
        },
        error: function(jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        },complete: function () {
            unlockAjax(obj);
        }
    });
}

function checkboxInsuranceHide() {
    $(".checkboxInsurance").each(function () {
        if($(this).is(':checked')){
            $(this).parents('h5').next('table').find('.modify').show();
        }else{
            $(this).parents('h5').next('table').find('.modify').hide();
        }
    })
}
/**
 * 取消ボタンをクリック
 */
function adminCancel(){
    let type=$('.cancelButton').data('over');
    if(type=='取消'){
        $('.adminModify').show();
        $('.adminSave').hide();
        $('.cancelButton').attr('data-over','戻る').data('over','戻る');
        modifyToEnter();
        $('.modify').show();
        $('.date-modify').show();
        $('.date-modify+.date-japan').show();
        $('.enter').hide();
        $('.adminModifyAfter').hide();
        $('.adminModifyBefore').show();
        modifyBoo=false;
        tableHeightSet();
        // $("#mugshot").attr('src',iconSrc);
        $("#residenceCardFront").attr('src',frontSrc);
        $("#residenceCardBack").attr('src',backSrc);
        $('#employee-insurance').find('input[type=checkbox]').each(function (index) {
            if(insuranceCheckbox[index]){
                $(this).prop('checked',true);
            }else{
                $(this).prop("checked",false);
            }
        });
        checkboxInsurance();
        checkboxInsuranceHide()
        $("#iconImg").cropper('clear')
        $("#iconImg").cropper('replace',iconSrc)
        $("input[name=icon]").val('');
    }else{
        window.location.href=employeeIndexUrl;
    }

}
/**
 * 管理員 確認と否認　ボタンをクリック
 * @param boo => boolean
 */
function adminConfirm(boo) {
    const obj = lockAjax();
    $.ajax({
        url:adminConfirmUrl,
        type:"GET",
        data: {"id":id,"type":boo,"updateTime":updateTime},
        success:function(response){
            ajaxSuccessAction(response,function () {
                location.reload();
            });
        },error: function(jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        },complete: function () {
            unlockAjax(obj);
        }
    });
}
/**
 * 保存後　対処する
 */
function enterToModify() {
    const enter = $('.enter');
    enter.find('input[type=text]:not(.dateInput),textarea').each(function () {
        $(this).parents('td').prev().html($(this).val());
    });
    $('.dateInput').each(function () {
        $(this).parents('tr').find('.date-modify').html($(this).val());
    });
    enter.find('select').each(function () {
        $(this).parents('td').prev().html($(this).find('option:selected').html());
    });
    enter.find("input[type=file]").each(function () {

    });
    tableHeightSet();
    // iconSrc = $("#mugshot").attr('src');
    frontSrc = $("#residenceCardFront").attr('src');
    backSrc = $("#residenceCardBack").attr('src');
}
/**
 * 取消後　対処する
 */
function modifyToEnter() {
    $('.enter').find('input[type=text]:not(.dateInput),textarea').each(function () {
        let textHtml = $(this).parents('td').prev().html();
        if(textHtml!='' && textHtml!=undefined){
            $(this).val(textHtml.trim());
            if($(this).attr('name')=="family_num"){
                $(this).trigger("input");
            }
        }
    });
    $('.dateInput').each(function () {
        let textHtml =$(this).parents('tr').find('.date-modify').html();
        if(textHtml!='' && textHtml!=undefined){
            $(this).val(textHtml.trim());
        }
    });
    $('.enter').find('select').each(function () {
        let selectHtml = $(this).parents('td').prev().html().trim();
        let selectVal = 0;
        $(this).find('option').each(function () {
            if($(this).html()==selectHtml){
                selectVal=$(this).val()
            }
        });
        $(this).val(selectVal).select2({
            minimumResultsForSearch: Infinity
        });
        $(this).trigger("change");
    });
    $("input[type=file]").each(function () {
        $(this).val('');
    });
    const obj = lockAjax();
    relationCardReplace(obj);
}
/**
 * 时间入力ボクス　初期化
 */
function dateTimePicker() {
    $('.dateInput').each(function () {
        initSingleDatePicker(this);
    })
}
/**
 * 社員コード 重複チェック
 */
function employeeCodeCheck(e) {
    let employeeCode = $(e).val();
    if(employeeCode.length==4){
        if(employeeCode==employeeCodeHistory){
            $.notify({
                message: 'この番号は使用できます！'
            },{
                type: 'success'
            });
            submitBoo=true;
        }else{
            $.ajax({
                url:employeeCodeUrl,
                type:"GET",
                data: {"employeeCode":employeeCode},
                success:function(response){
                    if(response){
                        printErrorMsg('この番号は既に存在しています！');
                        submitBoo=false;
                    }else{$.notify({
                        message: 'この番号は使用できます！'
                    },{
                        type: 'success'
                    });
                        submitBoo=true;
                    }
                },
                error: function(jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });
        }

    }else{
        printErrorMsg('「社員番号」の範囲が「0001~9999」です！');
        submitBoo=false;
    }
}
/**
 * 保険・年金情報 checkbox 選択された、　input　入力できます
 */
function checkboxInsurance() {
    $(".checkboxInsurance").each(function () {
        if($(this).is(':checked')){
            $(this).parents('h5').next('table').find('input').removeAttr('readonly');
        }else{
            $(this).parents('h5').next('table').find('input').prop('readonly','true');
        }
        $(this).click(function () {
            if(!modifyBoo){
                return false;
            }else{
                if($(this).is(':checked')){
                    $(this).parents('h5').next('table').find('input').removeAttr('readonly');
                }else{
                    $(this).parents('h5').next('table').find('input').prop('readonly','true');
                }
            }
        })
    })
}
/**
 * 扶養関係 カード 左右の高さは同じです
 */
 function tableHeightSet(){
     $("#relationInfo table:visible").odd().each(function () {
         $(this).css('height','auto');
         $(this).parents(".relationCard").prevAll(".relationCard:visible").first().find("table").css('height','auto');
         let rightHeight = $(this).height();
         let leftHeight = $(this).parents(".relationCard").prevAll(".relationCard:visible").first().find("table").height();
         if(rightHeight>leftHeight){
             $(this).parents(".relationCard").prevAll(".relationCard:visible").first().find("table").css('height',rightHeight+'px');
         }else{
             $(this).css('height',leftHeight+'px');
         }
     });
}
/**
 * 扶養関係 カード 自動的に追加
 *
 */
function relationCardAdd() {
    let num=$('.familyNum').val();
    let cardNum=$('.relationCard').length;
    let index=Number(num)-Number(cardNum);
    for (index;index>0;index--){
        let cloneHtml = $("#cloneRelationCard").clone();
        cloneHtml.removeAttr("id");
        cloneHtml.addClass("relationCard");
        $("#relationInfo").append(cloneHtml);
        $(".relationCard:last").show();
        $('.relationCard:last select').select2({
            minimumResultsForSearch: Infinity
        });
    }
    tableHeightSet();
    dateTimePicker();
}
/**
 * 扶養関係 card "×" click　
 * @param e
 */
function relationDeleteClick(e) {
    relationCard=$(e).parents('.relationCard')
}
/**
 * 扶養関係 card delete　
 */
function relationCardDelete() {
    let num=$('.familyNum').val();
    let cardNum=$('.relationCard').length;
    let index=Number(num)-Number(cardNum);
    if(index>=0){
        printErrorMsg("扶養家族情報数と扶養家族人数と不一致である");
    }else{
        $(relationCard).remove();
    }
    $("#delete").modal('hide');
}

/**
 * 写真を表示する
 * @param event
 */

function showImg(event) {
    let rd = new FileReader();
    let files = event.files[0];
    rd.readAsDataURL(files);
    rd.onloadend = function(e) {
        if($(event).attr("name")=="icon"){
            $('#mugshot').attr('src',this.result);
        }else if($(event).attr("name")=="residence_card_front"){
            $('#residenceCardFront').attr('src',this.result);
        }else if($(event).attr("name")=="residence_card_back"){
            $('#residenceCardBack').attr('src',this.result);
        }
    }
}
/**
 * 写真 click 拡大
 * @param e
 */
function photoShow(e) {
    let src = $(e).attr('src');
    $('#photo-show img').attr('src',src);
    $('#photo-show').modal('show');
}

function addPostMark(e) {
    const val = $(e).val();
    if (val.trim() !== ''){
        if(val.length>3){
            $(e).val('〒' + val.substr(0,3) +'-' + val.substr(3));
        }else{
            $(e).val('〒' + val);
        }
    }
}

function onPostFocus(e) {
    e.value = e.value.replace(/[〒-]/g, "");
    e.setSelectionRange(0, e.value.length);
}

/**
 * 「区分」を変更して、カードを変換する
 * @param e
 */
function cardChange(e) {
    let relationshipType = $(e).val();
    switch (relationshipType) {
        case "1":
            $(e).parents('table').find('tr:eq(3)').hide();
            $(e).parents('table').find('tr:eq(5)').hide();
            break;
        case "0":
        case "2":
            $(e).parents('table').find('tr:eq(3)').show();
            $(e).parents('table').find('tr:eq(5)').show();
            break;
        case "3":
        case "4":
            $(e).parents('table').find('tr:eq(3)').show();
            $(e).parents('table').find('tr:eq(5)').hide();
            break;
    }
    $(e).parents('table').find('select').each(function () {
        $(this).select2('destroy');
        $(this).select2({
            minimumResultsForSearch: Infinity
        });
    });
    tableHeightSet();
}
function photoCut(event){
    let rd = new FileReader();
    let files = event.files[0];
    rd.readAsDataURL(files);
    rd.onloadend = function() {
        $("#iconImg").cropper('replace',this.result)
    };
    iconReCoverType = false;
    if(iconFileType==1){
        $('.iconFile:eq(0)').attr('name','icon');
        $('.iconFile:eq(1)').attr('name','icon-temporary');
        iconFileType=2;
    }else{
        $('.iconFile:eq(0)').attr('name','icon-temporary');
        $('.iconFile:eq(1)').attr('name','icon');
        iconFileType=1;
    }
    $("input[name=icon-temporary]").val('');
    if($('#icon-model').is(':hidden')){
        $('#icon-model').modal('show');
    }
}
function newIcon(){
    $('input[name=icon]').click();
}
function iconIsClick(boo){
    iconReCoverType = boo;
    $('#icon-model').modal('hide');
    if(boo){
        let imgData=$('#iconImg').cropper('getCropBoxData');
        let temp=$('#iconImg').cropper('getImageData');
        let canvas=$('#iconImg').cropper('getCanvasData');
        imgData.rate = temp.width/temp.naturalWidth;
        imgData.left = imgData.left - canvas.left;
        imgData.top = imgData.top - canvas.top;
        iconData = JSON.stringify(imgData);
    }
}
function lockAjax() {
    const obj = $('.adminButton');
    obj.each(function () {
        $(this).data('click',$(this).attr('onclick'));
        $(this).removeAttr('onclick');
        $(this).css('cursor','wait');
    });
    return obj;
}
function printErrorMsg(msg) {
    if(msg!=undefined){
        $.notify({
            message: msg
        }, {
            type: 'danger'
        });
    }
    unlockAjax(adminObj);
}
