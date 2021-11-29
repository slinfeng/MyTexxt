<div class="tab-content">
    <div class="pro-overview tab-pane fade show active">
<div class="row">
        <div class="col-lg-12">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="row schedule-div">
                        <div> @include('admin.schedule.details.item')</div>
                        <div>
                            <div id="itemSchedule"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@section('footer_append_itemSchedule')
{{--    <script type="text/javascript">--}}
{{--        $(function () {--}}
{{--            scheduleDraw('itemSchedule',9);--}}
{{--        });--}}

{{--    </script>--}}
@endsection
