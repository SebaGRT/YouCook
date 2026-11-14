#!/usr/bin/env bash
# Simula las guías día a día DESDE CERO (laravel new) y verifica en cada corte:
# que las vistas compilen, que el front compile, que las pruebas pasen y que
# todas las páginas abran. Termina comparando el resultado con la referencia.
# Uso, desde la raíz del repo:   bash scripts/guias/simular_integracion.sh
set -uo pipefail
RAIZ="$(cd "$(dirname "$0")/../.." && pwd)"
R="$RAIZ/referencia/web"              # resultado esperado
T="$RAIZ/scripts/guias/provisional"   # archivos provisionales y muestras
G="$RAIZ/scripts/guias/generar.py"
TMP="$(mktemp -d)"; S="$TMP/web"
V=resources/views; C=resources/views/components
fallas=0

ref() { for f in "$@"; do mkdir -p "$(dirname "$f")"; cp "$R/$f" "$f"; done; }
prov() { mkdir -p "$(dirname "$2")"; cp "$T/$1" "$2"; }
parte() { python3 "$G" --parte "$1" "$2" ${3:-} > "$1"; }   # archivo, rango, --cierre
check() {
  echo "=== $1"
  local v b t
  php artisan view:clear >/dev/null 2>&1
  v=$(php artisan view:cache 2>&1 | tail -2 | tr -d '\n'); php artisan view:clear >/dev/null 2>&1
  [[ $v == *"cached successfully"* ]] && echo "    vistas:  ok" || { echo "    vistas:  ✗ $v"; fallas=$((fallas + 1)); }
  b=$(npm run build 2>&1 | grep -E "built in|rror" | head -2 | tr -d '\n')
  [[ $b == *"built in"* ]] && echo "    build:   ok" || { echo "    build:   ✗ $b"; fallas=$((fallas + 1)); }
  cp "$RAIZ/scripts/guias/verificacion/SimulacionSmokeTest.php" tests/Feature/
  if php artisan test >"$TMP/test.log" 2>&1; then
    echo "    pruebas: ok · $(grep -oE '"tests":[0-9]+|Tests: +[0-9]+ passed' "$TMP/test.log" | head -1)"
  else
    echo "    pruebas: ✗"; grep -oE '"message":"[^"]{0,300}|FAILED.*|Falló[^"]*' "$TMP/test.log" | head -5; fallas=$((fallas + 1))
  fi
  rm tests/Feature/SimulacionSmokeTest.php
}
muestra() { # la página de pruebas visuales de la guía se dibuja sin errores
  prov "$1" $V/muestra.blade.php
  local m; m=$(php artisan tinker --execute='echo strlen(view("muestra")->render()) > 500 ? "ok" : "corta";' 2>&1 | tail -1)
  echo "    muestra $1: $m"; [[ $m == ok ]] || fallas=$((fallas + 1))
  rm $V/muestra.blade.php
}

# ============ Día 1 · Sebastián, mañana: el esqueleto ============
cd "$TMP"
laravel new web --no-authentication --database=sqlite --phpunit --npm --no-boost -n >/dev/null 2>&1 || { echo "laravel new falló"; exit 1; }
cd "$S"
rm -f AGENTS.md CLAUDE.md
# Paso 2: Alpine.js
npm install -D alpinejs >/dev/null 2>&1
prov app_dia1.js resources/js/app.js
# Paso 3: el tema del mockup
ref resources/css/app.css vite.config.js
# Paso 4: sacar lo que no usamos
rm $V/welcome.blade.php tests/Feature/ExampleTest.php tests/Unit/ExampleTest.php
# Paso 5: configuración
sed -i 's/^APP_NAME=Laravel/APP_NAME=YouCook/; s/^APP_LOCALE=en/APP_LOCALE=es/; s/^APP_FAKER_LOCALE=en_US/APP_FAKER_LOCALE=es_ES/' .env .env.example
sed -i "s/'timezone' => 'UTC'/'timezone' => 'America\/Santiago'/" config/app.php
cp "$RAIZ/mockup/Mockup de YouCook/public/favicon.png" public/favicon.png
printf '\n# Página de pruebas visuales de cada integrante\n/resources/views/muestra.blade.php\n' >> .gitignore
# Paso 6: las cuentas, escritas a mano (clase 11)
ref app/Http/Controllers/AuthController.php routes/auth.php tests/Feature/Auth/AuthenticationTest.php tests/Feature/Auth/RegistrationTest.php
prov login_dia1.blade.php $V/auth/login.blade.php; prov registro_dia1.blade.php $V/auth/registro.blade.php
# Paso 7: rutas provisionales
prov web_dia1_esqueleto.php routes/web.php; prov ia_dia1_esqueleto.php routes/ia.php
prov pendiente_dia1.blade.php $V/pendiente.blade.php
php artisan migrate:fresh >/dev/null 2>&1
check "Día 1 · Sebastián, mañana (esqueleto: Laravel + cuentas + Tailwind 4 + rutas provisionales)"

# ============ Día 1 · mañana de Miguel y Emma ============
ref database/migrations/2026_11_13_000001_create_recipes_table.php database/migrations/2026_11_13_000002_create_saved_and_cooked_recipes_tables.php \
    app/Models/Recipe.php app/Models/Ingredient.php app/Models/User.php
