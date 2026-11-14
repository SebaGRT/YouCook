# Emma · Día 2 (sábado 14): catálogo y detalle de receta

**Meta del día:** las dos pantallas principales, iguales al mockup y con datos reales: el catálogo (buscador, filtros, tarjetas y paginación) y el detalle de receta.

← [Día 1](dia1.md) · [Índice de guías](../README.md) · Siguiente: [Día 3](dia3.md)

## Mapa del día

| Paso | Qué haces | Archivo | Depende de |
|---|---|---|---|
| 1 | Dificultad | `components/difficulty.blade.php` | — |
| 2 | Foto de la receta | `components/recipe-photo.blade.php` | — |
| 3 | Botón de guardar | `components/bookmark-button.blade.php` | — |
| 4 | Tarjeta de receta | `components/recipe-card.blade.php` | — |
| 5 | Selector de los filtros | `components/select.blade.php` | — |
| 6 | Paginación | `pagination/youcook.blade.php` | — |
| 7 | Catálogo | `recetas/index.blade.php` | Catálogo provisional de Miguel (viernes) · **PR 1** |
| 8 | Detalle de receta | `recetas/show.blade.php` | **`<x-ingredient-row>` de Sebastián (12:00)** · **PR 2** |

**Tiempo:** unas 3 h 30 (~470 líneas).

Rama: `git switch main && git pull && composer install && npm install && php artisan migrate:fresh --seed && git switch -c emma/dia2-catalogo`. Entra con `camila.rojas@uni.cl` / `password`: es la cuenta del mockup.

Las vistas `recetas/index.blade.php` y `recetas/show.blade.php` ya existen: son las **provisionales** de Miguel. Ábrelas antes de empezar: muestran qué variables entrega cada controlador. Hoy las reemplazas.

---

## Paso 1 · Dificultad

En el mockup: `DifficultyPill` en `RecipeCard.tsx`. **Crea:**

@@archivo resources/views/components/difficulty.blade.php@@

## Paso 2 · Foto de la receta

**Crea:**

@@archivo resources/views/components/recipe-photo.blade.php@@

**Entiende**
- Las 8 recetas del mockup tienen foto. Las otras 12 todavía no: en su lugar se dibuja su sticker sobre fondo crema, y la tarjeta se ve completa igual.
- Las clases que se le pasen (`class="size-full object-cover"`) van a la `<img>` o al `<div>`, según el caso.

## Paso 3 · Botón de guardar

En el mockup: `BookmarkButton`. Allá cambiaba una lista en la memoria; aquí es un **formulario**, porque guardar es un cambio en la base de datos. **Crea:**

@@archivo resources/views/components/bookmark-button.blade.php@@

**Entiende**
- Al tocar el botón, el navegador envía un `POST` a `/recetas/charquican/guardar`. El controlador de Miguel guarda (o quita) y devuelve a la misma página, que se dibuja de nuevo con el botón en su otro estado.
- `route('recetas.guardar', $recipe)`: al pasarle la receta, Laravel pone su slug en la URL.
- `aria-pressed` y `aria-label` le dicen a un lector de pantalla si está guardada y qué hace el botón.

## Paso 4 · Tarjeta de receta

En el mockup: `RecipeCard`. **Crea:**

@@archivo resources/views/components/recipe-card.blade.php@@

**Entiende**
- En el mockup toda la tarjeta era un enlace `<a>` con el botón adentro. En HTML un formulario no puede ir dentro de un enlace, así que la tarjeta es un `<article>` y el enlace está en el título. `after:absolute after:inset-0` estira el área del enlace sobre toda la tarjeta, y `z-10` deja el botón de guardar por encima.
- `group` y `group-hover:scale-105`: al pasar el mouse por la tarjeta (el grupo), la foto se agranda.
- `id="receta-…"` y `scroll-mt-24`: después de guardar, la página vuelve a esta tarjeta sin que la tape la barra de arriba.
- `$recipe->cost_label` es el atributo de Miguel que entrega «$3.500».

## Paso 5 · Selector de los filtros

En el mockup: `Select` en `Catalog.tsx`. **Crea:**

@@archivo resources/views/components/select.blade.php@@

**Entiende**
- `$options` es un arreglo `['valor' => 'Texto']`. `@selected(...)` marca la opción que coincide con el filtro actual.
- `appearance-none` quita la flecha del navegador; la nuestra es el `<svg>` de al lado.

## Paso 6 · Paginación

El mockup dibuja círculos con el número de página y un botón «Siguiente». Laravel deja escribir nuestra propia vista de paginación. **Crea:**

@@archivo resources/views/pagination/youcook.blade.php@@

**Entiende**
- `$paginator` y `$elements` los entrega Laravel. `$elements` trae los números de página con su URL (y `...` cuando hay muchas).
- `hasPages()`: si todo cabe en una página, no se dibuja nada.
- Se usa desde el catálogo con `{{ $recipes->links('pagination.youcook') }}`.

## Paso 7 · Catálogo

En el mockup: `screens/Catalog.tsx`. La diferencia grande: allá los filtros cambiaban una lista en la memoria; aquí **cada filtro es un parámetro de la URL** (`/recetas?q=arroz&categoria=Rápidas`) y quien filtra es la base de datos. **Reemplaza todo:**

@@parte resources/views/recetas/index.blade.php 1@@

