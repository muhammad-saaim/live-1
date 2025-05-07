<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitedMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'inviter_id',
        'name',
        'email',
        'relation',
        'invite',
        'status',
    ];
}
