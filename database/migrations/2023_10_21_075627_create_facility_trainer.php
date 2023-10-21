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
        Schema::create('facility_trainer', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('facility_id')->onDelete('cascade');
            // $table->foreignId('trainer_id')->onDelete('cascade');
            $table->unsignedBiginteger('facility_id')->unsigned();
            $table->unsignedBiginteger('trainer_id')->unsigned()->default(1);
            $table->foreign('facility_id')
                ->references('id')->on('facilities')
                ->onDelete('cascade');
            $table->foreign('trainer_id')
                ->references('id')->on('trainers')
                ->onDelete('cascade');
            $table->float('price')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_trainer');
    }
};
