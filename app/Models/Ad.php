<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ad extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'type',
        'billing_model',
        'rate',
        'total_budget',
        'total_spent',
        'placement',
        'content',
        'image_url',
        'link_url',
        'width',
        'height',
        'start_date',
        'end_date',
        'impressions',
        'clicks',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'impressions' => 'integer',
        'clicks' => 'integer',
        'priority' => 'integer',
        'rate' => 'decimal:2',
        'total_budget' => 'decimal:2',
        'total_spent' => 'decimal:2',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function impressionRecords()
    {
        return $this->hasMany(AdImpression::class);
    }

    public function clickRecords()
    {
        return $this->hasMany(AdClick::class);
    }

    public function getCtrAttribute()
    {
        return $this->impressions > 0 ? round(($this->clicks / $this->impressions) * 100, 2) : 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            });
    }

    public function scopePlacement($query, string $placement)
    {
        return $query->where('placement', $placement);
    }
}
