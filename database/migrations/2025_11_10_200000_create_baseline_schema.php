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
            $table->string('email2')->nullable();
            $table->string('phone2')->nullable();
            $table->date('date_of_birth');
            $table->text('address')->nullable();
            $table->date('starting_date')->nullable();
            $table->date('last_working_date')->nullable();
            $table->string('position')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('vacancy_id')->nullable()->constrained('vacancies');
            $table->foreignId('cv_id')->nullable()->constrained('c_vs');

            // Auth fields
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken()->nullable();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();

            // Professional profiles
            $table->string('linkedin_profile')->nullable();
            $table->string('github_profile')->nullable();
            $table->string('portfolio_url')->nullable();

            // Emergency contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->timestamps();
        });

        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('date_opened');
            $table->date('date_closed')->nullable();
            $table->decimal('budget', 10, 2)->nullable();
            $table->enum('status', ['Open', 'Closed', 'On Hold'])->default('Open');
            $table->foreignId('client_id')->constrained('clients');
            $table->timestamps();
        });

        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->string('serial')->unique();
            $table->date('purchase_date');
            $table->decimal('purchase_price', 10, 2);
            $table->foreignId('current_holder_id')->nullable()->constrained('people');
            $table->timestamp('retired_at')->nullable();
            $table->text('retirement_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('equipment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('holder_id')->nullable()->constrained('people')->onDelete('set null');
            $table->dateTime('change_date');
            $table->string('action');
            $table->text('notes')->nullable();
            $table->enum('action_type', ['purchased', 'assigned', 'returned', 'repaired', 'retired', 'note'])->default('assigned');
            $table->foreignId('performed_by_id')->nullable()->constrained('people')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->enum('type', ['Meeting', 'Interview', 'Training', 'Other'])->default('Meeting');
            $table->timestamps();
        });

        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('note_type');
            $table->unsignedBigInteger('entity_id');
            $table->text('note_text');
            $table->timestamps();

            $table->index(['note_type', 'entity_id']);
        });

        Schema::create('c_vs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable()->constrained('people')->onDelete('cascade');
            $table->string('file_path_or_url');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('person_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained()->onDelete('cascade');
            $table->dateTime('change_date');
            $table->string('action');
            $table->string('action_type');
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
        Schema::dropIfExists('c_vs');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('event_participants');
        Schema::dropIfExists('events');
        Schema::dropIfExists('equipment_history');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('vacancies');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('people');
    }
};
