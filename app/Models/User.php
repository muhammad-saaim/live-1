<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles,HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded= [];

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
    public static function generateUniqueUsername($username)
    {
        if ($username === null) {
            $username = Str::lower(Str::random(8));
        }
        if (User::where('username', $username)->exists()) {
            $newUsername = $username . Str::lower(Str::random(3));
            $username = self::generateUsername($newUsername);
        }
        return $username;
    }
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    public function surveys(): BelongsToMany
    {
        return $this->belongsToMany(Survey::class, 'users_surveys')
            ->withPivot('is_completed')
            ->withTimestamps();
    }

    public function usersSurveysRates()
    {
        return $this->hasMany(UsersSurveysRate::class, 'users_id');
    }
    
    public function relatives()
    {
        return $this->belongsToMany(User::class, 'user_relatives', 'user_id', 'relative_id')
                    ->using(UserRelative::class)
                    ->withPivot('relation_id')
                    // ->as('pivotRelation') // Optional: name the custom pivot accessor
                    ->withTimestamps();
    }
    
    public function relatedTo()
    {
        return $this->belongsToMany(User::class, 'user_relatives', 'relative_id', 'user_id')
                    ->withPivot('relation_id')
                    ->withTimestamps();
    }


}
