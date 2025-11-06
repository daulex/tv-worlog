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
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('datetime');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('location')->nullable();
            $table->enum('type', ['Meeting', 'Interview', 'Training', 'Other'])->default('Meeting');
        });

        // Update existing records to have default values
        \DB::table('events')->update([
            'start_date' => now(),
            'end_date' => now()->addHour(),
        ]);

        // Now make the columns not nullable
        Schema::table('events', function (Blueprint $table) {
            $table->dateTime('start_date')->nullable(false)->change();
            $table->dateTime('end_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'location', 'type']);
            $table->dateTime('datetime');
        });
    }
};
