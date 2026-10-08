<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tool extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'icon',
        'meta_title',
        'meta_description',
        'og_image',
        'sort_order',
        'is_featured',
        'is_popular',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function seoSections(): HasMany
    {
        return $this->hasMany(ToolSeoSection::class)
            ->where('status', true)
            ->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ToolFaq::class)
            ->where('status', true)
            ->orderBy('sort_order');
    }
    /**
 * Semantically related tools used for internal linking.
 */
    public function relatedTools(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'tool_related_tools',
            'tool_id',
            'related_tool_id'
        )
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }
}