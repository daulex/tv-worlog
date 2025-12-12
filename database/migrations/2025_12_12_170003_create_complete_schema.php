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
        // Users table (Laravel default with 2FA)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->timestamps();
        });

        // Password reset tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Cache tables
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->longText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // Jobs tables
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Application tables
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

            $table->index(['first_name', 'last_name'], 'people_full_name_index');
            $table->index('status');
            $table->index(['status', 'client_id'], 'people_status_client_index');
            $table->index(['status', 'vacancy_id'], 'people_status_vacancy_index');
            $table->index('email');
            $table->index('created_at');
            $table->index('updated_at');
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
            $table->foreignId('equipment_id')->nullable()->constrained('equipment')->onDelete('cascade');
            $table->foreignId('holder_id')->nullable()->constrained('people')->onDelete('set null');
            $table->timestamp('change_date');
            $table->string('action');
            $table->text('notes')->nullable();
            $table->string('action_type')->default('assigned');
            $table->foreignId('performed_by_id')->nullable()->constrained('people')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
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

        Schema::create('person_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->timestamp('change_date');
            $table->string('action');
            $table->string('action_type');
            $table->text('notes')->nullable();
            $table->foreignId('performed_by_id')->nullable()->constrained('people')->onDelete('set null');
            $table->timestamps();

            $table->index(['person_id', 'change_date']);
            $table->index('action_type');
        });

        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable()->constrained('people')->onDelete('cascade');
            $table->string('filename');
            $table->string('file_path');
            $table->string('file_type');
            $table->unsignedBigInteger('file_size');
            $table->enum('file_category', ['cv', 'contract', 'certificate', 'other'])->default('other');
            $table->text('description')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();

            $table->index(['person_id', 'file_category']);
            $table->index('file_category');
            $table->index('uploaded_at');
        });

        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_id')->constrained('checklists')->onDelete('cascade');
            $table->enum('type', ['bool', 'text', 'number', 'textarea']);
            $table->string('label');
            $table->boolean('required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['checklist_id', 'order']);
        });

        Schema::create('checklist_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_id')->constrained('checklists')->onDelete('cascade');
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['person_id', 'checklist_id']);
            $table->index('person_id');
            $table->index('completed_at');
        });

        Schema::create('checklist_item_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_instance_id')->constrained('checklist_instances')->onDelete('cascade');
            $table->foreignId('checklist_item_id')->constrained('checklist_items')->onDelete('cascade');
            $table->text('value')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('checklist_instance_id');
            $table->index(['checklist_instance_id', 'checklist_item_id'], 'cii_instance_item_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to handle foreign keys
        Schema::dropIfExists('checklist_item_instances');
        Schema::dropIfExists('checklist_instances');
        Schema::dropIfExists('checklist_items');
        Schema::dropIfExists('checklists');
        Schema::dropIfExists('files');
        Schema::dropIfExists('person_histories');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('event_participants');
        Schema::dropIfExists('events');
        Schema::dropIfExists('equipment_history');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('people');
        Schema::dropIfExists('vacancies');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
