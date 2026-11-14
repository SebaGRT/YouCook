#!/usr/bin/env python3
"""Genera docs/guias/ a partir de las plantillas y del código de referencia.

Uso, desde la raíz del repo:  python3 scripts/guias/generar.py

Directivas que se expanden dentro de las plantillas (una por línea):
  @@archivo RUTA@@                 archivo completo de referencia/web/RUTA
  @@archivo RUTA como ETIQUETA@@   lo mismo, pero la cabecera muestra ETIQUETA
                                   (migraciones: el nombre real lleva la fecha)
  @@provisional NOMBRE RUTA@@      archivo de scripts/guias/provisional/NOMBRE,
                                   que se escribe en RUTA
  @@parte RUTA N@@                 parte N de referencia/web/RUTA
  @@parte RUTA N-M@@               partes N a M juntas
  @@parte RUTA N-M cierre@@        lo mismo y agrega la llave final `}` (para
                                   una clase que se termina de escribir otro día)
  @@fragmento RUTA <<INICIO>> <<FIN>>@@
                                   un trozo de referencia/web/RUTA: desde la
                                   primera línea que contiene INICIO hasta la
                                   primera línea posterior igual a FIN

Las partes se separan en los comentarios de sección del código:
  // --- Título ---      /* --- Título --- */      {{-- --- Título --- --}}

Para la simulación:  python3 scripts/guias/generar.py --parte RUTA N-M [--cierre]
imprime esas partes (así el archivo provisional sale del mismo código).
"""
import re
import sys
from pathlib import Path

RAIZ = Path(__file__).resolve().parents[2]
REFERENCIA = RAIZ / 'referencia' / 'web'
AQUI = Path(__file__).resolve().parent
PLANTILLAS = AQUI / 'plantillas'
SALIDA = RAIZ / 'docs' / 'guias'
CARPETA = 'web'

MARCA = re.compile(r'^\s*(//|/\*|\{\{--) --- .+ ---( \*/| --\}\})?\s*$')


def lenguaje(ruta: str) -> str:
    if ruta.endswith('.blade.php'):
        return 'blade'
    return {'.php': 'php', '.js': 'js', '.css': 'css', '.json': 'json',
            '.env': 'ini', '.example': 'ini'}.get(Path(ruta).suffix, '')


def bloque(ruta: str, texto: str, nota: str = '') -> str:
    lineas = texto.rstrip('\n').count('\n') + 1
    cabecera = f'📄 **`{CARPETA}/{ruta}`** · {lineas} líneas{nota}'
    return f'{cabecera}\n\n```{lenguaje(ruta)}\n{texto.rstrip()}\n```'


def partes(texto: str) -> list[list[str]]:
    """Corta el archivo antes de cada comentario de sección (salvo el primero)."""
    lineas = texto.rstrip('\n').split('\n')
    cortes = [i for i, l in enumerate(lineas) if MARCA.match(l)][1:]
    inicios = [0] + cortes
    finales = cortes + [len(lineas)]
    return [lineas[a:b] for a, b in zip(inicios, finales)]


def texto_partes(ruta: str, desde: int, hasta: int, cierre: bool) -> tuple[str, int, int, int]:
    todas = partes((REFERENCIA / ruta).read_text())
    if not 1 <= desde <= hasta <= len(todas):
        raise SystemExit(f'{ruta}: no existen las partes {desde}-{hasta} (tiene {len(todas)})')
    elegidas = todas[desde - 1:hasta]
    primera = sum(len(p) for p in todas[:desde - 1]) + 1
    lineas = [l for p in elegidas for l in p]
    while lineas and not lineas[-1].strip():
        lineas.pop()
    ultima = primera + len(lineas) - 1
    if cierre:
        lineas.append('}')
    return '\n'.join(lineas), primera, ultima, len(todas)


def bloque_partes(ruta: str, desde: int, hasta: int, cierre: bool) -> str:
    texto, primera, ultima, total = texto_partes(ruta, desde, hasta, cierre)
    cual = f'parte {desde}' if desde == hasta else f'partes {desde} a {hasta}'
    nota = 'empieza el archivo' if desde == 1 else 'sigue debajo de la parte anterior'
    if cierre:
        nota += ' · la `}` final cierra la clase por hoy'
    cabecera = (f'📄 **`{CARPETA}/{ruta}`** · {cual} de {total} '
                f'(líneas {primera}–{ultima}) · {nota}')
    return f'{cabecera}\n\n```{lenguaje(ruta)}\n{texto}\n```'


def expandir(linea: str) -> str:
    l = linea.strip()
    m = re.fullmatch(r'@@parte (\S+) (\d+)(?:-(\d+))?( cierre)?@@', l)
    if m:
        ruta, desde, hasta, cierre = m.groups()
        return bloque_partes(ruta, int(desde), int(hasta or desde), bool(cierre))
    m = re.fullmatch(r'@@archivo (\S+)(?: como (\S+))?@@', l)
    if m:
        ruta, etiqueta = m.groups()
        return bloque(etiqueta or ruta, (REFERENCIA / ruta).read_text())
    m = re.fullmatch(r'@@provisional (\S+) (\S+)@@', l)
    if m:
        nombre, ruta = m.groups()
        texto = (AQUI / 'provisional' / nombre).read_text()
        return bloque(ruta, texto, ' · **versión provisional**')
    m = re.fullmatch(r'@@fragmento (\S+) <<(.+?)>> <<(.+?)>>@@', l)
    if m:
        ruta, inicio, fin = m.groups()
        lineas = (REFERENCIA / ruta).read_text().split('\n')
        a = next(i for i, x in enumerate(lineas) if inicio in x)
        b = next(i for i, x in enumerate(lineas) if i >= a and x == fin)
        trozo = lineas[a:b + 1]
        cabecera = f'📄 **`{CARPETA}/{ruta}`** · agrega este bloque ({len(trozo)} líneas)'
        return f'{cabecera}\n\n```{lenguaje(ruta)}\n' + '\n'.join(trozo) + '\n```'
    if l.startswith('@@') and l.endswith('@@'):
        raise SystemExit(f'Directiva desconocida: {l}')
    return linea


def main() -> None:
    if len(sys.argv) > 1 and sys.argv[1] == '--parte':
        ruta, rango = sys.argv[2], sys.argv[3]
        desde, _, hasta = rango.partition('-')
        texto, *_ = texto_partes(ruta, int(desde), int(hasta or desde), '--cierre' in sys.argv)
        print(texto)
        return

    total = 0
    for plantilla in sorted(PLANTILLAS.rglob('*.md')):
        destino = SALIDA / plantilla.relative_to(PLANTILLAS)
        destino.parent.mkdir(parents=True, exist_ok=True)
        texto = '\n'.join(expandir(l) for l in plantilla.read_text().split('\n'))
        aviso = ('<!-- Archivo generado por scripts/guias/generar.py: '
                 'edita la plantilla, no este archivo. -->\n')
        destino.write_text(aviso + texto)
        lineas = texto.count('\n') + 1
        total += lineas
        print(f'✓ {destino.relative_to(RAIZ)} ({lineas} líneas)')
    print(f'{total} líneas en total')


if __name__ == '__main__':
    main()
