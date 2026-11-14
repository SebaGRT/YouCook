# Guías de código: YouCook en Laravel, con el diseño del mockup

Estas guías traen **el código completo que cada uno escribe**, día por día. El proyecto parte **vacío** (`laravel new`) y termina igual al mockup de Figma (`mockup/Mockup de YouCook/`, hecho en React), pero en **PHP + Laravel**: Blade, Eloquent, SQLite, cuentas escritas a mano y una IA real (Groq). Todo con lo que vimos en las clases 9, 10 y 11 (ver [qué usamos de cada clase](#qué-usamos-de-cada-clase)).

El orden está pensado para que **la app funcione al cierre de cada PR**. Lo verificamos simulando el trabajo completo de los tres días sobre un proyecto recién creado (`scripts/guias/simular_integracion.sh`): en cada corte las vistas compilan, las pruebas pasan y todas las páginas abren.

| | Viernes 13 | Sábado 14 | Domingo 15 |
|---|---|---|---|
| **Sebastián** (líder · IA e integración) | [Día 1](sebastian/dia1.md): proyecto nuevo, cuentas, tema, rutas provisionales y servicio de Groq | [Día 2](sebastian/dia2.md): «No lo tengo» con IA y el asistente | [Día 3](sebastian/dia3.md): integración, limpieza, README y ensayo |
| **Emma** (diseño y frontend) | [Día 1](emma/dia1.md): íconos, stickers, ingreso, registro y barra de navegación | [Día 2](emma/dia2.md): catálogo y detalle de receta | [Día 3](emma/dia3.md): Guardados, Perfil y revisión visual |
| **Miguel** (backend) | [Día 1](miguel/dia1.md): migraciones, modelos, las 20 recetas y catálogo | [Día 2](miguel/dia2.md): guardar, cocinada, perfil, pruebas y textos en español | [Día 3](miguel/dia3.md): pruebas del perfil, fotos y revisión del backend |

El **lunes 16** se presenta: ese día no se escribe código. El plan con horarios, reglas y qué se recorta si vamos atrasados está en [`docs/plan/`](../plan/README.md).

## Cómo trabajar con la guía

1. **Escribe el código tú, sin copiar y pegar.** Es la evidencia de tu trabajo y la manera de entenderlo. Las únicas excepciones están marcadas: los datos de las recetas y los trazos de los stickers.
2. Después de cada archivo, lee la sección **«Entiende»**. En la presentación te pueden preguntar por cualquier línea.
3. Trabaja con dos terminales abiertas en `web/`: `php artisan serve` y `npm run dev`. Mira el resultado en `http://localhost:8000` después de cada paso.
4. Antes de cada commit, desde `web/`:
   ```bash
   vendor/bin/pint && npm run build && php artisan test
   ```
5. Un commit por paso (o por grupo de pasos), en tu rama del día (`nombre/diaN-tema`). Cada guía dice cuándo abrir el PR. Sebastián integra.
6. Cada mañana, antes de crear tu rama:
   ```bash
   git switch main && git pull
   composer install && npm install
   php artisan migrate:fresh --seed
   ```
7. ¿Algo falla y no ves dónde? Compara tu archivo con el de la guía. Los errores más comunes: una etiqueta o un `@endif` sin cerrar, una coma que falta en un arreglo de PHP, o `npm run dev` apagado (las clases nuevas no toman estilo).

### Cómo leer los bloques de código

- Cada guía empieza con un **Mapa del día**: los pasos, el archivo de cada uno y de quién dependes.
- 📄 **`web/…`** dice en qué archivo escribes. Todas las rutas parten de `web/`.
- **«Crea» / «Reemplaza todo»:** el archivo queda exactamente como el bloque.
- **«parte 2 de 4 · sigue debajo de la parte anterior»:** los archivos largos vienen en partes. Escríbelas **una debajo de la otra, en el mismo archivo**. Después de cada parte viene su explicación.
- Las líneas `// --- Título ---` y `{{-- --- Título --- --}}` también se escriben: separan las secciones del archivo.
- **«versión provisional»:** un archivo que se reemplaza más adelante. La guía avisa cuándo.
- **«agrega este bloque»:** un trozo que se agrega a un archivo que ya existe. La guía dice dónde.

### Del mockup (React) a Blade

El mockup ya trae las clases de Tailwind correctas: el trabajo es traducir la **estructura**.

| En el mockup (React) | En Laravel |
|---|---|
| `src/screens/Catalog.tsx` | `resources/views/recetas/index.blade.php` + `RecipeController@index` |
| `src/screens/Detail.tsx` | `recetas/show.blade.php` + `components/ingredient-row.blade.php` |
| `src/screens/Assistant.tsx` | `asistente/show.blade.php` + `resources/js/chat.js` |
| `src/screens/Saved.tsx`, `Profile.tsx` | `guardados/index.blade.php`, `perfil/show.blade.php` |
| `src/screens/Auth.tsx` | `layouts/guest.blade.php`, `auth/login.blade.php`, `auth/registro.blade.php` + `AuthController` |
| `src/components/Nav.tsx`, `Icons.tsx`, `Stickers.tsx`, `RecipeCard.tsx` | `components/nav`, `icon`, `sticker` (+ `stickers/*`), `recipe-card` |
| `src/data.ts` (arreglo `RECIPES`) | Tablas `recipes` e `ingredients` + `database/data/recetas.php` |
| `src/store.tsx` (`saved`, `cooked` en memoria) | Tablas `saved_recipes` y `cooked_recipes`, por persona |
| `src/index.css` | `resources/css/app.css` (el mismo tema) |

| React | Blade / Alpine |
|---|---|
| `className="…"` | `class="…"` |
| `<RecipeCard r={r} />` | `<x-recipe-card :recipe="$recipe" />` |
| `{r.title}` | `{{ $recipe->title }}` |
| `{cond && <span>…</span>}` | `@if ($cond) <span>…</span> @endif` |
| `{list.map((r) => …)}` | `@foreach ($list as $r) … @endforeach` |
| `` className={`a ${on ? 'b' : 'c'}`} `` | `class="a {{ $on ? 'b' : 'c' }}"` |
| `href="#/recetas/charquican"` | `href="{{ route('recetas.show', $recipe) }}"` |
| `useState` para algo que se guarda (guardar, cocinada) | Un formulario `POST` y un controlador |
| `useState` para algo solo visual (pasos, ojo de la contraseña) | Alpine: `x-data`, `x-on:click`, `x-show`, `:class` |
| `setTimeout` que simulaba la IA | `fetch` a una ruta de Laravel que llama a la IA real |

### Mirar una pieza antes de que exista su pantalla

Para ver componentes sueltos, cada uno puede crear `resources/views/muestra.blade.php` y abrir `http://localhost:8000/muestra` (con sesión iniciada). Ese archivo está en `.gitignore`: no se sube. Las guías traen ejemplos.

### Pantallas «En construcción» y vistas provisionales

- Desde el viernes en la mañana **todas las rutas existen**. Las que aún no tienen pantalla muestran «En construcción» (`pendiente.blade.php`).
- Miguel escribe primero cada controlador con una **vista provisional** (sin diseño, solo los datos). Emma la reemplaza por la del mockup. Así ninguno espera al otro, y Emma ve qué variables entrega cada controlador.
- Las pruebas de Miguel buscan textos del mockup («8 de 20 recetas», «Aún no guardas recetas»). Tienen que pasar con la vista provisional y con la definitiva: si una prueba falla después de cambiar una vista, falta ese texto.

### Cómo debe verse

En [`objetivo/`](objetivo/) están las capturas de la versión terminada (escritorio y teléfono). Compara tu resultado con ellas y con el mockup.

## Entregas con hora (lo que otro está esperando)

| Cuándo | Quién entrega | Qué | Quién lo necesita |
|---|---|---|---|
| **Vie 13, ~10:30** | Sebastián | Proyecto Laravel en `main` (cuentas, tema y rutas provisionales) | Todos: recién ahí se puede clonar |
| **Vie 13, 13:00** | Miguel | Migraciones y modelos | Emma (barra de navegación, en la tarde) |
| Vie 13, 15:00 | Miguel | Las 20 recetas y la cuenta de Camila | Todos (datos para mirar) |
| Vie 13, tarde | Emma | Layout y barra de navegación | Sebastián (pantalla del asistente, sábado) |
| Vie 13, noche | Miguel | Catálogo y detalle con vistas provisionales | Emma (catálogo y detalle, sábado) |
| **Sáb 14, 10:00** | Miguel | Factories | Sebastián (pruebas) |
| **Sáb 14, 12:00** | Sebastián | `<x-ingredient-row>` y su JavaScript | Emma (detalle, en la tarde) |
| **Sáb 14, 16:00** | Miguel | Guardar, cocinada y perfil (con vistas provisionales) | Emma (Guardados y Perfil, domingo) |
| **Dom 15, 13:00** | Emma | Guardados y Perfil | Sebastián (limpieza) y Miguel (pruebas del perfil) |
| **Dom 15, 18:00** | Todos | Congelamiento de `main` | Ensayo de las 21:00 |

Sebastián integra los PR apenas llegan: el trabajo de los demás depende de eso. Si una entrega se atrasa, avisa en el chat y adelanta un paso que no dependa de ella (el mapa de cada guía dice cuáles).

## Quién es dueño de cada archivo

| Archivo (dentro de `web/`) | Dueño | Día |
|---|---|---|
| Proyecto, `resources/css/app.css`, `vite.config.js`, `.env.example`, `config/`, `AuthController.php`, `routes/auth.php`, `tests/Feature/Auth/*`, `routes/ia.php`, `routes/console.php`, `pendiente.blade.php` | Sebastián | 1 |
| `app/Services/AsistenteIA.php`, `app/Exceptions/IaNoDisponible.php`, `tests/Feature/AsistenteIATest.php` | Sebastián | 1 y 2 |
| `resources/js/*`, `components/ia-loading`, `ia-error`, `ingredient-row`, `IngredientAlternativeController.php`, `AssistantController.php`, `asistente/show.blade.php`, `tests/Feature/AssistantTest.php` | Sebastián | 2 |
| `database/` (migraciones, datos, seeders), `app/Models/*`, `RecipeController.php`, `routes/web.php` | Miguel | 1 |
| `database/factories/*`, `SavedRecipeController.php`, `CookedRecipeController.php`, `ProfileController.php`, `lang/es/validation.php`, `tests/Feature/RecipeCatalogTest.php`, `SavedAndCookedTest.php` | Miguel | 2 |
| `tests/Feature/ProfileTest.php` | Miguel | 3 |
| `components/icon`, `logo`, `sticker`, `sticker-face`, `stickers/*`, `auth-field`, `primary-button`, `text-input`, `input-label`, `input-error`, `layouts/guest.blade.php`, `auth/login`, `auth/registro` | Emma | 1 (mañana) |
| `components/avatar`, `nav`, `toast`, `layouts/app.blade.php` | Emma | 1 (tarde) |
| `components/difficulty`, `recipe-photo`, `bookmark-button`, `recipe-card`, `select`, `pagination/youcook`, `recetas/index`, `recetas/show` | Emma | 2 |
| `guardados/index`, `perfil/show` | Emma | 3 |

**Regla:** nadie edita un archivo de otro. Si necesitas un cambio (una variable más en una vista, un texto distinto), pídelo en el chat diciendo el archivo y qué debería hacer.

**Archivos provisionales**, que después se reemplazan (cada guía avisa cuándo):
- `routes/web.php`: tres versiones (Sebastián el viernes en la mañana, Miguel el viernes en la tarde y la definitiva de Miguel el sábado).
- `routes/ia.php`: provisional el viernes, definitiva el sábado.
- `auth/login` y `auth/registro`: sin diseño (Sebastián, viernes en la mañana) hasta que Emma escribe las del mockup.
- `pendiente.blade.php`: sin layout (Sebastián), con layout (Emma) y se borra el domingo.
- `recetas/index`, `recetas/show`, `guardados/index` y `perfil/show`: provisionales de Miguel hasta que Emma escribe las del mockup.
- `resources/js/app.js`: una el viernes (solo Alpine) y dos más el sábado.
- `AsistenteIA.php` y `AsistenteIATest.php`: se empiezan el viernes y se terminan el sábado.

## Qué cambia respecto del mockup

- **Los datos son reales.** Guardar, cocinar y el progreso quedan en la base de datos, por persona. Los filtros del catálogo van en la URL y los resuelve la base de datos.
- **La IA es real** (la API de Groq, como en el traductor de la clase 9). En el mockup las respuestas estaban escritas a mano y siempre eran las mismas.
- **20 recetas completas.** El mockup tenía 8 y solo el título de otras 12. Las 12 nuevas todavía no tienen foto: muestran su sticker.
- **Sticker `estrella`.** Nueve recetas no tenían dibujo en el mockup y mostraban «?» aun desbloqueadas. Ahora comparten una estrella.
- **Registro sin confirmar contraseña**, como en el mockup (en la clase 11 se pedía dos veces, con `confirmed`).
- **Sin «¿Olvidaste tu contraseña?».** Recuperarla necesita enviar correos, y eso queda fuera del alcance.
- **«Editar» en el Perfil funciona:** cambia el nombre y el correo. En el mockup era solo un botón.
- **Nivel del perfil.** El mockup decía «Cocinera en entrenamiento» con 7 recetas, pero su propia barra marca «5 · Aprendiz». Seguimos la barra: con 7 dice «Aprendiz».
- **Asistente sin receta.** Si se entra directo a `/asistente`, no aparece la tarjeta «Hablando sobre» y las sugerencias son generales. El mockup siempre hablaba del charquicán.
- **La tarjeta de receta** es un `<article>` con el enlace en el título (un formulario no puede ir dentro de un enlace). Se ve igual.
- **No está el panel «Mockup»** (el botón flotante para cambiar de pantalla y simular errores): era una herramienta del prototipo. El estado de error de la IA se ve quitando `GROQ_API_KEY` de `.env`.

## Qué usamos de cada clase

Las guías se escribieron para que todo calce con lo que vimos en la Unidad 3. Lo que va **más allá** de las clases está marcado y explicado en su «Entiende».

| Clase | Lo que vimos | Dónde aparece en YouCook |
|---|---|---|
| **9** · PHP, instalación, primer proyecto | `laravel new` con SQLite, rutas con nombre, layouts con `@extends`, `@section` y `@yield`, y el traductor con Groq: `Http::withToken(config('services.groq.key'))`, `config/services.php`, `GROQ_MODEL=openai/gpt-oss-20b`, `failed()` y `Log::error` | Sebastián día 1 (proyecto, `routes/web.php`, `AsistenteIA`), Emma día 1 (`layouts/app` y `layouts/guest`) y todas las pantallas |
| **10** · Clases, modelos y base de datos | Clases de PHP, migraciones, modelos con `$fillable` y `casts()`, `hasMany`, seeders, controladores con rutas escritas una por una, `$request->validate()`, componentes `<x-…>` | Miguel días 1 y 2 (tablas, `Recipe`, `Ingredient`, `RecipeController`), Emma (todos los componentes) |
| **11** · Usuarios, dueños y autorización | `AuthController` escrito a mano (`Auth::attempt`, `Auth::login`, `session()->regenerate()`), middleware `auth` y `guest`, `belongsTo`/`hasMany` con el usuario, y por qué **no** usar Breeze en un proyecto con diseño propio | Sebastián día 1 (`AuthController`, `routes/auth.php`), Miguel (guardados y cocinadas por persona) |

**Lo que va más allá de las clases** (cada guía lo explica la primera vez que aparece):

- **Alpine.js** (`x-data`, `x-show`, `x-on:click`): lo interactivo sin recargar la página (el ojo de la contraseña, «No lo tengo», el chat, el aviso). Se instala con `npm install -D alpinejs`.
- **`fetch` y respuestas JSON**: «No lo tengo» y el chat le piden datos a Laravel sin recargar.
- **`belongsToMany`** con tabla pivote (guardadas y cocinadas): la versión «muchos a muchos» del `hasMany` de la clase 10.
- **Pruebas automáticas** (`php artisan test`): en clases no las usamos, pero nos dicen en cada PR que no se rompió nada. Ninguna llama a la API real (`Http::fake()`).
- **`Cache`**, **`throttle`** y el manejo de `ConnectionException`: para cuidar el límite gratis de Groq y que la app no se caiga sin internet.
- **No hay policies** (`Gate::authorize`, `@can`): cada persona solo ve y cambia **sus** guardadas y cocinadas porque los controladores siempre parten de `$request->user()`, así que no hay recursos ajenos que proteger.

