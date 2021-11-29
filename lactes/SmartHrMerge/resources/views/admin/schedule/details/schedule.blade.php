<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
<div class="row">
        <div class="col-lg-12">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="row schedule-div">
                        <div>
                            @include('admin.schedule.details.item')
                        </div>
                        <div style="width: calc(100% - 260px)">
                            <div id="schedule-title" class="row m-0">
                                <div class="year-month-day">2021年11月29日</div>
                                <div class="select-day-btn">
                                    <span onclick="lastDay()">
                                        <img src="http://www.smarthr.com/assets/img/left.png">前日
                                    </span>
                                    <span onclick="toDay()">
                                        <img src="http://www.smarthr.com/assets/img/right.png" style="transform: rotate(90deg);">
                                        本日
                                    </span>
                                    <span onclick="nextDay()">
                                        <img src="http://www.smarthr.com/assets/img/right.png">翌日
                                    </span>
                                </div>
                                <div class="scale-adjust row">
                                    <div class="scale-title">表示比例調節</div>
                                    <div class="adjustment-scale" style="width: 210px">
                                        <span class="adjustment"></span>
                                        <span class="scale"></span>
                                    </div>
                                    <div class="memberSelectDiv" style="display: none;width: 200px">
                                        <div style="margin-top: -15px;" class="">
                                            <select class="select memberSelect form-control" onchange="itemSelectChange(this,function (){scheduleDraw()});">
                                                @foreach($members as $member)
                                                    <option value="{{$member->id}}">{{$member->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div id="schedule"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@section('footer_append_schedule')
<script type="text/javascript">


</script>
@endsection
