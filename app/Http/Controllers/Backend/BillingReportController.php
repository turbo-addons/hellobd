<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Vendor;
use App\Models\WalletTransaction;

class BillingReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue' => WalletTransaction::where('type', 'debit')->sum('amount'),
            'total_recharged' => WalletTransaction::where('type', 'credit')->where('status', 'completed')->sum('amount'),
            'active_ads' => Advertisement::where('status', 'active')->count(),
            'total_impressions' => Advertisement::sum('impressions'),
            'total_clicks' => Advertisement::sum('clicks'),
        ];

        $vendors = Vendor::with(['ads' => function($q) {
            $q->select('vendor_id', 'spent', 'impressions', 'clicks');
        }])->get()->map(function($vendor) {
            return [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'email' => $vendor->email,
                'wallet_balance' => $vendor->wallet_balance,
                'active_ads' => $vendor->ads->where('status', 'active')->count(),
                'total_spent' => $vendor->ads->sum('spent'),
                'total_impressions' => $vendor->ads->sum('impressions'),
                'total_clicks' => $vendor->ads->sum('clicks'),
            ];
        });

        $recentTransactions = WalletTransaction::with('vendor')
            ->where('type', 'debit')
            ->latest()
            ->take(20)
            ->get();

        return view('backend.pages.billing.index', compact('stats', 'vendors', 'recentTransactions'));
    }
}
