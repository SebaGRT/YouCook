# Miguel · Día 3 (domingo 15): pruebas del perfil, fotos y revisión del backend

**Meta del día:** las 40 pruebas en verde, las recetas con foto y el backend revisado caso por caso. Todo en `main` antes de las 18:00.

← [Día 2](dia2.md) · [Índice de guías](../README.md)

## Mapa del día

| Paso | Qué haces | Archivo | Depende de |
|---|---|---|---|
| 1 | Pruebas del perfil | `tests/Feature/ProfileTest.php` | — |
| 2 | Fotos de las 12 recetas nuevas | `database/data/recetas.php` | — |
| 3 | Revisión del backend, caso por caso | `docs/evidencia/revision-backend.md` | Todo integrado |
| 4 | Preparar tu parte de la presentación | — | — |

**Tiempo:** unas 2 h 30.

Rama: `git switch main && git pull && composer install && php artisan migrate:fresh --seed && git switch -c miguel/dia3-pruebas`.

---

## Paso 1 · Pruebas del perfil

**Crea:**

@@archivo tests/Feature/ProfileTest.php@@

**Entiende**
- La primera prueba arma un caso chico (4 recetas, 1 cocinada) y revisa lo que se ve: «1 de 4 recetas cocinadas», «25%», el nombre del sticker ganado y el nivel.
- `->from('/perfil')` le dice a la prueba desde qué página se envió el formulario: ahí vuelve Laravel cuando la validación falla.
- `assertSessionHasErrors('email')`: el correo de otra persona es rechazado por la regla `Rule::unique(...)` de `ProfileController@update`.
- Si Emma ya integró el Perfil del mockup y la primera prueba falla, compara los textos: tienen que ser los del mockup.

```bash
php artisan test     # 40 pruebas en verde
```

Sube el PR ahora (`git add . && git commit -m "Pruebas del perfil" && git push -u origin miguel/dia3-pruebas`) y sigue.

## Paso 2 · Fotos de las 12 recetas nuevas

Las recetas 9 a 20 tienen `'image' => null`. Para cada una:

1. Busca el plato en <https://unsplash.com> (las fotos son de uso libre).
2. Abre la foto, clic derecho sobre la imagen → «Copiar dirección de la imagen». La dirección empieza con `https://images.unsplash.com/photo-…`.
3. Copia solo el código (`photo-1234567890123-abcdef123456`) y ponlo en `database/data/recetas.php`:
   ```php
   'image' => $foto('photo-1234567890123-abcdef123456'),
   ```
4. `php artisan migrate:fresh --seed` y mira la receta en el navegador.

Si no encuentras una foto que de verdad parezca el plato (sopaipillas o porotos con riendas pueden costar), **déjala en `null`**: el sticker se ve mejor que una foto equivocada. Anota en el README cuáles quedaron sin foto.

## Paso 3 · Revisión del backend, caso por caso

Con todo integrado y datos limpios (`php artisan migrate:fresh --seed`), prueba cada caso y anota el resultado en `docs/evidencia/revision-backend.md` (una tabla: caso, resultado esperado, ¿pasó?):

| Caso | Resultado esperado |
|---|---|
| Abrir `/recetas` sin iniciar sesión | Redirige a `/login` |
| Registrarse con un correo que ya existe | Error en español, sin crear otra cuenta |
| Registrarse con contraseña de 5 letras | «…debe tener al menos 8 caracteres» |
| Iniciar sesión con contraseña incorrecta | «Esas credenciales no coinciden con ninguna cuenta.» |
| `/recetas/no-existe` | 404 |
| `/recetas?tiempo=abc` | Vuelve atrás con un error; no se cae |
| Buscar `'; DROP TABLE recipes; --` | Sin resultados; la tabla sigue ahí |
| Guardar la misma receta desde dos pestañas | No se duplica ni falla |
| Marcar y desmarcar «cocinada» | El contador sube y baja; el aviso sale solo al marcar |
| Dos cuentas distintas | Cada una ve solo sus guardados y su progreso |
| Editar el perfil con el correo de la otra cuenta | Error «Ya existe una cuenta con ese correo» |
| Enviar un mensaje vacío al asistente | No se envía |
| Más de 20 consultas a la IA en un minuto | La número 21 muestra «Reintentar» (429) |

Lo que falle, avísalo en el chat con el caso exacto. Si es de tu código, corrígelo y agrega una prueba que lo cubra.

## Paso 4 · Preparar tu parte de la presentación

Tienes que poder explicar en 2 minutos, mostrando el código:

- [ ] El modelo de datos: las 4 tablas nuestras y sus relaciones (`hasMany`, `belongsToMany` con tabla pivote).
- [ ] Cómo una URL llega a una vista: ruta → controlador → Eloquent → vista (por ejemplo `/recetas?q=zapallo`).
- [ ] `scopeFilter`: cómo se arma la consulta solo con los filtros que vienen.
- [ ] Por qué un formulario `POST` necesita `@csrf`, por qué las rutas están dentro de `middleware('auth')` y cómo `Auth::attempt` revisa la contraseña encriptada.
- [ ] Qué prueban las pruebas y cómo se ejecutan (`php artisan test`).

## Cierre del día

```bash
vendor/bin/pint && php artisan test
git add . && git commit -m "Fotos de recetas y revisión del backend"
git push
```

- [ ] Todo integrado **antes de las 18:00** (congelamiento).
- [ ] Ensayo general a las 21:00.
