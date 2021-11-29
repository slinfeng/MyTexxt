var initvalValue = $("textarea[name=initval_value]").html();
var initvalDocument = $("textarea[name=document_send]").html();
var borderHave='none';
var printAfterDate;
let id;


$(function () {
    $('select').removeAttr('onchange');
    widthChange();
    textareaFormat();
    framedChecked();
    initvalChecked();
    initSingleDatePicker('.datetime');
    disableFormat();
    if(initvalDocument!==''){
        changeContent(initvalDocument);
    }
    $("input[class=initDistance]").each(function () {
        numFormat(this);
    });

    var client_id=$('input[name=client_id]').val();
    if((client_id!=='')&&(client_id!==0)&&(client_id!==undefined)){
        $('select[name=client_id]').children("option").each(function () {
            if($(this).val()===client_id){
                $(SELECTOR_CLIENT_ID).data('searchableSelect').selectItem($('div.searchable-select-item[data-value='+client_id+']').first());
            }
        });
    }
    $('select').change(function () {
        changeNum(this,true);
    });
    id = $('input[name=id]').val();
})

function handleBeforePrint() {
    if($('#switch_annual3').is(':checked')){
        borderHave='1px solid black';
    }else{
        borderHave='none';
    }
    $('p[name=datetime]').html('送付日：'+$('input[name=delivery_date]').val()).removeClass('hide');
    printAfterDate=$('input[name=delivery_date]').val();
}

function letterLayout() {
    const print = $('.print-receipt');
    var print_width=print.width();
    var print_height=print_width*1.47;
    var top_bottom_distance=($('input[name=top_bottom_distance]').val()-10)/297*print_height;
    var left_right_distance=($('input[name=left_right_distance]').val()-10)/210*print_width;
    var print_top_bottom_margin=3/297*print_height;
    var print_left_right_margin=3/210*print_width;

    print.height(print_height).css({'padding':print_top_bottom_margin+'px '+print_left_right_margin+'px'});
    $('.layout_border').css({'border':borderHave,'padding':top_bottom_distance+'px '+left_right_distance+'px'});
    // $('.layout_border').css({'border':borderHave,'padding-top':top_bottom_distance+'px'});
    // $('.client-position').css({'margin-left': left_right_distance + 'px'});
    // $('.layout_border').append('<p style="text-align: right;padding:0;margin: 0;">以上</p>');
    $('.title_name').css('font-size','40px');
    $('ul').css('list-style-type','none');
}

function handleAfterPrint() {
    initSingleDatePicker('.datetime');
    $('input[name=delivery_date]').val(printAfterDate);
    numFormat($('input[name=top_bottom_distance]')[0],0);
    numFormat($('input[name=left_right_distance]')[0],1);
    $('input[name=id]').val(id);
}


function widthChange() {
    $("#float_text").html($("#title_name").val());
    var getWidth = $("#float_text").width();
    if (getWidth > 80 && getWidth < 1800) {
        $("#title_name").width(getWidth + 35);
    }
    $("#float_text").html("");
}

function postCodeCheck(selector, msg) {
    if($(selector).val().indexOf('〒')>=0){
        var vali=/^〒\d{3}-\d{4}/;
        var e=$(selector).val().split('\n')[0];
        if(!vali.test(e)){
            printErrorMsg(msg);
            throw false;
        }
    }
}

function numFormat(e, type) {
    var num = Number(e.value);
    var inputName=e.name;
    if(num>50){
        num=50.00;
        printErrorMsg('距離の最大値は50.00mmです。');
    }
    if(num<20){
        num=20.00;
        printErrorMsg('距離の最小値は20.00mmです。');
    }
    $('input[name='+inputName+']').each(function (){
        $(this).val(num.toFixed(2));
    });
    num = num - 10;
    if (type === 0||inputName==='top_bottom_distance') {
        $('.layout_border').css({'padding-top': num + 'mm', 'padding-bottom': num + 'mm'});
        // $('.layout_border').css({'padding-top': num + 'mm'});
    } else {
        $('.layout_border').css({'padding-left': num + 'mm', 'padding-right': num + 'mm'});
        // $('.client-position').css({'margin-left': num + 'mm'});
    }
    textareaFormat();
}

