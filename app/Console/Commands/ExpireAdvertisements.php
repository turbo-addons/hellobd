<?php

namespace App\Console\Commands;

use App\Models\Advertisement;
use Illuminate\Console\Command;

class ExpireAdvertisements extends Command
{
    protected $signature = 'ads:expire';
    protected $description = 'Automatically expire advertisements that have passed their end date or exceeded budget';

    public function handle()
    {
        $expired = Advertisement::where('status', 'active')
            ->where(function($query) {
                $query->where('end_date', '<', now())
                    ->orWhereRaw('total_budget IS NOT NULL AND spent >= total_budget');
            })
            ->update(['status' => 'expired']);

        $this->info("Expired {$expired} advertisements");
        
        return 0;
    }
}
