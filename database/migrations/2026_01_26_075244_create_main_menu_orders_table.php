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
        Schema::create('main_menu_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('term_id')->unique(); // parent category id
            $table->integer('menu_order')->default(0);
            $table->timestamps();

            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_menu_orders');
    }
};
