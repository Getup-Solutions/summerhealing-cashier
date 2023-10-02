<?php

namespace App\Models;

use App\Models\Agegroup;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $appends = ['thumbnail_url'];

    public function scopeFilter($query, array $filters)
    {
        $query
            ->when(
                $filters['search'] ?? false, fn($query, $search) =>
                $query
                    ->where(fn($query) =>
                        $query
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('excerpt', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            // ->orWhere('email', 'like', "%{$search}%")
                            // ->orWhere('email', 'like', "%{$search}%")
                            // ->orWhere('keywords', 'like', "%{$search}%")
                            ->orWhere('id', '=', $search)
                            ->orWhere('price', '=', $search)
                    )
            )

            ->when($filters['dateStart'] ?? false, function ($query, $dateStart) {
                $dateStart = Carbon::createFromFormat('m/d/Y', $dateStart)->format('Y-m-d');
                $query
                    ->whereDate('created_at', '>=', $dateStart);
            }
            )

            ->when(
                $filters['dateEnd'] ?? false,
                function ($query, $dateEnd) {
                    $dateEnd = Carbon::createFromFormat('m/d/Y', $dateEnd)->format('Y-m-d');
                    $query
                        ->whereDate('created_at', '<=', $dateEnd);
                }
            )

            ->when($filters['published'] ?? false, fn($query, $published) =>
                $query
                    ->whereIn('published', json_decode($published))

            )

            //     ->when($filters['published'] ?? false, fn($query, $published) =>
            //     $query
            //         ->where('published', fn($query) =>
            //             $query->where('value', json_decode($roles))
            //         )
            // )

            //     ->when($filters['published'] ?? false, fn($query, $published) =>
            //         $query
            //             ->where('published','=',$published[0])
            //     )
            ->when($filters['subscriptions'] ?? false, fn($query, $subscriptions) =>
                $query
                    ->whereHas('subscriptions', fn($query) =>
                        $query->whereIn('slug', json_decode($subscriptions))
                    )
            )

            ->when(
                $filters['sortBy'] ?? 'default',
                function ($query, $sortBy) {

                    if ($sortBy === 'date-dsc') {
                        $query->latest();
                    }
                    if ($sortBy === 'date-asc') {
                        $query->oldest();
                    }
                    if ($sortBy === 'default') {
                        $query->latest();
                    }
                }
            );
    }

    protected function thumbnailUrl(): Attribute
    {
        parse_url($this->avatar)['host'] ?? '' === 'images.pexels.com' ? $avatar = $this->avatar : $avatar = '' . $this->avatar;
        return Attribute::make(
            get: fn($value) => asset($this->thumbnail)
        );
    }

    public function ageGroup(): BelongsTo
    {
        return $this->belongsTo(Agegroup::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'course_subscription')->withPivot('course_price');
    }

    public function trainers()
    {
        return $this->belongsToMany(Trainer::class, 'course_trainer');
    }

    // protected static function booted()
    // {
    //     static::deleting(function ($course) {
    //         $course->subscriptions()->detach();
    //     });
    // }

}
