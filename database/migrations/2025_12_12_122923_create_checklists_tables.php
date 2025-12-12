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
        // Checklists table (templates)
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        // Checklist items table (template items)
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

        // Checklist instances table (assigned to people)
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

        // Checklist item instances table (responses)
        Schema::create('checklist_item_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_instance_id')->constrained('checklist_instances')->onDelete('cascade');
            $table->foreignId('checklist_item_id')->constrained('checklist_items')->onDelete('cascade');
            $table->text('value')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('checklist_instance_id');
            $table->index(['checklist_instance_id', 'checklist_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_item_instances');
        Schema::dropIfExists('checklist_instances');
        Schema::dropIfExists('checklist_items');
        Schema::dropIfExists('checklists');
    }
};
