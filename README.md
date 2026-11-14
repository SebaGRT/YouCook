# YouCook

Recetas simples para cocinar con lo que hay en el refri: un catálogo con filtros, recetas guardadas, alternativas para los ingredientes que faltan y un asistente de cocina con IA. Proyecto integrador de la Unidad 3 (Laravel) del ramo IIP323W, UDD 2026.

**Equipo:** Sebastián Herrera (líder, IA e integración) · Emma Westcott (diseño y frontend) · Miguel Figueroa (backend).

## Estructura

- `web/`: la aplicación Laravel (se crea el viernes 13 siguiendo las guías).
- `mockup/Mockup de YouCook/`: el mockup de Figma Make (React + Tailwind), la referencia visual.
- `docs/plan/`: plan del 12 al 16 de noviembre, por integrante.
- `docs/guias/`: el código a escribir, por integrante y por día.
- `docs/evidencia/`: capturas, revisiones y pruebas.
- `docs/plantilla-propuesta-laravel.docx`: la propuesta entregada.
- `scripts/guias/`: generador de las guías y simulación que las verifica.

## Ejecutar

```bash
cd web
composer install && npm install
cp .env.example .env && php artisan key:generate
# agregar GROQ_API_KEY en .env (https://console.groq.com/keys)
php artisan migrate:fresh --seed
npm run build && php artisan serve
```

Cuenta de demostración: `camila.rojas@uni.cl` / `password`.

## Verificar

```bash
cd web && php artisan test        # ninguna prueba llama a la API real
php artisan ia:probar             # conexión con Groq
```
