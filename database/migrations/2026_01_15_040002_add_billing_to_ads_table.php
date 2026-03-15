<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->enum('billing_model', ['cpc', 'cpm', 'fixed'])->default('cpm')->after('type');
            $table->decimal('rate', 10, 2)->default(0)->after('billing_model');
            $table->decimal('total_budget', 10, 2)->nullable()->after('rate');
            $table->decimal('total_spent', 10, 2)->default(0)->after('total_budget');
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['billing_model', 'rate', 'total_budget', 'total_spent']);
        });
    }
};
