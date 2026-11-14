# Miguel · Día 1 (viernes 13): la base de datos, los modelos y las 20 recetas

**Meta del día:** que las recetas vivan en la base de datos y que `/recetas` y `/recetas/charquican` muestren datos reales (todavía sin diseño: eso es de Emma).

← [Índice de guías](../README.md) · Siguiente: [Día 2](dia2.md)

## Mapa del día

| Paso | Qué haces | Archivo | Entrega |
|---|---|---|---|
| 0 | Clonar y dejar el proyecto andando | (comandos) | Necesita el esqueleto de Sebastián (~10:30) |
| 1 | Tablas de recetas e ingredientes | `database/migrations/…create_recipes_table.php` | |
| 2 | Tablas de guardadas y cocinadas | `database/migrations/…create_saved_and_cooked_recipes_tables.php` | |
| 3 | Modelos `Recipe` e `Ingredient` | `app/Models/Recipe.php`, `Ingredient.php` | |
| 4 | Relaciones y atributos de `User` | `app/Models/User.php` | **📣 PR 1 a las 13:00 (Emma lo espera)** |
| 5 | Las 20 recetas | `database/data/recetas.php` | |
| 6 | Seeders | `RecipeSeeder.php`, `DatabaseSeeder.php` | **PR 2 a las 15:00** |
| 7 | Controlador del catálogo | `app/Http/Controllers/RecipeController.php` | |
| 8 | Rutas del catálogo | `routes/web.php` | |
| 9 | Vistas provisionales | `recetas/index.blade.php`, `recetas/show.blade.php` | **PR 3 (fin del día)** |

