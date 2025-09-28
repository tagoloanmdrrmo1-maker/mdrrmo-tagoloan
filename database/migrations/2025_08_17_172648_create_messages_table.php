<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('mes_id');

            // Use direct, type-matching FKs
            $table->string('intensity_level'); // Related to rainfalls.intensity_level (string)
            $table->unsignedBigInteger('contact_id');    // FK to contacts.contact_id

            $table->string('brgy_location'); // Related to contacts.brgy_location
            $table->bigInteger('contact_num');
            $table->text('text_desc');
            $table->string('status')->default('pending');
            $table->timestamp('date_created')->useCurrent();
            $table->unsignedBigInteger('user_id')->nullable(); // User who sent the message
            $table->timestamps();

            $table->foreign('contact_id')->references('contact_id')->on('contacts')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
        });
        // No post-create ALTERs needed when types match
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert column changes back to BIGINT UNSIGNED
        try {
            // Drop foreign key constraints
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'messages'
                AND COLUMN_NAME IN ('contact_id', 'user_id')
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            foreach ($foreignKeys as $fk) {
                try {
                    DB::statement("ALTER TABLE messages DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                } catch (\Throwable $e) {
                    // Ignore if FK doesn't exist
                }
            }

            // Revert columns back to BIGINT UNSIGNED
            DB::statement('ALTER TABLE messages MODIFY contact_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE messages DROP COLUMN user_id');

            // Add back original foreign key constraints
            DB::statement('ALTER TABLE messages ADD CONSTRAINT fk_messages_contact_id_orig FOREIGN KEY (contact_id) REFERENCES contacts(contact_id) ON DELETE CASCADE');

        } catch (\Throwable $e) {
            try {
                DB::statement('ALTER TABLE messages CHANGE contact_id contact_id BIGINT UNSIGNED NOT NULL');
                DB::statement('ALTER TABLE messages DROP COLUMN user_id');
            } catch (\Throwable $e2) {
                // If fails, drop the table
                Schema::dropIfExists('messages');
            }
        }
    }
};
