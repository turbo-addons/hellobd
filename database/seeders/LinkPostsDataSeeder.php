<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Reporter;
use App\Models\Term;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinkPostsDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting to link posts with reporters and categories...');

        $reporters = Reporter::all();
        $categories = Term::where('taxonomy', 'category')->get();

        if ($reporters->isEmpty()) {
            $this->command->error('No reporters found! Please run ReporterSeeder first.');
            return;
        }

        if ($categories->isEmpty()) {
            $this->command->error('No categories found! Please run ContentSeeder first.');
            return;
        }

        $this->command->info("Found {$reporters->count()} reporters and {$categories->count()} categories");

        // Update posts in chunks
        Post::chunk(500, function ($posts) use ($reporters, $categories) {
            foreach ($posts as $post) {
                // Assign random reporter
                $reporter = $reporters->random();
                
                // Assign random category
                $category = $categories->random();
                
                // Random post type meta - EVERY post gets at least one type
                $postTypeMeta = [];
                $rand = rand(1, 100);
                
                // Ensure every post has at least one type
                if ($rand <= 15) { // 15% breaking
                    $postTypeMeta['is_breaking'] = true;
                } elseif ($rand <= 35) { // 20% featured
                    $postTypeMeta['is_featured'] = true;
                } elseif ($rand <= 50) { // 15% slide
                    $postTypeMeta['is_slide'] = true;
                } elseif ($rand <= 65) { // 15% live
                    $postTypeMeta['is_live'] = true;
                } else { // 35% get featured as default
                    $postTypeMeta['is_featured'] = true;
                }
                
                // Some posts can have multiple types (10% chance)
                if (rand(1, 100) <= 10) {
                    $types = ['is_breaking', 'is_featured', 'is_slide', 'is_live'];
                    $extraType = $types[array_rand($types)];
                    $postTypeMeta[$extraType] = true;
                }
                
                // Random sponsored (5%)
                $isSponsored = rand(1, 100) <= 5;
                
                // Random views
                $views = rand(100, 5000);
                
                // Random Google News score
                $googleScore = rand(20, 50);
                
                // Update post
                $post->update([
                    'reporter_id' => $reporter->id,
                    'post_type_meta' => $postTypeMeta,
                    'is_sponsored' => $isSponsored,
                    'views' => $views,
                    'google_news_score' => $googleScore,
                ]);
                
                // Attach category
                if (!$post->terms()->where('taxonomy', 'category')->exists()) {
                    $post->terms()->attach($category->id);
                }
            }
            
            $this->command->info('Processed ' . $posts->count() . ' posts...');
        });

        // Update reporter statistics
        foreach ($reporters as $reporter) {
            $totalPosts = Post::where('reporter_id', $reporter->id)->count();
            $totalViews = Post::where('reporter_id', $reporter->id)->sum('views');
            
            $reporter->update([
                'total_articles' => $totalPosts,
                'total_views' => $totalViews,
            ]);
        }

        $this->command->info('✅ All posts linked successfully!');
        $this->command->info('✅ Reporter statistics updated!');
    }
}
