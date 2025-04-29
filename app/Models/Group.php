<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
    protected $guarded = [];
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    public function groupTypes(): BelongsToMany
    {
        return $this->belongsToMany(GroupType::class, 'group_group_type')->withTimestamps();
    }

    public function defaultSurveys()
    {
        $groupType = $this->groupTypes->first()?->name ?? null;
    // dd($groupType);
        return Survey::where('is_default', true)
            ->where('is_active', true)
            ->whereJsonContains('applies_to', 'Group')
            ->get();
    }

}
