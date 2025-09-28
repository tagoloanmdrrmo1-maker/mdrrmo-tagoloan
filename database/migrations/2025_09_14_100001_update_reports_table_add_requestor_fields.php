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
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('requestor_id')->nullable()->after('user_id');
            $table->string('requestor_type')->default('no_requestor')->after('requestor_id'); // no_requestor, old_requestor, new_requestor
            $table->string('organization')->nullable()->after('requestor_type');
            $table->text('purpose')->nullable()->after('end_date');
            $table->enum('status', ['pending', 'approved', 'completed', 'rejected'])->default('pending')->after('purpose');
            
            $table->foreign('requestor_id')->references('requestor_id')->on('requestors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['requestor_id']);
            $table->dropColumn(['requestor_id', 'requestor_type', 'organization', 'purpose', 'status']);
        });
    }
};