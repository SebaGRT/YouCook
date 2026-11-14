# Sebastián · Día 1 (viernes 13): el proyecto desde cero y la conexión con Groq

**Meta del día:** que exista el proyecto Laravel en `main` antes de las 10:30, con el tema del mockup, las cuentas (registro, ingreso y salida, escritos a mano como en la clase 11) y todas las rutas (provisionales), para que Emma y Miguel puedan trabajar en paralelo. En la tarde, la primera respuesta real de Groq.

← [Índice de guías](../README.md) · Siguiente: [Día 2](dia2.md)

## Mapa del día

| Paso | Qué haces | Archivo | Entrega |
|---|---|---|---|
| 1 | Crear el proyecto Laravel | (comandos) | |
| 2 | Instalar Alpine.js | `resources/js/app.js` | |
| 3 | El tema del mockup | `resources/css/app.css`, `vite.config.js` | |
| 4 | Sacar lo que no usamos | (comandos) | |
| 5 | Nombre, idioma, zona horaria y favicon | `.env`, `config/app.php` | |
| 6 | Las cuentas: registro, ingreso, salida y sus pruebas | `AuthController.php`, `routes/auth.php`, `auth/login`, `auth/registro`, `tests/Feature/Auth/*` | |
| 7 | Rutas provisionales y pantalla «En construcción» | `routes/web.php`, `routes/ia.php`, `pendiente.blade.php` | |
| 8 | Verificar y subir | (comandos) | **Esqueleto en `main` (~10:30)** |
| 9 | La clave y la configuración de Groq | `.env`, `config/services.php` | |
| 10 | El servicio que habla con Groq | `app/Services/AsistenteIA.php` | |
| 11 | Comando para probar la conexión | `routes/console.php` | |
| 12 | Pruebas del servicio (sin gastar cuota) | `tests/Feature/AsistenteIATest.php` | **Servicio de IA en PR (fin del día)** |

**Tiempo:** unas 3 h 30 (2 h el esqueleto, 1 h 30 la IA). Lo que más importa es el paso 8: hasta que no subas el esqueleto, nadie más puede empezar.

