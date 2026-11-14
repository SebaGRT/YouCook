<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Las dos tablas son iguales: unen a una persona con una receta.
        foreach (['saved_recipes', 'cooked_recipes'] as $name) {
            Schema::create($name, function (Blueprint $table) {
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->primary(['user_id', 'recipe_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cooked_recipes');
        Schema::dropIfExists('saved_recipes');
    }
};