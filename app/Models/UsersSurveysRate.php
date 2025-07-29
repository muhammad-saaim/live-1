<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersSurveysRate extends Model
{

    protected $table = 'users_surveys_rates';

    protected $fillable = [
        'users_id',
        'survey_id',
        'question_id',
        'options_id',
        'evaluatee_id',
        'group_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
 public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function option()
    {
        return $this->belongsTo(Option::class, 'options_id');
    }

    public function surveyModel()
{
    return $this->hasOneThrough(
        SurveyModel::class,
        Survey::class,
        'id',         // Foreign key on surveys table...
        'id',         // Foreign key on survey_models table...
        'survey_id',  // Local key on users_surveys_rates table...
        'model_id'    // Local key on surveys table...
    );
}
public function types()
{
    return $this->hasOneThrough(
        Type::class,
        Question::class,
        'id',         // Foreign key on the questions table...
        'id',         // Foreign key on the types table...
        'question_id', // Local key on the users_surveys_rates table...
        'type_id'     // Local key on the questions table...
    );
}
       
    
}
