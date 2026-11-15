<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category');
            $table->unsignedSmallInteger('minutes');
            $table->string('difficulty');
            $table->unsignedTinyInteger('servings');
            $table->unsignedInteger('cost');
            $table->string('image')->nullable();
            $table->text('description');
            $table->json('allergens');
            $table->json('steps');
            $table->string('sticker');
            $table->string('sticker_name');
            $table->timestamps();
        });

        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('quantity');
            $table->unsignedTinyInteger('position');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
        Schema::dropIfExists('recipes');
    }
};
