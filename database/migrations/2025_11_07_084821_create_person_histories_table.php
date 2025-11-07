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
        Schema::create('person_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained()->onDelete('cascade');
            $table->dateTime('change_date');
            $table->string('action');
            $table->string('action_type'); // profile_updated, equipment_assigned, event_joined, note_added, cv_updated, vacancy_assigned
            $table->text('notes')->nullable();
            $table->foreignId('performed_by_id')->nullable()->constrained('people')->onDelete('set null');
            $table->timestamps();

            $table->index(['person_id', 'change_date']);
            $table->index('action_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_histories');
    }
};
