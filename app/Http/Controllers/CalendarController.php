<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Calendar;
use App\Models\Event;
use App\Models\User;
use App\Models\Credit;

class CalendarController extends Controller
{
    public function calendarPage()
    {
        return Inertia::render('Public/Calendar/Calendar', [
            'calendar' => Calendar::whereDate('formated_date', '<=', date("Y/m/d", strtotime("+ 1 month")))->get()
        ]);
    }

    public function bookEventPage()
    {
        $attributes = request()->validate([
            'event_id' => 'required',
            'calendar_id' => 'required',
            'date' => 'required',
            'time' => 'required',
        ]);
        $event = Event::find($attributes['event_id']);
        $calendar = Calendar::find($attributes['calendar_id']);
        $time = $attributes["time"];
        $date = $attributes["date"];
        // Credit::where('creditable_type',$event->eventable_type)->where('creditable_id',$event->eventable_id)->get();
        // dd(Credit::where('creditable_type',$event->eventable_type)->where('creditable_id',$event->eventable_id)->get());
        $bookingsForThis = Booking::where('calendar_id', $attributes['calendar_id'])->where('event_id', $attributes['event_id'])->get();
        if ($event && $calendar) {
            return Inertia::render('Public/Calendar/Booking', [
                'eventTitle' => $event->event_title,
                'eventId' => $event->id,
                'calendarId' => $calendar->id,
                'eventable' => $event->eventable()->first(),
                'eventSize' => $event->size,
                'eventTime' => $time,
                'eventDate' => $date,
                'users' => User::all(),
                'bookings' => $bookingsForThis,
                'totalBookingsForThis' => $bookingsForThis->count(),
            ]);
        }
        return redirect('/calendar')->with('error', 'Some error occured!');
    }

    public function bookEventStore()
    {
        $attributes = request()->validate([
            'event_id' => 'required',
            'calendar_id' => 'required',
            'user_id' => 'required',
        ], [
            'user_id' => 'Please select a user'
        ]);
        // User::find($attributes['user_id'])->credi
        // dd(User::find($attributes['user_id'])->subscriptionplans()->get());

        $attributes['bookable_id'] = Event::find($attributes['event_id'])->eventable_id;
        $attributes['bookable_type'] = Event::find($attributes['event_id'])->eventable_type;
        $attributes['date'] = Calendar::find($attributes['calendar_id'])->formated_date;
        $event = Event::find($attributes['event_id']);
        $totalBookingsForThis = Booking::where('calendar_id', $attributes['calendar_id'])->where('event_id', $attributes['event_id'])->count();
        if (!Booking::where('calendar_id', $attributes['calendar_id'])->where('user_id', $attributes['user_id'])->where('event_id', $attributes['event_id'])->first()) {
            if ($totalBookingsForThis >= $event->size) {
                return back()->with('success', 'Maximum seating capacity for this event is reached');
            } else {
                Booking::create($attributes);
                return back()->with('success', 'New booking added');
            }
        } else {
            return back()->with('success', 'User already booked for this event');
        }
    }

    public function bookEventDestroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'Booking has been removed');
    }

    public function attendanceEventPage()
    {
        $attributes = request()->validate([
            'event_id' => 'required',
            'calendar_id' => 'required',
            'date' => 'required',
            'time' => 'required',
        ]);
        $event = Event::find($attributes['event_id']);
        $calendar = Calendar::find($attributes['calendar_id']);
        $time = $attributes["time"];
        $date = $attributes["date"];
        $attendancesForThis = Attendance::where('calendar_id', $attributes['calendar_id'])->where('event_id', $attributes['event_id'])->get();
        // $attendancesForThisUserIds = Attendance::where('calendar_id', 1)->where('event_id', 1)->pluck('user_id')->toArray();
        $attendancesForThisUserIds = Attendance::where('calendar_id', $attributes['calendar_id'])->where('event_id', $attributes['event_id'])->pluck('user_id')->toArray();
        $bookingsForThisUserIds = Booking::where('calendar_id', $attributes['calendar_id'])->where('event_id', $attributes['event_id'])->pluck('user_id')->toArray();
        // $bookingsForThis = Booking::where('calendar_id', $attributes['calendar_id'])->where('event_id', $attributes['event_id'])->get();
        // dd($attendancesForThisUserIds);
        $bookingsForThisNoAttendance = Booking::whereNotIn('user_id', $attendancesForThisUserIds)->get();
        $usersNoBooking = User::whereNotIn('id',$bookingsForThisUserIds)->get();
        // dd($bookingsForThisNoAttendance);

        // foreach ($bookingsForThis as $booking) {
        //     if(!$attendancesForThis::where('user_id',$booking->user_id)->first()){
        //         $booking['']
        //     }
        // }
        if ($event && $calendar) {
            return Inertia::render('Public/Calendar/Attendance', [
                'eventTitle' => $event->event_title,
                'eventId' => $event->id,
                'calendarId' => $calendar->id,
                'eventable' => $event->eventable()->first(),
                'eventSize' => $event->size,
                'eventTime' => $time,
                'eventDate' => $date,
                'users' => $usersNoBooking,
                'bookingsNoAttendance' => $bookingsForThisNoAttendance,
                'attendances' => $attendancesForThis
            ]);
        }
        return redirect('/calendar')->with('error', 'Some error occured!');
    }

    public function attendanceEventStore()
    {
        $attributes = request()->validate([
            'event_id' => 'required',
            'calendar_id' => 'required',
            'user_id' => 'required'
        ], [
            'user_id' => 'Please select a user'
        ]);
        $event = Event::find($attributes['event_id']);
        $attributes['attendanceable_id'] = $event->eventable_id;
        $attributes['attendanceable_type'] = $event->eventable_type;
        $attributes['date'] = Calendar::find($attributes['calendar_id'])->formated_date;
        $user = User::find('user_id');
        // $user->
        $totalBookingsForThis = Booking::where('calendar_id', $attributes['calendar_id'])->where('event_id', $attributes['event_id'])->count();
        if (!Attendance::where('calendar_id', $attributes['calendar_id'])->where('user_id', $attributes['user_id'])->where('event_id', $attributes['event_id'])->first()) {
            if ($totalBookingsForThis >= $event->size) {
                return back()->with('success', 'Maximum seating capacity for this event is reached');
            } else {
            if (!Booking::where('calendar_id', $attributes['calendar_id'])->where('user_id', $attributes['user_id'])->where('event_id', $attributes['event_id'])->first()) {
                $bookingAttributes = $attributes;
                unset($bookingAttributes['attendanceable_id']);
                unset($bookingAttributes['attendanceable_type']);
                $bookingAttributes['bookable_id'] = Event::find($attributes['event_id'])->eventable_id;
                $bookingAttributes['bookable_type'] = Event::find($attributes['event_id'])->eventable_type;
                Booking::create($bookingAttributes);
                // unset($attributes['bookable_id']);
                // unset($attributes['bookable_type']);
            }
            Attendance::create($attributes);
            return back()->with('success', 'Attendance has been registered');
        }
        } else {
            return back()->with('success', 'Attendance has been already registered');
        }
    }

    public function attendanceEventDestroy(Attendance $attendance)
    {
        // dd($attendance);
        $booking = Booking::where('calendar_id', $attendance->calendar_id)->where('event_id', $attendance->event_id)->where('user_id', $attendance->user_id)->first();
        if ($booking) {
            $booking->delete();
        }
        $attendance->delete();
        return back()->with('success', 'Attendance has been removed');
    }
}
