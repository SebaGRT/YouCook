import { useMemo, useState } from 'react'
import { CATEGORIES, RECIPES } from '../data'
import { RecipeCard } from '../components/RecipeCard'
import { Sticker } from '../components/Stickers'
import { Search, Sparkle, ArrowRight } from '../components/Icons'

const TIMES = [
  { v: '', l: 'Cualquier tiempo' },
  { v: '15', l: 'Hasta 15 min' },
  { v: '30', l: 'Hasta 30 min' },
  { v: '60', l: 'Hasta 1 hora' },
]

function Select({ label, value, onChange, options }: { label: string; value: string; onChange: (v: string) => void; options: { v: string; l: string }[] }) {
  return (
    <label className="relative flex items-center">
      <span className="sr-only">{label}</span>
      <select
        value={value}
        onChange={(e) => onChange(e.target.value)}
        className="h-11 appearance-none rounded-full border-2 border-cream-300 bg-white pr-10 pl-4 text-sm font-semibold outline-none hover:border-cream-300 focus:border-tomato-500"
      >
        {options.map((o) => (
          <option key={o.v} value={o.v}>{o.l}</option>
        ))}
      </select>
      <svg className="pointer-events-none absolute right-4" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden><path d="m2 4 4 4 4-4" /></svg>
    </label>
  )
}

