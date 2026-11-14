<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory;

    public const CATEGORIES = ['Chilenas', 'Pastas', 'Rápidas', 'Dulces', 'Vegetarianas'];

    public const DIFFICULTIES = ['Fácil', 'Media'];

    protected $fillable = [
        'slug', 'title', 'category', 'minutes', 'difficulty', 'servings', 'cost',
        'image', 'description', 'allergens', 'steps', 'sticker', 'sticker_name',
    ];

    protected function casts(): array
    {
        return [
            'allergens' => 'array',
            'steps' => 'array',
        ];
    }

    // Las URL usan el slug (/recetas/charquican), no el id.
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class)->orderBy('position');
    }

    // 3500 → "$3.500"
    protected function costLabel(): Attribute
    {
        return Attribute::get(fn () => '$'.number_format($this->cost, 0, ',', '.'));
    }

    // Filtros del catálogo: Recipe::filter([...])->paginate(8)
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query
            ->when($filters['q'] ?? null, function (Builder $query, string $q) {
                $query->where(function (Builder $query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhereHas('ingredients', fn (Builder $query) => $query->where('name', 'like', "%{$q}%"));
                });
            })
            ->when($filters['categoria'] ?? null, fn (Builder $query, string $category) => $query->where('category', $category))
            ->when($filters['tiempo'] ?? null, fn (Builder $query, int|string $minutes) => $query->where('minutes', '<=', (int) $minutes))
            ->when($filters['dificultad'] ?? null, fn (Builder $query, string $difficulty) => $query->where('difficulty', $difficulty));
    }
}