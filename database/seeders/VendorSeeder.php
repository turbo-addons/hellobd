<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        Vendor::updateOrCreate(
            ['email' => 'vendor@example.com'],
            [
                'name' => 'Sample Vendor Company',
                'slug' => 'sample-vendor-company',
                'email' => 'vendor@example.com',
                'phone' => '+880 1234-567890',
                'website' => 'https://example.com',
                'address' => 'Dhaka, Bangladesh',
                'description' => 'Leading advertising company providing digital marketing solutions.',
                'wallet_balance' => 5000.00,
                'total_spent' => 0.00,
                'is_active' => true,
            ]
        );

        $this->command->info('✅ Sample vendor created successfully!');
    }
}
