<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'question',
        'description',
        'options',
        'correct_answer',
        'points',
        'is_active',
        'survey_id',
        'type_id',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    // Define the relationship with the options (if applicable)
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function usersSurveysRates()
    {
        return $this->hasMany(UsersSurveysRate::class, 'question_id');
    }

}
