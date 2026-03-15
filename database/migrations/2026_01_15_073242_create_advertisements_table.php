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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content')->nullable();
            $table->enum('ad_type', ['banner', 'sidebar', 'footer', 'content', 'homepage', 'sponsored_post']);
            $table->enum('placement', ['header', 'sidebar', 'footer', 'content', 'homepage']);
            $table->enum('billing_model', ['cpc', 'cpm', 'fixed']);
            $table->decimal('rate', 10, 2);
            $table->decimal('total_budget', 10, 2)->nullable();
            $table->decimal('spent', 10, 2)->default(0);
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->string('image')->nullable();
            $table->string('link_url')->nullable();
            $table->foreignId('post_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'active', 'paused', 'expired', 'rejected'])->default('pending');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            
            $table->index(['status', 'start_date', 'end_date']);
            $table->index(['vendor_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
