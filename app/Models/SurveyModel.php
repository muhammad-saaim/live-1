<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $array)
 */
class SurveyModel extends Model
{
    protected $table = 'survey_models';

    protected $fillable = [
        'title',
        'description',
        'is_active',
    ];

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class, 'model_id');
    }

}
