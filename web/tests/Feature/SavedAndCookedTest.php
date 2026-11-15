<?php

namespace Tests\Feature;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedAndCookedTest extends TestCase
{
    use RefreshDatabase;

    public function test_guardar_dos_veces_guarda_y_luego_quita(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();

        $this->actingAs($user)->post("/recetas/{$recipe->slug}/guardar")->assertRedirect();
        $this->assertTrue($user->hasSaved($recipe));

        $this->actingAs($user)->post("/recetas/{$recipe->slug}/guardar");
        $this->assertFalse($user->hasSaved($recipe));
    }

    public function test_guardados_muestra_solo_mis_recetas(): void
    {
        $user = User::factory()->create();
        $mia = Recipe::factory()->create(['title' => 'Mi receta guardada']);
        $ajena = Recipe::factory()->create(['title' => 'Receta de otra persona']);
        $user->savedRecipes()->attach($mia);
        User::factory()->create()->savedRecipes()->attach($ajena);

        $this->actingAs($user)->get('/guardados')
            ->assertOk()
            ->assertSee('Mi receta guardada')
            ->assertDontSee('Receta de otra persona');
    }

    public function test_guardados_vacio_invita_a_explorar(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/guardados')
            ->assertOk()
            ->assertSee('Aún no guardas recetas');
    }

    public function test_marcar_como_cocinada_desbloquea_el_sticker(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create(['sticker' => 'choclo', 'sticker_name' => 'Choclito']);

        $response = $this->actingAs($user)->post("/recetas/{$recipe->slug}/cocinada");

        $response->assertRedirect()->assertSessionHas('toast', [
            'title' => '¡Receta cocinada!',
            'body' => 'Desbloqueaste el sticker «Choclito» · 1 de 1',
            'sticker' => 'choclo',
        ]);
        $this->assertTrue($user->hasCooked($recipe));
    }

    public function test_desmarcar_una_receta_cocinada_no_muestra_aviso(): void
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $user->cookedRecipes()->attach($recipe);

        $response = $this->actingAs($user)->post("/recetas/{$recipe->slug}/cocinada");

        $response->assertSessionMissing('toast');
        $this->assertFalse($user->hasCooked($recipe));
    }
}
