<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    public function definition(): array
    {
        $title = Str::ucfirst(fake()->unique()->words(3, true));

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'category' => fake()->randomElement(Recipe::CATEGORIES),
            'minutes' => fake()->numberBetween(10, 60),
            'difficulty' => fake()->randomElement(Recipe::DIFFICULTIES),
            'servings' => fake()->numberBetween(1, 6),
            'cost' => fake()->numberBetween(8, 50) * 100,
            'image' => null,
            'description' => fake()->sentence(),
            'allergens' => ['Sin alérgenos comunes'],
            'steps' => [fake()->sentence(), fake()->sentence()],
            'sticker' => 'estrella',
            'sticker_name' => 'Estrella',
        ];
    }
}