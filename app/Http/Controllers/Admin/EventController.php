<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calender;
use App\Models\Event;

class EventController extends Controller
{
    //
    public function store($eventInfo,$eventableId,$scheduleId,$day,$dayNames)
    {
        // $eventInfo['event_title'] = $schedule["title"];
        // $eventInfo["date"] = date('Y/m/d', $date);
        $eventInfo["eventable_id"] = $eventableId;
        // $eventInfo["eventable_type"] = $schedule["scheduleable_type"];
        $eventInfo["schedule_id"] = $scheduleId;
        // $eventInfo["calender_id"] = Calender::where('formated_date', '=', date('Y/m/d', $date))->first()->id;
        if ($eventInfo["event_trainers"] ?? false) {
            $trainers = json_decode($eventInfo["event_trainers"]);
        }
        unset($eventInfo["event_trainers"]);
        // if(Event::find($eventInfo["id"])){
        //     dd(Event::find($eventInfo["id"]));
        // }
        $event = Event::create($eventInfo);
        if (isset($trainers)) {
            $event->trainers()->sync($trainers);
        };
        // dd(array_search($day, $dayNames));
        $event->days()->detach();
        $event->days()->attach(array_search($day, $dayNames) + 1, ['schedule_id' => $scheduleId]);
        $event->save();
    }
}
