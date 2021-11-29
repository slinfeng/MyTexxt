let schedule;
let selectTime=''
let time=[];
let selectTimeArr=[];
let member_id='';
let drawSchedule='schedule';
let contextMenu=$('#context-menu');
let canvasOffsetLeft=0;
let canvasOffsetTop=0;
function itemCalendarDraw(year=time[0],month=time[1],i=2) {
    let table_name = "";
    switch(i){
        case 2:
            table_name = '.this-month';
            break;
        case 1:
            table_name = '.next-month';
            break;
        case 0:
            table_name = ".next-next-month";
            break;
    }
    $("#item-div "+table_name+" .year-month").html(year+"年"+month+"月");
    let week_day = new Date(year,month-1,1).getDay()
    let days = new Date(year,month, 0).getDate()
    let last_days = 0;
    if(month==1){
        last_days = new Date(year-1,12, 0).getDate()
    }else{
        last_days = new Date(year,month-1, 0).getDate()
    }
    itemGetWeekBody(year,month,week_day,days,last_days,table_name);
    if(i==0){
        return ;
    }else{
        i--;
        if(month==12){
            return itemCalendarDraw(year+1,1,i)
        }
        return itemCalendarDraw(year,month+1,i)
    }

}
function itemGetWeekBody(year,month,week_day,days,last_days,table_class) {
    let week_body = "<tr class='week-body'>";
    last_days=last_days-week_day+1;
    for (let i=week_day;i>0;i--){
        week_body+="<td class='last-month-day'>"+last_days+"</td>";
        last_days++;
    }
    let day = 1;
    month="00"+month;
    month=month.substring(month.length-2)

    while(day<=Number(days)){
        let temporaryDay="00"+day;
        temporaryDay=temporaryDay.substring(temporaryDay.length-2);
        let temporaryTime=year+"-"+month+"-"+temporaryDay;
        if(week_day==7){
            week_body+="</tr><tr class='week-body'>";
            week_day=0
        }
        week_body+="<td class='this-month-day";
        if(temporaryTime==selectTime){
            week_body+=" select-td";
            $(".year-month-day").html(year+"年"+month+"月"+day+"日");
        }
        week_body+="' onclick='checkThisDay(this)' data-time='"+temporaryTime+"'>"+day+"</td>";

        day++
        week_day++;
    }
    for (let i=week_day;i<7;i++){
        week_body+="<td></td>";
    }
    week_body+="</tr>";
    $("#item-div "+table_class+" .week-body").remove();
    $("#item-div "+table_class).append(week_body);
}
// function itemNowMonth() {
//     let day = new Date();
//     time[0]=day.getFullYear();
//     time[1]=day.getMonth()+1;
//     itemCalendarDraw()
// }
function itemNextMonth() {
    if(time[1]==12){
        time[0]=time[0]+1;
        time[1]=1;
    }else{
        time[1]=time[1]+1;
    }
    itemCalendarDraw()
}
function itemLastMonth() {
    if(time[1]==1){
        time[0]=time[0]-1;
        time[1]=12;
    }else{
        time[1]=time[1]-1;
    }
    itemCalendarDraw()
}

function selectCalendarDraw() {
    let day = new Date();
    let year=day.getFullYear();
    let month=day.getMonth();
    selectGetWeekBody(year,month+1);
}

function selectLastMonth() {
    let year = $('#calendar-select .year-month').data('year');
    let month = $('#calendar-select .year-month').data('month');
    if(month==1){
        month=12;
        year--
    }else{
        month--;
    }
    selectGetWeekBody(year,month);
}

function selectNextMonth() {
    let year = $('#calendar-select .year-month').data('year');
    let month = $('#calendar-select .year-month').data('month');
    if(month==12){
        month=1;
        year++;
    }else{
        month++;
    }
    selectGetWeekBody(year,month);
}

function selectThisDay(e) {
    let day=$(e).data('time');
    let selectCalendar = $('.select-calendar-show textarea').html();
    if(selectCalendar!=''){
        selectCalendar=selectCalendar.trim();
    }
    if($(e).hasClass('select-td')){
        selectTimeArr.splice(selectTimeArr.indexOf(day), 1)
        $(e).removeClass('select-td');
    }else{
        $(e).addClass('select-td');
        selectTimeArr.push(day)
        selectTimeArr.sort();
    }
    $('.select-calendar-show textarea').html(arrayToString());
}

