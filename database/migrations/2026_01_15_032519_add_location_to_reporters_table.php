<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reporters', function (Blueprint $table) {
            $table->string('location')->nullable()->after('bio');
            $table->timestamp('location_updated_at')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('reporters', function (Blueprint $table) {
            $table->dropColumn(['location', 'location_updated_at']);
        });
    }
};
