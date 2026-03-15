<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Critical indexes for high-traffic queries
            $table->index(['status', 'published_at', 'created_at'], 'posts_status_dates_idx');
            $table->index(['post_type', 'status'], 'posts_type_status_idx');
            $table->index(['reporter_id', 'status'], 'posts_reporter_status_idx');
            $table->index(['is_sponsored', 'status'], 'posts_sponsored_status_idx');
            $table->index('views', 'posts_views_idx');
            $table->index('google_news_score', 'posts_google_score_idx');
        });

        Schema::table('reporters', function (Blueprint $table) {
            $table->index(['verification_status', 'is_active'], 'reporters_status_idx');
            $table->index('total_articles', 'reporters_articles_idx');
            $table->index('rating', 'reporters_rating_idx');
        });

        Schema::table('term_relationships', function (Blueprint $table) {
            $table->index(['post_id', 'term_id'], 'term_rel_post_term_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_status_dates_idx');
            $table->dropIndex('posts_type_status_idx');
            $table->dropIndex('posts_reporter_status_idx');
            $table->dropIndex('posts_sponsored_status_idx');
            $table->dropIndex('posts_views_idx');
            $table->dropIndex('posts_google_score_idx');
        });

        Schema::table('reporters', function (Blueprint $table) {
            $table->dropIndex('reporters_status_idx');
            $table->dropIndex('reporters_articles_idx');
            $table->dropIndex('reporters_rating_idx');
        });

        Schema::table('term_relationships', function (Blueprint $table) {
            $table->dropIndex('term_rel_post_term_idx');
        });
    }
};
