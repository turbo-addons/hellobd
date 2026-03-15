<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingTransaction extends Model
{
    protected $fillable = [
        'vendor_id',
        'ad_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
