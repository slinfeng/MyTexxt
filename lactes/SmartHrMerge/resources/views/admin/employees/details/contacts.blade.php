<div class="card-body">
    <h3 class="card-title"><strong>{{ __('連絡情報') }}</strong></h3>
        {{--    <a href="#" class="edit-icon" data-toggle="modal" data-target="#address_modal"><i class="fa fa-pencil"></i></a></h3>--}}
{{--        <h5 class="section-title"><strong>{{ __('連絡') }}</strong></h5>--}}
    <table class="w-100 employee-table">
        <tr>
            <td class="title">{{ __('メール（アカウント名）') }}</td>
            <td class="color-grey">{{ $user->email}}</td>
        </tr>
        <tr>
            <td class="title">{{ __('携帯電話') }}</td>
            <td class="history">{{$employeeBase->EmployeeContacts->data_history['phone']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeContacts->phone}}</td>
            <td class="enter">
                <input name="phone" value="{{$employeeBase->EmployeeContacts->phone}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('電話番号') }}</td>
            <td class="history">{{$employeeBase->EmployeeContacts->data_history['telephone']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeContacts->telephone}}</td>
            <td class="enter">
                <input name="telephone" value="{{$employeeBase->EmployeeContacts->telephone}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('FAX') }}</td>
            <td class="history">{{$employeeBase->EmployeeContacts->data_history['fax']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeContacts->fax}}</td>
            <td class="enter">
                <input name="fax" value="{{$employeeBase->EmployeeContacts->fax}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('郵便番号') }}</td>
            <td class="history">{{$employeeBase->EmployeeContacts->data_history['postcode']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeContacts->postcode}}</td>
            <td class="enter">
                <input name="postcode" value="{{$employeeBase->EmployeeContacts->postcode}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('住所') }}</td>
            <td class="history">{{$employeeBase->EmployeeContacts->data_history['address']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeContacts->address}}</td>
            <td class="enter">
                <textarea type="text" name="address" rows="1" class="form-control" oninput="changeLength(this)">{{$employeeBase->EmployeeContacts->address}}</textarea>
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('最寄駅') }}</td>
            <td class="history">{{$employeeBase->EmployeeContacts->data_history['nearest_station']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeContacts->nearest_station}}</td>
            <td class="enter">
                <input name="nearest_station" value="{{$employeeBase->EmployeeContacts->nearest_station}}"
                       type="text" class="form-control">
            </td>
        </tr>
    </table>
        <hr>
{{--        <h5 class="section-title"><strong>{{ __('本籍') }}</strong></h5>--}}
    <table class="w-100 employee-table">
        <tr>
            <td class="title">{{ __('本籍地郵便番号') }}</td>
            <td class="history">{{$employeeBase->EmployeeContacts->data_history['home_town_postcode']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeContacts->home_town_postcode}}</td>
            <td class="enter">
                <input name="home_town_postcode" value="{{$employeeBase->EmployeeContacts->home_town_postcode}}" type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('本籍地住所') }}</td>
            <td class="history">{{$employeeBase->EmployeeContacts->data_history['home_town_address']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeContacts->home_town_address}}</td>
            <td class="enter">
                <textarea type="text" name="home_town_address" rows="1" class="form-control" oninput="changeLength(this)">{{$employeeBase->EmployeeContacts->home_town_address}}</textarea>
            </td>
        </tr>
    </table>
    <hr>
{{--        <h5 class="section-title"><strong>{{ __('緊急連絡先') }}</strong></h5>--}}
    <table class="w-100 employee-table">
        <tr>
            <td class="title">{{ __('緊急連絡先氏名') }}</td>
            <td class="history">{{$employeeBase->EmployeeContacts->data_history['emergency_name']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeContacts->emergency_name}}</td>
            <td class="enter">
                <input name="emergency_name" value="{{$employeeBase->EmployeeContacts->emergency_name}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('緊急連絡先関係') }}</td>
            <td class="history">{{$employeeBase->EmployeeContacts->data_history['emergency_relationship']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeContacts->emergency_relationship}}</td>
            <td class="enter">
                <input name="emergency_relationship" value="{{$employeeBase->EmployeeContacts->emergency_relationship}}"
                       type="text" class="form-control">
            </td>
        </tr>
        <tr>
            <td class="title">{{ __('緊急連絡先電話') }}</td>
            <td class="history">{{$employeeBase->EmployeeContacts->data_history['emergency_phone']}}</td>
            <td class="modify">{{ $employeeBase->EmployeeContacts->emergency_phone}}</td>
            <td class="enter">
                <input name="emergency_phone" value="{{$employeeBase->EmployeeContacts->emergency_phone}}"
                       type="text" class="form-control">
            </td>
        </tr>
    </table>
</div>

