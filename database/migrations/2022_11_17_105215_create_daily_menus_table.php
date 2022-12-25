<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_menu', static function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('day_id');
            $table->foreign('day_id')
                ->references('id')
                ->on('days')
                ->onDelete('cascade');
            $table->unsignedInteger('meal_id');
            $table->foreign('meal_id')
                ->references('id')
                ->on('meals')
                ->onDelete('cascade');
            $table->integer('status')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_menu');
    }
};
