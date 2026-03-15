<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Reporter;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BulkPostSeeder extends Seeder
{
    private array $titles = [
        'প্রধানমন্ত্রীর গুরুত্বপূর্ণ ঘোষণা',
        'বাংলাদেশে নতুন প্রযুক্তি উদ্ভাবন',
        'শিক্ষা ক্ষেত্রে নতুন সংস্কার',
        'স্বাস্থ্য সেবায় বিপ্লবী পরিবর্তন',
        'অর্থনৈতিক উন্নয়নে নতুন পরিকল্পনা',
        'কৃষি ক্ষেত্রে আধুনিকায়ন',
        'তথ্য প্রযুক্তিতে বাংলাদেশের অগ্রগতি',
        'যুব সমাজের জন্য নতুন সুযোগ',
        'নারী ক্ষমতায়নে সরকারি পদক্ষেপ',
        'পরিবেশ রক্ষায় নতুন উদ্যোগ',
        'শিল্প কারখানায় বিনিয়োগ বৃদ্ধি',
        'গ্রামীণ উন্নয়নে নতুন প্রকল্প',
    ];

    private array $excerpts = [
        'এই খবরটি দেশের জন্য অত্যন্ত গুরুত্বপূর্ণ এবং সকলের জানা উচিত।',
        'নতুন এই পদক্ষেপ দেশের উন্নয়নে গুরুত্বপূর্ণ ভূমিকা রাখবে।',
        'বিশেষজ্ঞরা এই সিদ্ধান্তকে ইতিবাচক হিসেবে দেখছেন।',
        'এই উদ্যোগ দেশের মানুষের জীবনযাত্রার মান উন্নত করবে।',
    ];

    public function run(): void
    {
        $reporters = Reporter::with('user')->get();
        $categories = Term::where('taxonomy', 'category')->pluck('id')->toArray();
        
        if ($reporters->isEmpty() || empty($categories)) {
            $this->command->error('Please seed reporters and categories first!');
            return;
        }

        $this->command->info('Creating 12000 posts...');
        $bar = $this->command->getOutput()->createProgressBar(12000);

        $posts = [];
        for ($i = 1; $i <= 12000; $i++) {
            $title = $this->titles[array_rand($this->titles)] . ' - ' . (11980 + $i);
            $isSponsored = rand(1, 100) <= 5;
            
            $posts[] = [
                'user_id' => 1,
                'reporter_id' => $reporters->random()->id,
                'post_type' => 'post',
                'title' => $title,
                'slug' => Str::slug($title) . '-' . $i,
                'excerpt' => $this->excerpts[array_rand($this->excerpts)],
                'content' => '<p>' . $this->excerpts[array_rand($this->excerpts)] . '</p>',
                'status' => 'published',
                'is_sponsored' => $isSponsored,
                'views' => rand(100, 5000),
                'google_news_score' => rand(20, 50),
                'published_at' => now()->subDays(rand(0, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($posts) >= 500) {
                Post::insert($posts);
                $posts = [];
            }
            $bar->advance();
        }

        if (!empty($posts)) {
            Post::insert($posts);
        }

        $bar->finish();
        $this->command->newLine();

        $this->command->info('Attaching categories to posts...');
        $totalPosts = Post::count();
        $bar2 = $this->command->getOutput()->createProgressBar($totalPosts);

        Post::chunk(1000, function ($posts) use ($categories, $bar2) {
            foreach ($posts as $post) {
                if ($post->terms()->count() == 0) {
                    $post->terms()->attach($categories[array_rand($categories)]);
                }
                $bar2->advance();
            }
        });

        $bar2->finish();
        $this->command->newLine();
        $this->command->info('12000 posts created successfully!');
    }
}
