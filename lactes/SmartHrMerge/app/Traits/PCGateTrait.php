<?php

namespace App\Traits;

use Illuminate\Support\Facades\Gate;
use Jenssegers\Agent\Facades\Agent;
use Symfony\Component\HttpFoundation\Response;

trait PCGateTrait{
    private function deniesModify($model){
        return Gate::denies($model::MODIFY);
    }
    private function none($model){
        return Gate::none([$model::VIEW,$model::MODIFY]);
    }
    private function outside($model){
        return Gate::denies($model::SELF_MODIFY);
    }
    private function deniesForView($model){
        abort_if($this->none($model), Response::HTTP_FORBIDDEN);
    }
    private function deniesForModify($model){
        abort_if($this->deniesModify($model), Response::HTTP_FORBIDDEN);
    }
    private function abortNotFoundForMobile(){
        abort_if(Agent::isMobile(),Response::HTTP_NOT_FOUND);
    }
    private function deniedForOutside($model){
        abort_if($this->outside($model),Response::HTTP_FORBIDDEN);
    }
}
