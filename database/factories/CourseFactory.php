<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = \App\Models\Course::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'intro_url' => $this->faker->url,
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'price_sale' => $this->faker->randomFloat(2, 5, 400),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->boolean(),
            'level' => $this->faker->randomElement(['basic', 'intermediate', 'advanced']),
            'requirements' => json_encode($this->faker->words(3)),
            'benefits' => json_encode($this->faker->words(4)),
            'qa' => json_encode([
                [
                    'question' => $this->faker->sentence(6),
                    'answer' => $this->faker->paragraph(),
                ],
                [
                    'question' => $this->faker->sentence(6),
                    'answer' => $this->faker->paragraph(),
                ],
            ]),
        ];
    }
}
