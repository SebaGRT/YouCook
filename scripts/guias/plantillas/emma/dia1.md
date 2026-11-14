# Emma · Día 1 (viernes 13): íconos, stickers, ingreso y barra de navegación

**Meta del día:** las piezas visuales que usa toda la app (íconos, logo y stickers), las pantallas de ingreso y registro del mockup y el layout con la barra de navegación.

← [Índice de guías](../README.md) · Siguiente: [Día 2](dia2.md)

## Mapa del día

| Paso | Qué haces | Archivo | Depende de |
|---|---|---|---|
| 0 | Clonar y dejar el proyecto andando | (comandos) | Esqueleto de Sebastián (~10:30) |
| 1 | Íconos | `components/icon.blade.php` | — |
| 2 | Logo | `components/logo.blade.php` | — |
| 3 | Stickers: la carita y los 12 dibujos | `components/sticker-face.blade.php`, `components/stickers/*.blade.php` | — |
| 4 | El sticker con borde blanco | `components/sticker.blade.php` | — |
| 5 | Mirar íconos y stickers | `resources/views/muestra.blade.php` (no se sube) | — |
| 6 | Layout de ingreso | `layouts/guest.blade.php` | — |
| 7 | Botón, campos y errores con nuestro estilo | 4 componentes chicos | — |
| 8 | Campo con ícono | `components/auth-field.blade.php` | — |
| 9 | Ingreso y registro | `auth/login.blade.php`, `auth/registro.blade.php` | **PR 1** |
| 10 | Avatar | `components/avatar.blade.php` | Modelos de Miguel (13:00) |
| 11 | Barra de navegación | `components/nav.blade.php` | |
| 12 | Aviso (toast) | `components/toast.blade.php` | |
| 13 | Layout de la app | `layouts/app.blade.php` | Modelos de Miguel (13:00) |
| 14 | «En construcción» con el layout | `pendiente.blade.php` | **PR 2** |

Todas las rutas de vistas parten de `web/resources/views/`.