function selectGetWeekBody(year,month) {
    $('#calendar-select .year-month').html(year+'年'+month+'月').attr({'data-year': year,'data-month':month}).data({'year': year,'month':month});
    let week_day = new Date(year,month-1,1).getDay();
    let days = new Date(year,month, 0).getDate();
    let week_body = "<tr class='week-body'>";
    for (let i=week_day;i>0;i--){
        week_body+="<td></td>";
    }
    let day = 1;
    month="00"+month;
    month=month.substring(month.length-2)
    let index=0;
    while(day<=days){
        let temporaryDay="00"+day;
        temporaryDay=temporaryDay.substring(temporaryDay.length-2);
        let temporaryTime=year+"-"+month+"-"+temporaryDay;
        if(week_day==7){
            week_body+="</tr><tr class='week-body'>";
            week_day=0
        }
        week_body+="<td ";
        while (selectTimeArr[index]!==undefined && selectTimeArr[index]<year+"-"+month+"-"+"00"){
            index++;
        }
        if(selectTimeArr[index]!==undefined){
            if(selectTimeArr[index]==temporaryTime){
                week_body+="class='select-td'";
                index++;
            }
        }
        week_body+=" onclick='selectThisDay(this)' data-time='"+temporaryTime+"'>"+day+"</td>";
        day++
        week_day++;
    }
    for (let i=week_day;i<7;i++){
        week_body+="<td></td>";
    }
    week_body+="</tr>";
    $("#calendar-select table .week-body").remove();
    $("#calendar-select table").append(week_body);
    $("#calendar-select textarea").html(arrayToString());
}

function calendarSelect(e){
    selectTimeArr=$(e).val().split(',');
    selectCalendarDraw();
    $('#calendar-select textarea').css('height','140px');
    $('#calendar-select').show();
}

function selectDatePush() {
    var scheduleDateArr=arrayToString();
    scheduleDateArr=scheduleDateArr.replace(/\n/g,'');
    $('input[name=schedule_date_arr]').val(scheduleDateArr);
    $('#calendar-select').hide();
}

function selectDateCancel() {
    $('#calendar-select').hide();
}

function checkThisDay(e) {
    let date=$(e).data('time');
    selectTime = date;
    date=date.split('-');
    time=[Number(date[0]),Number(date[1]),Number(date[2])];
    $(".year-month-day").html(time[0]+"年"+time[1]+"月"+time[2]+"日");
    $('#item-div .select-td').removeClass('select-td');
    $(e).addClass('select-td');
    scheduleDraw();
}

function arrayToString() {
    let str = '';
    selectTimeArr.forEach(function (value) {
        str+=value+",\n"
    })
    str=str.replace(/^(\s|,)+|(\s|,)+$/g,"")
    return str;
}

function initMemberId(e,handle=function () {}) {
    const memberSelectDiv=$('.memberSelectDiv');
    member_id=$(e).data('member-id');
    if(member_id==''){
        memberSelectDiv.hide();
    }else{
        $('.memberSelect').select2('val',[member_id]);
        memberSelectDiv.show();
    }
    handle();
}


function itemSelectChange(e,handle=function () {}) {
    member_id=$(e).val();
    handle();
}

function rightMenuShow(x,y,eventData) {
    contextMenu.css({
        'width':'40px',
        'height':'50px',
        'position':'fixed',
        'left':x+'px',
        'top':y+'px',
        'background-color': '#f0f0f0',
        'border': '1px solid #a0a0a0',
        'z-index':599
    });
    $('#delete').data('id',eventData.dataId);
    contextMenu.find('a').first().on('click',function () {
        schedule.event.editEvent.call(undefined,eventData);
    });
    contextMenu.show();
}

function rightMenuHide() {
    contextMenu.hide();
}
function lastDay() {
    if(time[2]==1){
        if(time[1]==1){
            time[0]=time[0]-1;
            time[1]=12;
            time[2]=31;
        }else{
            time[1]=time[1]-1;
            time[2]=new Date(time[0],time[1], 0).getDate();
        }
    }else{
        time[2]=time[2]-1;
    }
    getSelectTime();
    itemCalendarDraw();
    scheduleDraw();
}
function toDay() {
    let day = new Date();
    time[0]=day.getFullYear();
    time[1]=day.getMonth()+1;
    time[2]=day.getDate();
    getSelectTime();
    itemCalendarDraw();
    scheduleDraw();
}
function nextDay() {
    let days = new Date(time[0],time[1], 0).getDate();
    if(time[2]==days){
        if(time[1]==12){
            time[0]=time[0]+1;
            time[1]=1;
            time[2]=1;
        }else{
            time[1]=time[1]+1;
            time[2]=1;
        }
    }else{
        time[2]=time[2]+1;
    }
    getSelectTime();
    itemCalendarDraw();
    scheduleDraw();
}
function getSelectTime() {
    let temporaryDay="00"+time[2];
    temporaryDay=temporaryDay.substring(temporaryDay.length-2);
    let temporaryMonth="00"+time[1];
    temporaryMonth=temporaryMonth.substring(temporaryMonth.length-2);
    selectTime=time[0]+'-'+temporaryMonth+'-'+temporaryDay;
}
function scaleMove() {
    let scaleLeft = $('#schedule-title .scale').offset().left;
    var event = event || window.event;
    let monseX = event.clientX;
    let left = monseX-scaleLeft-5;
    if(left>=0 && left<=200){
        $("#schedule-title .adjustment").css('left',left+'px');
    }else if(left<0){
        $("#schedule-title .adjustment").css('left','0px');
    }else{
        $("#schedule-title .adjustment").css('left','200px');
    }
}
