<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('days', static function (Blueprint $table) {
            $table->increments('id');
            $table->date('day');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->integer('status')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('days');
    }
};
