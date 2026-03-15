<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $table = 'general_settings';

    protected $fillable = [
        'title',
        'site_logo',
        'fav_icon',
        'email',
        'contact',
        'facebook',
        'twitter',
        'linkedin',
        'youtube',
        'other_one',
        'other_two',
        'other_three',
        'other_four',
        'other_five',
    ];

    // Add accessor methods to get full URL
    public function getSiteLogoUrlAttribute()
    {
        if ($this->site_logo) {
            return asset('storage/logos/' . $this->site_logo);
        }
        return null;
    }

    public function getFavIconUrlAttribute()
    {
        if ($this->fav_icon) {
            return asset('storage/icons/' . $this->fav_icon);
        }
        return null;
    }

    // Optional: Add thumbnails if needed
    public function getSiteLogoThumbUrlAttribute()
    {
        if ($this->site_logo) {
            return asset('storage/logos/thumb_' . $this->site_logo);
        }
        return null;
    }
}