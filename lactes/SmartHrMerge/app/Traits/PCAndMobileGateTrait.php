<?php

namespace App\Traits;

use Illuminate\Support\Facades\Gate;
use Jenssegers\Agent\Facades\Agent;
use Symfony\Component\HttpFoundation\Response;

trait PCAndMobileGateTrait{
    private function deniesPCModify($model){
        return Gate::denies($model::PC_MODIFY);
    }
    private function PCNone($model){
        return Gate::none([$model::PC_VIEW,$model::PC_MODIFY]);
    }
    private function deniesMobileModify($model){
        return Gate::denies($model::MOBILE_MODIFY);
    }
    private function deniesMobileAudit($model){
        return Gate::denies($model::MOBILE_AUDIT);
    }
    private function noneManage($model){
        return Gate::none([$model::MOBILE_AUDIT,$model::PC_MODIFY]);
    }
    private function deniesForPCModify($model){
        abort_if($this->deniesPCModify($model),Response::HTTP_FORBIDDEN);
    }
    private function deniesForPCView($model){
        abort_if($this->PCNone($model),Response::HTTP_FORBIDDEN);
    }
    private function deniesForMobileModify($model){
        abort_if($this->deniesMobileModify($model),Response::HTTP_FORBIDDEN);
    }
    private function deniesForMobileAudit($model){
        abort_if($this->deniesMobileAudit($model),Response::HTTP_FORBIDDEN);
    }
    private function deniesForManage($model){
        abort_if($this->noneManage($model),Response::HTTP_FORBIDDEN);
    }
    private function abortNotFoundForMobile(){
        abort_if(Agent::isMobile(),Response::HTTP_NOT_FOUND);
    }
}
