let manage_code_selector = "input[name=project_manage_code]";
let date_selector = "input[name=period]";
let init_val_created_date = $(date_selector).val();
let init_val_manage_code = $(manage_code_selector).val();
const POSITION_IN = "1";
const FOLDER='注文書';
$(function () {
    let for_print = window.location.href.indexOf('toPdf=true')>=0;
    if(!for_print) {
        initSingleDatePicker('input[name="created_date"]', function () {
        }, 'YYYY-MM-DD', true);
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
            initWorkingTime();
        } else {
            if ($("input[name='file_id']").length === 0) calcWorkingTime();
        }
        if ($('#form-A').length > 0) {
            showSeal();
            onChange();
            initCompanyInfoAndRemark();
            initTextarea();
        }
        changeNum();
    }else{
        calcWorkingTime();
        showSeal();
        onChange();
        initCompanyInfoAndRemark();
        initTextarea();
    }
});

function initWorkingTime() {
    if($(date_selector).val()!==''){
        $('input[name=month_sum]').val('1');
    }
}

function calcWorkingTime() {
    const total = toNumber($('input[name=estimate_subtotal]').val());
    const price = toNumber($('input[name=unit_price]').val());
    $('input[name=month_sum]').val(total/price);
}

function onChange() {
    let money = toNumber($("input[name='unit_price']").val());
    let month = $("input[name='month_sum']").val();
    if(month === "") month = 0;
    calcReuse(money, month);
}

//期间修改
function periodChange(el) {
    let period = $(el).val();
    $(date_selector).val(period);
    if($(el).parents('form').first().attr('id')==='form-A'){
        period = periodToMonths(period);
        const month_sum = $("input[name='month_sum']");
        month_sum.val(period);
        let money = $("input[name='unit_price']").val();
        if (money !== undefined && money !== '') {
            calcReuse(money, period);
        }
    }
}

function handleBeforePrint() {
    $('span.rect').each(function () {
        if($(this).hasClass('backcolor-white')){
            $(this).replaceWith('□');
        }else{
            $(this).replaceWith('■');
        }
    });
}

/**
 * 印刷プレビュー前に空白行の追加
 */
function addEmptyLine() {
    const prints = $('.print form');
    prints.each(function () {
        const print = $(this);
        const height = print.height();
        const width = print.width();
        const sum_height = width * 1.7;
        const label = print.find('table tr').last().find('td').last().find('label');
        const diff = (sum_height - height + label.height());
        if(diff>0)
            label.css({'cssText': 'height:' + diff + 'px!important'});
    });
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

//单金修改
function unitPriceChange(event) {
    let unitPrice = $(event).val();
    let period = $("input[name='month_sum']").val();
    if ((unitPrice !== "" && unitPrice !== undefined) && (period !== "" && period !== undefined)) {
        $(event).val(unitPrice);
        calcReuse(unitPrice, period);
    }
}

//手入力工数时 计算金额
function calcPrice(event) {
    let period = $(event).val();
    let money = $("input[name='unit_price']").val();
    if ((money !== "" && money !== undefined) && (period !== "" && period !== undefined)) {
        $(event).val(period);
        calcReuse(money, period);
    }
}

//计算金额 复用
function calcReuse(money, period) {
    money = toNumber(money);
    let total = money * period;
    $("input[name='estimate_subtotal']").val(numberToAmount(total));
}
