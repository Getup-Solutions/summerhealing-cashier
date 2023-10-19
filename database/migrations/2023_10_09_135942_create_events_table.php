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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('size');
            $table->foreignId('schedule_id')->cascade();
            // $table->string('location');
            // $table->foreignId('trainer_id')->cascade();
            $table->integer('eventable_id');
            $table->string('eventable_type');
            $table->string('event_title');
            // $table->string('trainers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
