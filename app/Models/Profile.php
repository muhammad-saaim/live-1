<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'full_name', 'city_of_residence', 'date_of_birth', 'blood_type', 'gender',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function personal_info(){
        return $this->hasOne(PersonalInfo::class);
    }


}
