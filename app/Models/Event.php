<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Day;
use App\Models\Calendar;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Booking;
use App\Models\Attendance;

class Event extends Model
{
    use HasFactory;
    protected $appends = ['trainer_names'];

    public function eventable(): MorphTo
    {
        return $this->morphTo();
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function days()
    {
        return $this->belongsToMany(Day::class, 'day_event')->withPivot('schedule_id');
    }

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class, 'event_trainer');
    }

    public function trainerNames(): Attribute
    {
        // return 'ddd';
        $trainers =  $this->trainers()->get();
        $trainerNames = '';
        foreach ($trainers as $trainer) {
            $trainerNames.=$trainer->user->full_name. ', ';
        }
        $trainerNames = substr($trainerNames, 0, -2);
        return Attribute::make(
            get: fn($value) => $trainerNames
        );
    }

    public function calendars()
    {
        return $this->belongsToMany(Calendar::class, 'calendar_event');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function bookingsFor($date)
    {
        return $this->bookings()->where('date',$date)->get();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendancesFor($date)
    {
        return $this->attendances()->where('date',$date)->get();
    }
}