**Tiempo:** unas 3 h 30 (~560 líneas; los dibujos de los stickers se pueden copiar). Antes de empezar, lee [cómo leer los bloques de código](../README.md#cómo-leer-los-bloques-de-código) y [cómo pasar del mockup a Blade](../README.md#del-mockup-react-a-blade).

Mientras esperas el esqueleto, abre el mockup (`mockup/Mockup de YouCook/src/components/`) y lee `Icons.tsx`, `Stickers.tsx` y `Nav.tsx`: es lo que vas a traducir hoy.

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
git switch -c emma/dia1-ingreso
```

Para trabajar, **dos terminales** en `web/`: `php artisan serve` (el sitio, en `http://localhost:8000`) y `npm run dev` (recompila los estilos al guardar). Regístrate en `/registro` para tener una cuenta.

## Paso 1 · Íconos

En el mockup cada ícono es una función de React (`Icons.tsx`). Aquí es **un** componente que recibe el nombre. **Crea:**

@@archivo resources/views/components/icon.blade.php@@

**Entiende**
- Un archivo en `resources/views/components/` es un componente: `icon.blade.php` se usa como `<x-icon name="clock" size="15" />`.
- `@props([...])` declara lo que recibe y sus valores por defecto. Lo que **no** está en `@props` (por ejemplo `class="text-tomato-500"`) llega en `$attributes` y se pega en el `<svg>`.
- `{!! ... !!}` imprime sin escapar. Se puede porque el contenido es nuestro (los trazos del SVG). Para cualquier texto que venga de una persona o de la IA se usa siempre `{{ }}`.
- `stroke="currentColor"`: el ícono toma el color del texto. Por eso se pinta con clases `text-…`.
- De React a HTML cambian los nombres de los atributos: `strokeWidth` → `stroke-width`, `strokeLinecap` → `stroke-linecap`, `className` → `class`.

## Paso 2 · Logo

**Crea:**

@@archivo resources/views/components/logo.blade.php@@

**Entiende**
- `$attributes->class('flex items-center gap-2')` une las clases del componente con las que le pasen: `<x-logo class="mb-10 lg:hidden" />`.
- `route('recetas.index')` escribe la URL de la ruta con ese nombre.

## Paso 3 · Stickers: la carita y los 12 dibujos

Todos los stickers comparten la misma carita. **Crea:**

@@archivo resources/views/components/sticker-face.blade.php@@

Cada dibujo es un archivo dentro de `components/stickers/`. **Los trazos (`d="…"`) son dibujo, no código: estos 12 archivos se pueden copiar y pegar.** Lo que tienes que entender es la estructura de uno: un grupo `<g>` con borde oscuro, las formas con su color y la carita encima.

@@archivo resources/views/components/stickers/tomate.blade.php@@

**Entiende**
- `<g stroke="#2E2B28" stroke-width="3">` le pone el borde oscuro a todas las formas de adentro.
- `<x-sticker-face y="60" />` dibuja la carita. `x`, `y` y `s` (tamaño) la mueven según el dibujo.
- Vienen de `Stickers.tsx`: son los mismos trazos, con los atributos escritos como HTML.

<details>
<summary>Los otros 11 dibujos (un archivo cada uno, en <code>components/stickers/</code>)</summary>

@@archivo resources/views/components/stickers/huevo.blade.php@@

@@archivo resources/views/components/stickers/huevo2.blade.php@@

@@archivo resources/views/components/stickers/tallarin.blade.php@@

@@archivo resources/views/components/stickers/panqueque.blade.php@@

@@archivo resources/views/components/stickers/palta.blade.php@@

@@archivo resources/views/components/stickers/pan.blade.php@@

@@archivo resources/views/components/stickers/olla.blade.php@@

@@archivo resources/views/components/stickers/olla2.blade.php@@

@@archivo resources/views/components/stickers/choclo.blade.php@@

@@archivo resources/views/components/stickers/zapallo.blade.php@@

@@archivo resources/views/components/stickers/estrella.blade.php@@

</details>

`estrella` no está en el mockup: es el sticker de las 9 recetas que todavía no tienen dibujo propio (en el mockup esas mostraban un «?» incluso desbloqueadas).

## Paso 4 · El sticker con borde blanco

**Crea:**

@@archivo resources/views/components/sticker.blade.php@@

**Entiende**
- `view()->exists(...)` pregunta si existe el archivo del dibujo. Si no existe (o si el sticker está bloqueado) se dibuja el círculo punteado con «?».
- `<x-dynamic-component :component="'stickers.'.$kind" />` incluye el componente cuyo nombre se arma en ese momento: `stickers.tomate`, `stickers.palta`…
- **Para agregar un sticker nuevo basta con crear su archivo** en `components/stickers/`: no hay que tocar nada más.
- El `<filter>` es el truco del borde blanco de «sticker recortado»: engorda la silueta 5 px, la pinta blanca y dibuja el original encima. Cada sticker necesita un `id` distinto para su filtro: por eso `Str::random(8)`.

## Paso 5 · Mirar íconos y stickers

Crea `resources/views/muestra.blade.php` (está en `.gitignore`: **no se sube**) y abre `http://localhost:8000/muestra`:

@@provisional muestra_emma_dia1.blade.php resources/views/muestra.blade.php@@

- [ ] Se ven los 24 íconos, el marcador relleno y el logo.
- [ ] Se ven los 12 stickers con borde blanco y carita, y dos «?» (el bloqueado y el que no existe).
- [ ] Compara con el mockup: en `mockup/Mockup de YouCook/` ejecuta `npm install && npm run dev` y ábrelo en el navegador, o usa el enlace de Figma.

## Paso 6 · Layout de ingreso

En el mockup, registro e ingreso son la misma pantalla (`screens/Auth.tsx`): una foto a la izquierda y el formulario a la derecha. Eso es un **layout**: el marco que comparten varias páginas. **Crea:**

@@parte resources/views/layouts/guest.blade.php 1@@

@@parte resources/views/layouts/guest.blade.php 2@@

**Entiende**
- Es un layout como el de la clase 9: el HTML que se repite, con huecos. `@yield('contenido')` es el hueco del formulario y `@yield('titulo')` el del título de la pestaña. Cada página dice `@extends('layouts.guest')` y llena los huecos con `@section`.
- `@vite(...)` carga nuestro CSS y JavaScript. `@fonts` carga Nunito e Inter. Las dos líneas van en el `<head>` de todo layout.
- `hidden lg:block`: oculto en teléfonos y visible desde 1024 px. Al revés, `lg:hidden` es «solo en pantallas chicas». Tailwind parte del teléfono y los prefijos (`sm:`, `md:`, `lg:`) agregan cambios para pantallas más grandes.
- `<x-logo />` y `<x-sticker />` son tus componentes de los pasos 2 y 4.
- La etiqueta `<meta name="csrf-token">` la usa el JavaScript de Sebastián.

## Paso 7 · Botón, campos y errores con nuestro estilo

Cuatro componentes chicos que usan el ingreso, el registro y (el domingo) el perfil. **Crea** cada uno:

@@archivo resources/views/components/primary-button.blade.php@@

@@archivo resources/views/components/text-input.blade.php@@

@@archivo resources/views/components/input-label.blade.php@@

@@archivo resources/views/components/input-error.blade.php@@

**Entiende**
- `$attributes->merge([...])` pone valores por defecto (`type="submit"`, las clases) que quien use el componente puede ampliar.
- `{{ $slot }}` es lo que va entre la etiqueta de apertura y la de cierre: en `<x-primary-button>Iniciar sesión</x-primary-button>`, el texto del botón.
- `input-error` recibe la lista de mensajes de un campo (`$errors->get('email')`) y dibuja uno por línea. Si no hay errores, no dibuja nada.
- `shadow-[inset_0_-4px_0_rgb(0_0_0/0.14)]` es una clase con un valor a la medida entre corchetes: la sombra interior que le da volumen al botón del mockup.

## Paso 8 · Campo con ícono

En el mockup es `Field` (dentro de `Auth.tsx`). **Crea:**

@@archivo resources/views/components/auth-field.blade.php@@

**Entiende**
- `x-data="{ ver: false }"` es **Alpine**: crea un dato para este campo. El botón del ojo lo cambia (`x-on:click="ver = ! ver"`) y el campo reacciona (`:type="ver ? 'text' : 'password'"`). Es lo que en React era `useState`.
- `old($name)` recupera lo que la persona escribió si el formulario volvió con errores (las contraseñas nunca se devuelven).
- `$errors->has($name)` y `$errors->get($name)`: los errores de validación que Laravel deja disponibles en todas las vistas. Si hay error, el borde queda rojo y el mensaje aparece abajo.
- `focus-within:` aplica el estilo al contenedor cuando el campo de adentro tiene el foco.

## Paso 9 · Ingreso y registro

**Reemplaza todo** en los dos (son las versiones provisionales de Sebastián):

@@archivo resources/views/auth/login.blade.php@@

@@archivo resources/views/auth/registro.blade.php@@

**Entiende**
- `@extends('layouts.guest')` usa tu layout del paso 6. `@section('titulo', 'Iniciar sesión')` llena el hueco del título en una línea; `@section('contenido') ... @endsection` llena el del formulario.
- `action="{{ route('login') }}"` y `route('registro')`: las rutas que Sebastián escribió en `routes/auth.php`. El controlador que los recibe (`AuthController`) ya existe.
- `@csrf` es obligatorio en todo formulario `POST`: sin él, Laravel responde 419.
- Los `name` de los campos (`name`, `email`, `password`, `remember`) son los que lee `AuthController`: no se pueden cambiar.
- `autofocus` y `autocomplete="…"` no están en `@props` de `auth-field`, así que llegan al `<input>` por `$attributes`.
- El mockup tenía «¿Olvidaste tu contraseña?». No lo ponemos: recuperar la contraseña necesita enviar correos, y eso no está en el alcance.

Revisa en el navegador (cierra sesión primero) y sube el **PR 1**:

- [ ] `/login` y `/registro` se ven como el mockup, también en ancho de teléfono (ahí desaparece la foto y aparece el logo).
- [ ] El ojo muestra y oculta la contraseña.
- [ ] Una contraseña incorrecta muestra el error bajo el correo, y el correo escrito se conserva.

```bash
php artisan test
git add . && git commit -m "Íconos, stickers, ingreso y registro"
git push -u origin emma/dia1-ingreso
```

---

## Tarde: la barra de navegación

Necesitas en `main` el PR 1 de Miguel (los modelos, ~13:00): el layout cuenta las recetas guardadas y cocinadas con sus relaciones. Rama nueva:

```bash
git switch main && git pull && php artisan migrate
git switch -c emma/dia1-layout
```

## Paso 10 · Avatar

**Crea:**

@@archivo resources/views/components/avatar.blade.php@@

**Entiende**
- `auth()->user()` es la persona que inició sesión. `initial` es un atributo que Miguel agregó al modelo `User` («Camila Rojas» → «C»).
- `style="…"` se usa aquí porque el tamaño es un número cualquiera que llega por `size`; Tailwind solo sirve para clases escritas de antemano.

## Paso 11 · Barra de navegación

En el mockup: `Nav.tsx`. Arriba, la barra con el logo, los tres enlaces y el perfil; abajo, solo en el teléfono, la barra de cuatro íconos. **Crea:**

@@parte resources/views/components/nav.blade.php 1@@

**Entiende**
- `$links` es la lista de enlaces, igual que `const links` del mockup.
- `request()->routeIs('recetas.*')` es verdadero en cualquier ruta cuyo nombre empiece con `recetas.` (catálogo y detalle). Con eso se marca el enlace activo.
- `{{ $activo ? 'clases A' : 'clases B' }}` elige clases según una condición. Es lo que en React se escribía con `${ ... ? ... : ... }`.
- `$saved`, `$cooked` y `$total` llegan desde el layout, que los cuenta con los modelos de Miguel.

@@parte resources/views/components/nav.blade.php 2@@

**Entiende**
- `md:hidden`: la barra de abajo solo existe en pantallas chicas. La lista de arriba es `hidden md:flex`: lo contrario.
- `[...$links, [...]]` copia la lista y le agrega «Perfil» al final.
- `pb-[env(safe-area-inset-bottom)]` deja espacio para la barra de gestos de los teléfonos.

## Paso 12 · Aviso (toast)

En el mockup: `Toast` en `App.tsx`. **Crea:**

@@archivo resources/views/components/toast.blade.php@@

**Entiende**
- Recibe un arreglo con `title` y, si los hay, `body` y `sticker`. Lo arma el controlador de Miguel cuando se marca una receta como cocinada.
- `x-init` se ejecuta al aparecer: programa que `visible` pase a `false` a los 3,8 segundos (el mismo tiempo del mockup). `x-show` lo oculta y `x-transition.opacity` lo desvanece.
- `@isset($toast['sticker'])`: el sticker se dibuja solo si viene.

## Paso 13 · Layout de la app

**Crea:**

@@archivo resources/views/layouts/app.blade.php@@

**Entiende**
- Es el marco de todas las páginas con sesión: barra, contenido (`@yield('contenido')`) y aviso. Cada pantalla empieza con `@extends('layouts.app')`, igual que el layout de la clase 9.
- `auth()->user()` es la persona con la sesión iniciada. `auth()->user()->savedRecipes()->count()` cuenta sus recetas guardadas con la relación que Miguel escribió en `User`: son tres consultas `count()` por página, y para una app de este tamaño está bien.
- `:user="..."` (con dos puntos) pasa una **variable de PHP** al componente. Sin los dos puntos (`kind="tomate"`) se pasa un texto.
- `pt-[96px]` deja libre el alto de la barra de arriba, que es fija; `pb-28` hace lo mismo con la de abajo en el teléfono.
- `session('toast')` lee el aviso que el controlador dejó para **esta** carga de página. Al recargar, ya no está.

## Paso 14 · «En construcción» con el layout

Para ver tu barra en todas las rutas provisionales, **reemplaza todo:**

@@provisional pendiente_dia1_layout.blade.php resources/views/pendiente.blade.php@@

- [ ] Con sesión iniciada, cualquier página muestra la barra: logo, tres enlaces (con «IA» en Asistente) y tu nombre corto con «0 de 20 recetas» (o «0 de 0» si los seeders de Miguel aún no están integrados).
- [ ] El enlace de la página actual queda marcado.
- [ ] En ancho de teléfono desaparecen los enlaces de arriba y aparece la barra de abajo.

## Cierre del día

```bash
npm run build && php artisan test
git add -A && git commit -m "Barra de navegación, aviso y layout"
git push -u origin emma/dia1-layout
```

- [ ] Dos PR abiertos (ingreso y layout). Sebastián los integra.
- [ ] Check-in de las 21:00.