check "Día 1 · Miguel, PR 1 (migraciones y modelos)"
ref $C/icon.blade.php $C/logo.blade.php $C/sticker-face.blade.php $C/sticker.blade.php
for f in "$R"/$C/stickers/*.blade.php; do ref "$C/stickers/$(basename "$f")"; done
muestra muestra_emma_dia1.blade.php
ref $V/layouts/guest.blade.php $C/primary-button.blade.php $C/text-input.blade.php \
    $C/input-label.blade.php $C/input-error.blade.php $C/auth-field.blade.php \
    $V/auth/login.blade.php $V/auth/registro.blade.php
check "Día 1 · Emma, PR 1 (íconos, stickers, ingreso y registro)"

# ============ Día 1 · tarde ============
ref database/data/recetas.php database/seeders/RecipeSeeder.php database/seeders/DatabaseSeeder.php
check "Día 1 · Miguel, PR 2 (las 20 recetas y la cuenta de demostración)"
ref $C/avatar.blade.php $C/nav.blade.php $C/toast.blade.php $V/layouts/app.blade.php
prov pendiente_dia1_layout.blade.php $V/pendiente.blade.php
check "Día 1 · Emma, PR 2 (barra de navegación y layout)"
ref app/Http/Controllers/RecipeController.php
prov web_dia1_miguel.php routes/web.php
prov recetas_index.blade.php $V/recetas/index.blade.php; prov recetas_show.blade.php $V/recetas/show.blade.php
check "Día 1 · Miguel, PR 3 (catálogo y detalle con vistas provisionales)"
python3 - <<PY
import re
ref = open("$R/config/services.php").read()
bloque = ref[ref.index("    // --- YouCook: la IA (Groq) ---"):ref.rindex("];")]
s = open("config/services.php").read()
i = s.rindex("];")
open("config/services.php", "w").write(s[:i] + bloque + "];\n")
ref = open("$R/routes/console.php").read()
open("routes/console.php", "w").write(ref)
for f in (".env", ".env.example"):
    s = open(f).read().rstrip("\n")
    extra = open("$R/.env.example").read()
    extra = extra[extra.index("# --- IA"):]
    open(f, "w").write(s + "\n\n" + extra)
PY
ref app/Exceptions/IaNoDisponible.php
mkdir -p app/Services
parte app/Services/AsistenteIA.php 1-2 --cierre
parte tests/Feature/AsistenteIATest.php 1 --cierre
check "Día 1 · Sebastián, tarde (servicio de IA: preguntar + ia:probar)"

# ============ Día 2 ============
ref database/factories/RecipeFactory.php database/factories/IngredientFactory.php tests/Feature/RecipeCatalogTest.php
check "Día 2 · Miguel, PR 1 (factories y pruebas del catálogo, con vistas provisionales)"
ref resources/js/ia.js resources/js/ingrediente.js $C/ia-loading.blade.php $C/ia-error.blade.php $C/ingredient-row.blade.php
prov app_dia2.js resources/js/app.js
check "Día 2 · Sebastián, PR 1 (fila de ingrediente y su JavaScript)"
ref $C/difficulty.blade.php $C/recipe-photo.blade.php $C/bookmark-button.blade.php $C/recipe-card.blade.php \
    $C/select.blade.php $V/pagination/youcook.blade.php $V/recetas/index.blade.php
check "Día 2 · Emma, PR 1 (catálogo del mockup)"
ref app/Http/Controllers/SavedRecipeController.php app/Http/Controllers/CookedRecipeController.php \
    app/Http/Controllers/ProfileController.php routes/web.php tests/Feature/SavedAndCookedTest.php \
    lang/es/validation.php
prov guardados_index.blade.php $V/guardados/index.blade.php; prov perfil_show.blade.php $V/perfil/show.blade.php
check "Día 2 · Miguel, PR 2 (guardar, cocinada, perfil y textos en español)"
ref $V/recetas/show.blade.php
check "Día 2 · Emma, PR 2 (detalle del mockup)"
ref app/Services/AsistenteIA.php tests/Feature/AsistenteIATest.php app/Http/Controllers/IngredientAlternativeController.php \
    app/Http/Controllers/AssistantController.php routes/ia.php resources/js/chat.js resources/js/app.js \
    $V/asistente/show.blade.php tests/Feature/AssistantTest.php
check "Día 2 · Sebastián, PR 2 (alternativas con IA y asistente)"

# ============ Día 3 ============
ref $V/guardados/index.blade.php $V/perfil/show.blade.php
check "Día 3 · Emma (Guardados y Perfil del mockup)"
ref tests/Feature/ProfileTest.php
check "Día 3 · Miguel (pruebas del perfil)"
rm $V/pendiente.blade.php
check "Día 3 · Sebastián (limpieza: sin pantallas provisionales)"

# ============ Comparación con la referencia ============
vendor/bin/pint >/dev/null 2>&1
difs=$(diff -rq "$R" . -x vendor -x node_modules -x .env -x .git -x storage -x build -x cache -x database.sqlite \
  -x composer.lock -x package-lock.json -x .phpunit.result.cache -x fonts-manifest.dev.json 2>&1)
if [[ -z $difs ]]; then echo "=== El proyecto simulado es idéntico a la referencia"; else echo "=== DIFIERE de la referencia:"; echo "$difs"; fallas=$((fallas + 1)); fi
grep -rn "pendiente" routes resources/views >/dev/null 2>&1 && { echo "    ✗ quedan referencias a 'pendiente'"; fallas=$((fallas + 1)); }

echo; [[ $fallas == 0 ]] && echo "✓ Simulación correcta" || echo "✗ $fallas problemas"
[[ ${CONSERVAR:-} == 1 ]] && echo "Proyecto simulado en $S" || rm -rf "$TMP"
exit $fallas
