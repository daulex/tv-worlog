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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['Candidate', 'Employee', 'Retired']);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('pers_code')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->date('date_of_birth');
            $table->text('address')->nullable();
            $table->date('starting_date')->nullable();
            $table->string('position')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('vacancy_id')->nullable()->constrained('vacancies');
            $table->foreignId('cv_id')->nullable()->constrained('c_vs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
