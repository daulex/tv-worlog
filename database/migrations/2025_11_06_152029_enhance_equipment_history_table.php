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
        Schema::table('equipment_history', function (Blueprint $table) {
            $table->enum('action_type', ['purchased', 'assigned', 'returned', 'repaired', 'retired'])->default('assigned')->after('notes');
            $table->foreignId('performed_by_id')->nullable()->constrained('people')->onDelete('set null')->after('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_history', function (Blueprint $table) {
            $table->dropColumn(['action_type', 'performed_by_id']);
        });
    }
};
