<tr class="employee-info">
    <td class="text-center input-able">
        <label>
            @if(isset($v))<input name = "accounts_estimate_detail_id[]" type="hidden" value="{{$v->id}}">@endif
            <input class="text-center" name="employee_name[]" type="text"
                   value="@if(isset($v)){{$v->employee_name}}@endif"/>
        </label>
    </td>
    <td class="text-left textarea-able">
        <label>
            <textarea oninput="changeLength(this)" rows="1" name="project_name[]"
            >@if(isset($v)){{$v->project_name}}@endif</textarea>
        </label>
    </td>
    <td class="text-center input-able">
        <label>
            <input class="text-center w-100 float" type="text"  name="month[]" value="" onchange="calcPrice(this)">
        </label>
    </td>
    <td class="text-center">人月</td>
    <td class="text-right input-able">
        <label>
            <input name="unit_price[]" class="text-right amount" onblur="unitPriceChange(this)" type="text"
                   value="@if(isset($v)){{$v->unit_price}}@else @yield('amount')@endif" maxlength="7">
        </label>
    </td>
    <td class="text-right position-relative">
        <input class="text-right w-100" name="total[]" type="text"
               value="@if(isset($v)){{$v->total}}@else @yield('amount')@endif" readonly>
        <span data-print="false" name="addAndDelete" style="display:inline-block;height: 100%;position: absolute;top:calc( 50% - 19px );right: -18px;line-height: 18px;font-size: 18px">
            <a href="javascript:void(0);" onclick="deleteLine(this)" onmousemove="showLinePoint(this)" onmouseleave="hideLinePoint(this)">⊝</a>
            <span class="linePoint" style="color: red">行削除</span>
            <br>
            <a href="javascript:void(0);" onclick="addLine(this)" onmousemove="showLinePoint(this)" onmouseleave="hideLinePoint(this)">⊕</a>
            <span class="linePoint" style="color: green">行追加</span>
        </span>
    </td>
</tr>
