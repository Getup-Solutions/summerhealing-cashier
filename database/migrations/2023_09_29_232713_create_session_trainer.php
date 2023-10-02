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
        Schema::create('session_trainer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('session_id')->unsigned();
            $table->unsignedBiginteger('trainer_id')->unsigned()->default(1);
            $table->foreign('session_id')
                ->references('id')->on('sessions')
                ->onDelete('cascade');
            $table->foreign('trainer_id')
                ->references('id')->on('trainers')
                ->onDelete('cascade');
            // $table->float('price')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_trainer');
    }
};
