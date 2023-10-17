<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Day;

class Schedule extends Model
{
    use HasFactory;

    public function scheduleable(): MorphTo
    {
        return $this->morphTo();
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function days()
    {
        return $this->belongsToMany(Day::class, 'day_schedule');
    }

    protected static function booted()
    {
        static::deleting(function ($schedule) {
            $schedule->days()->detach();
            $schedule->events()->delete();
        });
    }
}
