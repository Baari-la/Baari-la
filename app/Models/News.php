<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title_id',
        'summary_id',
        'content_id',

        'title_en',
        'summary_en',
        'content_en',

        'slug',
        'category',
        'image',
        'author_id',

        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $appends = [
        'title',
        'summary',
        'content',
    ];

    protected $casts = [
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

    public function getTitleAttribute()
    {
        return app()->getLocale() === 'en'
            ? $this->title_en
            : $this->title_id;
    }

    public function getSummaryAttribute()
    {
        return app()->getLocale() === 'en'
            ? $this->summary_en
            : $this->summary_id;
    }

    public function getContentAttribute()
    {
        return app()->getLocale() === 'en'
            ? $this->content_en
            : $this->content_id;
    }

    public function getCategoryAttribute($value)
    {
        return $value ?: 'Industry News';
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}