<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RecipeSeeder::class);

        // Cuenta de demostración, igual a la del mockup. Contraseña: password
        $camila = User::factory()->create([
            'name' => 'Camila Rojas',
            'email' => 'camila.rojas@uni.cl',
        ]);

        $ids = fn (array $slugs) => Recipe::whereIn('slug', $slugs)->pluck('id');

        $camila->savedRecipes()->attach($ids(['charquican', 'panqueques', 'crema-zapallo']));

        $camila->cookedRecipes()->attach($ids([
            'tallarines-salsa', 'arroz-huevo', 'panqueques', 'ensalada-chilena',
            'huevos-revueltos', 'sopaipillas', 'porotos',
        ]));
    }
}