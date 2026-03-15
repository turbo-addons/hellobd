<?php

namespace Database\Seeders;

use App\Models\Reporter;
use App\Models\User;
use Illuminate\Database\Seeder;

class BengaliReportersSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating 8 Bengali reporters...');

        $reporters = [
            [
                'name' => 'মোহাম্মদ রহিম',
                'email' => 'rahim@hellobd.news',
                'designation' => 'সিনিয়র রিপোর্টার',
                'age' => 35,
                'location' => 'Dhaka',
                'experience' => '8 years',
                'specialization' => 'Politics',
                'social_media' => [
                    'facebook' => 'https://facebook.com/rahim.reporter',
                    'twitter' => 'https://twitter.com/rahim_news',
                    'youtube' => 'https://youtube.com/@rahimnews',
                ],
            ],
            [
                'name' => 'ফাতেমা খাতুন',
                'email' => 'fatema@hellobd.news',
                'designation' => 'সিনিয়র রিপোর্টার',
                'age' => 32,
                'location' => 'Chittagong',
                'experience' => '7 years',
                'specialization' => 'Education',
                'social_media' => [
                    'facebook' => 'https://facebook.com/fatema.reporter',
                    'linkedin' => 'https://linkedin.com/in/fatema-khatun',
                    'youtube' => 'https://youtube.com/@fatemaedu',
                ],
            ],
            [
                'name' => 'আহমেদ হাসান',
                'email' => 'ahmed@hellobd.news',
                'designation' => 'সিনিয়র রিপোর্টার',
                'age' => 38,
                'location' => 'Dhaka',
                'experience' => '10 years',
                'specialization' => 'Sports',
                'social_media' => [
                    'twitter' => 'https://twitter.com/ahmed_sports',
                    'instagram' => 'https://instagram.com/ahmed.hassan',
                    'youtube' => 'https://youtube.com/@ahmedsports',
                ],
            ],
            [
                'name' => 'রোকেয়া বেগম',
                'email' => 'rokeya@hellobd.news',
                'designation' => 'সিনিয়র রিপোর্টার',
                'age' => 30,
                'location' => 'Sylhet',
                'experience' => '6 years',
                'specialization' => 'Economy',
                'social_media' => [
                    'linkedin' => 'https://linkedin.com/in/rokeya-begum',
                    'youtube' => 'https://youtube.com/@rokeyaeconomy',
                ],
            ],
            [
                'name' => 'করিম উদ্দিন',
                'email' => 'karim@hellobd.news',
                'designation' => 'সিনিয়র রিপোর্টার',
                'age' => 40,
                'location' => 'Rajshahi',
                'experience' => '9 years',
                'specialization' => 'International',
                'social_media' => [
                    'twitter' => 'https://twitter.com/karim_intl',
                    'youtube' => 'https://youtube.com/@kariminternational',
                ],
            ],
            [
                'name' => 'সালমা আক্তার',
                'email' => 'salma@hellobd.news',
                'designation' => 'সিনিয়র রিপোর্টার',
                'age' => 28,
                'location' => 'Khulna',
                'experience' => '5 years',
                'specialization' => 'Entertainment',
                'social_media' => [
                    'facebook' => 'https://facebook.com/salma.entertainment',
                    'instagram' => 'https://instagram.com/salma.aktar',
                    'youtube' => 'https://youtube.com/@salmaentertainment',
                ],
            ],
            [
                'name' => 'রফিকুল ইসলাম',
                'email' => 'rafiq@hellobd.news',
                'designation' => 'সিনিয়র রিপোর্টার',
                'age' => 42,
                'location' => 'Dhaka',
                'experience' => '11 years',
                'specialization' => 'Technology',
                'social_media' => [
                    'twitter' => 'https://twitter.com/rafiq_tech',
                    'linkedin' => 'https://linkedin.com/in/rafiqul-islam',
                    'youtube' => 'https://youtube.com/@rafiqtech',
                ],
            ],
            [
                'name' => 'নাসরিন সুলতানা',
                'email' => 'nasrin@hellobd.news',
                'designation' => 'সিনিয়র রিপোর্টার',
                'age' => 33,
                'location' => 'Barisal',
                'experience' => '8 years',
                'specialization' => 'Health',
                'social_media' => [
                    'facebook' => 'https://facebook.com/nasrin.health',
                    'linkedin' => 'https://linkedin.com/in/nasrin-sultana',
                    'youtube' => 'https://youtube.com/@nasrinhealth',
                ],
            ],
        ];

        foreach ($reporters as $reporterData) {
            // Create user first
            $user = User::firstOrCreate(
                ['email' => $reporterData['email']],
                [
                    'first_name' => $reporterData['name'],
                    'last_name' => '',
                    'username' => explode('@', $reporterData['email'])[0],
                    'password' => bcrypt('12345678'),
                    'email_verified_at' => now(),
                ]
            );

            // Assign Reporter role
            if (!$user->hasRole('Reporter')) {
                $user->assignRole('Reporter');
            }

            // Create reporter
            Reporter::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'type' => 'human',
                    'slug' => \Str::slug($reporterData['name'] . '-' . $user->id),
                    'designation' => $reporterData['designation'],
                    'age' => $reporterData['age'],
                    'location' => $reporterData['location'],
                    'experience' => $reporterData['experience'],
                    'specialization' => $reporterData['specialization'],
                    'social_media' => $reporterData['social_media'],
                    'verification_status' => 'pending',
                    'is_active' => true,
                    'rating' => 0,
                    'rating_count' => 0,
                ]
            );

            $this->command->info("✓ Created: {$reporterData['name']}");
        }

        $this->command->info('✅ 8 Bengali reporters created successfully!');
    }
}
