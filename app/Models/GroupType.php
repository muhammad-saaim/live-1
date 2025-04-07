<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupType extends Model
{
    protected $fillable = ['name', 'parent_id'];

    /**
     * The groups that belong to the group type.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_group_type');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(GroupType::class, 'parent_id');
    }

    /**
     * Get the child group types.
     */
    public function children(): HasMany
    {
        return $this->hasMany(GroupType::class, 'parent_id');
    }

}
