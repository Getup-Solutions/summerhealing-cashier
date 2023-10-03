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
        Schema::create('facility_subscriptionplan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('facility_id')->unsigned();
            $table->unsignedBiginteger('subscriptionplan_id')->unsigned()->default(1);
            $table->foreign('facility_id')
                ->references('id')->on('facilities')
                ->onDelete('cascade');
            $table->foreign('subscriptionplan_id')
                ->references('id')->on('subscriptionplans')
                ->onDelete('cascade');
            // $table->foreignId('facility_id')->onDelete('cascade');
            // $table->foreignId('subscriptionplan_id')->onDelete('cascade');
            $table->float('facility_price')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_subscriptionplan');
    }
};
