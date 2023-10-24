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
        Schema::create('subscriptionplans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->string('thumbnail')->default('assets/static/img/subscription.png');
            $table->text('description');
            $table->boolean('published')->default(true);
            $table->float('price');
            $table->string('validity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptionplans');
    }
};