**Entiende**
- `$filters` viene del controlador de Miguel: los filtros validados de la URL. `?? null` evita un error cuando un filtro no viene.
- El buscador es un formulario `GET`: al enviarlo, lo escrito queda en la URL (`?q=zapallo`). Por eso una búsqueda se puede compartir o guardar en favoritos.
- El campo oculto `categoria` hace que, al buscar, no se pierda la categoría elegida.
- «Populares» son enlaces normales a `/recetas?q=huevo`.

@@parte resources/views/recetas/index.blade.php 2@@

**Entiende**
- Las categorías son enlaces. `request()->fullUrlWithQuery([...])` toma la URL actual y le cambia solo esos parámetros: cambia la categoría, conserva los demás filtros y, con `'page' => null`, vuelve a la página 1.
- `App\Models\Recipe::CATEGORIES` es la lista de categorías del modelo de Miguel: un solo lugar para agregar o quitar una.
- Los dos `<x-select>` están fuera del `<form>`, pero `form="filtros"` los une a él. `onchange="this.form.submit()"` envía el formulario apenas se elige una opción.

@@parte resources/views/recetas/index.blade.php 3-4@@

**Entiende**
- `$recipes` es una página de resultados: `count()` son las que se ven ahora (8) y `$total`, todas las de la app (20). `links(...)` dibuja la paginación del paso 6.
- `in_array($recipe->id, $savedIds)` responde si **esta** receta está entre las guardadas. `:saved="…"` le pasa ese verdadero o falso a la tarjeta.
- `@if … @else`: con resultados, la grilla; sin resultados, el estado vacío del mockup.
- `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`: una columna en el teléfono, dos en tablet y cuatro en escritorio.

Revisa y sube el **PR 1**:

- [ ] Se ve igual al mockup: 8 tarjetas, «8 de 20 recetas», páginas 1-2-3 y «Siguiente».
- [ ] Buscar «zapallo», elegir una categoría, un tiempo y una dificultad: se combinan y la URL lo refleja.
- [ ] Una búsqueda sin resultados muestra la olla y «Limpiar filtros».
- [ ] El marcador de una tarjeta guarda (cuando el controlador de Miguel esté integrado; antes solo recarga) y la tarjeta entera lleva al detalle.
- [ ] Las recetas de la página 2 y 3 sin foto muestran su sticker.

```bash
npm run build && php artisan test      # las pruebas del catálogo de Miguel deben seguir en verde
git add . && git commit -m "Catálogo del mockup"
git push -u origin emma/dia2-catalogo
```

## Paso 8 · Detalle de receta

Rama nueva: `git switch main && git pull && git switch -c emma/dia2-detalle`. Necesitas que `<x-ingredient-row>` de Sebastián esté en `main`.

En el mockup: `screens/Detail.tsx`. **Reemplaza todo:**

@@parte resources/views/recetas/show.blade.php 1@@

**Entiende**
- `:title="$recipe->title"` pone el nombre de la receta en la pestaña del navegador.
- El botón de guardar sobre la foto es `lg:hidden`: solo en pantallas chicas. En escritorio se usa el botón «Guardar» de más abajo.
- `<dl>`, `<dt>` y `<dd>` son la lista de datos (tiempo, dificultad, porciones y costo): dos columnas en el teléfono y cuatro desde `sm:`.

@@parte resources/views/recetas/show.blade.php 2@@

**Entiende**
- «Marcar como cocinada» y «Guardar» son dos formularios `POST`, cada uno a su ruta. `$isCooked` e `$isSaved` vienen del controlador y deciden el texto y los colores.
- Al marcar como cocinada, el controlador deja un aviso en la sesión y tu layout lo muestra con el sticker de la receta.
- La caja amarilla de alérgenos es uno de los riesgos de la propuesta. `implode(' · ', …)` une la lista con puntos.

@@parte resources/views/recetas/show.blade.php 3-4@@

**Entiende**
- Cada ingrediente es un `<x-ingredient-row>` de Sebastián: la casilla, «No lo tengo» y el panel de la IA viven ahí.
- Los pasos se marcan **solo en el navegador** (no se guardan): es Alpine. `x-data="{ hechos: 0 }"` es el contador de la sección y cada paso tiene su propio `listo`. Al tocar un paso, cambia `listo` y suma o resta 1 a `hechos`.
- `:class="{ 'clases': condición }"` pone esas clases cuando la condición es verdadera y las quita cuando es falsa. Las del estado inicial también están en `class`, para que se vea bien antes de que Alpine cargue.
- `$loop->iteration` es el número de vuelta del `@foreach`, partiendo en 1.
- El enlace final lleva al asistente con `?receta=charquican`, para que la conversación sea sobre esta receta.

Revisa:

- [ ] `/recetas/charquican` se ve igual al mockup, en escritorio y en teléfono.
- [ ] Los pasos se marcan y el contador «2 de 6 pasos» cambia.
- [ ] «Marcar como cocinada» muestra el aviso con el sticker y cambia a «¡Ya la cocinaste!».
- [ ] `/recetas/pure` (sin foto) muestra el sticker grande.
- [ ] «No lo tengo» muestra el panel de la IA (con error o con respuestas, según cuánto haya avanzado Sebastián).

## Cierre del día

```bash
npm run build && php artisan test
git add . && git commit -m "Detalle de receta del mockup"
git push -u origin emma/dia2-detalle
```

- [ ] Dos PR abiertos (catálogo y detalle).
- [ ] Check-in de las 21:00.
