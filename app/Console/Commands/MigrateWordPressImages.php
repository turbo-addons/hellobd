<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Illuminate\Support\Facades\Http;

class MigrateWordPressImages extends Command
{
    protected $signature = 'wp:migrate-images {--limit=100}';
    protected $description = 'Download WordPress images from live site and migrate';

    public function handle()
    {
        $limit = $this->option('limit');
        
        $wpPosts = DB::select("
            SELECT 
                p.ID as wp_id,
                p.post_title,
                pm.meta_value as thumbnail_id,
                pm2.meta_value as image_path
            FROM hellobdn_wp656.wphp_posts p
            INNER JOIN hellobdn_wp656.wphp_postmeta pm ON p.ID = pm.post_id AND pm.meta_key = '_thumbnail_id'
            INNER JOIN hellobdn_wp656.wphp_postmeta pm2 ON pm.meta_value = pm2.post_id AND pm2.meta_key = '_wp_attached_file'
            WHERE p.post_type = 'post' AND p.post_status = 'publish'
            LIMIT ?
        ", [$limit]);

        $this->info("Found " . count($wpPosts) . " WordPress posts with images");

        $success = 0;
        $failed = 0;
        $notFound = 0;

        foreach ($wpPosts as $wpPost) {
            try {
                $post = Post::where('title', $wpPost->post_title)->first();
                
                if (!$post) {
                    $notFound++;
                    continue;
                }

                if ($post->hasMedia('featured')) {
                    continue;
                }

                $imageUrl = "https://oldsite.hellobd.news/wp-content/uploads/" . $wpPost->image_path;
                
                $response = Http::withoutVerifying()->timeout(30)->get($imageUrl);
                
                if (!$response->successful()) {
                    $this->warn("Failed to download: {$wpPost->image_path}");
                    $failed++;
                    continue;
                }

                $tempPath = sys_get_temp_dir() . '/' . basename($wpPost->image_path);
                file_put_contents($tempPath, $response->body());

                $post->addMedia($tempPath)
                    ->toMediaCollection('featured');

                @unlink($tempPath);

                $this->info("✓ {$post->title}");
                $success++;

            } catch (\Exception $e) {
                $this->error("Failed: {$wpPost->post_title} - {$e->getMessage()}");
                $failed++;
            }
        }

        $this->info("\nCompleted: {$success} success, {$failed} failed, {$notFound} not found");
    }
}
