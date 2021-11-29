// request_setting;

let bankAddBoo = false;
let deleteCard;
window.onload = function(){
    $('.radioInput.radioSelect').each(function () {
        $(this).attr('data-val',$(this).val());
    });
    $('.bankCard').last().hide();
    if($('.bankCard').length==1){
        bankCardAdd()
    }
    fontFamilyShow();
    initReceiveSelector();
    bankInfoGet();
};
function textareaHeight() {
    setTimeout(function () {
        initTextarea()
    },1)
}
function radioInputIsEnter(event,type){
    if(type){
        // let val =  $(event).parents('.input-group').find('.radioInput').attr('data-val');
        $(event).parents('.input-group').find('.radioInput').removeAttr('readonly');
    }else{
        // let val = $(event).parents('.input-group').find('.radioInput').val();
        $(event).parents('.input-group').find('.radioInput').prop('readonly','true');
        // if(val!=''){
        //     $(event).parents('.input-group').find('.radioInput').attr('data-val',val).val('');
        // }
    }
}
function radioSelectIsEnter(event,type){
    if(type){
        $(event).parents('.input-group').find('.radioSelect').each(function () {
            $(this).removeAttr('disabled');
        });
    }else{
        $(event).parents('.input-group').find('.radioSelect').each(function () {
            $(this).prop('disabled','true');
        });
    }
}
function bankCardAdd(){
    if(bankAddBoo == false){
        let bankCard = $('.bankCard').last();
        bankCard.before(bankCard.clone());
        bankCard = $('.bankCard:hidden').first();
        bankCard.show();
        bankCard.find('select').select2({
            minimumResultsForSearch: -1,
            width: '100%'
        });
        bankAddBoo = true;
    }else{
        $.notify({
            message: '新規した口座情報カードが存在しています。追加できません。'
        },{
            type: 'danger'
        });
    }
}
function bankInfoSave(event) {
    const obj = lockAjax();
    let id = $(event).parents('.bankCard').find('input[name=id]').val();
    let url = $(event).parents('form').attr('action');
    $.ajax({
        url:url,
        data: $(event).parents('form').serialize(),
        type:"post",
        success: function (response) {
            ajaxSuccessAction(response,function () {
                if(id==''){
                    $(event).parents('.bankCard').find('input[name=id]').val(response[0]);
                    let index=$('.bankCard').length-1;
                    $(event).parents('.bankCard').find('h4').html('口座情報'+index);
                    bankAddBoo = false;
                }
                bankInfoGet();
            })
        },
        error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        },complete: function () {
            unlockAjax(obj);
        }
    });
}
function bankCardDeleteData(event) {
    deleteCard = event;
}
//
function bankCardDelete(event) {
    let id = $(deleteCard).parents('.bankCard').find('input[name=id]').val();
    $('#delete').modal('hide');
    if(id!=''){
        const obj = lockAjax();
        let url = $(deleteCard).data('url');
        url = url.replace(':id', id);
        $.ajax({
            url:url,
            async:false,
            type:"delete",
            success: function (response) {
                ajaxSuccessAction(response,function () {
                    if($('.bankCard').length<=2){
                        $(deleteCard).parents('.bankCard').find('input').val('');
                        $(deleteCard).parents('.bankCard').find('select').val('1');
                        $(deleteCard).parents('.bankCard').find('h4').html('口座情報');
                        bankAddBoo = false;
                    }else{
                        $(deleteCard).parents('.bankCard').remove();
                    }
                    bankInfoGet();
                });
            },
            error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            },complete:function () {
                unlockAjax(obj);
            }
        });
    }else{
        bankAddBoo = false;
        $(deleteCard).parents('.bankCard').remove();
    }
}
//form 提交
function requestSettingSubmit(event) {
    //ajax start
    $('input:radio:checked').each(function () {
        if($(this).val()==0){
            $(this).parents('td').find('.radioInput').val('');
            $(this).parents('td').find('.radioSelect').val(0);
        }
    });
    let data = new FormData($(event).parents('form').get(0));
    let url = $(event).parents('form').attr('action');
    $.ajax({
        url:url,
        type:"post",
        contentType: false,//リクエストヘッダーを不要にする
        processData: false,//前処理を取り消す
        data: data,
        success: function (response) {
            ajaxSuccessAction(response,function () {
                $('.radioInput').each(function () {
                    if($(this).val()==''){
                        $(this).parents('td').find('input:radio[value=0]').click();
                    }
                });
                initTextarea();
            });
        },
        error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }
    });
    //ajax end
}

function bankInfoGet() {
    let url = $('input[name=bankInfoGet]').data('url');
    $.ajax({
        url:url,
        type:"get",
        success: function (response) {
            let selectHtmlStart = '<select class="bankInfo form-control">';
            let selectHtml = '';

            response.forEach(function (value,index,response) {
                let accountName = accountNameCut(value.account_name);
                selectHtml += '<option value="'+value.id+'">'+value.bank_name+'　'+value.branch_name+'（'+value.branch_code+'）（'+value.account_num+'）'+accountName+'</option>';
            });
            let selectHtmlend = '</select>';
            const selectOrigin = $(selectHtmlStart+selectHtml+selectHtmlend);
            $('.bankInfo').each(function () {
                let name = $(this).attr('name');
                let select = selectOrigin.clone();
                selectHtmlStart = select.attr('name', name);
                let value = $(this).val();
                $(this).replaceWith(select);
                select.val(value);
            })
        },
        error: function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        },
    });
}
function accountNameCut(accountName){
    let length = accountName.replace(/([\u4e00-\u9fa5\u3040-\u309F\u30A0-\u30FF\u4E00-\u9FBF\u0800-\u4e00])/g,'aa').length;
    if (length<=12){
        return accountName;
    }else{
        accountName = accountName.substring(0,accountName.length-1);
        return accountNameCut(accountName);
    }
}
function fontFamilyShow() {
    let option = $('select[name=font_family] option:selected');
    let font_family = option.data('fontfamily');
    let font_family_val = option.html();
    let view = $('input[name=font_family_view]');
    view.val(font_family_val);
    view.css({'font-family':font_family});
}

function mailSubmit(e) {
    $.ajax({
        url:$(e).attr('action'),
        type:"post",
        data: $(e).serialize(),
        success: function (response) {
            ajaxSuccessAction(response);
        },error:function (jqXHR, testStatus, error) {
            ajaxErrorAction(jqXHR, testStatus, error);
        }
    });
}

function receiveMailAddressChange() {
    const e = $('select[name=user_selected]');
    const userIdArr = e.val();
    if(userIdArr.length>=0){
        var url=$(e).data('route');
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data:{idArr:userIdArr},
            async: false,
            success: function (response) {
                ajaxSuccessAction(response);
            },
            error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            }
        });
    }
}

/*canvas*/
/*
  html2canvas 0.5.0-beta3 <http://html2canvas.hertzen.com>
  Copyright (c) 2016 Niklas von Hertzen

  Released under  License
*/

