let manage_code_selector = "input[name=est_manage_code]";
let date_selector = "input[name=period]";
let init_val_created_date = $(date_selector).val();
let init_val_manage_code = $(manage_code_selector).val();
const POSITION_IN = "2";
const LINE_HEIGHT = 40;
const FOLDER='見積書';
const ACCOUNT_NUM=$('#bank-accounts').find('tr').eq(2).find('td').html();
const ACCOUNT_NAME=$('#bank-accounts').find('tr').eq(3).find('td').html();
$(function(){
    let for_print = window.location.href.indexOf('toPdf=true')>=0;
    if(!for_print) {
        initSingleDatePicker('input[name="created_date"]', function () {
        }, DATE_FORMAT_A, true);
        $(date_selector).each(function () {
            let el = this;
            initDatePicker(this, function () {
                periodChange(el);
                changeNum();
            }, true);
        });
        if (window.location.href.indexOf('create') >= 0) {
            let input_position = $("input[name='our_position_type']:checked");
            if (input_position.val() !== POSITION_IN)
                $("input[name='last_format']").val(1);
            disableFormat(input_position[0]);
            if ($(date_selector).val() !== '') $("input[name='month[]']").val(1);
        } else {
            calcWorkingTime();
        }
        if ($('#form-A').length > 0) {
            initBankAccounts();
            showSeal();
            calcReuse();
            initCompanyInfoAndRemark();
            initTextarea();
        }
    }else{
        calcWorkingTime();
        showSeal();
        calcReuse();
        initCompanyInfoAndRemark();
        initTextarea();
    }
    bankData = bankInfoToArray($("#bank-accounts").data('bank'));
    let bankUse =$("#bank-accounts").data('bankuse');
    bankInfoToSelect(bankData,0,'','','',bankUse);
});

//期间修改
function periodChange(el){
    let period = $(el).val();
    $(date_selector).val(period);
    if($(el).parents('form').first().attr('id')==='form-A'){
        period = periodToMonths(period);
        $("input[name='month[]']").each(function(){
            $(this).val(period);
            let money = $(this).parents("tr").find("input[name='unit_price[]']").val();
            money = toNumber(money);
            let total = money*period;
            $(this).parents("tr").find("input[name='total[]']").val(numberToAmount(total));
        });
        calcReuse();
    }
}
//单金修改
function unitPriceChange(event){
    let unitPrice = $(event).val();
    let month = $(event).parents("tr").find("input[name='month[]']").val();
    let amount = '';
    if(unitPrice!=="" && month!==""){
        unitPrice = toNumber(unitPrice);
        let total = unitPrice*month;
        amount = numberToAmount(total);
    }else{
        amount = CURRENCY+'0';
    }
    $(event).parents("tr").find("input[name='total[]']").val(amount);
    calcReuse();
}
//手入力工数时 计算金额
function calcPrice(event){
    let period = $(event).val();
    let money = $(event).parents("tr").find("input[name='unit_price[]']").val();
    if(money!=="" && period!==""){
        money = toNumber(money);
        let total = money*period;
        $(event).parents("tr").find("input[name='total[]']").val(numberToAmount(total));
        calcReuse();
    }
}
//计算金额 复用
function calcReuse() {
    let totalAll = 0;
    $("tr input[name='total[]']").each(function(){
        if($(this).val()!==''){
            totalAll+=parseInt(toNumber($(this).val()));
        }
    });
    //税率计算
    let taxAll;
    if($("input[name=calc_type]").val() === '0'){
        taxAll=Math.round(totalAll*TAX_RATE/100);
    }else{
        taxAll=Math.floor(totalAll*TAX_RATE/100);
    }
    $("input[name=estimate_subtotal]").first().val(numberToAmount(totalAll));
    $("input[name=subtotal]").val(numberToAmount(totalAll));
    $("input[name=totalTax]").val(numberToAmount(taxAll));
    $("input[name=estimate_total]").val(numberToAmount(totalAll+taxAll));
}

function handleWhenClientChanged(result) {
    changeCalcType(result.calc_type);
}

function changeCalcType(type) {
    const input = $("input[name=calc_type]");
    if(type !== input.val()) {
        input.val(type);
        calcReuse();
    }
}

function calcWorkingTime() {
    $('input[name="month[]"]').each(function () {
        let tr = $(this).parents('tr').first();
        let month = toNumber(tr.find('input[name="total[]"]').val())/toNumber(tr.find('input[name="unit_price[]"]').val());
        $(this).val(month);
    });
}

//追加 行
function addLine(event){
    const tr = $(event).parents('tr');
    tr.after(tr.clone());
    const obj = tr.next();
    obj.find('input').each(function () {
        $(this).val('');
    });
    obj.find('.linePoint').hide();
    obj.find("input[name='month[]']").val(tr.find("input[name='month[]']").val());
}
//删除 行
function deleteLine(event){
    if($("input[name='month[]']").length===1){
        $(event).parents('tr').find('input').each(function () {
            $(this).val('');
        });
    }else{
        $(event).parents('tr').remove();
    }
    calcReuse();
}

let tr_empty;

function handleBeforePrint() {
    tr_empty = $('#projectTable').find('tr').eq(1).clone();
    tr_empty.find('td').empty();
    tr_empty.find('td').height(LINE_HEIGHT);
}

function addEmptyLine() {
    const print = $('.print');
    const heightSum = print.height();
    const heightAll = print.width() * 1.625;
    let rows = Math.round((heightAll - heightSum) / LINE_HEIGHT);
    let flag = rows!==1;
    for (let i = rows; i > 0; i--) {
        let trc = tr_empty.clone();
        if (flag) {
            trc.find('td').eq(1).html('**以下空白**');
            flag = false;
        }
        $('#projectTable tbody').find('tr.employee-info').last().after(trc);
    }
}

function handleAfterPrint() {
    $(date_selector).each(function () {
        let el = this;
        initDatePicker(this,function () {
            periodChange(el);
            changeNum();
        },true);
    });
    initSingleDatePicker('input[name="created_date"]');
}
