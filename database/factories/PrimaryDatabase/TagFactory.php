<?php

namespace Database\Factories\PrimaryDatabase;

use App\Models\PrimaryDatabase\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrimaryDatabase\User>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Tag::TITLE => fake()->unique()->word(),
        ];
    }
}
