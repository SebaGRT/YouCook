# Miguel · Día 2 (sábado 14): guardar, cocinar, perfil y pruebas

**Meta del día:** que guardar una receta, marcarla como cocinada y editar el perfil funcionen de verdad, con pruebas automáticas y mensajes en español.

← [Día 1](dia1.md) · [Índice de guías](../README.md) · Siguiente: [Día 3](dia3.md)

## Mapa del día

| Paso | Qué haces | Archivo | Entrega |
|---|---|---|---|
| 1 | Fábricas de datos de prueba | `database/factories/RecipeFactory.php`, `IngredientFactory.php` | **📣 A las 10:00 (Sebastián las espera)** |
| 2 | Pruebas del catálogo | `tests/Feature/RecipeCatalogTest.php` | PR 1 |
| 3 | Guardar y Guardados | `SavedRecipeController.php` | |
| 4 | Marcar como cocinada | `CookedRecipeController.php` | |
| 5 | Perfil | `ProfileController.php` | |
| 6 | Rutas definitivas | `routes/web.php` | |
| 7 | Vistas provisionales | `guardados/index.blade.php`, `perfil/show.blade.php` | |
| 8 | Pruebas de guardar y cocinar | `tests/Feature/SavedAndCookedTest.php` | |
| 9 | Mensajes en español | `lang/es/validation.php` | **PR 2 a las 16:00 (Emma necesita el perfil para mañana)** |

**Tiempo:** unas 3 h 30 (~520 líneas).

Rama: `git switch main && git pull && composer install && npm install && php artisan migrate:fresh --seed && git switch -c miguel/dia2-pruebas`.

---

## Paso 1 · Fábricas de datos de prueba

Una *factory* crea modelos de mentira para las pruebas: `Recipe::factory()->create()`. **Crea:**

@@archivo database/factories/RecipeFactory.php@@

@@archivo database/factories/IngredientFactory.php@@

**Entiende**
- `definition()` devuelve valores por defecto. En cada prueba se cambian solo los que importan: `Recipe::factory()->create(['title' => 'Charquicán'])`.
- `fake()` genera datos al azar (palabras, números, frases).
- `'recipe_id' => Recipe::factory()`: si no se indica la receta, crea una nueva para ese ingrediente.
- Los modelos ya tienen `use HasFactory;`: por eso existe `Recipe::factory()`.

Súbelas de inmediato (`git add . && git commit -m "Factories" && git push -u origin miguel/dia2-pruebas`), abre el PR y avisa: **📣 «Sebastián: factories en PR»**. Sigue en la misma rama.

## Paso 2 · Pruebas del catálogo

**Crea:**

@@archivo tests/Feature/RecipeCatalogTest.php@@

```bash
php artisan test --filter=RecipeCatalogTest     # 7 pruebas en verde
```

**Entiende**
- `use RefreshDatabase;`: cada prueba parte con una base de datos vacía (en memoria; no toca tu `database.sqlite`).
- Cada prueba tiene tres momentos: preparar datos, hacer la petición y comprobar. `actingAs($user)` es «como si esta persona hubiera iniciado sesión».
- `assertSee('texto')` busca el texto en la página. Las pruebas pasan con tus vistas provisionales **y** tienen que seguir pasando con las de Emma: son el contrato entre los dos.
- `assertViewHas('recipes', fn ...)` revisa los datos que el controlador entregó a la vista.

## Paso 3 · Guardar y Guardados

```bash
php artisan make:controller SavedRecipeController
```

**Reemplaza todo:**

@@archivo app/Http/Controllers/SavedRecipeController.php@@

**Entiende**
- `toggle($recipe)` hace todo el trabajo: si la pareja persona-receta no existe, la crea; si existe, la borra. Es el `toggleSave` de `store.tsx`.
- `back()` devuelve a la página desde donde se envió el formulario (catálogo, detalle o guardados). `withFragment('receta-charquican')` agrega `#receta-charquican` a la URL para que el navegador vuelva a la tarjeta y no al inicio de la página.
- En `index`, `orderBy('saved_recipes.created_at')` ordena por cuándo se guardó cada una.