$(function() {
    downLode();
});
function downLode() {
    html2canvas($("#container"), {
        onrendered: function(canvas) {
            $('#down_button').attr('href', canvas.toDataURL());
            $('#down_button').attr('download', '企業印鑑.png');
            var html_canvas = canvas.toDataURL();
            $.post('', {
                order_id: 1,
                type_id: 2,
                html_canvas: html_canvas
            }, function(json) {}, 'json');
        }
    });
}
window.onload = function () {
    var canvas = document.getElementById("canvas");
    var c = document.getElementById("canvas");
    c.style.writingMode="vertical-rl";
    var context = c.getContext('2d');
    var width = canvas.width;
    var fn = document.getElementById("fn");
    var text = "";
    bao = function() {
        var change = $("#img").val();
        if (change == 1) {
            $("#fn").focus();
            context.font = '170px 篆書';
            context.fillText("篆書",70,0);
            $('#fn').val('');
            $("#fn").focus();
        } else if (change == 2) {
            $("#fn").focus();
            context.font = '170px 印相';
            context.fillText("印相",70,0);
            $('#fn').val('');
            $("#fn").focus();
        } else if (change == 3) {
            $("#fn").focus();
            context.font = '170px 古印';
            context.fillText("古印",70,0);
            $('#fn').val('');
            $("#fn").focus();
        }
    }
    fn.onblur= function () {
        var change = $("#img").val();
        context.textBaseline = 'top';
        context.textAlign = 'center';
        context.fillStyle = 'red';
        if(change == 1) {
            if(text.length<3){
                if(text.length==1){
                    context.font = '170px 篆書';
                    context.fillText(text, 72.5, -10, 153);
                }
                if(text.length==2){
                    context.font = '170px 篆書';
                    context.fillText(text, 73.5, -10, 150);
                }
                context.stroke();
            }
            else if(text.length<7 && text.length>=3) {
                var res = text.slice(0,Math.ceil(text.length/2));
                var res1 = text.slice(Math.ceil(text.length/2));
                if(res.length==2 && res1.length == 1){
                    context.font = '90px 篆書';
                    context.fillText(res, width / 2, -7, 150);
                    context.font = '95px 篆書';
                    context.scale(1.6,0.9);
                    context.fillText(res1, 45, 78, 153);
                }
                else if(res.length==2 && res1.length==2){
                    context.font = '85px 篆書';
                    context.fillText(res, 73, -5, 150);
                    context.font = '85px 篆書';
                    context.fillText(res1, 73, 70, 150);
                }
                else if(res.length==3 && res1.length==2){
                    context.font = '85px 篆書';
                    context.fillText(res, width / 2, -5, 150);
                    context.fillText(res1, 73, 70, 150);
                }
                else if(res.length==3 && res1.length==3){
                    context.font = '85px 篆書';
                    context.fillText(res, width / 2, -5, 150);
                    context.fillText(res1, width / 2, 70, 150);
                }
                context.stroke();
            }
            else if(text.length<13 && text.length>=7){
                var res2 = text.slice(0,Math.ceil(text.length/3));
                var res3 = text.slice(Math.ceil(text.length/3),Math.ceil(text.length*2/3));
                var res4 = text.slice(Math.ceil(text.length*2/3));
                context.lineWidth = 3;
                if(res2.length==3 && res3.length==2 && res4.length==2){
                    context.font = '60px 篆書';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '62px 篆書';
                    context.scale(1.2,0.9);
                    context.fillText(res3, 61.5, 52, 150);
                    context.font = '62px 篆書';
                    context.fillText(res4, 61.5, 107, 150);
                }
                else if(res2.length==3 && res3.length==3 && res4.length==2){
                    context.font = '60px 篆書';
                    context.fillText(res2, width / 2, -4, 150);
                    context.font = '60px 篆書';
                    context.fillText(res3, width / 2, 47, 150);
                    context.font = '62px 篆書';
                    context.scale(1.2,0.9);
                    context.fillText(res4, 61.5, 109, 150);
                }
                else if(res2.length==3 && res3.length==3 && res4.length==3){
                    context.font = '57px 篆書';
                    context.fillText(res2, width / 2, -4, 150);
                    context.font = '57px 篆書';
                    context.fillText(res3, width / 2, 46, 150);
                    context.font = '57px 篆書';
                    context.fillText(res4, width / 2, 95, 150);
                }
                else if(res2.length==4 && res3.length==3 && res4.length==3){
                    context.font = '57px 篆書';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '60px 篆書';
                    context.fillText(res3, width / 2, 45, 150);
                    context.font = '60px 篆書';
                    context.fillText(res4, width / 2, 95, 150);
                }
                else if(res2.length==4 && res3.length==4 && res4.length==3){
                    context.font = '57px 篆書';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '57px 篆書';
                    context.fillText(res3, width / 2, 45, 150);
                    context.font = '60px 篆書';
                    context.fillText(res4, width / 2, 95, 150);
                }
                else if(res2.length==4 && res3.length==4 && res4.length==4){
                    context.font = '57px 篆書';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '57px 篆書';
                    context.fillText(res3, width / 2, 45, 150);
                    context.font = '57px 篆書';
                    context.fillText(res4, width / 2, 95, 150);
                }
                context.stroke();
            }
            else if(text.length<21 && text.length>=13){
                var res5 = text.slice(0,Math.ceil(text.length/4));
                var res6 = text.slice(Math.ceil(text.length/4),Math.ceil(text.length/2));
                var res7 = text.slice(Math.ceil(text.length/2),Math.ceil(text.length*3/4));
                var res8 = text.slice(Math.ceil(text.length*3/4));
                var res51 = text.slice(0,4);
                var res61 = text.slice(4,8);
                var res71 = text.slice(8,11);
                var res81 = text.slice(11);
                var res52 = text.slice(0,5);
                var res62 = text.slice(5,10);
                var res72 = text.slice(10,14);
                var res82 = text.slice(14);
                if(res5.length==4 && res6.length==3 && res7.length==3 && res8.length==3){
                    context.font = '43px 篆書';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '45px 篆書';
                    context.scale(1.1,1);
                    context.fillText(res6, 68, 34, 150);
                    context.font = '45px 篆書';
                    context.fillText(res7, 68, 71, 150);
                    context.font = '45px 篆書';
                    context.fillText(res8, 68, 108, 150);
                }
                else if(res51.length==4 && res61.length==4 && res71.length==3 && res81.length==3){
                    context.font = '43px 篆書';
                    context.fillText(res51, width / 2, -3, 150);
                    context.font = '43px 篆書';
                    context.fillText(res61, width / 2, 34, 150);
                    context.font = '43px 篆書';
                    context.scale(1.1,1);
                    context.fillText(res71, 68, 69, 150);
                    context.font = '45px 篆書';
                    context.fillText(res81, 68, 108, 150);
                }
                else if(res5.length==4 && res6.length==4 && res7.length==4 && res8.length==3){
                    context.font = '43px 篆書';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '43px 篆書';
                    context.fillText(res6, width / 2, 34, 150);
                    context.font = '43px 篆書';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '45px 篆書';
                    context.scale(1.1,1);
                    context.fillText(res8, 68, 108, 150);
                }
                else if(res5.length==4 && res6.length==4 && res7.length==4 && res8.length==4){
                    context.font = '43px 篆書';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '43px 篆書';
                    context.fillText(res6, width / 2, 35, 150);
                    context.font = '43px 篆書';
                    context.fillText(res7, width / 2, 73, 150);
                    context.font = '43px 篆書';
                    context.fillText(res8, width / 2, 111, 150);
                }
                else if(res5.length==5 && res6.length==4 && res7.length==4 && res8.length==4){
                    context.font = '40px 篆書';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '43px 篆書';
                    context.fillText(res6, width / 2, 33, 150);
                    context.font = '43px 篆書';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '43px 篆書';
                    context.fillText(res8, width / 2, 109, 150);
                }
                else if(res52.length==5 && res62.length==5 && res72.length==4 && res82.length==4){
                    context.font = '40px 篆書';
                    context.fillText(res52, width / 2, -3, 150);
                    context.font = '40px 篆書';
                    context.fillText(res62, width / 2, 33, 150);
                    context.font = '43px 篆書';
                    context.fillText(res72, width / 2, 71, 150);
                    context.font = '43px 篆書';
                    context.fillText(res82, width / 2, 109, 150);
                }
                else if(res5.length==5 && res6.length==5 && res7.length==5 && res8.length==4){
                    context.font = '40px 篆書';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '40px 篆書';
                    context.fillText(res6, width / 2, 33, 150);
                    context.font = '40px 篆書';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '43px 篆書';
                    context.fillText(res8, width / 2, 109, 150);
                }
                else if(res5.length==5 && res6.length==5 && res7.length==5 && res8.length==5){
                    context.font = '40px 篆書';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '40px 篆書';
                    context.fillText(res6, width / 2, 33, 150);
                    context.font = '40px 篆書';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '40px 篆書';
                    context.fillText(res8, width / 2, 109, 150);
                }
                context.stroke();
            }
            else if(text.length<31 && text.length>=21){
                var res9 = text.slice(0,Math.ceil(text.length/5));
                var res10 = text.slice(Math.ceil(text.length/5),Math.ceil(text.length*2/5));
                var res11 = text.slice(Math.ceil(text.length*2/5),Math.ceil(text.length*3/5));
                var res12 = text.slice(Math.ceil(text.length*3/5),Math.ceil(text.length*4/5));
                var res13 = text.slice(Math.ceil(text.length*4/5));
                var res92 = text.slice(0,5);
                var res102 = text.slice(5,10);
                var res112 = text.slice(10,14);
                var res122 = text.slice(14,18);
                var res132 = text.slice(18);
                var res93 = text.slice(0,5);
                var res103 = text.slice(5,10);
                var res113 = text.slice(10,15);
                var res123 = text.slice(15,19);
                var res133 = text.slice(19);
                var res91 = text.slice(0,6);
                var res101 = text.slice(6,12);
                var res111 = text.slice(12,17);
                var res121 = text.slice(17,22);
                var res131 = text.slice(22);
                var res911 = text.slice(0,6);
                var res1011 = text.slice(6,12);
                var res1111 = text.slice(12,18);
                var res1211 = text.slice(18,23);
                var res1311 = text.slice(23);
                if (res9.length==5 && res10.length==4 && res11.length==4 && res12.length==4  && res13.length==4){
                    context.font = '35px 篆書';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 篆書';
                    context.scale(1.1,1);
                    context.fillText(res10, 70, 27, 150);
                    context.font = '35px 篆書';
                    context.fillText(res11, 70, 57, 150);
                    context.font = '35px 篆書';
                    context.fillText(res12, 70, 87, 150);
                    context.font = '35px 篆書';
                    context.fillText(res13, 70, 117, 150);
                }
                if(res92.length==5 && res102.length==5 && res112.length==4 && res122.length==4  && res132.length==4){
                    context.font = '35px 篆書';
                    context.fillText(res92, width / 2, -1, 150);
                    context.font = '35px 篆書';
                    context.fillText(res102, width / 2, 28, 150);
                    context.font = '35px 篆書';
                    context.scale(1.1,1);
                    context.fillText(res112, 70, 57, 150);
                    context.font = '35px 篆書';
                    context.fillText(res122, 70, 87, 150);
                    context.font = '35px 篆書';
                    context.fillText(res132, 70, 117, 150);
                }
                if(res93.length==5 && res103.length==5 && res113.length==5 && res123.length==4  && res133.length==4){
                    context.font = '35px 篆書';
                    context.fillText(res93, width / 2, -1, 150);
                    context.font = '35px 篆書';
                    context.fillText(res103, width / 2, 28, 150);
                    context.font = '35px 篆書';
                    context.fillText(res113, width / 2, 58, 150);
                    context.font = '35px 篆書';
                    context.scale(1.1,1);
                    context.fillText(res123, 70, 87, 150);
                    context.font = '35px 篆書';
                    context.fillText(res133, 70, 117, 150);
                }
                if(res9.length==5 && res10.length==5 && res11.length==5 && res12.length==5  && res13.length==4){
                    context.font = '35px 篆書';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 篆書';
                    context.fillText(res10, width / 2, 28, 150);
                    context.font = '35px 篆書';
                    context.fillText(res11, width / 2, 58, 150);
                    context.font = '35px 篆書';
                    context.fillText(res12, width / 2, 88, 150);
                    context.font = '35px 篆書';
                    context.scale(1.1,1);
                    context.fillText(res13, 70, 117, 150);
                }
                if(res9.length==5 && res10.length==5 && res11.length==5 && res12.length==5  && res13.length==5){
                    context.font = '35px 篆書';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 篆書';
                    context.fillText(res10, width / 2, 28, 150);
                    context.font = '35px 篆書';
                    context.fillText(res11, width / 2, 58, 150);
                    context.font = '35px 篆書';
                    context.fillText(res12, width / 2, 88, 150);
                    context.font = '35px 篆書';
                    context.fillText(res13, width / 2, 118, 150);
                }
                if(res9.length==6 && res10.length==5 && res11.length==5 && res12.length==5  && res13.length==5){
                    context.font = '33px 篆書';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 篆書';
                    context.fillText(res10, width / 2, 27, 150);
                    context.font = '35px 篆書';
                    context.fillText(res11, width / 2, 57, 150);
                    context.font = '35px 篆書';
                    context.fillText(res12, width / 2, 87, 150);
                    context.font = '35px 篆書';
                    context.fillText(res13, width / 2, 117, 150);
                }
                if(res91.length==6 && res101.length==6 && res111.length==5 && res121.length==5  && res131.length==5){
                    context.font = '33px 篆書';
                    context.fillText(res91, width / 2, -1, 150);
                    context.font = '33px 篆書';
                    context.fillText(res101, width / 2, 27, 150);
                    context.font = '35px 篆書';
                    context.fillText(res111, width / 2, 56, 150);
                    context.font = '35px 篆書';
                    context.fillText(res121, width / 2, 86, 150);
                    context.font = '35px 篆書';
                    context.fillText(res131, width / 2, 116, 150);
                }
                if(res911.length==6 && res1011.length==6 && res1111.length==6 && res1211.length==5  && res1311.length==5){
                    context.font = '33px 篆書';
                    context.fillText(res911, width / 2, -1, 150);
                    context.font = '33px 篆書';
                    context.fillText(res1011, width / 2, 27, 150);
                    context.font = '33px 篆書';
                    context.fillText(res1111, width / 2, 57, 150);
                    context.font = '35px 篆書';
                    context.fillText(res1211, width / 2, 86, 150);
                    context.font = '35px 篆書';
                    context.fillText(res1311, width / 2, 116, 150);
                }
                if(res9.length==6 && res10.length==6 && res11.length==6 && res12.length==6  && res13.length==5){
                    context.font = '33px 篆書';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '33px 篆書';
                    context.fillText(res10, width / 2, 27, 150);
                    context.font = '33px 篆書';
                    context.fillText(res11, width / 2, 57, 150);
                    context.font = '33px 篆書';
                    context.fillText(res12, width / 2, 87, 150);
                    context.font = '35px 篆書';
                    context.fillText(res13, width / 2, 116, 150);
                }
                if(res9.length==6 && res10.length==6 && res11.length==6 && res12.length==6  && res13.length==6){
                    context.font = '33px 篆書';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '33px 篆書';
                    context.fillText(res10, width / 2, 29, 150);
                    context.font = '33px 篆書';
                    context.fillText(res11, width / 2, 59, 150);
                    context.font = '33px 篆書';
                    context.fillText(res12, width / 2, 89, 150);
                    context.font = '33px 篆書';
                    context.fillText(res13, width / 2, 119, 150);
                }

                context.stroke();
            }
            else if(text.length<43 && text.length>=31){
                var res14 = text.slice(0,Math.ceil(text.length/6));
                var res15 = text.slice(Math.ceil(text.length/6),Math.ceil(text.length*2/6));
                var res16 = text.slice(Math.ceil(text.length*2/6),Math.ceil(text.length*3/6));
                var res17 = text.slice(Math.ceil(text.length*3/6),Math.ceil(text.length*4/6));
                var res18 = text.slice(Math.ceil(text.length*4/6),Math.ceil(text.length*5/6));
                var res19 = text.slice(Math.ceil(text.length*5/6));
                var res141 =text.slice(0,6);
                var res151 =text.slice(6,12);
                var res161 =text.slice(12,17);
                var res171 =text.slice(17,22);
                var res181 =text.slice(22,27);
                var res191 =text.slice(27);
                var res142 =text.slice(0,6);
                var res152 =text.slice(6,12);
                var res162 =text.slice(12,18);
                var res172 =text.slice(18,23);
                var res182 =text.slice(23,28);
                var res192 =text.slice(28);
                var res143 =text.slice(0,6);
                var res153 =text.slice(6,12);
                var res163 =text.slice(12,18);
                var res173 =text.slice(18,24);
                var res183 =text.slice(24,29);
                var res193 =text.slice(29);
                var res144 =text.slice(0,7);
                var res154 =text.slice(7,14);
                var res164 =text.slice(14,20);
                var res174 =text.slice(20,26);
                var res184 =text.slice(26,32);
                var res194 =text.slice(32);
                var res145 =text.slice(0,7);
                var res155 =text.slice(7,14);
                var res165 =text.slice(14,21);
                var res175 =text.slice(21,27);
                var res185 =text.slice(27,33);
                var res195 =text.slice(33);
                var res146 =text.slice(0,7);
                var res156 =text.slice(7,14);
                var res166 =text.slice(14,21);
                var res176 =text.slice(21,28);
                var res186 =text.slice(28,34);
                var res196 =text.slice(34);
                if(res14.length==6 && res15.length==5 && res16.length==5 && res17.length==5  && res18.length==5 && res19.length==5){
                    context.font = '27px 篆書';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '30px 篆書';
                    context.fillText(res15, width / 2, 22, 150);
                    context.font = '30px 篆書';
                    context.fillText(res16, width / 2, 47, 150);
                    context.font = '30px 篆書';
                    context.fillText(res17, width / 2, 72, 150);
                    context.font = '30px 篆書';
                    context.fillText(res18, width / 2, 97, 150);
                    context.font = '30px 篆書';
                    context.fillText(res19, width / 2, 122, 150);

                }
                if(res141.length==6 && res151.length==6 && res161.length==5 && res171.length==5  && res181.length==5 && res191.length==5){
                    context.font = '27px 篆書';
                    context.fillText(res141, width / 2, -1, 150);
                    context.font = '27px 篆書';
                    context.fillText(res151, width / 2, 23, 150);
                    context.font = '30px 篆書';
                    context.fillText(res161, width / 2, 47, 150);
                    context.font = '30px 篆書';
                    context.fillText(res171, width / 2, 72, 150);
                    context.font = '30px 篆書';
                    context.fillText(res181, width / 2, 97, 150);
                    context.font = '30px 篆書';
                    context.fillText(res191, width / 2, 122, 150);
                }
                if(res142.length==6 && res152.length==6 && res162.length==6 && res172.length==5  && res182.length==5 && res192.length==5){
                    context.font = '27px 篆書';
                    context.fillText(res142, width / 2, -1, 150);
                    context.font = '27px 篆書';
                    context.fillText(res152, width / 2, 23, 150);
                    context.font = '27px 篆書';
                    context.fillText(res162, width / 2, 48, 150);
                    context.font = '30px 篆書';
                    context.fillText(res172, width / 2, 72, 150);
                    context.font = '30px 篆書';
                    context.fillText(res182, width / 2, 97, 150);
                    context.font = '30px 篆書';
                    context.fillText(res192, width / 2, 122, 150);

                }
                if(res143.length==6 && res153.length==6 && res163.length==6 && res173.length==6  && res183.length==5 && res193.length==5){
                    context.font = '27px 篆書';
                    context.fillText(res143, width / 2, -1, 150);
                    context.font = '27px 篆書';
                    context.fillText(res153, width / 2, 23, 150);
                    context.font = '27px 篆書';
                    context.fillText(res163, width / 2, 48, 150);
                    context.font = '27px 篆書';
                    context.fillText(res173, width / 2, 73, 150);
                    context.font = '30px 篆書';
                    context.fillText(res183, width / 2, 97, 150);
                    context.font = '30px 篆書';
                    context.fillText(res193, width / 2, 122, 150);

                }
                if(res14.length==6 && res15.length==6 && res16.length==6 && res17.length==6  && res18.length==6 && res19.length==5){
                    context.font = '27px 篆書';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '27px 篆書';
                    context.fillText(res15, width / 2, 23, 150);
                    context.font = '27px 篆書';
                    context.fillText(res16, width / 2, 48, 150);
                    context.font = '27px 篆書';
                    context.fillText(res17, width / 2, 73, 150);
                    context.font = '27px 篆書';
                    context.fillText(res18, width / 2, 98, 150);
                    context.font = '30px 篆書';
                    context.fillText(res19, width / 2, 122, 150);
                }
                if(res14.length==6 && res15.length==6 && res16.length==6 && res17.length==6  && res18.length==6 && res19.length==6){
                    context.font = '27px 篆書';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '27px 篆書';
                    context.fillText(res15, width / 2, 23, 150);
                    context.font = '27px 篆書';
                    context.fillText(res16, width / 2, 48, 150);
                    context.font = '27px 篆書';
                    context.fillText(res17, width / 2, 73, 150);
                    context.font = '27px 篆書';
                    context.fillText(res18, width / 2, 98, 150);
                    context.font = '27px 篆書';
                    context.fillText(res19, width / 2, 123, 150);

                }
                if(res14.length==7 && res15.length==6 && res16.length==6 && res17.length==6  && res18.length==6 && res19.length==6){
                    context.font = '26px 篆書';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '28px 篆書';
                    context.fillText(res15, width / 2, 23, 150);
                    context.font = '28px 篆書';
                    context.fillText(res16, width / 2, 48, 150);
                    context.font = '28px 篆書';
                    context.fillText(res17, width / 2, 73, 150);
                    context.font = '28px 篆書';
                    context.fillText(res18, width / 2, 98, 150);
                    context.font = '28px 篆書';
                    context.fillText(res19, width / 2, 123, 150);

                }
                if(res144.length==7 && res154.length==7 && res164.length==6 && res174.length==6  && res184.length==6 && res194.length==6){
                    context.font = '26px 篆書';
                    context.fillText(res144, width / 2, -1, 150);
                    context.font = '28px 篆書';
                    context.fillText(res154, width / 2, 23, 150);
                    context.font = '28px 篆書';
                    context.fillText(res164, width / 2, 48, 150);
                    context.font = '28px 篆書';
                    context.fillText(res174, width / 2, 73, 150);
                    context.font = '28px 篆書';
                    context.fillText(res184, width / 2, 98, 150);
                    context.font = '28px 篆書';
                    context.fillText(res194, width / 2, 123, 150);

                }
                if(res145.length==7 && res155.length==7 && res165.length==7 && res175.length==6  && res185.length==6 && res195.length==6){
                    context.font = '26px 篆書';
                    context.fillText(res145, width / 2, -1, 150);
                    context.font = '26px 篆書';
                    context.fillText(res155, width / 2, 23, 150);
                    context.font = '26px 篆書';
                    context.fillText(res165, width / 2, 47, 150);
                    context.font = '28px 篆書';
                    context.fillText(res175, width / 2, 72, 150);
                    context.font = '28px 篆書';
                    context.fillText(res185, width / 2, 97, 150);
                    context.font = '28px 篆書';
                    context.fillText(res195, width / 2, 122, 150);

                }
                if(res146.length==7 && res156.length==7 && res166.length==7 && res176.length==7  && res186.length==6 && res196.length==6){
                    context.font = '26px 篆書';
                    context.fillText(res146, width / 2, -1, 150);
                    context.font = '26px 篆書';
                    context.fillText(res156, width / 2, 23, 150);
                    context.font = '26px 篆書';
                    context.fillText(res166, width / 2, 47, 150);
                    context.font = '26px 篆書';
                    context.fillText(res176, width / 2, 72, 150);
                    context.font = '28px 篆書';
                    context.fillText(res186, width / 2, 97, 150);
                    context.font = '28px 篆書';
                    context.fillText(res196, width / 2, 122, 150);

                }
                if(res14.length==7 && res15.length==7 && res16.length==7 && res17.length==7  && res18.length==7 && res19.length==6){
                    context.font = '26px 篆書';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '26px 篆書';
                    context.fillText(res15, width / 2, 24, 150);
                    context.font = '26px 篆書';
                    context.fillText(res16, width / 2, 49, 150);
                    context.font = '26px 篆書';
                    context.fillText(res17, width / 2, 74, 150);
                    context.font = '26px 篆書';
                    context.fillText(res18, width / 2, 99, 150);
                    context.font = '28px 篆書';
                    context.fillText(res19, width / 2, 123, 150);

                }
                if(res14.length==7 && res15.length==7 && res16.length==7 && res17.length==7  && res18.length==7 && res19.length==7){
                    context.font = '26px 篆書';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '26px 篆書';
                    context.fillText(res15, width / 2, 24, 150);
                    context.font = '26px 篆書';
                    context.fillText(res16, width / 2, 49, 150);
                    context.font = '26px 篆書';
                    context.fillText(res17, width / 2, 74, 150);
                    context.font = '26px 篆書';
                    context.fillText(res18, width / 2, 99, 150);
                    context.font = '26px 篆書';
                    context.fillText(res19, width / 2, 124, 150);
                }
                context.stroke();
            }
        }
        else if(change == 2) {
            if(text.length<3){
                if(text.length==1){
                    context.font = '170px 印相';
                    context.fillText(text, 72.5, -10, 153);
                }
                if(text.length==2){
                    context.font = '170px 印相';
                    context.fillText(text, 73.5, -10, 150);
                }
                context.stroke();
            }
            else if(text.length<7 && text.length>=3) {
                var res = text.slice(0,Math.ceil(text.length/2));
                var res1 = text.slice(Math.ceil(text.length/2));
                if(res.length==2 && res1.length == 1){
                    context.font = '90px 印相';
                    context.fillText(res, width / 2, -7, 150);
                    context.font = '95px 印相';
                    context.scale(1.6,0.9);
                    context.fillText(res1, 45, 78, 153);
                }
                else if(res.length==2 && res1.length==2){
                    context.font = '85px 印相';
                    context.fillText(res, 73, -5, 150);
                    context.font = '85px 印相';
                    context.fillText(res1, 73, 70, 150);
                }
                else if(res.length==3 && res1.length==2){
                    context.font = '85px 印相';
                    context.fillText(res, width / 2, -5, 150);
                    context.font = '85px 印相';
                    context.fillText(res1, 73, 70, 150);
                }
                else if(res.length==3 && res1.length==3){
                    context.font = '85px 印相';
                    context.fillText(res, width / 2, -5, 150);
                    context.font = '85px 印相';
                    context.fillText(res1, width / 2, 70, 150);
                }
                context.stroke();
            }
            else if(text.length<13 && text.length>=7){
                var res2 = text.slice(0,Math.ceil(text.length/3));
                var res3 = text.slice(Math.ceil(text.length/3),Math.ceil(text.length*2/3));
                var res4 = text.slice(Math.ceil(text.length*2/3));
                if(res2.length==3 && res3.length==2 && res4.length==2){
                    context.font = '60px 印相';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '62px 印相';
                    context.scale(1.2,0.9);
                    context.fillText(res3, 61.5, 52, 150);
                    context.font = '62px 印相';
                    context.fillText(res4, 61.5, 107, 150);
                }
                else if(res2.length==3 && res3.length==3 && res4.length==2){
                    context.font = '60px 印相';
                    context.fillText(res2, width / 2, -4, 150);
                    context.font = '60px 印相';
                    context.fillText(res3, width / 2, 47, 150);
                    context.font = '62px 印相';
                    context.scale(1.2,0.9);
                    context.fillText(res4, 61.5, 109, 150);
                }
                else if(res2.length==3 && res3.length==3 && res4.length==3){
                    context.font = '57px 印相';
                    context.fillText(res2, width / 2, -4, 150);
                    context.font = '57px 印相';
                    context.fillText(res3, width / 2, 46, 150);
                    context.font = '57px 印相';
                    context.fillText(res4, width / 2, 95, 150);
                }
                else if(res2.length==4 && res3.length==3 && res4.length==3){
                    context.font = '57px 印相';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '60px 印相';
                    context.fillText(res3, width / 2, 45, 150);
                    context.font = '60px 印相';
                    context.fillText(res4, width / 2, 95, 150);
                }
                else if(res2.length==4 && res3.length==4 && res4.length==3){
                    context.font = '57px 印相';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '57px 印相';
                    context.fillText(res3, width / 2, 45, 150);
                    context.font = '60px 印相';
                    context.fillText(res4, width / 2, 95, 150);
                }
                else if(res2.length==4 && res3.length==4 && res4.length==4){
                    context.font = '57px 印相';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '57px 印相';
                    context.fillText(res3, width / 2, 45, 150);
                    context.font = '57px 印相';
                    context.fillText(res4, width / 2, 95, 150);
                }
                context.stroke();
            }
            else if(text.length<21 && text.length>=13){
                var res5 = text.slice(0,Math.ceil(text.length/4));
                var res6 = text.slice(Math.ceil(text.length/4),Math.ceil(text.length/2));
                var res7 = text.slice(Math.ceil(text.length/2),Math.ceil(text.length*3/4));
                var res8 = text.slice(Math.ceil(text.length*3/4));
                var res51 = text.slice(0,4);
                var res61 = text.slice(4,8);
                var res71 = text.slice(8,11);
                var res81 = text.slice(11);
                var res52 = text.slice(0,5);
                var res62 = text.slice(5,10);
                var res72 = text.slice(10,14);
                var res82 = text.slice(14);
                if(res5.length==4 && res6.length==3 && res7.length==3 && res8.length==3){
                    context.font = '43px 印相';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '45px 印相';
                    context.scale(1.1,1);
                    context.fillText(res6, 68, 34, 150);
                    context.font = '45px 印相';
                    context.fillText(res7, 68, 71, 150);
                    context.font = '45px 印相';
                    context.fillText(res8, 68, 108, 150);
                }
                else if(res51.length==4 && res61.length==4 && res71.length==3 && res81.length==3){
                    context.font = '43px 印相';
                    context.fillText(res51, width / 2, -3, 150);
                    context.font = '43px 印相';
                    context.fillText(res61, width / 2, 34, 150);
                    context.font = '43px 印相';
                    context.scale(1.1,1);
                    context.fillText(res71, 68, 69, 150);
                    context.font = '45px 印相';
                    context.fillText(res81, 68, 108, 150);
                }
                else if(res5.length==4 && res6.length==4 && res7.length==4 && res8.length==3){
                    context.font = '43px 印相';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '43px 印相';
                    context.fillText(res6, width / 2, 34, 150);
                    context.font = '43px 印相';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '45px 印相';
                    context.scale(1.1,1);
                    context.fillText(res8, 68, 108, 150);
                }
                else if(res5.length==4 && res6.length==4 && res7.length==4 && res8.length==4){
                    context.font = '43px 印相';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '43px 印相';
                    context.fillText(res6, width / 2, 35, 150);
                    context.font = '43px 印相';
                    context.fillText(res7, width / 2, 73, 150);
                    context.font = '43px 印相';
                    context.fillText(res8, width / 2, 111, 150);
                }
                else if(res5.length==5 && res6.length==4 && res7.length==4 && res8.length==4){
                    context.font = '40px 印相';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '43px 印相';
                    context.fillText(res6, width / 2, 33, 150);
                    context.font = '43px 印相';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '43px 印相';
                    context.fillText(res8, width / 2, 109, 150);
                }
                else if(res52.length==5 && res62.length==5 && res72.length==4 && res82.length==4){
                    context.font = '40px 印相';
                    context.textAlign = 'center';
                    context.fillText(res52, width / 2, -3, 150);
                    context.font = '40px 印相';
                    context.fillText(res62, width / 2, 33, 150);
                    context.font = '43px 印相';
                    context.fillText(res72, width / 2, 71, 150);
                    context.font = '43px 印相';
                    context.fillText(res82, width / 2, 109, 150);
                }
                else if(res5.length==5 && res6.length==5 && res7.length==5 && res8.length==4){
                    context.font = '40px 印相';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '40px 印相';
                    context.fillText(res6, width / 2, 33, 150);
                    context.font = '40px 印相';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '43px 印相';
                    context.fillText(res8, width / 2, 109, 150);
                }
                else if(res5.length==5 && res6.length==5 && res7.length==5 && res8.length==5){
                    context.font = '40px 印相';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '40px 印相';
                    context.fillText(res6, width / 2, 33, 150);
                    context.font = '40px 印相';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '40px 印相';
                    context.fillText(res8, width / 2, 109, 150);
                }
                context.stroke();
            }
            else if(text.length<31 && text.length>=21){
                var res9 = text.slice(0,Math.ceil(text.length/5));
                var res10 = text.slice(Math.ceil(text.length/5),Math.ceil(text.length*2/5));
                var res11 = text.slice(Math.ceil(text.length*2/5),Math.ceil(text.length*3/5));
                var res12 = text.slice(Math.ceil(text.length*3/5),Math.ceil(text.length*4/5));
                var res13 = text.slice(Math.ceil(text.length*4/5));
                var res92 = text.slice(0,5);
                var res102 = text.slice(5,10);
                var res112 = text.slice(10,14);
                var res122 = text.slice(14,18);
                var res132 = text.slice(18);
                var res93 = text.slice(0,5);
                var res103 = text.slice(5,10);
                var res113 = text.slice(10,15);
                var res123 = text.slice(15,19);
                var res133 = text.slice(19);
                var res91 = text.slice(0,6);
                var res101 = text.slice(6,12);
                var res111 = text.slice(12,17);
                var res121 = text.slice(17,22);
                var res131 = text.slice(22);
                var res911 = text.slice(0,6);
                var res1011 = text.slice(6,12);
                var res1111 = text.slice(12,18);
                var res1211 = text.slice(18,23);
                var res1311 = text.slice(23);
                if (res9.length==5 && res10.length==4 && res11.length==4 && res12.length==4  && res13.length==4){
                    context.font = '35px 印相';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 印相';
                    context.scale(1.1,1);
                    context.fillText(res10, 70, 27, 150);
                    context.font = '35px 印相';
                    context.fillText(res11, 70, 57, 150);
                    context.font = '35px 印相';
                    context.fillText(res12, 70, 87, 150);
                    context.font = '35px 印相';
                    context.fillText(res13, 70, 117, 150);
                }
                if(res92.length==5 && res102.length==5 && res112.length==4 && res122.length==4  && res132.length==4){
                    context.font = '35px 印相';
                    context.fillText(res92, width / 2, -1, 150);
                    context.font = '35px 印相';
                    context.fillText(res102, width / 2, 28, 150);
                    context.font = '35px 印相';
                    context.scale(1.1,1);
                    context.fillText(res112, 70, 57, 150);
                    context.font = '35px 印相';
                    context.fillText(res122, 70, 87, 150);
                    context.font = '35px 印相';
                    context.fillText(res132, 70, 117, 150);
                }
                if(res93.length==5 && res103.length==5 && res113.length==5 && res123.length==4  && res133.length==4){
                    context.font = '35px 印相';
                    context.fillText(res93, width / 2, -1, 150);
                    context.font = '35px 印相';
                    context.fillText(res103, width / 2, 28, 150);
                    context.font = '35px 印相';
                    context.fillText(res113, width / 2, 58, 150);
                    context.font = '35px 印相';
                    context.scale(1.1,1);
                    context.fillText(res123, 70, 87, 150);
                    context.font = '35px 印相';
                    context.fillText(res133, 70, 117, 150);
                }
                if(res9.length==5 && res10.length==5 && res11.length==5 && res12.length==5  && res13.length==4){
                    context.font = '35px 印相';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 印相';
                    context.fillText(res10, width / 2, 28, 150);
                    context.font = '35px 印相';
                    context.fillText(res11, width / 2, 58, 150);
                    context.font = '35px 印相';
                    context.fillText(res12, width / 2, 88, 150);
                    context.font = '35px 印相';
                    context.scale(1.1,1);
                    context.fillText(res13, 70, 117, 150);
                }
                if(res9.length==5 && res10.length==5 && res11.length==5 && res12.length==5  && res13.length==5){
                    context.font = '35px 印相';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 印相';
                    context.fillText(res10, width / 2, 28, 150);
                    context.font = '35px 印相';
                    context.fillText(res11, width / 2, 58, 150);
                    context.font = '35px 印相';
                    context.fillText(res12, width / 2, 88, 150);
                    context.font = '35px 印相';
                    context.fillText(res13, width / 2, 118, 150);
                }
                if(res9.length==6 && res10.length==5 && res11.length==5 && res12.length==5  && res13.length==5){
                    context.font = '33px 印相';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 印相';
                    context.fillText(res10, width / 2, 27, 150);
                    context.font = '35px 印相';
                    context.fillText(res11, width / 2, 57, 150);
                    context.font = '35px 印相';
                    context.fillText(res12, width / 2, 87, 150);
                    context.font = '35px 印相';
                    context.fillText(res13, width / 2, 117, 150);
                }
                if(res91.length==6 && res101.length==6 && res111.length==5 && res121.length==5  && res131.length==5){
                    context.font = '33px 印相';
                    context.fillText(res91, width / 2, -1, 150);
                    context.font = '33px 印相';
                    context.fillText(res101, width / 2, 27, 150);
                    context.font = '35px 印相';
                    context.fillText(res111, width / 2, 56, 150);
                    context.font = '35px 印相';
                    context.fillText(res121, width / 2, 86, 150);
                    context.font = '35px 印相';
                    context.fillText(res131, width / 2, 116, 150);
                }
                if(res911.length==6 && res1011.length==6 && res1111.length==6 && res1211.length==5  && res1311.length==5){
                    context.font = '33px 印相';
                    context.fillText(res911, width / 2, -1, 150);
                    context.font = '33px 印相';
                    context.fillText(res1011, width / 2, 27, 150);
                    context.font = '33px 印相';
                    context.fillText(res1111, width / 2, 57, 150);
                    context.font = '35px 印相';
                    context.fillText(res1211, width / 2, 86, 150);
                    context.font = '35px 印相';
                    context.fillText(res1311, width / 2, 116, 150);
                }
                if(res9.length==6 && res10.length==6 && res11.length==6 && res12.length==6  && res13.length==5){
                    context.font = '33px 印相';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '33px 印相';
                    context.fillText(res10, width / 2, 27, 150);
                    context.font = '33px 印相';
                    context.fillText(res11, width / 2, 57, 150);
                    context.font = '33px 印相';
                    context.fillText(res12, width / 2, 87, 150);
                    context.font = '35px 印相';
                    context.fillText(res13, width / 2, 116, 150);
                }
                if(res9.length==6 && res10.length==6 && res11.length==6 && res12.length==6  && res13.length==6){
                    context.font = '33px 印相';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '33px 印相';
                    context.fillText(res10, width / 2, 29, 150);
                    context.font = '33px 印相';
                    context.fillText(res11, width / 2, 59, 150);
                    context.font = '33px 印相';
                    context.fillText(res12, width / 2, 89, 150);
                    context.font = '33px 印相';
                    context.fillText(res13, width / 2, 119, 150);
                }
                context.stroke();
            }
            else if(text.length<43 && text.length>=31){
                var res14 = text.slice(0,Math.ceil(text.length/6));
                var res15 = text.slice(Math.ceil(text.length/6),Math.ceil(text.length*2/6));
                var res16 = text.slice(Math.ceil(text.length*2/6),Math.ceil(text.length*3/6));
                var res17 = text.slice(Math.ceil(text.length*3/6),Math.ceil(text.length*4/6));
                var res18 = text.slice(Math.ceil(text.length*4/6),Math.ceil(text.length*5/6));
                var res19 = text.slice(Math.ceil(text.length*5/6));
                var res141 =text.slice(0,6);
                var res151 =text.slice(6,12);
                var res161 =text.slice(12,17);
                var res171 =text.slice(17,22);
                var res181 =text.slice(22,27);
                var res191 =text.slice(27);
                var res142 =text.slice(0,6);
                var res152 =text.slice(6,12);
                var res162 =text.slice(12,18);
                var res172 =text.slice(18,23);
                var res182 =text.slice(23,28);
                var res192 =text.slice(28);
                var res143 =text.slice(0,6);
                var res153 =text.slice(6,12);
                var res163 =text.slice(12,18);
                var res173 =text.slice(18,24);
                var res183 =text.slice(24,29);
                var res193 =text.slice(29);
                var res144 =text.slice(0,7);
                var res154 =text.slice(7,14);
                var res164 =text.slice(14,20);
                var res174 =text.slice(20,26);
                var res184 =text.slice(26,32);
                var res194 =text.slice(32);
                var res145 =text.slice(0,7);
                var res155 =text.slice(7,14);
                var res165 =text.slice(14,21);
                var res175 =text.slice(21,27);
                var res185 =text.slice(27,33);
                var res195 =text.slice(33);
                var res146 =text.slice(0,7);
                var res156 =text.slice(7,14);
                var res166 =text.slice(14,21);
                var res176 =text.slice(21,28);
                var res186 =text.slice(28,34);
                var res196 =text.slice(34);
                if(res14.length==6 && res15.length==5 && res16.length==5 && res17.length==5  && res18.length==5 && res19.length==5){
                    context.font = '27px 印相';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '30px 印相';
                    context.fillText(res15, width / 2, 22, 150);
                    context.font = '30px 印相';
                    context.fillText(res16, width / 2, 47, 150);
                    context.font = '30px 印相';
                    context.fillText(res17, width / 2, 72, 150);
                    context.font = '30px 印相';
                    context.fillText(res18, width / 2, 97, 150);
                    context.font = '30px 印相';
                    context.fillText(res19, width / 2, 122, 150);
                }
                if(res141.length==6 && res151.length==6 && res161.length==5 && res171.length==5  && res181.length==5 && res191.length==5){
                    context.font = '27px 印相';
                    context.fillText(res141, width / 2, -1, 150);
                    context.font = '27px 印相';
                    context.fillText(res151, width / 2, 23, 150);
                    context.font = '30px 印相';
                    context.fillText(res161, width / 2, 47, 150);
                    context.font = '30px 印相';
                    context.fillText(res171, width / 2, 72, 150);
                    context.font = '30px 印相';
                    context.fillText(res181, width / 2, 97, 150);
                    context.font = '30px 印相';
                    context.fillText(res191, width / 2, 122, 150);
                }
                if(res142.length==6 && res152.length==6 && res162.length==6 && res172.length==5  && res182.length==5 && res192.length==5){
                    context.font = '27px 印相';
                    context.fillText(res142, width / 2, -1, 150);
                    context.font = '27px 印相';
                    context.fillText(res152, width / 2, 23, 150);
                    context.font = '27px 印相';
                    context.fillText(res162, width / 2, 48, 150);
                    context.font = '30px 印相';
                    context.fillText(res172, width / 2, 72, 150);
                    context.font = '30px 印相';
                    context.fillText(res182, width / 2, 97, 150);
                    context.font = '30px 印相';
                    context.fillText(res192, width / 2, 122, 150);
                }
                if(res143.length==6 && res153.length==6 && res163.length==6 && res173.length==6  && res183.length==5 && res193.length==5){
                    context.font = '27px 印相';
                    context.fillText(res143, width / 2, -1, 150);
                    context.font = '27px 印相';
                    context.fillText(res153, width / 2, 23, 150);
                    context.font = '27px 印相';
                    context.fillText(res163, width / 2, 48, 150);
                    context.font = '27px 印相';
                    context.fillText(res173, width / 2, 73, 150);
                    context.font = '30px 印相';
                    context.fillText(res183, width / 2, 97, 150);
                    context.font = '30px 印相';
                    context.fillText(res193, width / 2, 122, 150);
                }
                if(res14.length==6 && res15.length==6 && res16.length==6 && res17.length==6  && res18.length==6 && res19.length==5){
                    context.font = '27px 印相';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '27px 印相';
                    context.fillText(res15, width / 2, 23, 150);
                    context.font = '27px 印相';
                    context.fillText(res16, width / 2, 48, 150);
                    context.font = '27px 印相';
                    context.fillText(res17, width / 2, 73, 150);
                    context.font = '27px 印相';
                    context.fillText(res18, width / 2, 98, 150);
                    context.font = '30px 印相';
                    context.fillText(res19, width / 2, 122, 150);
                }
                if(res14.length==6 && res15.length==6 && res16.length==6 && res17.length==6  && res18.length==6 && res19.length==6){
                    context.font = '27px 印相';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '27px 印相';
                    context.fillText(res15, width / 2, 23, 150);
                    context.font = '27px 印相';
                    context.fillText(res16, width / 2, 48, 150);
                    context.font = '27px 印相';
                    context.fillText(res17, width / 2, 73, 150);
                    context.font = '27px 印相';
                    context.fillText(res18, width / 2, 98, 150);
                    context.font = '27px 印相';
                    context.fillText(res19, width / 2, 123, 150);
                }
                if(res14.length==7 && res15.length==6 && res16.length==6 && res17.length==6  && res18.length==6 && res19.length==6){
                    context.font = '26px 印相';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '28px 印相';
                    context.fillText(res15, width / 2, 23, 150);
                    context.font = '28px 印相';
                    context.fillText(res16, width / 2, 48, 150);
                    context.font = '28px 印相';
                    context.fillText(res17, width / 2, 73, 150);
                    context.font = '28px 印相';
                    context.fillText(res18, width / 2, 98, 150);
                    context.font = '28px 印相';
                    context.fillText(res19, width / 2, 123, 150);
                }
                if(res144.length==7 && res154.length==7 && res164.length==6 && res174.length==6  && res184.length==6 && res194.length==6){
                    context.font = '26px 印相';
                    context.fillText(res144, width / 2, -1, 150);
                    context.font = '28px 印相';
                    context.fillText(res154, width / 2, 23, 150);
                    context.font = '28px 印相';
                    context.fillText(res164, width / 2, 48, 150);
                    context.font = '28px 印相';
                    context.fillText(res174, width / 2, 73, 150);
                    context.fillStyle = 'red';
                    context.font = '28px 印相';
                    context.textAlign = 'center';
                    context.fillText(res184, width / 2, 98, 150);
                    context.font = '28px 印相';
                    context.fillText(res194, width / 2, 123, 150);
                }
                if(res145.length==7 && res155.length==7 && res165.length==7 && res175.length==6  && res185.length==6 && res195.length==6){
                    context.font = '26px 印相';
                    context.fillText(res145, width / 2, -1, 150);
                    context.font = '26px 印相';
                    context.fillText(res155, width / 2, 23, 150);
                    context.font = '26px 印相';
                    context.fillText(res165, width / 2, 47, 150);
                    context.font = '28px 印相';
                    context.fillText(res175, width / 2, 72, 150);
                    context.font = '28px 印相';
                    context.fillText(res185, width / 2, 97, 150);
                    context.font = '28px 印相';
                    context.fillText(res195, width / 2, 122, 150);
                }
                if(res146.length==7 && res156.length==7 && res166.length==7 && res176.length==7  && res186.length==6 && res196.length==6){
                    context.font = '26px 印相';
                    context.fillText(res146, width / 2, -1, 150);
                    context.font = '26px 印相';
                    context.fillText(res156, width / 2, 23, 150);
                    context.font = '26px 印相';
                    context.fillText(res166, width / 2, 47, 150);
                    context.font = '26px 印相';
                    context.fillText(res176, width / 2, 72, 150);
                    context.font = '28px 印相';
                    context.fillText(res186, width / 2, 97, 150);
                    context.font = '28px 印相';
                    context.fillText(res196, width / 2, 122, 150);
                }
                if(res14.length==7 && res15.length==7 && res16.length==7 && res17.length==7  && res18.length==7 && res19.length==6){
                    context.font = '26px 印相';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '26px 印相';
                    context.fillText(res15, width / 2, 24, 150);
                    context.font = '26px 印相';
                    context.fillText(res16, width / 2, 49, 150);
                    context.font = '26px 印相';
                    context.fillText(res17, width / 2, 74, 150);
                    context.font = '26px 印相';
                    context.fillText(res18, width / 2, 99, 150);
                    context.font = '28px 印相';
                    context.fillText(res19, width / 2, 123, 150);
                }
                if(res14.length==7 && res15.length==7 && res16.length==7 && res17.length==7  && res18.length==7 && res19.length==7){
                    context.font = '26px 印相';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '26px 印相';
                    context.fillText(res15, width / 2, 24, 150);
                    context.font = '26px 印相';
                    context.fillText(res16, width / 2, 49, 150);
                    context.font = '26px 印相';
                    context.fillText(res17, width / 2, 74, 150);
                    context.font = '26px 印相';
                    context.fillText(res18, width / 2, 99, 150);
                    context.font = '26px 印相';
                    context.fillText(res19, width / 2, 124, 150);

                }
                context.stroke();
            }
        }
        else if(change == 3) {
            if(text.length<3){
                if(text.length==1){
                    context.font = '170px 古印';
                    context.fillText(text, 72.5, -10, 153);
                }
                if(text.length==2){
                    context.font = '170px 古印';
                    context.fillText(text, 73.5, -10, 150);
                }
                context.stroke();
            }
            else if(text.length<7 && text.length>=3) {
                var res = text.slice(0,Math.ceil(text.length/2));
                var res1 = text.slice(Math.ceil(text.length/2));
                if(res.length==2 && res1.length == 1){
                    context.font = '90px 古印';
                    context.fillText(res, width / 2, -7, 150);
                    context.font = '95px 古印';
                    context.scale(1.6,0.9);
                    context.fillText(res1, 45, 78, 153);
                }
                else if(res.length==2 && res1.length==2){
                    context.font = '85px 古印';
                    context.fillText(res, 73, -5, 150);
                    context.font = '85px 古印';
                    context.fillText(res1, 73, 70, 150);
                }
                else if(res.length==3 && res1.length==2){
                    context.font = '85px 古印';
                    context.fillText(res, width / 2, -5, 150);
                    context.font = '85px 古印';
                    context.fillText(res1, 73, 70, 150);
                }
                else if(res.length==3 && res1.length==3){
                    context.font = '85px 古印';
                    context.fillText(res, width / 2, -5, 150);
                    context.font = '85px 古印';
                    context.fillText(res1, width / 2, 70, 150);
                }
                context.stroke();
            }
            else if(text.length<13 && text.length>=7){
                var res2 = text.slice(0,Math.ceil(text.length/3));
                var res3 = text.slice(Math.ceil(text.length/3),Math.ceil(text.length*2/3));
                var res4 = text.slice(Math.ceil(text.length*2/3));
                if(res2.length==3 && res3.length==2 && res4.length==2){
                    context.font = '60px 古印';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '62px 古印';
                    context.scale(1.2,0.9);
                    context.fillText(res3, 61.5, 52, 150);
                    context.font = '62px 古印';
                    context.fillText(res4, 61.5, 107, 150);
                }
                else if(res2.length==3 && res3.length==3 && res4.length==2){
                    context.font = '60px 古印';
                    context.fillText(res2, width / 2, -4, 150);
                    context.font = '60px 古印';
                    context.fillText(res3, width / 2, 47, 150);
                    context.font = '62px 古印';
                    context.scale(1.2,0.9);
                    context.fillText(res4, 61.5, 109, 150);
                }
                else if(res2.length==3 && res3.length==3 && res4.length==3){
                    context.font = '57px 古印';
                    context.fillText(res2, width / 2, -4, 150);
                    context.font = '57px 古印';
                    context.fillText(res3, width / 2, 46, 150);
                    context.font = '57px 古印';
                    context.fillText(res4, width / 2, 95, 150);
                }
                else if(res2.length==4 && res3.length==3 && res4.length==3){
                    context.font = '57px 古印';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '60px 古印';
                    context.fillText(res3, width / 2, 45, 150);
                    context.font = '60px 古印';
                    context.fillText(res4, width / 2, 95, 150);
                }
                else if(res2.length==4 && res3.length==4 && res4.length==3){
                    context.font = '57px 古印';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '57px 古印';
                    context.fillText(res3, width / 2, 45, 150);
                    context.font = '60px 古印';
                    context.fillText(res4, width / 2, 95, 150);
                }
                else if(res2.length==4 && res3.length==4 && res4.length==4){
                    context.font = '57px 古印';
                    context.fillText(res2, width / 2, -5, 150);
                    context.font = '57px 古印';
                    context.fillText(res3, width / 2, 45, 150);
                    context.font = '57px 古印';
                    context.fillText(res4, width / 2, 95, 150);
                }
                context.stroke();
            }
            else if(text.length<21 && text.length>=13){
                var res5 = text.slice(0,Math.ceil(text.length/4));
                var res6 = text.slice(Math.ceil(text.length/4),Math.ceil(text.length/2));
                var res7 = text.slice(Math.ceil(text.length/2),Math.ceil(text.length*3/4));
                var res8 = text.slice(Math.ceil(text.length*3/4));
                var res51 = text.slice(0,4);
                var res61 = text.slice(4,8);
                var res71 = text.slice(8,11);
                var res81 = text.slice(11);
                var res52 = text.slice(0,5);
                var res62 = text.slice(5,10);
                var res72 = text.slice(10,14);
                var res82 = text.slice(14);
                if(res5.length==4 && res6.length==3 && res7.length==3 && res8.length==3){
                    context.font = '43px 古印';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '45px 古印';
                    context.scale(1.1,1);
                    context.fillText(res6, 68, 34, 150);
                    context.font = '45px 古印';
                    context.fillText(res7, 68, 71, 150);
                    context.font = '45px 古印';
                    context.fillText(res8, 68, 108, 150);
                }
                else if(res51.length==4 && res61.length==4 && res71.length==3 && res81.length==3){
                    context.font = '43px 古印';
                    context.fillText(res51, width / 2, -3, 150);
                    context.font = '43px 古印';
                    context.fillText(res61, width / 2, 34, 150);
                    context.font = '43px 古印';
                    context.scale(1.1,1);
                    context.fillText(res71, 68, 69, 150);
                    context.font = '45px 古印';
                    context.fillText(res81, 68, 108, 150);
                }
                else if(res5.length==4 && res6.length==4 && res7.length==4 && res8.length==3){
                    context.font = '43px 古印';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '43px 古印';
                    context.fillText(res6, width / 2, 34, 150);
                    context.font = '43px 古印';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '45px 古印';
                    context.scale(1.1,1);
                    context.fillText(res8, 68, 108, 150);
                }
                else if(res5.length==4 && res6.length==4 && res7.length==4 && res8.length==4){
                    context.font = '43px 古印';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '43px 古印';
                    context.fillText(res6, width / 2, 35, 150);
                    context.font = '43px 古印';
                    context.fillText(res7, width / 2, 73, 150);
                    context.font = '43px 古印';
                    context.fillText(res8, width / 2, 111, 150);
                }
                else if(res5.length==5 && res6.length==4 && res7.length==4 && res8.length==4){
                    context.font = '40px 古印';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '43px 古印';
                    context.fillText(res6, width / 2, 33, 150);
                    context.font = '43px 古印';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '43px 古印';
                    context.fillText(res8, width / 2, 109, 150);
                }
                else if(res52.length==5 && res62.length==5 && res72.length==4 && res82.length==4){
                    context.font = '40px 古印';
                    context.textAlign = 'center';
                    context.fillText(res52, width / 2, -3, 150);
                    context.font = '40px 古印';
                    context.fillText(res62, width / 2, 33, 150);
                    context.font = '43px 古印';
                    context.fillText(res72, width / 2, 71, 150);
                    context.font = '43px 古印';
                    context.fillText(res82, width / 2, 109, 150);
                }
                else if(res5.length==5 && res6.length==5 && res7.length==5 && res8.length==4){
                    context.font = '40px 古印';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '40px 古印';
                    context.fillText(res6, width / 2, 33, 150);
                    context.font = '40px 古印';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '43px 古印';
                    context.fillText(res8, width / 2, 109, 150);
                }
                else if(res5.length==5 && res6.length==5 && res7.length==5 && res8.length==5){
                    context.font = '40px 古印';
                    context.fillText(res5, width / 2, -3, 150);
                    context.font = '40px 古印';
                    context.fillText(res6, width / 2, 33, 150);
                    context.font = '40px 古印';
                    context.fillText(res7, width / 2, 71, 150);
                    context.font = '40px 古印';
                    context.fillText(res8, width / 2, 109, 150);
                }
                context.stroke();
            }
            else if(text.length<31 && text.length>=21){
                var res9 = text.slice(0,Math.ceil(text.length/5));
                var res10 = text.slice(Math.ceil(text.length/5),Math.ceil(text.length*2/5));
                var res11 = text.slice(Math.ceil(text.length*2/5),Math.ceil(text.length*3/5));
                var res12 = text.slice(Math.ceil(text.length*3/5),Math.ceil(text.length*4/5));
                var res13 = text.slice(Math.ceil(text.length*4/5));
                var res92 = text.slice(0,5);
                var res102 = text.slice(5,10);
                var res112 = text.slice(10,14);
                var res122 = text.slice(14,18);
                var res132 = text.slice(18);
                var res93 = text.slice(0,5);
                var res103 = text.slice(5,10);
                var res113 = text.slice(10,15);
                var res123 = text.slice(15,19);
                var res133 = text.slice(19);
                var res91 = text.slice(0,6);
                var res101 = text.slice(6,12);
                var res111 = text.slice(12,17);
                var res121 = text.slice(17,22);
                var res131 = text.slice(22);
                var res911 = text.slice(0,6);
                var res1011 = text.slice(6,12);
                var res1111 = text.slice(12,18);
                var res1211 = text.slice(18,23);
                var res1311 = text.slice(23);
                if (res9.length==5 && res10.length==4 && res11.length==4 && res12.length==4  && res13.length==4){
                    context.font = '35px 古印';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 古印';
                    context.scale(1.1,1);
                    context.fillText(res10, 70, 27, 150);
                    context.font = '35px 古印';
                    context.fillText(res11, 70, 57, 150);
                    context.font = '35px 古印';
                    context.fillText(res12, 70, 87, 150);
                    context.font = '35px 古印';
                    context.fillText(res13, 70, 117, 150);
                }
                if(res92.length==5 && res102.length==5 && res112.length==4 && res122.length==4  && res132.length==4){
                    context.font = '35px 古印';
                    context.fillText(res92, width / 2, -1, 150);
                    context.font = '35px 古印';
                    context.fillText(res102, width / 2, 28, 150);
                    context.font = '35px 古印';
                    context.scale(1.1,1);
                    context.fillText(res112, 70, 57, 150);
                    context.font = '35px 古印';
                    context.fillText(res122, 70, 87, 150);
                    context.font = '35px 古印';
                    context.fillText(res132, 70, 117, 150);
                }
                if(res93.length==5 && res103.length==5 && res113.length==5 && res123.length==4  && res133.length==4){
                    context.font = '35px 古印';
                    context.fillText(res93, width / 2, -1, 150);
                    context.font = '35px 古印';
                    context.fillText(res103, width / 2, 28, 150);
                    context.font = '35px 古印';
                    context.fillText(res113, width / 2, 58, 150);
                    context.font = '35px 古印';
                    context.scale(1.1,1);
                    context.fillText(res123, 70, 87, 150);
                    context.font = '35px 古印';
                    context.fillText(res133, 70, 117, 150);
                }
                if(res9.length==5 && res10.length==5 && res11.length==5 && res12.length==5  && res13.length==4){
                    context.font = '35px 古印';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 古印';
                    context.fillText(res10, width / 2, 28, 150);
                    context.font = '35px 古印';
                    context.fillText(res11, width / 2, 58, 150);
                    context.font = '35px 古印';
                    context.fillText(res12, width / 2, 88, 150);
                    context.font = '35px 古印';
                    context.scale(1.1,1);
                    context.fillText(res13, 70, 117, 150);
                }
                if(res9.length==5 && res10.length==5 && res11.length==5 && res12.length==5  && res13.length==5){
                    context.font = '35px 古印';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 古印';
                    context.fillText(res10, width / 2, 28, 150);
                    context.font = '35px 古印';
                    context.fillText(res11, width / 2, 58, 150);
                    context.font = '35px 古印';
                    context.fillText(res12, width / 2, 88, 150);
                    context.font = '35px 古印';
                    context.fillText(res13, width / 2, 118, 150);
                }
                if(res9.length==6 && res10.length==5 && res11.length==5 && res12.length==5  && res13.length==5){
                    context.font = '33px 古印';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '35px 古印';
                    context.fillText(res10, width / 2, 27, 150);
                    context.font = '35px 古印';
                    context.fillText(res11, width / 2, 57, 150);
                    context.font = '35px 古印';
                    context.fillText(res12, width / 2, 87, 150);
                    context.font = '35px 古印';
                    context.fillText(res13, width / 2, 117, 150);
                }
                if(res91.length==6 && res101.length==6 && res111.length==5 && res121.length==5  && res131.length==5){
                    context.font = '33px 古印';
                    context.fillText(res91, width / 2, -1, 150);
                    context.font = '33px 古印';
                    context.fillText(res101, width / 2, 27, 150);
                    context.font = '35px 古印';
                    context.fillText(res111, width / 2, 56, 150);
                    context.font = '35px 古印';
                    context.fillText(res121, width / 2, 86, 150);
                    context.font = '35px 古印';
                    context.fillText(res131, width / 2, 116, 150);
                }
                if(res911.length==6 && res1011.length==6 && res1111.length==6 && res1211.length==5  && res1311.length==5){
                    context.font = '33px 古印';
                    context.fillText(res911, width / 2, -1, 150);
                    context.font = '33px 古印';
                    context.fillText(res1011, width / 2, 27, 150);
                    context.font = '33px 古印';
                    context.fillText(res1111, width / 2, 57, 150);
                    context.font = '35px 古印';
                    context.fillText(res1211, width / 2, 86, 150);
                    context.font = '35px 古印';
                    context.fillText(res1311, width / 2, 116, 150);
                }
                if(res9.length==6 && res10.length==6 && res11.length==6 && res12.length==6  && res13.length==5){
                    context.font = '33px 古印';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '33px 古印';
                    context.fillText(res10, width / 2, 27, 150);
                    context.font = '33px 古印';
                    context.fillText(res11, width / 2, 57, 150);
                    context.font = '33px 古印';
                    context.fillText(res12, width / 2, 87, 150);
                    context.font = '35px 古印';
                    context.fillText(res13, width / 2, 116, 150);
                }
                if(res9.length==6 && res10.length==6 && res11.length==6 && res12.length==6  && res13.length==6){
                    context.font = '33px 古印';
                    context.fillText(res9, width / 2, -1, 150);
                    context.font = '33px 古印';
                    context.fillText(res10, width / 2, 29, 150);
                    context.font = '33px 古印';
                    context.fillText(res11, width / 2, 59, 150);
                    context.font = '33px 古印';
                    context.fillText(res12, width / 2, 89, 150);
                    context.font = '33px 古印';
                    context.fillText(res13, width / 2, 119, 150);
                }
                context.stroke();
            }
            else if(text.length<43 && text.length>=31){
                var res14 = text.slice(0,Math.ceil(text.length/6));
                var res15 = text.slice(Math.ceil(text.length/6),Math.ceil(text.length*2/6));
                var res16 = text.slice(Math.ceil(text.length*2/6),Math.ceil(text.length*3/6));
                var res17 = text.slice(Math.ceil(text.length*3/6),Math.ceil(text.length*4/6));
                var res18 = text.slice(Math.ceil(text.length*4/6),Math.ceil(text.length*5/6));
                var res19 = text.slice(Math.ceil(text.length*5/6));
                var res141 =text.slice(0,6);
                var res151 =text.slice(6,12);
                var res161 =text.slice(12,17);
                var res171 =text.slice(17,22);
                var res181 =text.slice(22,27);
                var res191 =text.slice(27);
                var res142 =text.slice(0,6);
                var res152 =text.slice(6,12);
                var res162 =text.slice(12,18);
                var res172 =text.slice(18,23);
                var res182 =text.slice(23,28);
                var res192 =text.slice(28);
                var res143 =text.slice(0,6);
                var res153 =text.slice(6,12);
                var res163 =text.slice(12,18);
                var res173 =text.slice(18,24);
                var res183 =text.slice(24,29);
                var res193 =text.slice(29);
                var res144 =text.slice(0,7);
                var res154 =text.slice(7,14);
                var res164 =text.slice(14,20);
                var res174 =text.slice(20,26);
                var res184 =text.slice(26,32);
                var res194 =text.slice(32);
                var res145 =text.slice(0,7);
                var res155 =text.slice(7,14);
                var res165 =text.slice(14,21);
                var res175 =text.slice(21,27);
                var res185 =text.slice(27,33);
                var res195 =text.slice(33);
                var res146 =text.slice(0,7);
                var res156 =text.slice(7,14);
                var res166 =text.slice(14,21);
                var res176 =text.slice(21,28);
                var res186 =text.slice(28,34);
                var res196 =text.slice(34);
                if(res14.length==6 && res15.length==5 && res16.length==5 && res17.length==5  && res18.length==5 && res19.length==5){
                    context.font = '27px 古印';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '30px 古印';
                    context.fillText(res15, width / 2, 22, 150);
                    context.font = '30px 古印';
                    context.fillText(res16, width / 2, 47, 150);
                    context.font = '30px 古印';
                    context.fillText(res17, width / 2, 72, 150);
                    context.font = '30px 古印';
                    context.fillText(res18, width / 2, 97, 150);
                    context.font = '30px 古印';
                    context.fillText(res19, width / 2, 122, 150);
                }
                if(res141.length==6 && res151.length==6 && res161.length==5 && res171.length==5  && res181.length==5 && res191.length==5){
                    context.font = '27px 古印';
                    context.fillText(res141, width / 2, -1, 150);
                    context.font = '27px 古印';
                    context.fillText(res151, width / 2, 23, 150);
                    context.font = '30px 古印';
                    context.fillText(res161, width / 2, 47, 150);
                    context.font = '30px 古印';
                    context.fillText(res171, width / 2, 72, 150);
                    context.font = '30px 古印';
                    context.fillText(res181, width / 2, 97, 150);
                    context.font = '30px 古印';
                    context.fillText(res191, width / 2, 122, 150);
                }
                if(res142.length==6 && res152.length==6 && res162.length==6 && res172.length==5  && res182.length==5 && res192.length==5){
                    context.font = '27px 古印';
                    context.fillText(res142, width / 2, -1, 150);
                    context.font = '27px 古印';
                    context.fillText(res152, width / 2, 23, 150);
                    context.font = '27px 古印';
                    context.fillText(res162, width / 2, 48, 150);
                    context.font = '30px 古印';
                    context.fillText(res172, width / 2, 72, 150);
                    context.font = '30px 古印';
                    context.fillText(res182, width / 2, 97, 150);
                    context.font = '30px 古印';
                    context.fillText(res192, width / 2, 122, 150);
                }
                if(res143.length==6 && res153.length==6 && res163.length==6 && res173.length==6  && res183.length==5 && res193.length==5){
                    context.font = '27px 古印';
                    context.fillText(res143, width / 2, -1, 150);
                    context.font = '27px 古印';
                    context.fillText(res153, width / 2, 23, 150);
                    context.font = '27px 古印';
                    context.fillText(res163, width / 2, 48, 150);
                    context.font = '27px 古印';
                    context.fillText(res173, width / 2, 73, 150);
                    context.font = '30px 古印';
                    context.fillText(res183, width / 2, 97, 150);
                    context.font = '30px 古印';
                    context.fillText(res193, width / 2, 122, 150);
                }
                if(res14.length==6 && res15.length==6 && res16.length==6 && res17.length==6  && res18.length==6 && res19.length==5){
                    context.font = '27px 古印';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '27px 古印';
                    context.fillText(res15, width / 2, 23, 150);
                    context.font = '27px 古印';
                    context.fillText(res16, width / 2, 48, 150);
                    context.font = '27px 古印';
                    context.fillText(res17, width / 2, 73, 150);
                    context.font = '27px 古印';
                    context.fillText(res18, width / 2, 98, 150);
                    context.font = '30px 古印';
                    context.fillText(res19, width / 2, 122, 150);
                }
                if(res14.length==6 && res15.length==6 && res16.length==6 && res17.length==6  && res18.length==6 && res19.length==6){
                    context.font = '27px 古印';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '27px 古印';
                    context.fillText(res15, width / 2, 23, 150);
                    context.font = '27px 古印';
                    context.fillText(res16, width / 2, 48, 150);
                    context.font = '27px 古印';
                    context.fillText(res17, width / 2, 73, 150);
                    context.font = '27px 古印';
                    context.fillText(res18, width / 2, 98, 150);
                    context.font = '27px 古印';
                    context.fillText(res19, width / 2, 123, 150);
                }
                if(res14.length==7 && res15.length==6 && res16.length==6 && res17.length==6  && res18.length==6 && res19.length==6){
                    context.font = '26px 古印';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '28px 古印';
                    context.fillText(res15, width / 2, 23, 150);
                    context.font = '28px 古印';
                    context.fillText(res16, width / 2, 48, 150);
                    context.font = '28px 古印';
                    context.fillText(res17, width / 2, 73, 150);
                    context.font = '28px 古印';
                    context.fillText(res18, width / 2, 98, 150);
                    context.font = '28px 古印';
                    context.fillText(res19, width / 2, 123, 150);
                }
                if(res144.length==7 && res154.length==7 && res164.length==6 && res174.length==6  && res184.length==6 && res194.length==6){
                    context.font = '26px 古印';
                    context.fillText(res144, width / 2, -1, 150);
                    context.font = '28px 古印';
                    context.fillText(res154, width / 2, 23, 150);
                    context.font = '28px 古印';
                    context.fillText(res164, width / 2, 48, 150);
                    context.font = '28px 古印';
                    context.fillText(res174, width / 2, 73, 150);
                    context.font = '28px 古印';
                    context.fillText(res184, width / 2, 98, 150);
                    context.font = '28px 古印';
                    context.fillText(res194, width / 2, 123, 150);
                }
                if(res145.length==7 && res155.length==7 && res165.length==7 && res175.length==6  && res185.length==6 && res195.length==6){
                    context.font = '26px 古印';
                    context.fillText(res145, width / 2, -1, 150);
                    context.font = '26px 古印';
                    context.fillText(res155, width / 2, 23, 150);
                    context.font = '26px 古印';
                    context.fillText(res165, width / 2, 47, 150);
                    context.font = '28px 古印';
                    context.fillText(res175, width / 2, 72, 150);
                    context.font = '28px 古印';
                    context.fillText(res185, width / 2, 97, 150);
                    context.font = '28px 古印';
                    context.fillText(res195, width / 2, 122, 150);
                }
                if(res146.length==7 && res156.length==7 && res166.length==7 && res176.length==7  && res186.length==6 && res196.length==6){
                    context.font = '26px 古印';
                    context.fillText(res146, width / 2, -1, 150);
                    context.font = '26px 古印';
                    context.fillText(res156, width / 2, 23, 150);
                    context.font = '26px 古印';
                    context.fillText(res166, width / 2, 47, 150);
                    context.font = '26px 古印';
                    context.fillText(res176, width / 2, 72, 150);
                    context.font = '28px 古印';
                    context.fillText(res186, width / 2, 97, 150);
                    context.font = '28px 古印';
                    context.fillText(res196, width / 2, 122, 150);
                }
                if(res14.length==7 && res15.length==7 && res16.length==7 && res17.length==7  && res18.length==7 && res19.length==6){
                    context.font = '26px 古印';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '26px 古印';
                    context.fillText(res15, width / 2, 24, 150);
                    context.font = '26px 古印';
                    context.fillText(res16, width / 2, 49, 150);
                    context.font = '26px 古印';
                    context.fillText(res17, width / 2, 74, 150);
                    context.font = '26px 古印';
                    context.fillText(res18, width / 2, 99, 150);
                    context.font = '28px 古印';
                    context.fillText(res19, width / 2, 123, 150);
                }
                if(res14.length==7 && res15.length==7 && res16.length==7 && res17.length==7  && res18.length==7 && res19.length==7){
                    context.font = '26px 古印';
                    context.fillText(res14, width / 2, -1, 150);
                    context.font = '26px 古印';
                    context.fillText(res15, width / 2, 24, 150);
                    context.font = '26px 古印';
                    context.fillText(res16, width / 2, 49, 150);
                    context.font = '26px 古印';
                    context.fillText(res17, width / 2, 74, 150);
                    context.font = '26px 古印';
                    context.fillText(res18, width / 2, 99, 150);
                    context.font = '26px 古印';
                    context.fillText(res19, width / 2, 124, 150);

                }
                context.stroke();
            }
        }
        downLode();
    }
    setText = function () {
        text = fn.value;
    }
    $("#fn").focus(function(){
        $('#canvas').remove();
        $('#container').append('<canvas width="150" height="150" id="canvas"></canvas>');
        container=document.getElementById("canvas");
        context=container.getContext("2d");
        container.style.writingMode="vertical-rl";
    });
}
