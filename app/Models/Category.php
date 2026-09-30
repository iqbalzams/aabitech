<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'icon',
        'meta_title',
        'meta_description',
        'og_image',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function tools(): HasMany
    {
        return $this->hasMany(Tool::class)
            ->where('status', true)
            ->orderBy('sort_order');
    }
}