## Paso 4 · Marcar como cocinada

```bash
php artisan make:controller CookedRecipeController
```

**Reemplaza todo:**

@@archivo app/Http/Controllers/CookedRecipeController.php@@

**Entiende**
- `toggle()` devuelve qué pasó: `['attached' => [...], 'detached' => [...]]`. Si `attached` está vacío, la receta se **desmarcó** y no hay aviso.
- `->with('toast', [...])` guarda el aviso en la sesión **solo para la siguiente página** (un dato *flash*). El layout de Emma lo lee con `session('toast')` y dibuja el aviso con el sticker.
- `sprintf('… «%s» · %d de %d', ...)` arma el texto: `%s` es un texto y `%d`, un número.

## Paso 5 · Perfil

```bash
php artisan make:controller ProfileController
```

**Reemplaza todo:**

@@archivo app/Http/Controllers/ProfileController.php@@

**Entiende**
- `show` entrega todo lo que la pantalla necesita: la lista de las 20 recetas (para los stickers y el checklist), los ids de las cocinadas y los contadores.
- `match (true) { ... }` elige el nivel según cuántas recetas van: es una cadena de `if` más ordenada. Los cortes (5, 10 y 20) son los de la barra de progreso del mockup.
- `update` valida con `$request->validate([...])`, como el `AuthController` de Sebastián: nombre obligatorio y correo válido. Si algo falla, Laravel vuelve al formulario con los errores y el resto del método no se ejecuta.
- `Rule::unique('users')->ignore($user->id)`: el correo no puede ser el de **otra** cuenta. Sin `ignore`, guardar el perfil sin cambiar el correo fallaría, porque el correo «ya existe»: es el mío.
- `$user->update($datos)` guarda solo los campos validados (y que están en `$fillable`).

## Paso 6 · Rutas definitivas

**Reemplaza todo** (versión definitiva):

@@archivo routes/web.php@@

## Paso 7 · Vistas provisionales

Igual que ayer: sin diseño. Emma las reemplaza mañana. **Crea:**

@@provisional guardados_index.blade.php resources/views/guardados/index.blade.php@@

@@provisional perfil_show.blade.php resources/views/perfil/show.blade.php@@

- [ ] En `/recetas/charquican`, los botones guardan y marcan (si el detalle del mockup aún no está, usa tu vista provisional).
- [ ] `/guardados` y `/perfil` muestran los datos de Camila: 3 guardadas, «7 de 20 recetas cocinadas (35%)».
- [ ] En `/perfil`, cambiar el nombre funciona; un correo sin `@` muestra un error.

## Paso 8 · Pruebas de guardar y cocinar

**Crea:**

@@archivo tests/Feature/SavedAndCookedTest.php@@

**Entiende**
- `test_guardados_muestra_solo_mis_recetas` es una prueba de privacidad: lo que guarda otra persona no aparece en mi lista.
- `assertSessionHas('toast', [...])` comprueba el aviso exacto que verá la persona, con el nombre del sticker y «1 de 1».

## Paso 9 · Mensajes en español

Con `APP_LOCALE=es`, Laravel busca los textos en `lang/es/`. Si no existen, los muestra en inglés. **Crea:**

@@archivo lang/es/validation.php@@

**Entiende**
- Son los mensajes de `$request->validate()`. `:attribute`, `:min` y `:max` se reemplazan solos: «El campo contraseña debe tener al menos 8 caracteres.» El arreglo `attributes` traduce el nombre de cada campo.
- El mensaje de «correo o contraseña incorrectos» no está aquí: lo escribió Sebastián en español directo en `AuthController`.
- Prueba: en `/registro`, una contraseña de 3 letras debe mostrar el error en español.

## Cierre del día

```bash
vendor/bin/pint && php artisan test
git add . && git commit -m "Guardar, cocinada, perfil y textos en español"
git push
```

- [ ] PR 2 abierto antes de las 16:00. Avisa: **📣 «Emma: `/guardados` y `/perfil` ya entregan datos»**.
- [ ] Check-in de las 21:00.
