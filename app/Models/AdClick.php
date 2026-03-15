<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdClick extends Model
{
    public $timestamps = false;
    protected $fillable = ['ad_id', 'ip_address', 'user_agent', 'referrer', 'clicked_at'];
    protected $casts = ['clicked_at' => 'datetime'];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
