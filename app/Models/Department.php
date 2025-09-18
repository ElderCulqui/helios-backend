<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'ambassador_name',
        'level',
        'employees_count',
    ];

    /**
     * Get the parent that owns the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id', 'id');
    }

    /**
     * Get all of the children for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id', 'id');
    }

    public function getParentNameAttribute()
    {
        return $this->parent ? $this->parent->name : null;
    }

    public function getChildrenCountAttribute()
    {
        return $this->children()->count();
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->level = rand(1, 100);
            $model->employees_count = rand(1, 100);
        });
    }
}
