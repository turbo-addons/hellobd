<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('title');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->string('seo_keywords')->nullable()->after('seo_description');
            $table->string('og_image')->nullable()->after('seo_keywords');
            $table->boolean('index')->default(true)->after('og_image');
            $table->boolean('follow')->default(true)->after('index');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'seo_keywords', 'og_image', 'index', 'follow']);
        });
    }
};
