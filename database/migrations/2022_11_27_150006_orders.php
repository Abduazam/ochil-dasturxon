<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('address_log', static function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('chat_id');
            $table->integer('address_id');
            $table->integer('address_type');
            $table->integer('status')->default(1);
        });

        Schema::create('orders', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onDelete('cascade');
            $table->unsignedInteger('meal_id');
            $table->foreign('meal_id')
                ->references('id')
                ->on('meals')
                ->onDelete('cascade');
            $table->integer('count');
            $table->date('date');
            $table->timestamp('created_date');
            $table->integer('status')->default(0);
        });

        Schema::create('orders_unreal', static function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('chat_id');
            $table->unsignedInteger('meal_id');
            $table->foreign('meal_id')
                ->references('id')
                ->on('meals')
                ->onDelete('cascade');
            $table->integer('count');
        });

        Schema::create('payment_card', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onDelete('cascade');
            $table->integer('status')->default(0);
        });

        Schema::create('payment_log', static function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('chat_id');
            $table->integer('type');
            $table->integer('status')->default(1);
        });

        Schema::create('payment_money', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onDelete('cascade');
            $table->integer('status')->default(0);
        });

        Schema::create('payment_proof', static function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('user')
                ->onDelete('cascade');
            $table->date('date');
            $table->string('image', 255);
            $table->double('minus_sum')->nullable();
            $table->integer('status')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('address_log');
        Schema::dropIfExists('orders_unreal');
        Schema::dropIfExists('payment_card');
        Schema::dropIfExists('payment_log');
        Schema::dropIfExists('payment_money');
        Schema::dropIfExists('payment_proof');
        Schema::dropIfExists('orders');
    }
};
