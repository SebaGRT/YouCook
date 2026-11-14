<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Los campos que se pueden llenar de una vez, igual que en Recipe.php.
    protected $fillable = ['name', 'email', 'password'];

    // Los que no salen cuando el objeto se convierte en JSON.
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- YouCook: recetas guardadas y cocinadas ---

    public function savedRecipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'saved_recipes')->withTimestamps();
    }

    public function cookedRecipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'cooked_recipes')->withTimestamps();
    }

    public function hasSaved(Recipe $recipe): bool
    {
        return $this->savedRecipes()->where('recipe_id', $recipe->id)->exists();
    }

    public function hasCooked(Recipe $recipe): bool
    {
        return $this->cookedRecipes()->where('recipe_id', $recipe->id)->exists();
    }

    // "Camila Rojas" → "Camila"
    protected function firstName(): Attribute
    {
        return Attribute::get(fn () => Str::before($this->name, ' '));
    }

    // "Camila Rojas" → "Camila R."
    protected function shortName(): Attribute
    {
        return Attribute::get(function () {
            $lastName = Str::after($this->name, ' ');

            return $lastName === $this->name
                ? $this->name
                : $this->first_name.' '.Str::upper(Str::substr($lastName, 0, 1)).'.';
        });
    }

    // "Camila Rojas" → "C" (la letra del avatar)
    protected function initial(): Attribute
    {
        return Attribute::get(fn () => Str::upper(Str::substr($this->name, 0, 1)));
    }
}