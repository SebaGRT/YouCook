# Sebastián · Día 3 (domingo 15): integración, limpieza y entrega

**Meta del día:** `main` congelado a las 18:00 con todo funcionando, el README escrito y el ensayo general hecho. Hoy casi no escribes código nuevo: tu trabajo es integrar, probar y dejar todo listo para el lunes.

← [Día 2](dia2.md) · [Índice de guías](../README.md)

## Mapa del día

| Paso | Qué haces | Archivo | Depende de |
|---|---|---|---|
| 1 | Integrar los PR de la mañana | (GitHub) | Emma (Guardados, Perfil) y Miguel (pruebas) |
| 2 | Borrar lo provisional | `resources/views/pendiente.blade.php` | Paso 1 |
| 3 | Recorrido completo con datos limpios | (navegador) | Paso 2 |
| 4 | README del repositorio | `README.md` (raíz del repo) | — |
| 5 | Congelar `main` (18:00) | (git) | Todo lo anterior |
| 6 | Preparar el computador de la presentación | (comandos) | Paso 5 |
| 7 | Ensayo general (21:00) | — | Los tres |

**Tiempo:** unas 3 h repartidas en el día.

---

## Paso 1 · Integrar los PR de la mañana

Integra apenas lleguen, en este orden si llegan juntos: primero Emma (vistas), después Miguel (pruebas). Tras cada integración:

```bash
git switch main && git pull
composer install && npm install
php artisan migrate:fresh --seed
npm run build && php artisan test
```

Si una prueba de Miguel falla por un **texto** que Emma escribió distinto en la vista, se corrige la vista (los textos vienen del mockup), no la prueba.

## Paso 2 · Borrar lo provisional

Cuando Guardados y Perfil del mockup estén en `main`, ya ninguna ruta usa «En construcción»:

```bash
git switch -c sebastian/dia3-limpieza
grep -rn "pendiente" routes resources/views     # no debe aparecer nada
rm resources/views/pendiente.blade.php
```

Si `grep` todavía muestra algo, falta integrar un archivo de rutas definitivo (`routes/web.php` es de Miguel; `routes/ia.php`, tuyo).

Revisa también que no quede ninguna vista provisional:

```bash
grep -rln "Versión provisional" resources/views   # no debe aparecer nada
```

## Paso 3 · Recorrido completo con datos limpios

```bash
php artisan migrate:fresh --seed && php artisan cache:clear
php artisan serve      # y en otra terminal: npm run dev
```

Con una cuenta **nueva** (regístrate):

- [ ] Registro con contraseña corta: el error sale en español. Con una válida, llegas al catálogo y dice «Hola, (tu nombre)».
- [ ] Catálogo: 8 recetas, paginación 1-2-3 y «Siguiente». Buscar «zapallo», filtrar por categoría, tiempo y dificultad. Una búsqueda sin resultados muestra el estado vacío.
- [ ] Guardar una receta desde la tarjeta: el contador de «Guardados» sube.
- [ ] Detalle: marcar pasos (el contador cambia), marcar ingredientes, «No lo tengo» con respuesta de la IA.
- [ ] «Marcar como cocinada»: aparece el aviso con el sticker y «1 de 20».
- [ ] Guardados: aparece la receta. Al quitarla, se ve el estado vacío.
- [ ] Asistente desde el detalle («¿Dudas con algún paso?»): habla de esa receta.
- [ ] Perfil: progreso, sticker desbloqueado, checklist, «Editar» guarda el nombre y «Cerrar sesión» sale.

Con la cuenta del mockup (`camila.rojas@uni.cl` / `password`):

- [ ] Perfil con 7 de 20, 35 % y 7 stickers. Guardados con 3 recetas.

En el teléfono (o con las herramientas del navegador, en 390 px de ancho):

- [ ] La barra de abajo navega entre las cuatro pantallas y nada se sale por los lados.

