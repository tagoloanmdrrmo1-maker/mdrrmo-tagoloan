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
        Schema::create('rainfalls', function (Blueprint $table) {
            $table->id('rainfall_id');
            $table->string('dev_location');
            $table->unsignedBigInteger('device_id')->nullable()->index();
            $table->integer('rain_tips')->default(0);
            $table->float('cumulative_rainfall')->default(0);
            $table->string('intensity_level')->nullable()->index();
            $table->timestamps();

            // Foreign key to devices
            $table->foreign('device_id')->references('dev_id')->on('devices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rainfalls');
    }
};
