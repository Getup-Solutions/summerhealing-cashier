<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendar extends Model
{
    protected $with = ['events'];
    protected $append = ['session_events'];
    use HasFactory;
    public function events()
    {
        return $this->belongsToMany(Event::class, 'calendar_event');
    }
    
    public function sessionEvents(){
         return $this->events()->where('eventable_type','=','App\Models\Session')->get();
    }
}
