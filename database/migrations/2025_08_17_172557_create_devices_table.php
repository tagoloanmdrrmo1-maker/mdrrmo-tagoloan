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
        Schema::create('devices', function (Blueprint $table) {
            
            $table->id('dev_id');
            $table->string('serial_number')->unique()->nullable(); // ✅ each device must have a unique serial
            $table->string('dev_location')->nullable();
            $table->date('date_installed')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('status')->default('pending'); // pending until first report
            $table->unsignedBigInteger('added_by')->nullable(); // Added by user ID
            $table->foreign('added_by')->references('user_id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices'); // fixed table name
    }
};