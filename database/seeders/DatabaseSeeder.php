<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PrimaryDatabase\Size;
use App\Models\PrimaryDatabase\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected static array $sizes = [
        ['name' => 'XS', 'description' => 'Extra small'],
        ['name' => 'S', 'description' => 'Small'],
        ['name' => 'M', 'description' => 'Medium'],
        ['name' => 'L', 'description' => 'Large'],
        ['name' => 'XL', 'description' => 'Extra large'],
        ['name' => '2XL', 'description' => 'Extra extra large'],
        ['name' => '3XL', 'description' => 'Extra extra extra large'],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(2)->create();

        Size::factory()->createMany(self::$sizes);
    }
}
