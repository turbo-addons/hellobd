<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporters', function (Blueprint $table) {
            $table->enum('type', ['human', 'desk'])->default('human')->after('user_id');
            $table->string('desk_name')->nullable()->after('type');
            $table->decimal('rating', 3, 2)->default(0)->after('total_views');
            $table->integer('rating_count')->default(0)->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('reporters', function (Blueprint $table) {
            $table->dropColumn(['type', 'desk_name', 'rating', 'rating_count']);
        });
    }
};
