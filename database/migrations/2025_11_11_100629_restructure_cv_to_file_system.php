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
        // Create new files table
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable()->constrained('people')->onDelete('cascade');
            $table->string('filename'); // Original filename
            $table->string('file_path'); // Storage path
            $table->string('file_type'); // MIME type
            $table->bigInteger('file_size'); // File size in bytes
            $table->enum('file_category', ['cv', 'contract', 'certificate', 'other'])->default('other');
            $table->text('description')->nullable(); // User-defined label/description
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['person_id', 'file_category']);
            $table->index('file_category');
            $table->index('uploaded_at');
        });

        // Migrate existing CV data to files table
        if (Schema::hasTable('c_vs')) {
            \DB::table('c_vs')->orderBy('id')->chunk(100, function ($cvs) {
                foreach ($cvs as $cv) {
                    \DB::table('files')->insert([
                        'person_id' => $cv->person_id,
                        'filename' => basename($cv->file_path_or_url),
                        'file_path' => $cv->file_path_or_url,
                        'file_type' => 'application/pdf', // Default to PDF for existing CVs
                        'file_size' => 0, // Unknown size for existing entries
                        'file_category' => 'cv',
                        'description' => 'CV - migrated from previous system',
                        'uploaded_at' => $cv->uploaded_at ?? now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
        }

        // Remove cv_id from people table
        if (Schema::hasColumn('people', 'cv_id')) {
            Schema::table('people', function (Blueprint $table) {
                $table->dropForeign(['cv_id']);
                $table->dropColumn('cv_id');
            });
        }

        // Drop old c_vs table
        Schema::dropIfExists('c_vs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate c_vs table
        Schema::create('c_vs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable()->constrained('people')->onDelete('cascade');
            $table->string('file_path_or_url');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });

        // Migrate CV files back to c_vs table
        \DB::table('files')->where('file_category', 'cv')->orderBy('id')->chunk(100, function ($files) {
            foreach ($files as $file) {
                \DB::table('c_vs')->insert([
                    'person_id' => $file->person_id,
                    'file_path_or_url' => $file->file_path,
                    'uploaded_at' => $file->uploaded_at,
                    'created_at' => $file->created_at,
                    'updated_at' => $file->updated_at,
                ]);
            }
        });

        // Add cv_id back to people table (first CV per person)
        Schema::table('people', function (Blueprint $table) {
            $table->foreignId('cv_id')->nullable()->constrained('c_vs');
        });

        // Update people.cv_id with first CV for each person
        \DB::statement('
            UPDATE people 
            SET cv_id = (
                SELECT MIN(id) FROM c_vs WHERE c_vs.person_id = people.id
            )
        ');

        // Drop files table
        Schema::dropIfExists('files');
    }
};
