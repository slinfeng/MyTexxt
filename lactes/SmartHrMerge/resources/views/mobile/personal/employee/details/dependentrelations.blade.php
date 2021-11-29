@extends('layouts.backend')
@section('page_title', 'ホーム')
@section('css_append')
    <link rel="stylesheet" href="{{ asset('assets/css/lactes.mobile.css') }}">
    <style>
        body{
            background-color: #e9e9e9 !important;
        }
        .back-color-white{
            border-radius: 5px;
        }
        .downboder{
            border-bottom: #f1f1f1 1px solid;
        }
        td{
            padding: 5px 20px;
        }
        .employee-relations-card .name{
            font-size: 20px;
            font-weight: 800;
            padding-bottom: 0;
            width: auto;
            height: 40px;
            min-width: 2em;
        }
        .employee-relations-card .dependent_residence_card_num{
             font-size: 14px;
             text-align: left;
             padding: 0;
             vertical-align: bottom;
         }
        .employee-relations-card .right-img{
            width: 40px;
            padding: 0;
            text-align: center;
         }

        .employee-relations-card .relationship_type{
            font-size: 12px;
            color: darkgrey;
            height: 20px;
            padding: 0 20px;
        }
        .employee-relations-card .estimated{
            height: 35px;
        }
        .add-button{
            font-size: 14px;
            color: darkgrey;
            text-align: center;
            vertical-align: center;
            height: 100%;
            width: 100%;
        }
        .color-darkgrey{
            color: darkgrey;
        }
        .back-color-white{
            margin-bottom: 0.5em;
        }
        .delete-button{
            padding: 0 0 5px 40px;
            text-align: center;
            height: 20px;
            color: grey;
        }
        #base-model-relationship-select .modal-body{
            padding-bottom: 10px;
        }
        #base-model-relationship-select .modal-title{
            text-align: center;
        }
        #base-model-relationship-select table{
            font-size: 12px;
            width: 100%;
        }
        #base-model-relationship-select td{
            padding-left: 0;
            padding-right: 0;
        }
        #base-model-relationship-select tr>td:nth-of-type(2){
           text-align: right;
        }
        #base-model-relationship-select input[type=button]{
            padding-top: 20px;
            border: none;
            color: grey;
            background-color: white;
        }
        .red-color{
            color: red;
        }
    </style>
@endsection
@section('content')
    <div class="body">
        <div class="back-color-white dependent-card">
            <table class="w-100 employee-relations-card">
                <tr>
                    <td class="name">

                    </td>
                    <td class="dependent_residence_card_num">

                    </td>
                    <td class="right-img" rowspan="4" class="p-0" onclick="dependentRelationEdit(this)">
                        <img class="w-50" src="{{ asset('assets/img/chevron-right-grey.png') }}">
                    </td>
                </tr>
                <tr>
                    <td name="relationship_type" class="relationship_type" colspan="2">

                    </td>
                </tr>
                <tr>
                    <td class="estimated" colspan="2">

                    </td>
                </tr>
                <tr>
                    <td class="delete-button" colspan="2" onclick="deleteRelationsCard(this)">
                        削除
                    </td>
                </tr>
            </table>
        </div>
        <div class="back-color-white dependent-add-card">
            <table class="add-button">
                <tr>
                    <td onclick="$('#base-model-relationship-select').modal('show')">
                        <span style=""><img style="width: 20px;margin-bottom: 3px" src="{{ asset('assets/img/add-icon.png') }}"></span>
                        扶養家族を追加
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="modal custom-modal fade" id="base-model-relationship-select" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-btn delete-action">
                        <div class="modal-title">
                            区分
                        </div>
                        <div class="modal-value">
                            <table>
                                <tr onclick="dependentRelationAdd(this,1)">
                                    <td>
                                        配偶者
                                    </td>
                                    <td>
                                        <span></span>/1
                                    </td>
                                </tr>
                                <tr onclick="dependentRelationAdd(this,2)">
                                    <td>
                                        扶養親族(16歳以上)
                                    </td>
                                    <td>
                                        <span></span>/5
                                    </td>
                                </tr>
                                <tr onclick="dependentRelationAdd(this,3)">
                                    <td>
                                        他の所得者が控除を受ける扶養親族等
                                    </td>
                                    <td>
                                        <span></span>/3
                                    </td>
                                </tr>
                                <tr onclick="dependentRelationAdd(this,4)">
                                    <td>
                                        16歳未満の扶養親族
                                    </td>
                                    <td>
                                        <span></span>/3
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-button text-right">
                            <input type="button" value="キャンセル" onclick="$('#base-model-relationship-select').modal('hide');$('.red-color').removeClass('red-color')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('mobile.personal.employee.modal')
    @include('mobile.personal.footer')
