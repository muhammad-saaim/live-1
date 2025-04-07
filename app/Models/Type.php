<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    public function scopeInactive($query){
        return $query->where('status', false);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

}
