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
        // SQLite doesn't support modifying ENUM columns directly
        // We need to recreate the table with the new enum values
        Schema::dropIfExists('equipment_history');

        Schema::create('equipment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->nullable()->constrained('people')->onDelete('set null');
            $table->date('change_date');
            $table->string('action');
            $table->text('notes')->nullable();
            $table->enum('action_type', ['purchased', 'assigned', 'returned', 'repaired', 'retired', 'note'])->default('assigned');
            $table->foreignId('performed_by_id')->nullable()->constrained('people')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_history');

        // Recreate with original enum values
        Schema::create('equipment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->nullable()->constrained('people')->onDelete('set null');
            $table->date('change_date');
            $table->string('action');
            $table->text('notes')->nullable();
            $table->enum('action_type', ['purchased', 'assigned', 'returned', 'repaired', 'retired'])->default('assigned');
            $table->foreignId('performed_by_id')->nullable()->constrained('people')->onDelete('set null');
            $table->timestamps();
        });
    }
};
