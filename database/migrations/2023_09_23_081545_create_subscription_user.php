<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->references('id')
                ->on('subscriptions')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->timestamp('created_at');
            // $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_user');
    }
};