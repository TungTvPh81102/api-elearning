<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecture>
 */
class LectureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::pluck('id')->random(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
