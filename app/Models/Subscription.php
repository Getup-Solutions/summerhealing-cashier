<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Subscription extends Model
{
    use HasFactory;
    protected $with = ['user'];
    protected $appends = ['end_date', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user->subscribed($this->name) ? "Active" : "Inactive"
        );
        // return Carbon::createFromTimeStamp($this->user->subscription('demo')->asStripeSubscription()->current_period_end)->toFormattedDateString();
    }

    public function endDate(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::createFromTimeStamp($this->user->subscription('demo')->asStripeSubscription()->current_period_end)->toFormattedDateString()
        );
        // return Carbon::createFromTimeStamp($this->user->subscription('demo')->asStripeSubscription()->current_period_end)->toFormattedDateString();
    }

    public function scopeFilter($query, array $filters)
    {
        $query
            ->when(
                $filters['search'] ?? false,
                fn ($query, $search) =>
                $query
                    ->where(
                        fn ($query) =>
                        $query
                            ->where('name', 'like', "%{$search}%")
                            // ->orWhere('description', 'like', "%{$search}%")
                            // ->orWhere('email', 'like', "%{$search}%")
                            // ->orWhere('email', 'like', "%{$search}%")
                            // ->orWhere('keywords', 'like', "%{$search}%")
                            // ->orWhere('price', '=', $search)
                            // ->orWhere('validity', '=', $search)
                            ->orWhereHas(
                                'user',
                                fn ($query) =>
                                $query->where('first_name', 'like', "%{$search}%")
                            )
                            // ->orWhereHas(
                            //     'user',
                            //     fn($query) =>
                            //     $query->where('full_name', 'like', "%{$search}%")
                            // )
                            ->orWhereHas(
                                'user',
                                fn ($query) =>
                                $query->where('last_name', 'like', "%{$search}%")
                            )
                    )
            )

            ->when(
                $filters['roles'] ?? false,
                fn ($query, $roles) =>
                $query
                    ->whereHas(
                        'roles',
                        fn ($query) =>
                        $query->whereIn('value', json_decode($roles))
                    )
            )

            ->when(
                $filters['dateStart'] ?? false,
                function ($query, $dateStart) {
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

            ->when(
                $filters['status'] ?? false,
                fn ($query, $status) =>
                $query->whereHas(
                    'status',
                    fn ($query) =>
                    $query->whereIn('status', json_decode($status))
                )
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

            ->when(
                $filters['sortBy'] ?? 'default',
                function ($query, $sortBy) {

                    if ($sortBy === 'date-dsc') {
                        $query->latest();
                    }
                    if ($sortBy === 'date-asc') {
                        $query->oldest();
                    }
                    if ($sortBy === 'exp-date-dsc') {
                        $query->latest();
                    }
                    if ($sortBy === 'exp-date-dsc') {
                        $query->oldest();
                    }
                    if ($sortBy === 'default') {
                        $query->latest();
                    }
                }
            );
    }
}
