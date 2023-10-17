<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calender extends Model
{
    protected $with = ['events'];
    protected $append = ['session_events'];
    use HasFactory;
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
    
    public function sessionEvents(){
         return $this->events()->where('eventable_type','=','App\Models\Session')->get();
    }
}
