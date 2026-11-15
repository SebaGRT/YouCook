<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = require database_path('data/recetas.php');

        foreach ($recipes as $data) {
            // updateOrCreate: se puede ejecutar varias veces sin duplicar recetas.
            $recipe = Recipe::updateOrCreate(
                ['slug' => $data['slug']],
                Arr::except($data, ['ingredients']),
            );

            $recipe->ingredients()->delete();

            foreach ($data['ingredients'] as $index => [$name, $quantity]) {
                $recipe->ingredients()->create([
                    'name' => $name,
                    'quantity' => $quantity,
                    'position' => $index + 1,
                ]);
            }
        }
    }
}
