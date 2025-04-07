<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    protected $table = 'question_options';
    protected $fillable = [
        'question_id',
        'name',
        'point',
        'is_correct',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
    public function usersSurveysRates()
    {
        return $this->hasMany(UsersSurveysRate::class, 'options_id');
    }

}
