<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    // Table name
    protected $table = 'surveys';

    // Mass-assignable attributes
    protected $fillable = [
        'model_id',
        'title',
        'description',
        'is_active',
        'is_default',
        'applies_to',
        'targets',
    ];

    // Casts for JSON fields
    protected $casts = [
        'applies_to' => 'array',
        'targets' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Define a relationship with the SurveyModel (assuming SurveyModel is another model).
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(SurveyModel::class, 'model_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'survey_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_surveys')
            ->withPivot('is_completed') // Access the status column
            ->withTimestamps();  // Include created_at and updated_at
    }

    public function usersSurveysRates()
    {
        return $this->hasMany(UsersSurveysRate::class, 'survey_id');
    }

}
