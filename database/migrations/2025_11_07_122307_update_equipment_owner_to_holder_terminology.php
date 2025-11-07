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
        Schema::table('equipment', function (Blueprint $table) {
            $table->renameColumn('current_owner_id', 'current_holder_id');
        });

        Schema::table('equipment_history', function (Blueprint $table) {
            $table->renameColumn('owner_id', 'holder_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->renameColumn('current_holder_id', 'current_owner_id');
        });

        Schema::table('equipment_history', function (Blueprint $table) {
            $table->renameColumn('holder_id', 'owner_id');
        });
    }
};
