<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trainer extends Model
{
    use HasFactory;

    protected $with = ['user'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_trainer');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_trainer');
    }

    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'sesssion_trainer');
    }

    /**
     * Get the user that owns the Trainer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::deleting(function ($trainer) {
            $trainer->courses()->detach();
            $trainer->facilities()->detach();
            $trainer->sessions()->detach();
        });
    }
}
