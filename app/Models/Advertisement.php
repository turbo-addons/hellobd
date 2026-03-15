<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Advertisement extends Model
{
    protected $fillable = [
        'vendor_id', 'title', 'content', 'ad_type', 'placement', 'billing_model',
        'rate', 'total_budget', 'spent', 'impressions', 'clicks', 'image',
        'link_url', 'post_id', 'status', 'start_date', 'end_date'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'total_budget' => 'decimal:2',
        'spent' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function getCtrAttribute(): float
    {
        return $this->impressions > 0 ? ($this->clicks / $this->impressions) * 100 : 0;
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->end_date);
    }

    public function isBudgetExceeded(): bool
    {
        return $this->total_budget && $this->spent >= $this->total_budget;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
}
