<?php

namespace APOSite\Http\Controllers\Filters;


use APOSite\Http\Controllers\Controller;
use APOSite\Models\Reports\Types\BrotherhoodReport;
use APOSite\Models\Reports\Types\ServiceReport;

class EventFilter extends Controller{

    public function validateServiceEvent($event){
        return $event instanceof ServiceReport && $event->approved == true;
    }

    public function validateBrotherhoodEvent($event){
        return $event instanceof BrotherhoodReport && $event->approved = true;
    }

    public function validateDuesEvent($event){
        return false;
    }

    public function validateInsideHours($event){
        return $event instanceof ServiceReport && $event->project_type == 'inside' && $event->approved == true;
    }
}