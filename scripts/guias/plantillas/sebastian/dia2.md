# Sebastián · Día 2 (sábado 14): alternativas con IA y el asistente

**Meta del día:** que el botón «No lo tengo» y el chat del asistente funcionen con la IA real, con sus estados de carga y de error iguales a los del mockup.

← [Día 1](dia1.md) · [Índice de guías](../README.md) · Siguiente: [Día 3](dia3.md)

## Mapa del día

| Paso | Qué haces | Archivo | Depende de |
|---|---|---|---|
| 1 | Enviar JSON a Laravel desde JavaScript | `resources/js/ia.js` | — |
| 2 | El estado de una fila de ingrediente | `resources/js/ingrediente.js`, `resources/js/app.js` ① | — |
| 3 | «Cargando» y «error» de la IA | `components/ia-loading.blade.php`, `ia-error.blade.php` | Íconos de Emma (viernes) |
| 4 | La fila de ingrediente | `components/ingredient-row.blade.php` | **📣 Emma la espera a las 12:00** |
| 5 | Las preguntas a la IA | `app/Services/AsistenteIA.php` (partes 3 a 5) | Modelos de Miguel (viernes) |
| 6 | Controlador de «No lo tengo» | `IngredientAlternativeController.php` | — |
| 7 | Controlador del asistente | `AssistantController.php` | — |
| 8 | Rutas definitivas de la IA | `routes/ia.php` | — |
| 9 | El estado del chat | `resources/js/chat.js`, `resources/js/app.js` ② | — |
| 10 | La pantalla del asistente | `resources/views/asistente/show.blade.php` | Layout de Emma (viernes) |
| 11 | Pruebas | `AsistenteIATest.php` (parte 2), `AssistantTest.php` | Factories de Miguel (10:00) |
| 12 | Prueba con la IA real | (navegador) | Detalle de Emma (tarde) |

`app.js` cambia **dos veces** hoy (①②).

**Tiempo:** unas 4 h (~620 líneas). Es tu día más largo: los pasos 1 a 4 van primero porque Emma los necesita.

Rama de la mañana: `git switch main && git pull && git switch -c sebastian/dia2-ingrediente`.

---

## Cómo se reparte el trabajo entre el servidor y el navegador

En el mockup todo pasa en el navegador (React) y la IA es de mentira. Aquí la IA es real y la clave no puede salir del servidor:

1. La persona toca «No lo tengo». **Alpine** (JavaScript) cambia el estado de esa fila a `cargando` y hace un `fetch` a nuestra ruta de Laravel.
2. **Laravel** recibe la petición, le pregunta a Groq con la clave secreta y responde JSON.
3. Alpine recibe el JSON, cambia el estado a `listo` (o `error`) y la página se redibuja sola, sin recargar.

## Paso 1 · Enviar JSON a Laravel desde JavaScript

**Crea:**

@@archivo resources/js/ia.js@@

**Entiende**
- `fetch` hace una petición sin recargar la página. `await` espera la respuesta sin congelar el navegador.
- `X-CSRF-TOKEN`: el mismo código secreto de `@csrf`, pero enviado como encabezado. Sale de la etiqueta `<meta name="csrf-token">` que el layout pone en el `<head>`. Sin él, Laravel responde 419.
- `Accept: application/json` le pide a Laravel que, si algo falla, responda JSON y no una página HTML.
- `respuesta.ok` es falso cuando el código es 4xx o 5xx. Ahí lanzamos un error para que quien llamó muestre «Reintentar».

## Paso 2 · El estado de una fila de ingrediente

En el mockup, cada fila (`IngredientRow` en `Detail.tsx`) tiene dos estados de React: `have` y `state`. En Alpine es un objeto con datos y funciones. **Crea:**

@@archivo resources/js/ingrediente.js@@

**Entiende**
- La función devuelve el estado inicial de **una** fila. Alpine la llama una vez por cada fila, así que cada ingrediente tiene su propio `tengo` y su propio `estado`.
- `estado` recorre el mismo camino del mockup: `inactivo → cargando → listo` o `error`. La vista muestra una cosa distinta según su valor.
- La IA responde «Sin zapallo → zanahoria rallada». `split('→')` lo separa para pintar la primera parte en gris y la segunda en negrita. Si la respuesta no trae flecha, se muestra completa.
- `try / catch`: cualquier error (sin internet, 503, 429) termina en `estado = 'error'`.

Registra el componente en Alpine. **Reemplaza todo:**

@@provisional app_dia2.js resources/js/app.js@@

## Paso 3 · «Cargando» y «error» de la IA

