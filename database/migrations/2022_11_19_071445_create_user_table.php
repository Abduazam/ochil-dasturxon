<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bot_users', static function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('chat_id')->nullable();
            $table->string('first_name', 50);
            $table->string('phone_number', 15)->unique()->nullable();
            $table->integer('status')->default(1);
        });

        Schema::create('user_organization', static function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('bot_users')
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
                ->on('bot_users')
                ->onDelete('cascade');
            $table->string('latitude', 30);
            $table->string('longitude', 30);
            $table->integer('status')->default(1);
        });

        Schema::create('balance_organ', static function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('organ_id');
            $table->foreign('organ_id')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');
            $table->double('balance');
            $table->integer('status')->default(1);
        });

        Schema::create('balance_user', static function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('bot_users')
                ->onDelete('cascade');
            $table->double('balance');
            $table->integer('status')->default(1);
        });

        Schema::create('action', static function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id');
            $table->integer('step_1');
            $table->integer('step_2');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_location');
        Schema::dropIfExists('user_organization');
        Schema::dropIfExists('balance_organ');
        Schema::dropIfExists('balance_user');
        Schema::dropIfExists('action');
        Schema::dropIfExists('bot_users');
    }
};
