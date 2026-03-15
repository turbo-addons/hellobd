<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Vendor;
use App\Models\Post;
use Illuminate\Database\Seeder;

class SampleAdvertisementSeeder extends Seeder
{
    public function run(): void
    {
        $vendor = Vendor::first();
        
        if (!$vendor) {
            $this->command->error('No vendor found. Please run VendorSeeder first.');
            return;
        }

        $post = Post::where('status', 'published')->first();

        $ads = [
            [
                'vendor_id' => $vendor->id,
                'title' => 'Header Banner - Tech Product Launch',
                'content' => 'Discover the latest technology products',
                'ad_type' => 'banner',
                'placement' => 'header',
                'billing_model' => 'cpm',
                'rate' => 2.50,
                'total_budget' => 500.00,
                'spent' => 125.50,
                'impressions' => 50200,
                'clicks' => 1250,
                'link_url' => 'https://example.com/tech-products',
                'status' => 'active',
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(20),
            ],
            [
                'vendor_id' => $vendor->id,
                'title' => 'Sidebar Ad - Special Offer',
                'content' => 'Limited time offer - 50% off',
                'ad_type' => 'sidebar',
                'placement' => 'sidebar',
                'billing_model' => 'cpc',
                'rate' => 0.75,
                'total_budget' => 300.00,
                'spent' => 82.50,
                'impressions' => 15000,
                'clicks' => 110,
                'link_url' => 'https://example.com/special-offer',
                'status' => 'active',
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(25),
            ],
            [
                'vendor_id' => $vendor->id,
                'title' => 'Sponsored Post - New Service',
                'content' => 'Check out our new service',
                'ad_type' => 'sponsored_post',
                'placement' => 'content',
                'billing_model' => 'fixed',
                'rate' => 500.00,
                'total_budget' => 500.00,
                'spent' => 500.00,
                'impressions' => 8500,
                'clicks' => 425,
                'post_id' => $post?->id,
                'link_url' => 'https://example.com/new-service',
                'status' => 'active',
                'start_date' => now()->subDays(3),
                'end_date' => now()->addDays(27),
            ],
            [
                'vendor_id' => $vendor->id,
                'title' => 'Footer Banner - Subscribe Now',
                'content' => 'Subscribe to our newsletter',
                'ad_type' => 'footer',
                'placement' => 'footer',
                'billing_model' => 'cpm',
                'rate' => 1.50,
                'total_budget' => null,
                'spent' => 45.00,
                'impressions' => 30000,
                'clicks' => 600,
                'link_url' => 'https://example.com/subscribe',
                'status' => 'active',
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(15),
            ],
            [
                'vendor_id' => $vendor->id,
                'title' => 'Expired Campaign - Old Product',
                'content' => 'This campaign has ended',
                'ad_type' => 'banner',
                'placement' => 'header',
                'billing_model' => 'cpc',
                'rate' => 1.00,
                'total_budget' => 200.00,
                'spent' => 200.00,
                'impressions' => 25000,
                'clicks' => 200,
                'link_url' => 'https://example.com/old-product',
                'status' => 'expired',
                'start_date' => now()->subDays(60),
                'end_date' => now()->subDays(5),
            ],
        ];

        foreach ($ads as $ad) {
            Advertisement::create($ad);
        }

        // Deduct spent amount from vendor wallet
        $totalSpent = collect($ads)->sum('spent');
        $vendor->decrement('wallet_balance', $totalSpent);

        $this->command->info('✅ Sample advertisements created successfully!');
        $this->command->info("Total spent deducted from vendor wallet: $" . number_format($totalSpent, 2));
    }
}
