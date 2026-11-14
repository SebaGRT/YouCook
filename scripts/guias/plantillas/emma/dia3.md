# Emma · Día 3 (domingo 15): Guardados, Perfil y revisión visual

**Meta del día:** las dos últimas pantallas del mockup y la revisión visual de toda la app, en escritorio y en teléfono. Todo en `main` antes de las 18:00.

← [Día 2](dia2.md) · [Índice de guías](../README.md)

## Mapa del día

| Paso | Qué haces | Archivo | Depende de |
|---|---|---|---|
| 1 | Guardados | `guardados/index.blade.php` | Controlador de Miguel (sábado) |
| 2 | Perfil | `perfil/show.blade.php` | Controlador de Miguel (sábado) · **PR antes de las 13:00** |
| 3 | Revisión visual contra el mockup | `docs/evidencia/qa-visual.md` | Todo integrado |
| 4 | Capturas | `docs/evidencia/capturas/` | Paso 3 |
| 5 | (Opcional) Stickers nuevos | `components/stickers/*.blade.php` | — |
| 6 | Preparar tu parte de la presentación | — | — |

**Tiempo:** unas 3 h (~210 líneas de código y la revisión).

Rama: `git switch main && git pull && composer install && npm install && php artisan migrate:fresh --seed && git switch -c emma/dia3-perfil`.

Igual que ayer, las dos vistas ya existen en versión **provisional** de Miguel: míralas para ver las variables y reemplázalas.

---

## Paso 1 · Guardados

En el mockup: `screens/Saved.tsx`. **Reemplaza todo:**

@@archivo resources/views/guardados/index.blade.php@@

**Entiende**
- `trans_choice('{1} … |[2,*] …', $n)` elige el texto según el número: «1 receta» o «3 recetas».
- `<x-recipe-card … saved />`: un atributo sin valor vale `true`. Aquí todas las tarjetas están guardadas.
- El estado vacío es el del mockup: dos stickers superpuestos (con `absolute`) y el botón «Explorar recetas».
- Para verlo vacío, quita los tres guardados de Camila o registra una cuenta nueva.

## Paso 2 · Perfil

En el mockup: `screens/Profile.tsx`. **Reemplaza todo:**

@@parte resources/views/perfil/show.blade.php 1@@

**Entiende**
- El `@php` de arriba calcula lo que se usa varias veces: el porcentaje, cuántas faltan para la insignia y los giros de los stickers (los mismos números del mockup).
- `$level`, `$cooked`, `$saved` y `$total` vienen del controlador de Miguel.
- El `@foreach` recorre un arreglo `['Cocinadas' => 7, ...]`: `$nombre` es la clave y `$valor`, el número. Cada sticker se gana al cocinar una receta: por eso «Stickers» es igual a «Cocinadas».

@@parte resources/views/perfil/show.blade.php 2@@

**Entiende**
- En el mockup, «Editar» no hacía nada. Aquí sí: `editando` (Alpine) cambia entre ver los datos como texto y editarlos en campos. `x-show` muestra uno u otro.
- `x-data="{ editando: @js($errors->any()) }"`: si el formulario volvió con errores, parte abierto en modo edición para que se vean.
- `@method('PATCH')`: HTML solo envía `GET` y `POST`; este campo oculto le dice a Laravel que es una actualización (`PATCH /perfil`).
- `old('name', $user->name)`: lo que la persona escribió si hubo un error; si no, el valor guardado.
- «Cerrar sesión» envía **otro** formulario (`form="salir"`), porque un formulario no puede ir dentro de otro.
- `translatedFormat('F Y')` escribe la fecha en español: «Noviembre 2026».

@@parte resources/views/perfil/show.blade.php 3@@

**Entiende**
- La barra de progreso es un `<div>` dentro de otro: el de adentro mide `width: 35%`.
- `role="progressbar"` con `aria-valuenow` describe la barra a los lectores de pantalla.
- `"Te faltan {$faltan} para…"`: dentro de comillas dobles, PHP reemplaza `{$variable}` por su valor.

@@parte resources/views/perfil/show.blade.php 4-5@@

**Entiende**
- Los stickers recorren las 20 recetas: si está cocinada (`$ganado`), se dibuja su sticker con su nombre; si no, el «?» bloqueado y «???».
- `$loop->index` es el número de vuelta partiendo en 0. Se usa para el giro de cada sticker y para el retraso de la animación `pop` (aparecen de a uno).
- El fondo de puntitos es `bg-[radial-gradient(...)]` con `[background-size:18px_18px]`: valores a la medida, copiados del mockup.
- En el checklist, todas las recetas enlazan a su detalle (en el mockup solo 8 existían de verdad).

