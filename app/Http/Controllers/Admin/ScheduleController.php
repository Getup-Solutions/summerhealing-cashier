<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Calender;
use App\Models\Day;
use App\Models\Event;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function store($scheduleInfo)
    {

        if ($scheduleInfo["days"] ?? false) {
            $days = $scheduleInfo["days"];
        }
        if ($scheduleInfo["daysEvent"] ?? false) {
            $daysEvent = $scheduleInfo["daysEvent"];
        }
        unset($scheduleInfo["daysEvent"]);

        $scheduleInfo["start_date"] = date($scheduleInfo["start_date"]);
        $scheduleInfo["end_date"] = date($scheduleInfo["end_date"]);
        $scheduleInfo["days"] = json_encode($scheduleInfo["days"]);

        $schedule = Schedule::create($scheduleInfo);
        if (isset($days)) {
            $schedule->days()->sync($days);
        }

        $dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        $start_date = strtotime($scheduleInfo["start_date"]);
        $end_date = strtotime($scheduleInfo["end_date"]);
        while ($start_date <= $end_date) {
            $day = substr(date("l", $start_date), 0, 3);
            if (in_array($day, $schedule->days()->pluck('day_name')->toArray())) {
                $dayEvents = $daysEvent[array_search($day, $dayNames)];
                foreach ($dayEvents as $eventInfo) {

                    $eventInfo['event_title'] = $schedule["title"];
                    $eventInfo["date"] = date('Y/m/d', $start_date);
                    $eventInfo["eventable_id"] = $schedule["scheduleable_id"];
                    $eventInfo["eventable_type"] = $schedule["scheduleable_type"];
                    $eventInfo["schedule_id"] = $schedule->id;
                    $eventInfo["calender_id"] = Calender::where('formated_date', '=', date('Y/m/d', $start_date))->first()->id;
                    if ($eventInfo["event_trainers"] ?? false) {
                        $trainers = $eventInfo["event_trainers"];
                    }
                    unset($eventInfo["event_trainers"]);
                    $event = Event::create($eventInfo);
                    if (isset($trainers)) {
                        $event->trainers()->sync($trainers);
                    };
                    $event->days()->attach(array_search($day, $dayNames) + 1, ['schedule_id' => $schedule->id]);
                    $event->save();
                }
            }
            $start_date = strtotime('+1 day', $start_date);
        }
        $schedule->save();
        return;
    }

    public function update($scheduleInfo)
    {
        // dd($scheduleInfo);

        if ($scheduleInfo["days"] ?? false) {
            $days = $scheduleInfo["days"];
        }
        if ($scheduleInfo["daysEvent"] ?? false) {
            $daysEvent = $scheduleInfo["daysEvent"];
        }
        unset($scheduleInfo["daysEvent"]);

        $scheduleInfo["start_date"] = date($scheduleInfo["start_date"]);
        $scheduleInfo["end_date"] = date($scheduleInfo["end_date"]);
        $scheduleInfo["days"] = json_encode($scheduleInfo["days"]);

        $schedule = Schedule::find($scheduleInfo['id']);

        if (isset($days)) {
            $schedule->days()->sync($days);
        }
        $dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        $start_date = strtotime($scheduleInfo["start_date"]);
        $end_date = strtotime($scheduleInfo["end_date"]);
        while ($start_date <= $end_date) {
            $day = substr(date("l", $start_date), 0, 3);
            if (in_array($day, $schedule->days()->pluck('day_name')->toArray())) {
                $dayEvents = $daysEvent[array_search($day, $dayNames)];
                if(count($dayEvents) > 0){
                    foreach ($dayEvents as $eventInfo) {
                        if (isset($eventInfo["id"])) {
                            $event = Event::find($eventInfo["id"]);
                            $event->event_title = $schedule["title"];
                            $event->date = date('Y/m/d', $start_date);
                            $event->eventable_id = $schedule["scheduleable_id"];
                            $event->eventable_type = $schedule["scheduleable_type"];
                            $event->schedule_id = $schedule->id;
                            $event->calender_id = Calender::where('formated_date', '=', date('Y/m/d', $start_date))->first()->id;
                            $event->days()->sync(array_search($day, $dayNames) + 1, ['schedule_id' => $schedule->id]);
                            unset($eventInfo["pivot"]);
                            if ($eventInfo["event_trainers"] ?? false) {
                                $trainers = $eventInfo["event_trainers"];
                            }
                            if (isset($trainers)) {
                                $event->trainers()->sync($trainers);
                            };
                            unset($eventInfo["trainer_names"]);
                            
                            $event->update($eventInfo);
                        } else {
                            $eventInfo['event_title'] = $schedule["title"];
                            $eventInfo["date"] = date('Y/m/d', $start_date);
                            $eventInfo["eventable_id"] = $schedule["scheduleable_id"];
                            $eventInfo["eventable_type"] = $schedule["scheduleable_type"];
                            $eventInfo["schedule_id"] = $schedule->id;
                            $eventInfo["calender_id"] = Calender::where('formated_date', '=', date('Y/m/d', $start_date))->first()->id;
                            if ($eventInfo["event_trainers"] ?? false) {
                                $trainers = $eventInfo["event_trainers"];
                            }
                            unset($eventInfo["event_trainers"]);
                            $event = Event::create($eventInfo);
                            if (isset($trainers)) {
                                $event->trainers()->sync($trainers);
                            };
                            $event->days()->attach(array_search($day, $dayNames) + 1, ['schedule_id' => $schedule->id]);
                            $event->save();
                        }
                    }
                } else {
                    $removedDay = Day::find(array_search($day, $dayNames)+1);
                    $removedDay->events()->wherePivot('schedule_id','=',$schedule->id)->delete();
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