@endsection
@section('footer_append')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/mobileSelect.min.js') }}"></script>
    <script src="{{ asset('assets/js/lactes.mobile.js') }}"></script>
    <script>
        let modalSelect=[];
        let modalSelectId=[];
        let dependentRelationIdA=[];
        let dependentRelationIdB=[];
        let dependentRelationIdC=[];
        let dependentRelationIdD=[];
        const relationshipType=['配偶者','扶養親族(16歳以上)','他の所得者が控除を受ける扶養親族等','16歳未満の扶養親族'];
        const relationshipTypeId=['1','2','3','4'];
        let type='dependentRelationsDelete';
        let dependentCard = $('.dependent-card').last().clone();
        $(function () {
            $('.dependent-card').remove();
            $.ajax({
                url:'/employee/getEmployeeInfo',
                data: {"type":"dependentRelations"},
                type:"get",
                success: function (response) {
                    response.forEach(function (element,index) {
                        if(element.dname!='' && element.dname!=null){
                            $('.dependent-add-card').before(dependentCard);
                            const temp = $('.dependent-card').last();
                            dependentCard = temp.clone();
                            temp.find('.name').html(element.dname);
                            temp.find('.dependent_residence_card_num').html(element.dependent_residence_card_num);
                            temp.find('.estimated').html(element.estimated);
                            temp.attr('data-id',element.id);
                            switch (element.relationship_type) {
                                case 1:
                                    temp.find('.relationship_type').html(relationshipType[0]).attr('data-id',1);
                                    break;
                                case 2:
                                    temp.find('.relationship_type').html(relationshipType[1]).attr('data-id',2);
                                    break;
                                case 3:
                                    temp.find('.relationship_type').html(relationshipType[2]).attr('data-id',3);
                                    break;
                                case 4:
                                    temp.find('.relationship_type').html(relationshipType[3]).attr('data-id',4);
                                    break;
                            }
                        }else{
                            if(element.relationship_type==1){
                                dependentRelationIdA.push(element.id);
                            }else if(element.relationship_type==2){
                                dependentRelationIdB.push(element.id);
                            }else if(element.relationship_type==3){
                                dependentRelationIdC.push(element.id);
                            }else if(element.relationship_type==4){
                                dependentRelationIdD.push(element.id);
                            }
                        }
                    });
                    modalSet();
                },
                error: function (jqXHR,testStatus,error) {
                    ajaxErrorAction(jqXHR,testStatus,error);
                }
            });
        });
        function modalSet() {
            const temp = $('#base-model-relationship-select');
            temp.find('span:eq(0)').html(dependentRelationIdA.length);
            temp.find('span:eq(1)').html(dependentRelationIdB.length);
            temp.find('span:eq(2)').html(dependentRelationIdC.length);
            temp.find('span:eq(3)').html(dependentRelationIdD.length);
        }
        function dependentRelationEdit(e) {
            let id = $(e).parents('.dependent-card').data('id');
            if(id!=null && id!='') window.location = "/employee/dependentRelationEdit/"+id;
        }
        function dependentRelationAdd(e,type) {
            $('.red-color').removeClass('red-color');
            $(e).addClass('red-color');
            switch (type) {
                case 1:
                    if(dependentRelationIdA.length!=0){
                        dependentRelationCardAdd(dependentRelationIdA[0]);
                        dependentRelationIdA.splice(0,1);
                        $('.dependent-card').last().find('.relationship_type').html(relationshipType[0]).attr('data-id',1);
                        $('#base-model-relationship-select').find('span:eq(0)').html(dependentRelationIdA.length);
                    }else{
                        alert();
                    }
                    break;
                case 2:
                    if(dependentRelationIdB.length!=0){
                        dependentRelationCardAdd(dependentRelationIdB[0]);
                        dependentRelationIdB.splice(0,1);
                        $('.dependent-card').last().find('.relationship_type').html(relationshipType[1]).attr('data-id',2);
                        $('#base-model-relationship-select').find('span:eq(1)').html(dependentRelationIdB.length);
                    }else{
                        alert();
                    }
                    break;
                case 3:
                    if(dependentRelationIdC.length!=0){
                        dependentRelationCardAdd(dependentRelationIdC[0]);
                        dependentRelationIdC.splice(0,1);
                        $('.dependent-card').last().find('.relationship_type').html(relationshipType[2]).attr('data-id',3);
                        $('#base-model-relationship-select').find('span:eq(2)').html(dependentRelationIdC.length);
                    }else{
                        alert();
                    }
                    break;
                case 4:
                    if(dependentRelationIdD.length!=0){
                        dependentRelationCardAdd(dependentRelationIdD[0]);
                        dependentRelationIdD.splice(0,1);
                        $('.dependent-card').last().find('.relationship_type').html(relationshipType[3]).attr('data-id',4);
                        $('#base-model-relationship-select').find('span:eq(3)').html(dependentRelationIdD.length);
                    }else{
                        alert();
                    }
                    break;
            }
            $('#base-model-relationship-select').modal('hide');
        }
        function dependentRelationCardAdd(id){
            $('.dependent-add-card').before(dependentCard);
            const temp = $('.dependent-card').last();
            dependentCard = temp.clone();
            temp.last().attr('data-id',id);
        }
        function deleteRelationsCard(e) {
            let id = $(e).parents('.dependent-card').data('id');
            let data = "type="+type+"&id="+id;
            let relationshipId = $(e).parents('.dependent-card').find('.relationship_type').data('id');
            $(e).parents('.dependent-card').remove();
            switch (relationshipId) {
                case 1:
                    dependentRelationIdA.push(id);
                    break;
                case 2:
                    dependentRelationIdB.push(id);
                    break;
                case 3:
                    dependentRelationIdC.push(id);
                    break;
                case 4:
                    dependentRelationIdD.push(id);
                    break;
            }
            modalSet();
            employeeEdit(data,'');
        }
    </script>
@endsection