Revisa con la cuenta de Camila y sube el PR **antes de las 13:00**:

- [ ] «7 de 20 recetas cocinadas», 35 %, «Te faltan 3…», 7 stickers con nombre y 13 bloqueados.
- [ ] «Editar» abre los campos; guardar muestra el aviso «Datos actualizados». Un correo inválido muestra el error con el formulario abierto.
- [ ] «Cerrar sesión» lleva a `/login`.
- [ ] `/guardados` con 3 recetas y, con una cuenta nueva, el estado vacío.

```bash
npm run build && php artisan test
git add . && git commit -m "Guardados y Perfil del mockup"
git push -u origin emma/dia3-perfil
```

## Paso 3 · Revisión visual contra el mockup

Con todo integrado (`git switch main && git pull && php artisan migrate:fresh --seed`), compara **pantalla por pantalla** con el mockup y con las capturas de [`objetivo/`](../objetivo/). Hazlo en dos anchos: escritorio (1440 px) y teléfono (390 px; en el navegador, `F12` → ícono de teléfono).

Anota en `docs/evidencia/qa-visual.md` una tabla con: pantalla, ancho, ¿igual al mockup?, diferencia y si se corrigió.

| Pantalla | Qué mirar |
|---|---|
| Registro e ingreso | Foto y stickers a la izquierda (solo escritorio); errores bajo cada campo |
| Catálogo | Stickers del buscador (solo escritorio), categorías con scroll horizontal en teléfono, 4 / 2 / 1 columnas |
| Detalle | Botón de guardar sobre la foto (solo teléfono), datos en 2 o 4 columnas, panel de la IA en sus 3 estados |
| Asistente | La columna izquierda desaparece en teléfono; el chat ocupa el alto de la pantalla; las sugerencias se deslizan |
| Guardados | Con recetas y vacío |
| Perfil | Stickers en 3, 5 o 10 columnas; checklist en 1 o 2 columnas |
| Todas | La barra de abajo no tapa contenido; el aviso (toast) queda sobre la barra en teléfono |

Además:

- [ ] Recorre la app solo con el teclado (`Tab` y `Enter`): todo lo que se puede tocar muestra el borde de foco.
- [ ] Ningún texto se sale de su caja con un nombre largo (regístrate como «María Fernanda de los Ángeles Valenzuela»).

Las diferencias **intencionales** ya están en el [índice](../README.md#qué-cambia-respecto-del-mockup): no las anotes como errores. Corrige lo pequeño tú misma (son clases de Tailwind); si el problema está en un archivo de otro, avísale. **Todo antes de las 18:00.**

## Paso 4 · Capturas

Guarda en `docs/evidencia/capturas/` una captura de cada pantalla en escritorio y en teléfono (14 en total), más tres de estados: alternativas de la IA, aviso de receta cocinada y guardados vacío. Van al README y a la presentación.

## Paso 5 · (Opcional) Stickers nuevos

Solo si sobra tiempo. Nueve recetas comparten el sticker `estrella`. Para darle uno propio a una receta:

1. Copia `components/stickers/tomate.blade.php` con otro nombre, por ejemplo `cazuela.blade.php`, y cambia las formas (el lienzo mide 100 × 100).
2. En `database/data/recetas.php`, cambia `'sticker' => 'estrella'` por `'sticker' => 'cazuela'` en esa receta (el archivo es de Miguel: avísale).
3. `php artisan migrate:fresh --seed` y mira el Perfil.

En la propuesta ya dejamos anotado el riesgo de no alcanzar: la estrella es un resultado válido.

## Paso 6 · Preparar tu parte de la presentación

Tienes que poder explicar en 2 minutos, mostrando el código:

- [ ] Cómo se pasó el mockup de React a Blade: un componente de React = un componente Blade (`<RecipeCard r={r} />` → `<x-recipe-card :recipe="$recipe" />`), con las mismas clases de Tailwind.
- [ ] Qué es un layout y cómo lo usan las páginas: `@extends`, `@section` y `@yield` (`layouts/app.blade.php`).
- [ ] Dónde hay Alpine y por qué (el ojo de la contraseña, los pasos, «Editar») y dónde hay un formulario (guardar, cocinada), y la diferencia entre ambos.
- [ ] Cómo se adapta la app al teléfono (`md:hidden`, `lg:grid-cols-4`, las dos barras).
- [ ] Las diferencias declaradas con el mockup.

## Cierre del día

- [ ] `qa-visual.md` y las capturas en `main` antes de las 18:00.
- [ ] Ensayo general a las 21:00.