**Tiempo:** unas 3 h 30 (~450 líneas escritas; los datos del paso 5 se copian). Antes de empezar, lee [cómo leer los bloques de código](../README.md#cómo-leer-los-bloques-de-código).

Mientras esperas el esqueleto puedes adelantar leyendo `mockup/Mockup de YouCook/src/data.ts`: de ahí salen las tablas.

---

## Paso 0 · Clonar y dejar el proyecto andando

Cuando Sebastián avise «Esqueleto en main»:

```bash
git clone <url-del-repo> && cd YouCook/web
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate          # responde «yes» si pregunta por crear database.sqlite
git switch -c miguel/dia1-modelos
```

Para trabajar, dos terminales en `web/`: `php artisan serve` y `npm run dev`.

## Paso 1 · Tablas de recetas e ingredientes

```bash
php artisan make:migration create_recipes_table
```

Se crea un archivo en `database/migrations/` con la fecha en el nombre. **Reemplaza todo** su contenido:

@@archivo database/migrations/2026_11_13_000001_create_recipes_table.php como database/migrations/(fecha)_create_recipes_table.php@@

**Entiende**
- Una migración es la receta para crear tablas: `up()` las crea y `down()` las deshace. Así los tres tenemos la misma base de datos con `php artisan migrate`.
- `slug` es el nombre de la receta para la URL (`charquican`). `unique()` impide que haya dos iguales.
- `cost` es un número (3500), no un texto: el formato «$3.500» se arma al mostrarlo.
- `allergens` y `steps` son listas guardadas como JSON en una columna. Los ingredientes, en cambio, tienen tabla propia porque la IA necesita referirse a **un** ingrediente por su `id`.
- `foreignId('recipe_id')->constrained()->cascadeOnDelete()`: cada ingrediente pertenece a una receta; si se borra la receta, se borran sus ingredientes.

## Paso 2 · Tablas de guardadas y cocinadas

```bash
php artisan make:migration create_saved_and_cooked_recipes_tables
```

**Reemplaza todo:**

@@archivo database/migrations/2026_11_13_000002_create_saved_and_cooked_recipes_tables.php como database/migrations/(fecha)_create_saved_and_cooked_recipes_tables.php@@

**Entiende**
- Son **tablas pivote**: cada fila une a una persona con una receta. Una persona guarda muchas recetas y una receta es guardada por muchas personas (relación «muchos a muchos»).
- `primary(['user_id', 'recipe_id'])`: la pareja no se puede repetir, así que nadie guarda dos veces la misma receta.
- En el mockup esto era `saved: string[]` y `cooked: string[]` en la memoria del navegador (`store.tsx`). Aquí queda guardado por persona.

```bash
php artisan migrate
```

## Paso 3 · Modelos `Recipe` e `Ingredient`

**Crea:**

@@archivo app/Models/Recipe.php@@

**Entiende**
- Un modelo es una clase que representa una tabla: `Recipe` ↔ `recipes` (Laravel deduce el nombre en plural).
- `$fillable` son las columnas que se pueden llenar con `Recipe::create([...])`. Es una protección: nadie puede colar otras columnas desde un formulario.
- `casts()`: `allergens` y `steps` se guardan como JSON y se leen como arreglos de PHP.
- `getRouteKeyName()`: cuando una ruta tiene `{recipe}`, Laravel busca la receta por `slug` en vez de por `id`.
- `ingredients()` es la relación «una receta tiene muchos ingredientes». Se usa como `$recipe->ingredients`.
- `costLabel()` es un atributo calculado: `$recipe->cost_label` devuelve «$3.500».
- `scopeFilter` es un *scope*: un trozo de consulta con nombre. Se usa como `Recipe::filter($filtros)`. Cada `when(valor, función)` agrega su condición **solo si** el filtro viene con valor.
- La búsqueda mira el título **o** el nombre de algún ingrediente (`orWhereHas`), igual que el buscador del mockup. Los valores van como parámetros de la consulta: no hay riesgo de inyección SQL.

@@archivo app/Models/Ingredient.php@@

## Paso 4 · Relaciones y atributos de `User`

**Reemplaza todo** (lo nuevo empieza en la línea `// --- YouCook`. Arriba cambian dos cosas respecto de lo que trae Laravel: tres `use` nuevos, y `$fillable` y `$hidden` escritos como propiedades, igual que en `Recipe.php` y que en la clase 10):

@@archivo app/Models/User.php@@

**Entiende**
- `$fillable` son los campos que `User::create([...])` puede llenar (los usa `AuthController` al registrar) y `$hidden` los que nunca salen si el usuario se convierte en JSON. Laravel 13 los trae escritos como atributos (`#[Fillable([...])]`); funcionan igual, pero los dejamos como en clases.
- `belongsToMany(Recipe::class, 'saved_recipes')`: la relación «muchos a muchos» a través de la tabla pivote. `$user->savedRecipes` entrega las recetas guardadas y `$user->savedRecipes()->toggle($recipe)` guarda o quita.
- `hasSaved()` y `hasCooked()` preguntan a la base de datos si existe la pareja, sin traer todas las recetas.
- `first_name`, `short_name` e `initial` son atributos calculados para la interfaz: «Hola, Camila», «Camila R.» y la letra del avatar. En el código se escriben en `camelCase` (`firstName`) y se leen en `snake_case` (`$user->first_name`).

Comprueba que todo calce y sube el **PR 1**:

```bash
php artisan migrate:fresh && php artisan test      # 9 pruebas en verde
git add . && git commit -m "Migraciones y modelos"
git push -u origin miguel/dia1-modelos
```

Avisa: **📣 «Emma: modelos en PR»** (su layout cuenta las guardadas y cocinadas con tus relaciones de `User`).

## Paso 5 · Las 20 recetas

Rama nueva: `git switch -c miguel/dia1-recetas`.

Las 8 primeras son las del mockup (`data.ts`) y las otras 12 completan el reto de 20. **Este archivo es contenido, no código: se puede copiar y pegar.** Lo que sí tienes que entender es la forma de **una** receta. **Crea** `database/data/recetas.php`:

@@parte database/data/recetas.php 1@@

<details>
<summary>Recetas 5 a 20 (copiar debajo, en el mismo archivo)</summary>

@@parte database/data/recetas.php 2-5@@

</details>

**Entiende**
- El archivo devuelve (`return`) un arreglo de recetas. `$foto` es una función corta que arma la URL de Unsplash a partir del código de la foto.
- Las 12 recetas nuevas tienen `'image' => null`: la app muestra su sticker en vez de la foto. Buscar esas fotos es una tarea del domingo.
- `sticker` es el nombre del dibujo (un archivo de Emma en `components/stickers/`). Las recetas sin dibujo propio usan `estrella`.

## Paso 6 · Seeders

Un seeder llena la base de datos. **Crea:**

@@archivo database/seeders/RecipeSeeder.php@@

**Reemplaza todo:**

@@archivo database/seeders/DatabaseSeeder.php@@

```bash
php artisan migrate:fresh --seed
php artisan tinker
```

Dentro de tinker, comprueba (y sal con `exit`):

```php
App\Models\Recipe::count();                                   // 20
App\Models\Recipe::first()->ingredients->pluck('name');       // Carne molida, Papas, ...
App\Models\Recipe::first()->cost_label;                       // "$3.500"
App\Models\Recipe::filter(['q' => 'zapallo'])->pluck('title');
$u = App\Models\User::first();
$u->short_name;                                               // "Camila R."
$u->savedRecipes()->count();                                  // 3
$u->cookedRecipes()->count();                                 // 7
```

**Entiende**
- `updateOrCreate([buscar], [datos])`: si ya existe una receta con ese slug, la actualiza; si no, la crea. Por eso el seeder se puede ejecutar varias veces.
- `Arr::except($data, ['ingredients'])` saca los ingredientes del arreglo, porque van a otra tabla. `$recipe->ingredients()->create([...])` crea cada uno ya unido a su receta.
- `foreach (... as $index => [$name, $quantity])` desarma cada par `['Papas', '4 medianas']` en dos variables.
- `DatabaseSeeder` crea a Camila con los mismos guardados (3) y cocinadas (7) del mockup. Su contraseña es `password` (la que pone `User::factory()`).
- `migrate:fresh --seed` **borra todo** y parte de cero. Úsalo cada vez que cambien las migraciones o los datos.

Sube el **PR 2**: `git add . && git commit -m "Las 20 recetas y la cuenta de demostración" && git push -u origin miguel/dia1-recetas`.

## Paso 7 · Controlador del catálogo

Rama nueva: `git switch -c miguel/dia1-catalogo`.

```bash
php artisan make:controller RecipeController
```

**Reemplaza todo:**

@@archivo app/Http/Controllers/RecipeController.php@@

**Entiende**
- `validate([...])` revisa los filtros de la URL. `nullable` permite que no vengan. Devuelve solo los campos validados.
- `paginate(8)` trae 8 recetas según `?page=`. `withQueryString()` hace que los enlaces de las páginas conserven los filtros (`?q=arroz&page=2`). El mockup ya anunciaba «Paginación clásica (Laravel paginate)».
- `pluck('recipes.id')->all()` entrega solo los ids de las recetas guardadas (y cocinadas). La vista pregunta `in_array($recipe->id, $savedIds)` por cada tarjeta, sin hacer una consulta por receta.
- En `show`, `Recipe $recipe` llega ya buscada por su slug. Si no existe, Laravel responde 404 sola. `load('ingredients')` trae los ingredientes en una sola consulta.

## Paso 8 · Rutas del catálogo

**Reemplaza todo** (cambian las dos primeras rutas del grupo; el resto sigue provisional hasta mañana):

@@provisional web_dia1_miguel.php routes/web.php@@

## Paso 9 · Vistas provisionales

Dos vistas **sin diseño**, solo para comprobar los datos. Emma las reemplaza mañana por las del mockup y le sirven para ver qué variables entrega cada controlador. **Crea:**

@@provisional recetas_index.blade.php resources/views/recetas/index.blade.php@@

@@provisional recetas_show.blade.php resources/views/recetas/show.blade.php@@

Entra con `camila.rojas@uni.cl` / `password` y revisa:

- [ ] `/recetas` muestra «8 de 20 recetas» y los enlaces de página.
- [ ] `/recetas?q=zapallo` y `/recetas?categoria=Chilenas&tiempo=45` filtran.
- [ ] `/recetas?q=xyz` muestra «No encontramos recetas con esos filtros».
- [ ] `/recetas/charquican` muestra ingredientes, pasos y alérgenos. `/recetas/no-existe` responde 404.

## Cierre del día

```bash
vendor/bin/pint && php artisan test
git add . && git commit -m "Catálogo y detalle con vistas provisionales"
git push -u origin miguel/dia1-catalogo
```

- [ ] Tres PR abiertos (modelos, recetas, catálogo). Sebastián los integra.
- [ ] Check-in de las 21:00.
