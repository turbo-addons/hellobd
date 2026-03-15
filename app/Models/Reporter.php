<?php

namespace App\Models;

use App\Concerns\HasMedia;
use App\Observers\ReporterCacheObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

#[ObservedBy([ReporterCacheObserver::class])]
class Reporter extends Model implements SpatieHasMedia
{
    use HasMedia;
    protected $appends = ['photo_url'];
    protected $fillable = [
        'user_id',
        'type',
        'desk_name',
        'slug',
        'designation',
        'age',
        'bio',
        'location',
        'location_updated_at',
        'credentials',
        'social_links',
        'social_media',
        'verification_status',
        'is_active',
        'total_articles',
        'total_views',
        'rating',
        'rating_count',
        'experience',
        'specialization',
    ];

    protected $casts = [
        'social_links' => 'array',
        'social_media' => 'array',
        'is_active' => 'boolean',
        'location_updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->type === 'desk') {
            return $this->desk_name ?? 'Unknown Desk';
        }
        
        if ($this->user) {
            return $this->user->full_name ?? $this->user->first_name ?? $this->user->name ?? 'Unknown Reporter';
        }
        
        // Fallback for human type without user relationship
        return 'Unknown Reporter';
    }

    public function getNameAttribute(): string
    {
        return $this->getDisplayNameAttribute();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->fit(Fit::Crop, 150, 150);

        $this->addMediaConversion('medium')
            ->width(300)
            ->height(300);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('photo');
    }
}
