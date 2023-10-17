<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\Schedule;

class Day extends Model
{
    use HasFactory;

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'day_schedule');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'day_event')->withPivot('schedule_id');
    }
}
