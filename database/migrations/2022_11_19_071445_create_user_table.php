<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', static function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('chat_id')->nullable();
            $table->string('first_name', 50);
            $table->string('username', 50)->nullable();
            $table->string('phone_number', 15)->unique()->nullable();
            $table->double('balance')->default(0);
            $table->integer('status')->default(1);
        });

        Schema::create('user_organization', static function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onDelete('cascade');
            $table->unsignedInteger('organ_id');
            $table->foreign('organ_id')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');
            $table->integer('status')->default(1);
        });

        Schema::create('user_location', static function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onDelete('cascade');
            $table->string('latitude', 30);
            $table->string('longitude', 30);
            $table->integer('status')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_location');
        Schema::dropIfExists('user_organization');
        Schema::dropIfExists('user');
    }
};
