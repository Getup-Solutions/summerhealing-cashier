<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
class Lead extends Model
{
    use HasFactory;

    protected $appends = ['full_name'];

    public function scopeFilter($query, array $filters)
    {
        $query
            ->when(
                $filters['search'] ?? false, fn($query, $search) =>
                $query
                    ->where(fn($query) =>
                        $query
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('source', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%")
                            ->orWhere('id', '=', $search)
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

            // ->when($filters['roles'] ?? false, fn($query, $roles) =>
            //     $query
            //         ->whereHas('roles', fn($query) =>
            //             $query->whereIn('value', json_decode($roles))
            //         )
            // )



                ->when($filters['privilage'] ?? false, function($query, $privilage){
                   
                    // dd($privilage);
                    // dd(json_decode($privilage));
                    if(in_array('user',json_decode($privilage))){
                        $query
                        ->whereNotNull('user_id');
                    }
                    // else  if(in_array('subscriber',json_decode($privilage))){
                    //     $query
                    //     ->whereNotNull('subscription_id');
                    // }
                    
                }   
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

    public function fullName(): Attribute
    {
        return Attribute::make(
            get:fn() => $this->first_name . ' ' . $this->last_name
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
