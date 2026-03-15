<?php

namespace App\Observers;

use App\Models\Reporter;
use Illuminate\Support\Facades\Cache;

class ReporterCacheObserver
{
    public function created(Reporter $reporter): void
    {
        $this->clearCache();
    }

    public function updated(Reporter $reporter): void
    {
        $this->clearCache();
    }

    public function deleted(Reporter $reporter): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        Cache::forget('dashboard_stats');
        Cache::forget('reporter_stats');
    }
}