Dos componentes que usan la fila de ingrediente y el chat. **Crea:**

@@archivo resources/views/components/ia-loading.blade.php@@

@@archivo resources/views/components/ia-error.blade.php@@

**Entiende**
- `@props(['label'])` declara lo que recibe el componente: `<x-ia-loading label="Buscando…" />`.
- `{{ $attributes }}` son todos los atributos que **no** están en `@props`. En `ia-error` van a parar al botón: `<x-ia-error x-on:click="pedir()" />` deja el `x-on:click` en «Reintentar». Así el mismo componente sirve para reintentar cosas distintas.
- `role="status"` y `role="alert"` hacen que un lector de pantalla anuncie el cambio.

## Paso 4 · La fila de ingrediente

**Crea:**

@@parte resources/views/components/ingredient-row.blade.php 1@@

**Entiende**
- `x-data="ingrediente(...)"` conecta este `<li>` con el objeto del paso 2. `@js(...)` convierte un valor de PHP (la URL de la ruta) en un valor de JavaScript seguro.
- `route('recetas.alternativas', [$recipe, $ingredient])` arma `/recetas/charquican/ingredientes/3/alternativas`.
- `x-on:click="tengo = ! tengo"` cambia el dato. `:class="{ 'clases': condición }"` pone o quita clases según el dato. La clase por defecto también está en `class="..."`, para que la fila se vea bien antes de que Alpine cargue.
- `x-show` muestra u oculta. `x-cloak` evita que lo que parte oculto se alcance a ver mientras Alpine carga.

@@parte resources/views/components/ingredient-row.blade.php 2@@

**Entiende**
- Son los tres estados del mockup, uno por `x-show`: cargando, error y listo.
- `<template x-for="...">` repite el `<li>` por cada alternativa. `x-text` escribe el texto escapado: aunque la IA respondiera con HTML, se vería como texto y no se ejecutaría.
- El aviso «Revisa alérgenos antes de reemplazar» responde a uno de los riesgos de la propuesta.

Para mirarla antes de que exista el detalle, crea `resources/views/muestra.blade.php` (no se sube) y abre `http://localhost:8000/muestra`:

```blade
@extends('layouts.app')

@section('titulo', 'Muestra')

@section('contenido')
    @php $recipe = App\Models\Recipe::with('ingredients')->first(); @endphp
    <ul class="max-w-xl rounded-[28px] bg-white p-6 shadow-soft">
        @foreach ($recipe->ingredients as $ingredient)
            <x-ingredient-row :ingredient="$ingredient" :recipe="$recipe" />
        @endforeach
    </ul>
@endsection
```

- [ ] La casilla marca y tacha el ingrediente.
- [ ] «No lo tengo» muestra los puntos saltando y luego **el error con «Reintentar»** (es lo esperado: la ruta todavía es la provisional y responde 503).

```bash
npm run build && php artisan test
git add . && git commit -m "Fila de ingrediente con sus estados de IA"
git push -u origin sebastian/dia2-ingrediente
```

Integra el PR y avisa: **📣 «Emma: `<x-ingredient-row :ingredient :recipe />` está en main»**.

---

## Tarde: la IA de verdad

Rama nueva: `git switch main && git pull && git switch -c sebastian/dia2-asistente`.

## Paso 5 · Las preguntas a la IA

Abre `app/Services/AsistenteIA.php`. **Borra la `}` final** y sigue escribiendo debajo:

@@parte app/Services/AsistenteIA.php 3-5@@

**Entiende**
- `conversar()` arma la lista de mensajes: primero el de sistema (las reglas, el nombre de la persona y, si hay, la receta) y después el historial. `...$historial` «desarma» el arreglo dentro del otro.
- La IA no tiene memoria: en cada pregunta le enviamos la conversación de nuevo. Por eso el controlador solo manda los últimos mensajes.
- `alternativas()` pide un formato exacto («SOLO 2 líneas», «Sin X → Y») para poder convertir la respuesta en una lista. `preg_split('/\R/u', ...)` corta por saltos de línea y `preg_replace` quita viñetas o números si el modelo los pone igual.
- Si después de limpiar no queda nada, se trata como una falla: es mejor mostrar «Reintentar» que una lista vacía.
- `describir()` convierte la receta en texto. Darle la receta completa al modelo evita que invente ingredientes o pasos.

## Paso 6 · Controlador de «No lo tengo»

**Crea:**

@@archivo app/Http/Controllers/IngredientAlternativeController.php@@