export function Catalog() {
  const [q, setQ] = useState('')
  const [cat, setCat] = useState('Todas')
  const [time, setTime] = useState('')
  const [diff, setDiff] = useState('')

  const list = useMemo(
    () =>
      RECIPES.filter(
        (r) =>
          (cat === 'Todas' || r.category === cat) &&
          (!time || r.minutes <= Number(time)) &&
          (!diff || r.difficulty === diff) &&
          (!q || (r.title + r.ingredients.map((i) => i.name).join(' ')).toLowerCase().includes(q.toLowerCase())),
      ),
    [q, cat, time, diff],
  )

  return (
    <div>
      {/* Hero / buscador */}
      <section className="relative overflow-hidden rounded-[32px] bg-tomato-500 px-6 py-10 text-white md:px-12 md:py-14">
        <div className="pointer-events-none absolute -top-24 -right-20 size-80 rounded-full bg-[#FF7A5C]" />
        <div className="pointer-events-none absolute -right-6 -bottom-28 size-64 rounded-full bg-tomato-600" />
        <div className="pointer-events-none absolute top-8 right-16 hidden gap-3 lg:flex">
          <Sticker kind="tomate" size={92} rotate={-10} />
          <Sticker kind="choclo" size={92} rotate={8} />
        </div>
        <div className="pointer-events-none absolute right-40 bottom-6 hidden lg:block"><Sticker kind="panqueque" size={84} rotate={-6} /></div>
        <div className="relative max-w-[640px]">
          <p className="font-display text-sm font-extrabold tracking-wider text-white/80 uppercase">Hola, Camila</p>
          <h1 className="mt-2 text-[34px] leading-[1.05] font-black md:text-[48px]">¿Qué cocinamos hoy?</h1>
          <form role="search" method="GET" action="/recetas" className="mt-7" onSubmit={(e) => e.preventDefault()}>
            <label className="flex h-15 items-center gap-3 rounded-2xl bg-white pr-2 pl-5 text-charcoal shadow-lift">
              <Search size={22} className="text-charcoal-soft" />
              <input
                name="q"
                value={q}
                onChange={(e) => setQ(e.target.value)}
                placeholder="Busca por plato o ingrediente: huevo, arroz, zapallo…"
                className="h-14 w-full bg-transparent text-[16px] outline-none placeholder:text-[#B3A99D]"
              />
              <button className="hidden h-11 shrink-0 rounded-xl bg-charcoal px-5 font-display font-bold text-white sm:block">Buscar</button>
            </label>
          </form>
          <p className="mt-4 text-sm text-white/85">Populares: <button onClick={() => setQ('huevo')} className="font-semibold underline-offset-2 hover:underline">huevo</button> · <button onClick={() => setQ('arroz')} className="font-semibold underline-offset-2 hover:underline">arroz</button> · <button onClick={() => setQ('zapallo')} className="font-semibold underline-offset-2 hover:underline">zapallo</button></p>
        </div>
      </section>

      {/* Filtros */}
      <section className="mt-10 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div className="-mx-5 flex gap-2 overflow-x-auto px-5 pb-1 lg:mx-0 lg:px-0">
          {CATEGORIES.map((c) => (
            <button
              key={c}
              onClick={() => setCat(c)}
              aria-pressed={cat === c}
              className={`h-11 shrink-0 rounded-full px-5 font-display text-[15px] font-bold transition ${
                cat === c ? 'bg-charcoal text-white' : 'border-2 border-cream-300 bg-white text-charcoal hover:border-tomato-200'
              }`}
            >
              {c}
            </button>
          ))}
        </div>
        <div className="flex gap-2">
          <Select label="Tiempo" value={time} onChange={setTime} options={TIMES} />
          <Select label="Dificultad" value={diff} onChange={setDiff} options={[{ v: '', l: 'Toda dificultad' }, { v: 'Fácil', l: 'Fácil' }, { v: 'Media', l: 'Media' }]} />
        </div>
      </section>

      <div className="mt-8 mb-5 flex items-baseline justify-between">
        <h2 className="text-2xl font-black">{cat === 'Todas' ? 'Todas las recetas' : cat}</h2>
        <span className="text-sm text-charcoal-soft">{list.length} de 20 recetas</span>
      </div>

      {list.length ? (
        <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          {list.map((r) => <RecipeCard key={r.slug} r={r} />)}
        </div>
      ) : (
        <div className="rounded-3xl border-2 border-dashed border-cream-300 px-6 py-16 text-center">
          <div className="mx-auto w-fit"><Sticker kind="olla" size={96} /></div>
          <h3 className="mt-4 text-xl font-extrabold">No encontramos recetas con esos filtros</h3>
          <p className="mt-1 text-charcoal-soft">Prueba con otro ingrediente o quita algún filtro.</p>
          <button onClick={() => { setQ(''); setCat('Todas'); setTime(''); setDiff('') }} className="mt-5 rounded-full bg-charcoal px-5 py-2.5 font-display font-bold text-white">Limpiar filtros</button>
        </div>
      )}

      {/* Paginación clásica (Laravel paginate) */}
      {list.length > 0 && (
        <nav className="mt-10 flex items-center justify-center gap-2" aria-label="Paginación">
          {[1, 2, 3].map((n) => (
            <a key={n} href="#/recetas" className={`grid size-10 place-items-center rounded-full font-display font-bold ${n === 1 ? 'bg-tomato-500 text-white' : 'bg-white text-charcoal shadow-soft hover:text-tomato-600'}`}>{n}</a>
          ))}
          <a href="#/recetas" className="ml-1 flex h-10 items-center gap-1 rounded-full bg-white px-4 font-display font-bold shadow-soft hover:text-tomato-600">Siguiente <ArrowRight size={16} /></a>
        </nav>
      )}

      <a href="#/asistente" className="mt-14 flex flex-col items-start gap-5 rounded-[28px] bg-basil-500 p-7 text-white sm:flex-row sm:items-center md:p-9">
        <span className="grid size-14 shrink-0 place-items-center rounded-2xl bg-white/15"><Sparkle size={28} /></span>
        <span className="flex-1">
          <span className="block font-display text-2xl font-black">¿No sabes qué hacer con lo que tienes?</span>
          <span className="mt-1 block text-white/85">Cuéntale al asistente qué hay en tu refri y te sugiere una receta en segundos.</span>
        </span>
        <span className="flex items-center gap-2 rounded-full bg-white px-5 py-3 font-display font-extrabold text-basil-600">Abrir asistente <ArrowRight size={18} /></span>
      </a>
    </div>
  )
}
