<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id","biography","full_name","city","birthdate","blood_type","graduated_department","graduated_school","graduated_year","phone","profession","current_job",
        "past_job","hobies","pet_type","skill_1","skill_2","skill_3"
    ];

    function user(){
        return $this->belongsTo(Profile::class);
    }
}