**Entiende**
- Un controlador con un solo método `__invoke` es un controlador de una sola acción: en la ruta se escribe solo la clase.
- `Recipe $recipe, Ingredient $ingredient`: Laravel busca los dos modelos a partir de la URL. `AsistenteIA $ia`: Laravel crea el servicio y lo entrega (inyección de dependencias).
- `Cache::remember(clave, tiempo, función)`: si ya hay una respuesta guardada para ese ingrediente, la devuelve sin llamar a la IA. Si no, ejecuta la función y guarda el resultado por una semana. Cuando la función lanza una excepción, no se guarda nada.
- Con la IA caída responde **503** con un JSON. `ia.js` ve que la respuesta no es `ok` y la fila muestra «Reintentar».

## Paso 7 · Controlador del asistente

**Crea:**

@@archivo app/Http/Controllers/AssistantController.php@@

**Entiende**
- La conversación se guarda en la **sesión** (`session(...)`): dura mientras la persona tenga la sesión abierta y no necesita una tabla.
- `show`: si se llega con `?receta=charquican` y es una receta distinta de la anterior, empieza una conversación nueva sobre esa receta. `??=` asigna solo si la variable es `null`.
- `send`: valida el mensaje (obligatorio, máximo 500 caracteres) y envía a la IA los últimos 10 mensajes. La respuesta se guarda **solo si la IA respondió**: por eso «Reintentar» puede enviar la misma pregunta sin que quede repetida.
- `first_name` es el atributo que Miguel agregó a `User` («Camila Rojas» → «Camila»).
- `reset` borra los mensajes y conserva la receta, igual que «Nueva conversación» en el mockup.

## Paso 8 · Rutas definitivas de la IA

**Reemplaza todo:**

@@archivo routes/ia.php@@

**Entiende**
- `throttle:20,1`: máximo 20 peticiones por minuto por persona. A la número 21, Laravel responde 429 sin llamar a la IA. Protege el límite del plan gratuito de Groq, que es por minuto y por día para toda la clave.
- `scopeBindings()`: el ingrediente tiene que pertenecer a la receta de la URL. `/recetas/charquican/ingredientes/999/alternativas` con un ingrediente de otra receta responde 404.
- Misma URL `/asistente` con tres verbos: `GET` muestra, `POST` envía un mensaje y `DELETE` borra la conversación.

## Paso 9 · El estado del chat

En el mockup es `Assistant.tsx` (`msgs`, `text`, `send`, `retry`). **Crea:**

@@parte resources/js/chat.js 1@@

@@parte resources/js/chat.js 2@@

**Entiende**
- `mensajes` parte con lo que había en la sesión (por si se recarga la página). El servidor los guarda como `{role, content}`; aquí se convierten a `{id, de, texto, estado}`.
- `get ocupado()` es un dato calculado: es verdadero mientras alguna burbuja esté `cargando`. La vista lo usa para desactivar el botón y las sugerencias.
- `enviar()` agrega **dos** burbujas de inmediato (la pregunta y una respuesta «pensando») y después pide la respuesta. Así la pantalla reacciona al instante, como en el mockup.
- La burbuja de la IA guarda la `pregunta` que la originó. «Reintentar» llama a `responder(id)` y vuelve a enviar esa misma pregunta.
- `caida` cambia el texto de arriba a «Sin conexión con la IA».
- `$refs.fin` es el elemento marcado con `x-ref="fin"`, al final de los mensajes. `$nextTick` espera a que Alpine termine de dibujar antes de bajar.

Agrega el chat a Alpine. **Reemplaza todo** (versión definitiva):

@@archivo resources/js/app.js@@

## Paso 10 · La pantalla del asistente

En el mockup: `screens/Assistant.tsx`. **Crea:**

@@parte resources/views/asistente/show.blade.php 1@@

**Entiende**
- `@extends('layouts.app')` usa el layout de Emma (barra de navegación, contenido y avisos), y `@section('titulo', 'Asistente')` llena el título de la pestaña.
- `@if ($recipe)`: la tarjeta «Hablando sobre» solo aparece si se llegó desde una receta. `<x-recipe-photo>` es de Emma (lo escribe hoy en la mañana): si a esta hora no está en `main`, usa por mientras `<img src="{{ $recipe->image }}" alt="" class="aspect-[16/10] w-full object-cover">`.

@@parte resources/views/asistente/show.blade.php 2@@