function framedChecked(e='#switch_annual3') {
    if ($(e).is(':checked')) {
        $('.layout_border').css('border', '0.1mm solid black');
    } else {
        $('.layout_border').css('border', '0.1mm solid white');
    }
}

function initvalChecked(e='#switch_annual1') {
    var textarea_a = $("textarea[name=initval_value]");
    if ($(e).is(':checked')) {
        textarea_a.val(initvalValue);
        textarea_a.prop('readonly', true);
        textarea_a.css('background-color', 'white');
    } else {
        textarea_a.prop('readonly', false);
        textarea_a.css('background-color', '#E8F0FE');
    }
}

// function goToBack() {
//     window.location.href = init_val.data('re-href');
// }

function changeNum(e,editInit=false) {
    if(e!==undefined){
        var client_id =$(e).val();
        if (client_id !== '0') {
            var url=$(e).data('get-one-client');
            url=url.replace(':id',client_id);
            $.ajax({
                type: 'post',
                url: url,
                dataType: 'json',
                async: false,
                success: function (response) {
                    const client = response.client;
                    $('span[name=companyName]').html(client.client_name);
                    $('span[name=companyAddress]').html(client.client_address);
                    $('span[name=postcode]').html(client.post_code);
                    $('input[name=client_id]').val(client_id);
                },
                error: function (jqXHR, testStatus, error) {
                    ajaxErrorAction(jqXHR, testStatus, error);
                }
            });
        // }
        }else {
            $('input[name=client_id]').val('');
        }
        if(editInit){
            var text01 = $('#lop').text();
            $("textarea[name=client_address]").val(text01);
            textareaFormat();
        }
    }else{
        $('span[name=companyAddress]').html('');
    }
}

function addLine(x) {
    // var ulContent = $('#send_content textarea:eq(' + x + ')');
    var addContent = $('#send_content div:eq(' + x + ')').html();
    // ulContent.append(addContent);
    $('#send_content textarea:eq(4)').append(addContent+'\n');
    sendContends();
}

function clearContent() {
    $('#send_content textarea').each(function () {
        $(this).text('');
    });
    sendContends();
}

function sendContends() {
    $('textarea[name=document_send]').val($('#send_content textarea:eq(4)').text());
    textareaFormat();
}

function textareaFormat() {
    $("textarea").each(function () {
        changeLength(this);
    });
}

function changeContent(getContent='') {
    if(getContent===''){
        getContent=$('textarea[name=document_send]').text()+'\n';
    }else{
        getContent=getContent+'\n\n';
    }
    while (getContent.indexOf('\n\n\n')>0){
        getContent=getContent.replace('\n\n\n','\n\n');
    }
    if(getContent==='\n\n'){
        $('#send_content textarea:eq(4)').text('');
    }else{
        $('#send_content textarea:eq(4)').text(getContent);
    }
    sendContends();
}

function charCheck(dataArray,char='') {
    var otherContent='';
    for(var i=0;i<dataArray.length;i++){
        if(char!==''){
            if(dataArray[i].indexOf(char)===1&&dataArray[i].indexOf(char)<=3){
                return dataArray[i];
            }
        }else{
            if((dataArray[i].indexOf('見積書')<0)&&(dataArray[i].indexOf('注文書')<0)&&(dataArray[i].indexOf('注文請書')<0)&&(dataArray[i].indexOf('請求書')<0)){
                otherContent=otherContent+dataArray[i]+'\n';
            }
        }
    }
    return otherContent;
}

function nameAndMemoChange(name,val) {
    $('input[name='+name+']').each(function () {
        this.value=val;
    });
}

function addEditLetterOfTransmittal() {
    const obj = lockAjax();
    let url=$('#lop').data('store');
    let type="POST";
    if($('input[name=id]').val()=== '0') $('input[name=id]').val(id);
    if (id !== "0") {
        url=$('#lop').data('update');
        url=url.replace(':id', id);
        type="PUT";
    }
    $.ajax({
        url: url,
        type: type,
        data: $('#aForm').serialize(),
        success: function (response) {
            ajaxSuccessAction(response,function (response) {
                if(id === '0') {
                    id=response[0];
                    $('input[name=id]').val(response[0]);
                }
            });
        },
        error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        },complete:function () {
            unlockAjax(obj);
        }
    });
}

function pageChange(res) {
    window.location.href=res[0];
}
function getPdfName() {
    return $('input[name=memo]').val();
}
