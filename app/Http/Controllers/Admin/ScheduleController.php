<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
// use App\Models\Day;
use App\Models\Event;
use App\Models\Schedule;
// use Illuminate\Http\Request;
// use App\Http\Controllers\Admin\EventController;

class ScheduleController extends Controller
{
   
    protected $dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    public function store($scheduleInfo)
    {

        if ($scheduleInfo["days"] ?? false) {
            $days = $scheduleInfo["days"];
        }
        unset($scheduleInfo["days"]);

        if ($scheduleInfo["daysEvent"] ?? false) {
            $daysEvent = $scheduleInfo["daysEvent"];
        }
        unset($scheduleInfo["daysEvent"]);

        $scheduleInfo["start_date"] = date($scheduleInfo["start_date"]);
        $scheduleInfo["end_date"] = date($scheduleInfo["end_date"]);

        $schedule = Schedule::create($scheduleInfo);

        if (isset($days)) {
            $schedule->days()->sync($days);
        }

        $daysInSchedule = $schedule->days()->pluck('day_name')->toArray();

        $start_date = strtotime($scheduleInfo["start_date"]);
        $end_date = strtotime($scheduleInfo["end_date"]);
        foreach ($this->dayNames as $day) {
            if (in_array($day, $daysInSchedule)) {
                $dayEvents = $daysEvent[array_search($day, $this->dayNames)] ?? [];
                foreach ($dayEvents as $eventInfo) {
                    $eventInfo["eventable_id"] = $eventInfo["eventable_id"] ?? $schedule["scheduleable_id"];
                    $eventInfo["eventable_type"] = $schedule["scheduleable_type"];
                    $eventInfo["schedule_id"] = $schedule["id"];
                    $eventInfo["event_title"] = $schedule->scheduleable()->first()->title;
                    $event = Event::create($eventInfo);
                    $event->trainers()->sync($event->eventable()->get()->first()->trainers()->get()->pluck('id')->toArray());
                    $event->days()->detach();
                    $event->days()->attach(array_search($day, $this->dayNames) + 1, ['schedule_id' => $schedule["id"]]);
                    $event->save();
                }
            }
            $schedule->save();
        }

        while ($start_date <= $end_date) {
            $day = substr(date("l", $start_date), 0, 3);
            if (in_array($day, $daysInSchedule)) {
                $eventsInSchedule = Schedule::find($schedule["id"])->events()->get();
                foreach ($eventsInSchedule as $eventInSchedule) {
                    if (in_array($day, $eventInSchedule->days()->pluck('day_name')->toArray())) {
                        $eventInSchedule->calendars()->attach(Calendar::where('formated_date', '=', date('Y/m/d', $start_date))->first()->id,['schedule_id' => $schedule["id"]]);
                        $eventInSchedule->save();
                    }
                }
            }
            $start_date = strtotime('+1 day', $start_date);
        }
    }

    public function update($scheduleInfo)
    {
        // dd($scheduleInfo);

        if ($scheduleInfo["days"] ?? false) {
            $days = $scheduleInfo["days"];
        }
        unset($scheduleInfo["days"]);

        if ($scheduleInfo["daysEvent"] ?? false) {
            $daysEvent = $scheduleInfo["daysEvent"];
        }
        unset($scheduleInfo["daysEvent"]);


        $scheduleInfo["start_date"] = date($scheduleInfo["start_date"]);
        $scheduleInfo["end_date"] = date($scheduleInfo["end_date"]);

        $schedule = Schedule::find($scheduleInfo['id']);

        if (isset($days)) {
            $schedule->days()->sync($days);
        }

        $daysInSchedule = $schedule->days()->pluck('day_name')->toArray();
        
        $start_date = strtotime($scheduleInfo["start_date"]);
        $end_date = strtotime($scheduleInfo["end_date"]);
        $schedule->events()->delete();

        foreach ($this->dayNames as $day) {
            if (in_array($day, $daysInSchedule)) {
                $dayEvents = $daysEvent[array_search($day, $this->dayNames)] ?? [];
                foreach ($dayEvents as $eventInfo) {
                    $eventInfo["eventable_id"] = $eventInfo["eventable_id"] ?? $schedule["scheduleable_id"];
                    $eventInfo["eventable_type"] = $schedule["scheduleable_type"];
                    $eventInfo["schedule_id"] = $schedule["id"];
                    $eventInfo["event_title"] = $schedule->scheduleable()->first()->title;
                    unset($eventInfo["pivot"]);
                    unset($eventInfo["trainer_names"]);
                    $event = Event::create($eventInfo);
                    $event->trainers()->sync($event->eventable()->get()->first()->trainers()->get()->pluck('id')->toArray());
                    $event->days()->detach();
                    $event->days()->attach(array_search($day, $this->dayNames) + 1, ['schedule_id' => $schedule["id"]]);
                    $event->save();
                }
            }
            $schedule->save();
        }

        while ($start_date <= $end_date) {
            Calendar::where('formated_date', '=', date('Y/m/d', $start_date))->first()->events()->wherePivot('schedule_id', $schedule["id"])->delete();
            $day = substr(date("l", $start_date), 0, 3);
            if (in_array($day, $daysInSchedule)) {
                $eventsInSchedule = Schedule::find($schedule["id"])->events()->get();
                foreach ($eventsInSchedule as $eventInSchedule) {
                    if (in_array($day, $eventInSchedule->days()->pluck('day_name')->toArray())) {
                        $eventInSchedule->calendars()->attach(Calendar::where('formated_date', '=', date('Y/m/d', $start_date))->first()->id,['schedule_id' => $schedule["id"]]);
                        $eventInSchedule->save();
                    }
                }
            }
            $start_date = strtotime('+1 day', $start_date);
        }
        $schedule->update($scheduleInfo);
        return;
    }
    public function destroy($schedule)
    {
        $schedule->delete();
    }
}
