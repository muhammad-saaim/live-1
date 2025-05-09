<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;


class UserRelative extends Pivot
{
    use HasFactory;
    protected $table = 'user_relatives';

    protected $fillable = [
        'user_id',
        'relative_id',
        'relation_id',
    ];

    public function relation()
    {
        return $this->belongsTo(Relation::class);
    }

    public function relative()
    {
        return $this->belongsTo(User::class, 'relative_id');
    }
}
