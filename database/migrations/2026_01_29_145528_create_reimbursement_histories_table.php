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
        Schema::create('reimbursement_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reimbursement_id')->constrained()->onDelete('cascade');
            $table->timestamp('change_date');
            $table->string('action');
            $table->string('action_type');
            $table->text('notes')->nullable();
            $table->foreignId('performed_by_id')->nullable()->constrained('people')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimbursement_histories');
    }
};
