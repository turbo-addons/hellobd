<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'website',
        'address',
        'description',
        'logo',
        'wallet_balance',
        'total_spent',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'wallet_balance' => 'decimal:2',
        'total_spent' => 'decimal:2',
    ];

    public function ads(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
