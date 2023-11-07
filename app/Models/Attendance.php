<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attendance extends Model
{
    use HasFactory;
    protected $with=['user'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendanceable(): MorphTo
    {
        return $this->morphTo();
    }
}
