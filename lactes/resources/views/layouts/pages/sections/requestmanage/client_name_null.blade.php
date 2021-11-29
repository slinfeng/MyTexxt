@can($__env->yieldContent('self_modify'))
    @section('client_name',$requestSettingGlobal['company_name'])
@else
    @section('client_name','&nbsp　&nbsp　&nbsp　&nbsp　&nbsp')
@endcan
