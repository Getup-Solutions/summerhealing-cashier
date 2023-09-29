<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Trainer;
use App\Models\Subscription;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [ 'avatar_url','full_name'];

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'subscription_user');
    }

    // public function hasSubscribed($subscription)
    // {
    //     if ($this->roles()->where('value', $role)->first()) {
    //         return true;
    //     }
    //     return false;
    // }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasRole($role)
    {
        if ($this->roles()->where('value', $role)->first()) {
            return true;
        }
        return false;
    }

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

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
                            // ->orWhere('email', 'like', "%{$search}%")
                            // ->orWhere('keywords', 'like', "%{$search}%")
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

            ->when($filters['roles'] ?? false, fn($query, $roles) =>
                $query
                    ->whereHas('roles', fn($query) =>
                        $query->whereIn('value', json_decode($roles))
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

    protected function avatarUrl(): Attribute
    {
        parse_url($this->avatar)['host'] ?? '' === 'images.pexels.com' ? $avatar = $this->avatar : $avatar = '' . $this->avatar;
        return Attribute::make(
            get:fn($value) => asset($avatar)
        );
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            // get: fn (string $value) => ucfirst($value),
            set: function ($value) {
                // dd($value);
                if(empty($value)){
                    return fake()->randomElement(['assets/static/img/avatar_woman.png','assets/static/img/avatar_man.png']);
                }
                else {
                    return $value;
                }

            },
        );
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get:fn() => $this->first_name . ' ' . $this->last_name
        );
    }

    public function phone()
    {
        return $this->hasOne(Trainer::class);
    }
}
