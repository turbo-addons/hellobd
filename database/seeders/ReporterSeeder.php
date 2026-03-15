<?php

namespace Database\Seeders;

use App\Models\Reporter;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReporterSeeder extends Seeder
{
    public function run(): void
    {
        // Get first user
        $user = User::first();

        if (!$user) {
            $this->command->warn('No users found. Please create users first.');
            return;
        }

        // Create desk reporters
        Reporter::create([
            'user_id' => $user->id,
            'type' => 'desk',
            'desk_name' => 'Hellobd Desk',
            'slug' => 'hellobd-desk',
            'designation' => 'News Desk',
            'bio' => 'Official Hellobd news desk for breaking news and updates.',
            'verification_status' => 'verified',
            'is_active' => true,
            'rating' => 4.5,
            'rating_count' => 120,
            'experience' => '5+ years',
            'specialization' => 'General News',
        ]);

        Reporter::create([
            'user_id' => $user->id,
            'type' => 'desk',
            'desk_name' => 'Hellobd Sports Desk',
            'slug' => 'hellobd-sports-desk',
            'designation' => 'Sports Desk',
            'bio' => 'Covering all sports news and live updates.',
            'verification_status' => 'verified',
            'is_active' => true,
            'rating' => 4.7,
            'rating_count' => 85,
            'experience' => '7+ years',
            'specialization' => 'Sports',
        ]);

        Reporter::create([
            'user_id' => $user->id,
            'type' => 'desk',
            'desk_name' => 'HellobdLive Desk',
            'slug' => 'hellobdlive-desk',
            'designation' => 'Live News Desk',
            'bio' => 'Real-time news coverage and live reporting.',
            'verification_status' => 'verified',
            'is_active' => true,
            'rating' => 4.8,
            'rating_count' => 200,
            'experience' => '10+ years',
            'specialization' => 'Breaking News',
        ]);

        // Create human reporter
        Reporter::create([
            'user_id' => $user->id,
            'type' => 'human',
            'slug' => 'reporter-' . $user->id,
            'designation' => 'Senior Reporter',
            'bio' => 'Experienced journalist covering national and international news.',
            'verification_status' => 'verified',
            'is_active' => true,
            'total_articles' => 45,
            'total_views' => 12500,
            'rating' => 4.6,
            'rating_count' => 67,
            'experience' => '8 years',
            'specialization' => 'Politics',
        ]);

        $this->command->info('Reporters seeded successfully!');
    }
}
