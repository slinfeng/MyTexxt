<div id="item-div">
    <span class="put-away-span"></span>
    <span class="unfold-span"></span>
    <div class="item-sidebar">
        <table class="w-100 text-center this-month">
            <tr class="first-tr">
                <td class="text-right p-0">
                    <span onclick="itemLastMonth()"><img src="{{ asset('assets/img/left.png') }}"></span>
                </td>
                <td colspan="5" class="year-month">

                </td>
                <td class="text-left p-0">
                    <span onclick="itemNextMonth()"><img src="{{ asset('assets/img/right.png') }}"></span>
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
        <table class="w-100 text-center next-month">
            <tr class="first-tr">
                <td colspan="7" class="year-month">

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
        <table class="w-100 text-center next-next-month">
            <tr class="first-tr">
                <td colspan="7" class="year-month">

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
</div>


@section('footer_append_item')
    <script type="text/javascript">

    </script>
@endsection
