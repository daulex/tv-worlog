<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['cv', 'contract', 'certificate', 'other'];
        $fileTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png',
        ];

        return [
            'person_id' => Person::factory(),
            'filename' => fake()->words(3, true).'.'.fake()->fileExtension(),
            'file_path' => 'files/'.fake()->uuid().'.'.fake()->fileExtension(),
            'file_type' => fake()->randomElement($fileTypes),
            'file_size' => fake()->numberBetween(1024, 10485760), // 1KB to 10MB
            'file_category' => fake()->randomElement($categories),
            'description' => fake()->sentence(),
            'uploaded_at' => fake()->dateTime(),
        ];
    }

    /**
     * Create a CV file.
     */
    public function cv(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_category' => 'cv',
            'file_type' => 'application/pdf',
            'filename' => fake()->name().'_CV.pdf',
            'description' => 'CV document',
        ]);
    }

    /**
     * Create a contract file.
     */
    public function contract(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_category' => 'contract',
            'file_type' => 'application/pdf',
            'filename' => 'Contract_'.fake()->date('Y-m-d').'.pdf',
            'description' => 'Employment contract',
        ]);
    }

    /**
     * Create a certificate file.
     */
    public function certificate(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_category' => 'certificate',
            'filename' => 'Certificate_'.fake()->words(2, true).'.pdf',
            'description' => 'Professional certificate',
        ]);
    }

    /**
     * Create a PDF file.
     */
    public function pdf(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_type' => 'application/pdf',
            'filename' => fake()->words(3, true).'.pdf',
        ]);
    }

    /**
     * Create an image file.
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_type' => fake()->randomElement(['image/jpeg', 'image/png']),
            'filename' => fake()->words(2, true).'.'.fake()->randomElement(['jpg', 'png']),
        ]);
    }

    /**
     * Create an other file.
     */
    public function other(): static
    {
        return $this->state(fn (array $attributes) => [
            'file_category' => 'other',
        ]);
    }
}
