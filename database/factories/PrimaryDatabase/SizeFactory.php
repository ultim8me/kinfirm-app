<?php

namespace Database\Factories\PrimaryDatabase;

use App\Models\PrimaryDatabase\Size;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrimaryDatabase\User>
 */
class SizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            Size::NAME => fake()->unique()->word(),
            Size::DESCRIPTION => fake()->text(),
        ];
    }
}
