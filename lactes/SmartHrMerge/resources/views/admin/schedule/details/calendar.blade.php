<div id="calendar-div">
    <table class="w-100 text-center">
        <tr class="first-tr">
            <td class="text-left"><button class="btn btn-success" onclick="nowMonth()">本月</button></td>
            <td class="text-right">
                <span onclick="lastYear()"><img src="{{ asset('assets/img/mostleft.png') }}"></span>
                <span onclick="lastMonth()"><img src="{{ asset('assets/img/left.png') }}"></span>
            </td>
            <td colspan="3" class="year-month">
                2021/11
            </td>
            <td class="text-left">
                <span onclick="nextMonth()"><img src="{{ asset('assets/img/right.png') }}"></span>
                <span onclick="nextYear()"><img src="{{ asset('assets/img/mostright.png') }}"></span>
            </td>
            <td class="text-left memberSelectTd">
                <div class="memberSelectDiv" style="">
                    <div class="" style="padding-top: 1px">
                        <select class="select memberSelect form-control" onchange="itemSelectChange(this,function (){calendarDraw()});">
                            @foreach($members as $member)
                                <option value="{{$member->id}}">{{$member->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="week-name">
            <td>日</td>
            <td>月</td>
            <td>火</td>
            <td>水</td>
            <td>木</td>
            <td>金</td>
            <td>土</td>

        </tr>
    </table>
</div>
@section('footer_append_calendar')
<script type="text/javascript">

    $(function () {
        nowMonth();
    })
    function calendarDraw() {
        $("#calendar-div .year-month").html(time[0]+"年"+time[1]+"月");
        let week_day = new Date(time[0],time[1]-1,1).getDay()
        let days = new Date(time[0],time[1], 0).getDate()
        $.ajax({
            url: "{{route('schedules.calendar')}}",
            type: "GET",
            data:{time:time,member_id:member_id},
            success: function (response) {
                getWeekBody(week_day,days,response)
            },
            error: function (jqXHR, testStatus, error) {
                ajaxErrorAction(jqXHR, testStatus, error);
            }
        });

    }
    function getWeekBody(week_day,days,data) {
        let week_body = "<tr class='week-body'>";
        for (let i=week_day;i>0;i--){
            week_body+="<td></td>";
        }
        let day = 1;
        let index = 0;
        while(day<=Number(days)){
            if(week_day==7){
                week_body+="</tr><tr class='week-body'>";
                week_day=0
            }
            week_body+="<td><span>"+day+"</span><div class='td-contents'>";
            if(data[index]!=undefined){
                let thisDay = time[0]+'-'+time[1]+'-'+("00"+day).substr((day+"").length);
                let dataTemporary = dataAdd(data,index,thisDay,week_body);
                week_body = dataTemporary[0];
                index = dataTemporary[1];
            }
            week_body+="</div></td>";
            day++
            week_day++;
        }
        for (let i=week_day;i<7;i++){
            week_body+="<td></td>";
        }
        week_body+="</tr>";
        $('#calendar-div .week-body').remove();
        $("#calendar-div table").append(week_body);
    }
    function dataAdd(data,index,thisDay,week_body) {
        if(data[index]!=undefined){
            if(data[index].schedule_date==thisDay){
                week_body+=(data[index].schedule_time).substr(0,5)+"&nbsp"+data[index].title+"<br>";
                index++
                return dataAdd(data,index,thisDay,week_body)
            }else{
                return [week_body,index];
            }
        }
        return [week_body,index];
    }
    function nowMonth() {
        let day = new Date();
        time[0]=day.getFullYear();
        time[1]=day.getMonth()+1;
        // time=[day.getFullYear(),day.getMonth()+1];
        calendarDraw()
    }
    function nextMonth() {
        if(time[1]==12){
            time[0]=time[0]+1;
            time[1]=1;
        }else{
            time[1]=time[1]+1;
        }
        calendarDraw()
    }
    function nextYear() {
        time[0]=time[0]+1;
        calendarDraw(time,'data')
    }
    function lastMonth() {
        if(time[1]==1){
            time[0]=time[0]-1;
            time[1]=12;
        }else{
            time[1]=time[1]-1;
        }
        calendarDraw()
    }
    function lastYear() {
        time[0]=time[0]-1;
        calendarDraw()
    }
</script>
@endsection
