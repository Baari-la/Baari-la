<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TextileSector extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'name_en',
        'description',
        'parent_id',
        'level',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'level' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            self::class,
            'parent_id'
        );
    }

    public function children(): HasMany
    {
        return $this->hasMany(
            self::class,
            'parent_id'
        )->orderBy('sort_order');
    }

    public function activeChildren(): HasMany
    {
        return $this->children()
            ->where('is_active', true);
    }
}