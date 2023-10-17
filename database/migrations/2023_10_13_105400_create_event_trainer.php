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
        Schema::create('event_trainer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('event_id')->unsigned();
            $table->unsignedBiginteger('trainer_id')->unsigned()->default(1);
            $table->foreign('event_id')
                ->references('id')->on('events')
                ->onDelete('cascade');
            $table->foreign('trainer_id')
                ->references('id')->on('trainers')
                ->onDelete('cascade');
            // $table->foreignId('course_id')->onDelete('cascade');
            // $table->foreignId('subscriptionplan_id')->onDelete('cascade');
            // $table->foreignId('schedule_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_trainer');
    }
};