Antes de partir, lee [cómo leer los bloques de código](../README.md#cómo-leer-los-bloques-de-código).

---

## Paso 1 · Crear el proyecto

Desde la raíz del repo (la carpeta que tiene `docs/` y `mockup/`):

```bash
laravel new web --no-authentication --database=sqlite --phpunit --npm --no-boost -n
cd web
rm AGENTS.md CLAUDE.md
```

- `-n` (`--no-interaction`): no hace preguntas y usa lo que dicen las demás opciones. Así todos obtienen exactamente el mismo proyecto.
- **Si lo corriste sin `-n`** y el instalador pregunta, responde:
  - *Would you like to update now?* (versión nueva del instalador) → **Not now**. Puedes actualizarlo después; no cambia el proyecto.
  - *Do you want to use a starter kit?* → **No**.
  - *Which frontend stack do you want to build on?* → **Blade** («No additional frontend scaffolding»): Laravel limpio, con vistas Blade y sin React, Vue, Svelte ni Livewire.
  - Cualquier otra pregunta (base de datos, pruebas, npm, Boost) → lo mismo que dicen las opciones del comando: **SQLite**, **PHPUnit**, **sí** a instalar con npm y **no** a Boost. Si pregunta por correr las migraciones, **sí**.
- `--no-authentication`: sin kit de inicio (*starter kit*: «None», como en la clase 9). El registro y el login los escribimos nosotros en el paso 6, igual que en la clase 11.
- `--database=sqlite`: la base de datos es un archivo (`database/database.sqlite`). No hay que instalar ni levantar un servidor de base de datos.
- `--phpunit --npm --no-boost`: pruebas con PHPUnit, dependencias con npm y sin Laravel Boost.
- `AGENTS.md` y `CLAUDE.md` son instrucciones genéricas para asistentes de IA (piden instalar Boost). No las usamos.

Si el comando `laravel` no existe: `composer global require laravel/installer`.

Todo lo que sigue se ejecuta **dentro de `web/`**, y todas las rutas de archivos de las guías parten de ahí.

## Paso 2 · Alpine.js

Lo interactivo de la app (el ojo de la contraseña, «No lo tengo», el chat, el aviso que desaparece solo) lo hacemos con **Alpine.js**, una librería chica de JavaScript que se escribe directo en el HTML. No la vimos en clases: cada guía explica cada atributo de Alpine la primera vez que aparece.

```bash
npm install -D alpinejs
```

**Reemplaza todo** (mañana le agregas dos componentes):

@@provisional app_dia1.js resources/js/app.js@@

**Entiende**
- `import Alpine from 'alpinejs'` trae la librería que acabas de instalar; `Alpine.start()` la pone a revisar la página.
- Desde ahí, cualquier etiqueta con `x-data="{ ver: false }"` tiene sus propios datos, y `x-show`, `x-on:click` o `:class` reaccionan a ellos. Es lo que en React del mockup era `useState`.
- Vite junta este archivo con todo lo que importa y lo carga en cada página con `@vite(...)`.

## Paso 3 · El tema del mockup

`laravel new` ya trae **Tailwind 4**, la misma versión del mockup: sus colores están definidos con `@theme` y usa clases como `h-13`, `size-9` o `bg-cream/85`. Así podemos copiar las clases del mockup tal cual.

En Tailwind 4 no hay archivo de configuración: todo va en el CSS. **Reemplaza todo** el archivo con el tema del mockup (viene de `mockup/Mockup de YouCook/src/index.css`):

@@parte resources/css/app.css 1@@

**Entiende**
- Cada variable de `@theme` crea clases. `--color-tomato-500` crea `bg-tomato-500`, `text-tomato-500`, `border-tomato-500`… `--font-display` crea `font-display` y `--shadow-soft` crea `shadow-soft`.
- Las dos líneas `@source` le dicen a Tailwind que también busque clases en las vistas de paginación de Laravel y en las vistas compiladas.
- Tailwind solo genera el CSS de las clases que encuentra escritas en los archivos. Por eso `npm run dev` tiene que estar corriendo mientras se trabaja.

@@parte resources/css/app.css 2-3@@

**Entiende**
- `@layer base` son los estilos por defecto de las etiquetas: fondo crema, letra Inter y títulos con Nunito. Al estar en una capa, cualquier clase de Tailwind les gana.
- `[x-cloak]` es de Alpine: lo que lleve ese atributo queda oculto hasta que Alpine termina de cargar. Así no se ve un parpadeo de cosas que debían partir escondidas.
- `@utility` crea clases propias: `dot` (puntos que saltan), `skeleton` (línea gris que brilla), `pop` (el sticker que aparece) y `rise` (entrar subiendo). Son las mismas animaciones del mockup.

Ahora Vite. **Reemplaza todo:**

@@archivo vite.config.js@@

**Entiende**
- Es el archivo que trae Laravel, con nuestras dos fuentes. `bunny(...)` descarga Nunito e Inter al compilar y las sirve desde nuestro propio sitio.
- Para que las fuentes carguen, cada página lleva `@fonts` en su `<head>`, junto a `@vite(...)`.
- `tailwindcss()` es el plugin de Tailwind 4: por eso no hace falta `tailwind.config.js`.

## Paso 4 · Sacar lo que no usamos

No tendremos la portada de Laravel (`/` lleva a las recetas) ni sus pruebas de ejemplo (la de `/` espera la portada y fallaría). Borra:

```bash
rm resources/views/welcome.blade.php
rm tests/Feature/ExampleTest.php tests/Unit/ExampleTest.php
```

## Paso 5 · Nombre, idioma, zona horaria y favicon

En **`.env`** y también en **`.env.example`**, cambia estas tres líneas:

```ini
APP_NAME=YouCook
APP_LOCALE=es
APP_FAKER_LOCALE=es_ES
```

En `config/app.php`, la zona horaria:

```php
'timezone' => 'America/Santiago',
```

El favicon es el del mockup, y `.gitignore` gana dos líneas al final:

```bash
cp "../mockup/Mockup de YouCook/public/favicon.png" public/favicon.png
printf '\n# Página de pruebas visuales de cada integrante\n/resources/views/muestra.blade.php\n' >> .gitignore
```

**Entiende**
- `.env` tiene la configuración de **tu** computador y nunca se sube (está en `.gitignore`). `.env.example` es la plantilla que sí se sube: de ahí Emma y Miguel copian su propio `.env`.
- `APP_LOCALE=es` hace que las fechas salgan en español («Noviembre 2026»). Los mensajes de validación en español los escribe Miguel el sábado.
- Con la zona horaria de Santiago, la hora que muestra el chat es la nuestra y no la de Londres.

## Paso 6 · Las cuentas: registro, ingreso y salida

Es el `AuthController` de la clase 11, con nuestras rutas. La tabla `users` y el modelo `User` ya vienen con Laravel (con el *cast* `'password' => 'hashed'`, que guarda la contraseña encriptada).

```bash
php artisan make:controller AuthController
```

**Reemplaza todo:**

@@archivo app/Http/Controllers/AuthController.php@@

**Entiende**
- `$request->validate([...])` valida antes de hacer nada: si una regla falla, Laravel vuelve al formulario con los errores y el resto del método no se ejecuta. Devuelve **solo** los campos validados, por eso `User::create($datos)` no puede recibir un campo colado desde el navegador.
- `unique:users`: el correo no puede estar ya en la tabla `users`. `min:8`: la contraseña tiene al menos 8 caracteres. El mockup pide la contraseña **una vez**, así que no usamos `confirmed` (que exigiría un segundo campo `password_confirmation`).
- `Auth::login($usuario)` deja la sesión iniciada; `$request->session()->regenerate()` cambia el identificador de la sesión al entrar, para que nadie pueda reusar uno anterior.
- `Auth::attempt($credenciales, $request->boolean('remember'))` busca el correo, compara la contraseña encriptada y, si coinciden, inicia la sesión. El segundo argumento es la casilla «Recordarme»: la sesión dura aunque se cierre el navegador.
- `->onlyInput('email')`: al volver con el error, se conserva el correo escrito, pero nunca la contraseña.
- `redirect()->intended(...)`: si alguien quiso abrir `/perfil` sin sesión, después de entrar vuelve a `/perfil`; si no, va a las recetas.
- `salir` cierra la sesión, la invalida y cambia el código de `@csrf`.

**Crea** el archivo de rutas de las cuentas (lo carga la última línea de `routes/web.php` del paso 7):

@@archivo routes/auth.php@@

**Entiende**
- `middleware('guest')`: solo quien **no** tiene sesión puede ver el registro y el login. Si ya entraste, Laravel te manda a las recetas.
- La ruta `POST` de cada formulario no necesita nombre: el formulario usa `route('registro')` y `route('login')`, que tienen la misma dirección.
- `throttle:6,1`: máximo 6 intentos de ingreso por minuto. Al séptimo, Laravel responde 429 sin revisar la contraseña.
- Salir es un `POST` (no un enlace) con `middleware('auth')`: un enlace en otro sitio no puede cerrarte la sesión.

Las vistas, por ahora **provisionales** (Emma las reemplaza hoy por las del mockup). **Crea** las dos:

@@provisional login_dia1.blade.php resources/views/auth/login.blade.php@@

@@provisional registro_dia1.blade.php resources/views/auth/registro.blade.php@@

**Entiende**
- `@error('email') ... @enderror` muestra el mensaje de error de ese campo (en `$message`) si la validación falló.
- `old('email')` recupera lo que la persona escribió.
- Los `name` de los campos (`name`, `email`, `password`, `remember`) son los que lee `AuthController`. Emma tiene que usar los mismos.

Por último, las pruebas de las cuentas. Además de cuidar el login, sirven para que `php artisan test` tenga algo que correr desde el primer commit: sin ninguna prueba, el comando termina con error y corta el `pint && build && test` que todos corren antes de cada commit. **Crea** la carpeta `tests/Feature/Auth/` y los dos archivos:

@@archivo tests/Feature/Auth/AuthenticationTest.php@@

@@archivo tests/Feature/Auth/RegistrationTest.php@@

**Entiende**
- Cada método `test_…` es una prueba. `$this->get(...)` y `$this->post(...)` hacen una petición como el navegador, sin levantar el servidor; `RefreshDatabase` deja la base vacía antes de cada una.
- `assertAuthenticated()` y `assertGuest()` preguntan si quedó una sesión iniciada o no.
- `test_after_login_goes_back_to_the_page_it_asked_for`: quien abre `/perfil` sin sesión va a `/login` y, después de entrar, vuelve a `/perfil` (es el `redirect()->intended(...)`). Hoy `/perfil` es provisional, y la prueba funciona igual.
- Las de registro revisan las dos reglas que más importan: contraseña de al menos 8 caracteres y correo que no se repite (`unique:users`).
- `User::factory()->create()` crea una persona con la contraseña `password` (viene en `database/factories/UserFactory.php`).

## Paso 7 · Rutas provisionales y pantalla «En construcción»

Todas las rutas de la app existen **desde hoy**, aunque todavía no hagan nada. Así, cuando Emma escriba `route('recetas.guardar', $recipe)` en una vista, la ruta ya existe y la página no falla. **Reemplaza todo:**

@@provisional web_dia1_esqueleto.php routes/web.php@@

**Entiende**
- `->name('recetas.index')` le pone nombre a la ruta. En las vistas se usa `route('recetas.index')` en vez de escribir la URL a mano: si mañana cambia la URL, no hay que tocar ninguna vista.
- `middleware('auth')`: todo lo que está dentro del grupo exige haber iniciado sesión. Si no, Laravel redirige a la ruta llamada `login`.
- `Route::view(url, vista)` muestra una vista sin pasar por un controlador. `fn () => back()` es una ruta que solo devuelve a la página anterior.
- `{recipe}` es un parámetro: `/recetas/charquican`. Cuando Miguel conecte el controlador, Laravel buscará sola la receta con ese slug.
- `/muestra` solo existe en tu computador (`isLocal()`): es para mirar componentes sueltos mientras se construyen.
- `require __DIR__.'/auth.php'` suma las rutas de las cuentas del paso 6.

**Crea** el archivo de rutas de la IA (tuyo; mañana lo reemplazas por el definitivo):

@@provisional ia_dia1_esqueleto.php routes/ia.php@@

Y **crea** la pantalla que muestran todas las rutas provisionales:

@@provisional pendiente_dia1.blade.php resources/views/pendiente.blade.php@@

**Entiende**
- `{{ ... }}` imprime un valor (escapando el HTML, por seguridad). `@foreach`, `@csrf` y las demás palabras con `@` son directivas de Blade.
- `@csrf` agrega un campo oculto con un código secreto. Laravel rechaza cualquier formulario `POST` que no lo traiga: así otro sitio no puede enviar formularios a nombre tuyo.
- Esta página lleva su propio `<head>` porque el layout todavía no existe. Emma la cambia esta tarde por una versión con `@extends('layouts.app')`.

## Paso 8 · Verificar y subir

```bash
php artisan migrate      # si pregunta por crear database.sqlite, responde «yes»
npm run build
php artisan test         # 9 pruebas en verde
```

Y mirar en el navegador. Igual que en la clase 9, con `composer run dev` se levantan juntos el servidor y Vite; si prefieres verlos por separado, usa **dos terminales** abiertas en `web/`:

```bash
php artisan serve        # terminal 1: el servidor, en http://localhost:8000
npm run dev              # terminal 2: Vite recompila el CSS y el JS al guardar
```

- [ ] `http://localhost:8000` redirige a `/login` (todavía con el formulario provisional).
- [ ] En `/registro` puedes crear una cuenta y llegas a «En construcción», con el fondo crema y la letra Nunito en el título.
- [ ] Una contraseña de 4 letras o un correo repetido muestran el error bajo el campo.
- [ ] Los cuatro enlaces, «Cerrar sesión» y volver a entrar en `/login` funcionan.

Sube a `main` y avisa en el chat: **«Esqueleto en main»**.

```bash
cd ..
git add web
git status               # NO deben aparecer web/vendor, web/node_modules ni web/.env
git commit -m "Esqueleto: Laravel + cuentas + Tailwind 4 + rutas provisionales"
git push
```

---

## Tarde: la conexión con la IA

Rama nueva: `git switch -c sebastian/dia1-ia`. Nada de esto depende de Emma ni de Miguel.

## Paso 9 · La clave y la configuración de Groq

Igual que en el traductor de la clase 9. Saca la clave (es gratis y no pide tarjeta) con la cuenta del grupo:

- **Groq:** <https://console.groq.com/keys> → *Create API Key*. Empieza con `gsk_` y Groq la muestra **una sola vez**.

En **`.env.example`**, agrega al final (sin la clave):

@@fragmento .env.example <<# --- IA>> <<GROQ_MODEL=openai/gpt-oss-20b>>@@

En **tu `.env`**, agrega lo mismo **con** la clave. Pásasela a Emma y Miguel por mensaje privado: **nunca** por el repositorio (o que cada uno cree la suya).

En `config/services.php`, antes del `];` final:

@@fragmento config/services.php <<// --- YouCook: la IA (Groq) --->> <<    ],>>@@

Comprueba que llegó:

```bash
php artisan config:show services.groq
```

**Entiende**
- `env('GROQ_API_KEY')` lee el valor de `.env`, y `config/services.php` lo lleva a la configuración de la app. El código nunca usa `env()` directamente: siempre `config('services.groq.key')`. Si borras la entrada `'groq'`, `config:show` dice que no existe y la clave nunca llega al código.
- `openai/gpt-oss-20b` es un modelo rápido del plan gratuito (el mismo de la clase). Cambiar de modelo es cambiar `GROQ_MODEL` en `.env`, sin tocar código.
- Groq retira modelos de vez en cuando (los Llama que ofrecía se retiraron en agosto de 2026): si un día contesta `model_not_found`, elige uno vigente en <https://console.groq.com/docs/models>.

## Paso 10 · El servicio que habla con Groq

Primero, la excepción propia. **Crea:**

@@archivo app/Exceptions/IaNoDisponible.php@@

Ahora el servicio. Hoy escribes las dos primeras partes (las reglas y la llamada); mañana, las preguntas. **Crea:**

@@parte app/Services/AsistenteIA.php 1-2 cierre@@

**Entiende**
- `REGLAS` es el *mensaje de sistema* (el `role` `system` del traductor de la clase 9): las instrucciones que el modelo recibe antes de cada conversación. Ahí están tres de nuestros riesgos: que hable solo de cocina, que advierta sobre alérgenos y que no invente. `<<<'TXT' ... TXT` es un texto de varias líneas (*nowdoc*).
- `preguntar()` es el mismo pedido del traductor: `Http::withToken(config('services.groq.key'))` arma el encabezado `Authorization: Bearer <clave>` y `->post(...)` envía el modelo y los mensajes a `https://api.groq.com/openai/v1/chat/completions`.
- Lo que agregamos al traductor: `blank(config('services.groq.key'))` no llama a Groq si falta la clave (la tarea 3 del ejercicio del traductor), `->timeout(20)` corta la espera a los 20 segundos y `temperature` baja la «creatividad» para que responda parejo.
- `try { ... } catch (ConnectionException $e)`: si no hay internet o Groq no contesta a tiempo, `post()` no devuelve una respuesta sino que **lanza** una excepción. El `catch` la atrapa para que la app no se caiga.
- `$respuesta->failed()` es `true` ante cualquier error de Groq (clave mala 401, límite 429, modelo retirado 404). `Log::error(...)` deja el detalle en `storage/logs/laravel.log`: el primer lugar donde mirar cuando algo falla.
- `->json('choices.0.message.content')` saca el texto de la respuesta: `{"choices": [{"message": {"content": "..."}}]}`.
- En vez de volver al formulario (como el traductor), el servicio lanza `IaNoDisponible`. Los controladores de mañana la atrapan y muestran «El asistente no está disponible», con el botón «Reintentar» del mockup.
- Los `use` de `Ingredient` y `Recipe` todavía no se usan: son para las partes de mañana.

## Paso 11 · Comando para probar la conexión

En `routes/console.php`, agrega arriba dos líneas `use`, junto a las que ya están:

```php
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
```

Y al final del archivo:

@@fragmento routes/console.php <<// php artisan ia:probar>> <<})->purpose('Prueba la conexión con Groq');>>@@

Pruébalo con tu clave:

```bash
php artisan ia:probar
```

Debería responder algo así:

```text
Groq (openai/gpt-oss-20b): «listo» en 0.6 s
```

**Si da error 404 o 400**, lo más probable es que el modelo ya no exista. Pide la lista de modelos vigentes y pon uno en `GROQ_MODEL` de tu `.env` (y en `.env.example`, y avisa en el chat):

```bash
curl -s https://api.groq.com/openai/v1/models -H "Authorization: Bearer TU_CLAVE_GROQ"
```

**Error 401:** la clave está mal copiada. **Error 429:** se acabó el límite gratis por ahora (el plan gratuito da unas 1.000 consultas al día y 8.000 *tokens* por minuto); anota a qué hora pasó.

Y una pregunta de verdad, usando el servicio:

```bash
php artisan tinker --execute="echo app(App\Services\AsistenteIA::class)->preguntar([['role' => 'user', 'content' => 'No tengo zapallo para el charquicán. ¿Con qué lo reemplazo?']]);"
```

> ⚠️ Los nombres de modelo y la conexión real **no se pudieron probar al preparar esta guía** (no había claves). Este paso es justamente esa prueba: si algo no calza, corrígelo aquí.

## Paso 12 · Pruebas del servicio

Las pruebas no llaman a la API real: `Http::fake()` responde en su lugar. Así no gastan cuota y funcionan sin internet. Hoy escribes las cuatro primeras. **Crea:**

@@parte tests/Feature/AsistenteIATest.php 1 cierre@@

```bash
php artisan test --filter=AsistenteIATest     # 4 pruebas en verde
```

**Entiende**
- `setUp()` se ejecuta antes de cada prueba. Pone una clave falsa con `config([...])`, así la prueba no depende de tu `.env`.
- `Http::fake([...])` recibe «para esta URL, responde esto». `Http::assertSent(...)` revisa lo que se envió: la dirección de Groq, la clave, el modelo y el mensaje.
- Las otras tres simulan lo que puede salir mal: Groq sin cuota (error 429), una respuesta vacía y la clave que falta (`Http::assertNothingSent()`: ni siquiera se intentó). En los tres casos el servicio lanza `IaNoDisponible`.
- Los `use` de `Ingredient` y `Recipe` son para las pruebas de mañana.

## Cierre del día

```bash
vendor/bin/pint            # ordena el formato del código PHP
php artisan test           # 13 pruebas en verde
git add . && git commit -m "Servicio de IA con Groq y comando ia:probar"
git push -u origin sebastian/dia1-ia
```

- [ ] PR abierto e integrado por ti mismo (no hay conflictos: son archivos nuevos).
- [ ] Integrados los PR de Miguel (modelos, recetas, catálogo provisional) y de Emma (ingreso, registro y barra de navegación), en el orden en que llegaron.
- [ ] Después de integrar: `git pull && composer install && npm install && php artisan migrate:fresh --seed && php artisan test`.
- [ ] Check-in de las 21:00: ¿quedó algo de hoy para mañana?
