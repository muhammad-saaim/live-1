<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{

    protected $fillable = [
        'group_id',
        'invited_by',
        'email',
        'token',
    ];

    /**
     * Davetin ait olduğu grup.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Daveti gönderen kullanıcı.
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
