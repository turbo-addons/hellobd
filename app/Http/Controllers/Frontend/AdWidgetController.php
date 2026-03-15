<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;

class AdWidgetController extends Controller
{
    public function getAd(string $placement)
    {
        $ad = Advertisement::active()
            ->where('placement', $placement)
            ->inRandomOrder()
            ->first();

        if (!$ad) {
            return response()->json(['ad' => null]);
        }

        // Record impression
        $ad->increment('impressions');
        
        if ($ad->billing_model === 'cpm') {
            $cost = ($ad->rate / 1000);
            $ad->increment('spent', $cost);
            $ad->vendor->decrement('wallet_balance', $cost);
            
            // Create transaction
            \App\Models\WalletTransaction::create([
                'vendor_id' => $ad->vendor_id,
                'type' => 'debit',
                'amount' => $cost,
                'balance_after' => $ad->vendor->wallet_balance,
                'description' => 'Ad impression - ' . $ad->title,
                'advertisement_id' => $ad->id,
                'status' => 'completed',
            ]);
        }

        // Check if expired
        if ($ad->isExpired() || $ad->isBudgetExceeded()) {
            $ad->update(['status' => 'expired']);
            return response()->json(['ad' => null]);
        }

        return response()->json([
            'ad' => [
                'id' => $ad->id,
                'title' => $ad->title,
                'content' => $ad->content,
                'image' => $ad->image ? asset('storage/' . $ad->image) : null,
                'link_url' => $ad->link_url,
                'click_url' => route('ad.click', $ad->id),
            ]
        ]);
    }

    public function recordClick(Advertisement $ad)
    {
        $ad->increment('clicks');
        
        if ($ad->billing_model === 'cpc') {
            $ad->increment('spent', $ad->rate);
            $ad->vendor->decrement('wallet_balance', $ad->rate);
            
            // Create transaction
            \App\Models\WalletTransaction::create([
                'vendor_id' => $ad->vendor_id,
                'type' => 'debit',
                'amount' => $ad->rate,
                'balance_after' => $ad->vendor->wallet_balance,
                'description' => 'Ad click - ' . $ad->title,
                'advertisement_id' => $ad->id,
                'status' => 'completed',
            ]);
        }

        // Check if expired
        if ($ad->isExpired() || $ad->isBudgetExceeded()) {
            $ad->update(['status' => 'expired']);
        }

        return redirect($ad->link_url ?? '/');
    }
}
