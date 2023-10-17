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
        Schema::create('day_event', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('day_id')->unsigned();
            $table->unsignedBiginteger('event_id')->unsigned()->default(1);
            $table->foreign('day_id')
                ->references('id')->on('days')
                ->onDelete('cascade');
            $table->foreign('event_id')
                ->references('id')->on('events')
                ->onDelete('cascade');
            // $table->foreignId('course_id')->onDelete('cascade');
            // $table->foreignId('subscriptionplan_id')->onDelete('cascade');
            $table->foreignId('schedule_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_event');
    }
};