Anota lo que falle en el chat, con captura y a quién le toca. **Solo se corrigen errores: nada de funciones nuevas.**

## Paso 4 · README del repositorio

En la **raíz del repo** (no en `web/`), crea o completa `README.md` con:

1. **Qué es YouCook** y el problema que resuelve (dos líneas de la propuesta).
2. **Equipo y roles.**
3. **Tecnologías:** Laravel 13, Blade, Eloquent, SQLite, Tailwind 4, Alpine.js y la API de Groq. Registro e ingreso escritos a mano (`AuthController`), como en la clase 11.
4. **Cómo ejecutarlo:**
   ```bash
   cd web
   composer install && npm install
   cp .env.example .env && php artisan key:generate
   # agregar GROQ_API_KEY en .env
   php artisan migrate:fresh --seed
   npm run build && php artisan serve
   ```
   Cuenta de demostración: `camila.rojas@uni.cl` / `password`.
5. **Pruebas:** `php artisan test` (40 pruebas; ninguna llama a la API real).
6. **Qué cambia respecto del mockup** (copia la lista del [índice de guías](../README.md#qué-cambia-respecto-del-mockup)).
7. **Limitaciones conocidas:** lo que no alcanzó a quedar, dicho tal cual. Por ejemplo: recetas sin foto, la IA puede equivocarse, la conversación se guarda solo mientras dura la sesión.
8. **Capturas** (las de Emma, en `docs/evidencia/capturas/`).

Sobre la clave: `git grep -n "gsk_"` no debe encontrar nada (así empiezan las claves de Groq).

## Paso 5 · Congelar `main` (18:00)

```bash
git switch main && git pull
vendor/bin/pint --test           # formato
npm run build && php artisan test
git tag v1.0 && git push --tags
```

Desde este momento solo entran correcciones de errores que impidan presentar, y avisando en el chat.

## Paso 6 · Preparar el computador de la presentación

En el computador que se va a usar el lunes:

```bash
git clone <url-del-repo> && cd YouCook/web       # o git pull si ya está
composer install && npm install
cp .env.example .env && php artisan key:generate   # y pegar la clave de Groq
php artisan migrate:fresh --seed
npm run build
php artisan ia:probar
php artisan serve
```

- [ ] `ia:probar` responde con Groq **desde la red donde se va a presentar** (o compartiendo internet desde el teléfono).
- [ ] Antes de presentar, toca «No lo tengo» en los ingredientes que se van a mostrar: quedan en caché y responden al instante aunque la red esté lenta.
- [ ] **Plan B si no hay internet:** las alternativas ya consultadas siguen funcionando (caché). El chat mostrará «El asistente no está disponible» con «Reintentar»: se dice tal cual y se muestran las capturas de `docs/evidencia/`. **Nunca presentar una respuesta guardada como si fuera en vivo.**

## Paso 7 · Ensayo general (21:00)

Los tres, con el guion de [`docs/plan/README.md`](../../plan/README.md#guion-de-la-demo-5-minutos). Cada uno explica su parte en 2 minutos y responde una pregunta de otro sobre su código.

Lo tuyo:

- [ ] Por qué la clave de Groq está solo en el servidor (`.env` → `config/services.php` → `AsistenteIA`) y nunca llega al navegador.
- [ ] Cómo funcionan las cuentas: `AuthController`, `Auth::attempt`, `session()->regenerate()` y los middleware `auth` y `guest`.
- [ ] El camino de «No lo tengo»: Alpine → `fetch` → ruta → controlador → caché → servicio → Groq → JSON → Alpine.
- [ ] Qué pasa si la IA falla (`failed()`, `Log::error`, `IaNoDisponible`, 503 y «Reintentar») y cómo lo prueban las pruebas sin internet (`Http::fake`).
- [ ] Qué riesgos de la propuesta cubre el código: `REGLAS` (solo cocina, alérgenos, no inventar), `throttle` y caché (límite gratis), y la app que sigue funcionando sin IA (dependencia de una API).
