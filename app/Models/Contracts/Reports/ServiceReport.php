<?php

namespace APOSite\Models\Reports;

use APOSite\Jobs\ProcessEvent;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\Fractal\Manager;
use APOSite\Http\Transformers\ServiceReportTransformer;
use Request;
use Illuminate\Support\Facades\Queue;

class ServiceReport extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'service_type',
        'location',
        'project_type',
        'off_campus',
        'travel_time'
    ];

    public function transformer(Manager $manager)
    {
        return new ServiceReportTransformer($manager);
    }

    public function computeValue(array $brotherData)
    {
        $hours = array_key_exists('hours', $brotherData) ? $brotherData['hours'] : 0;
        $minutes = array_key_exists('minutes', $brotherData) ? $brotherData['minutes'] : 0;
        return $hours * 60 + $minutes;
    }

    public function createRules()
    {
        $rules = [
            //Rules for the core report data
            'display_name' => ['required', 'min:10'],
            'description' => ['required', 'min:40'],
            'event_date' => ['required', 'date'],
            'brothers' => ['required', 'array'],
            //Rules specific to the service report
            'location' => ['required', 'min:10'],
            'service_type' => ['required', 'in:chapter,country,community,campus'],
            'project_type' => ['required', 'in:inside,outside'],
            'off_campus' => ['required', 'boolean'],
            'travel_time' => ['required_if:off_campus,true', 'integer']
        ];
        $extraRules = [];
        foreach (Request::get('brothers') as $index => $brother) {
            $extraRules['brothers.' . $index . '.id'] = ['required', 'exists:users,id'];
            $extraRules['brothers.' . $index . '.hours'] = ['sometimes', 'required', 'integer', 'min:0'];
            $extraRules['brothers.' . $index . '.minutes'] = ['sometimes', 'required', 'integer', 'min:0'];
        }
        $newRules = array_merge($rules, $extraRules);
        return $newRules;
    }

    public function updateRules(){
        return [
            'approved'=>['sometimes','required','boolean']
        ];
    }

    public function errorMessages()
    {
        $messages = [
            'off_campus.in' => 'off_campus should be either true or false',
            'travel_time' => 'travel time is required if the event is off-campus'
        ];
        $extraMessages = [];
        if(Request::has('brothers')) {
            foreach (Request::get('brothers') as $index => $brother) {
                $extraMessages['brothers.' . $index . '.id.exists'] = 'The cwru id :input is not valid.';
            }
        }
        $allMessages = array_merge($messages, $extraMessages);
        return $allMessages;
    }

    public function updatable()
    {
        return [
            'approved',
            'project_type',
            'service_type'
        ];
    }

    public function onCreate()
    {
    }

    public function onUpdate()
    {
        if ($this->approved) {
            $event = new ProcessEvent($this->id, get_class($this));
            Queue::push($event);
        }
    }

    public function canStore($user){
        if($user == null){
            return false;
        } else {
            return true;
        }
    }

    public function canUpdate($user){
        return false;
        if($user != null){

        } else {
            return false;
        }
    }

    public function canRead($user){
        //Add in logic so not everyone can see the service reports that aren't theirs unless they are the service? vp or webmaster.
        if($user != null){
            return true;
        } else {
            return false;
        }
    }

    public function scopeNotApproved($query){
        return $query->whereApproved(false);
    }
    public function scopeApproved($query){
        return $query->whereApproved(true);
    }

}