**Entiende**
- `x-data="chat({...})"` entrega al componente la URL y los mensajes guardados. Todo lo que está dentro de esta `<section>` puede usar `mensajes`, `texto`, `caida`, `enviar()`…
- El texto «En línea» está escrito dos veces: dentro de la etiqueta (lo que se ve antes de que Alpine cargue) y en `x-text` (lo que Alpine pone después).
- «Nueva conversación» es un formulario. HTML solo sabe enviar `GET` y `POST`: `@method('DELETE')` agrega un campo oculto para que Laravel lo trate como `DELETE`.

@@parte resources/views/asistente/show.blade.php 3@@

**Entiende**
- El saludo lo escribe Blade (en el servidor) y no va a la IA. Los demás mensajes los dibuja Alpine con `x-for`.
- Cada mensaje es de la persona o de la IA: se dibujan las dos burbujas y `x-show` deja visible solo la que corresponde.
- La burbuja de la IA tiene tres caras según `m.estado`: `cargando` (puntos), `error` (con «Reintentar», que llama a `responder(m.id)`) o la respuesta.
- `whitespace-pre-line` respeta los saltos de línea que traiga la respuesta.

@@parte resources/views/asistente/show.blade.php 4@@

**Entiende**
- Las sugerencias vienen del controlador (`$sugerencias`) y cambian según haya receta o no. `@js($sugerencia)` pasa el texto a JavaScript con sus comillas bien escapadas.
- `x-on:submit.prevent="enviar()"`: al enviar el formulario (Enter o el botón), se ejecuta `enviar()` y `.prevent` evita que la página se recargue.
- `x-model="texto"` une el campo con el dato: lo que se escribe queda en `texto`, y cuando `enviar()` lo vacía, el campo se vacía.
- `:disabled="! texto.trim() || ocupado"`: no se puede enviar un mensaje vacío ni dos a la vez.

## Paso 11 · Pruebas

Necesitas las factories de Miguel (`Recipe::factory()`), que llegan a las 10:00.

Abre `tests/Feature/AsistenteIATest.php`. **Borra la `}` final** y sigue debajo:

@@parte tests/Feature/AsistenteIATest.php 2@@

Y las pruebas de las rutas. **Crea:**

@@archivo tests/Feature/AssistantTest.php@@

```bash
php artisan test     # 36 pruebas en verde si ya está integrado todo lo de hoy
```

**Entiende**
- `actingAs($user)` hace la petición como si esa persona hubiera iniciado sesión. `postJson` envía JSON, igual que nuestro `fetch`.
- `test_no_lo_tengo_devuelve_alternativas_y_las_guarda_en_cache` llama dos veces a la ruta y comprueba que a la IA se le preguntó **una sola vez**.
- `test_si_la_ia_no_responde_devuelve_503_y_no_guarda_nada` es la base de «Reintentar»: la pregunta fallida no queda en la conversación.
- `test_un_ingrediente_de_otra_receta_responde_404` prueba `scopeBindings()`.

## Paso 12 · Prueba con la IA real

Con la clave de Groq en tu `.env`, `php artisan serve` y `npm run dev`:

- [ ] `php artisan ia:probar` responde con Groq.
- [ ] En `/recetas/charquican`, «No lo tengo» en el zapallo muestra 2 reemplazos con sentido. Al cerrar y volver a pedir, responde al instante (caché).
- [ ] En `/asistente?receta=charquican`: el saludo nombra el charquicán; una sugerencia y una pregunta escrita reciben respuestas cortas y en español.
- [ ] Pregunta algo que no sea de cocina («¿quién ganó el mundial?»): debe negarse.
- [ ] Recarga la página: la conversación sigue. «Nueva conversación» la borra.
- [ ] **Estado de error:** borra `GROQ_API_KEY` de `.env` y repite. Deben verse «El asistente no está disponible», «Reintentar» y «Sin conexión con la IA». Vuelve a poner la clave.
- [ ] Guarda dos capturas de respuestas reales en `docs/evidencia/` (una de alternativas y una del chat).

Si las respuestas salen largas, con Markdown o en otro formato, ajusta el texto de `REGLAS` o el de `alternativas()` y vuelve a probar: es normal tener que afinarlo. Para repetir una pregunta de alternativas que quedó guardada: `php artisan cache:clear`.

## Cierre del día

```bash
vendor/bin/pint && npm run build && php artisan test
git add . && git commit -m "Alternativas con IA y asistente"
git push -u origin sebastian/dia2-asistente
```

- [ ] PR integrado, junto con los de Emma (catálogo y detalle) y Miguel (guardar, cocinada, perfil y textos en español).
- [ ] En `main`: `php artisan migrate:fresh --seed`, entrar como `camila.rojas@uni.cl` / `password` y recorrer todo.
- [ ] Check-in de las 21:00: lista de lo que falta para mañana.
