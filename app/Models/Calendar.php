<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Calendar extends Model
{
    protected $with = ['events'];
    protected $appends = ['is_today'];
    use HasFactory;
    
    public function events()
    {
        return $this->belongsToMany(Event::class, 'calendar_event')->withPivot('schedule_id');
    }
    
    public function sessionEvents()
    {
         return $this->events()->where('eventable_type','=','App\Models\Session')->get();
    }

    public function isToday(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->formated_date === date("Y-m-d",strtotime("today"))
        );
        // return  $this->formated_date === date("Y-m-d",strtotime("today"));
        // return date("Y-m-d",strtotime("today"));
    }
}
