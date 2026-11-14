import { CHALLENGE, RECIPES, STICKER_NAMES } from '../data'
import { useStore } from '../store'
import { Sticker } from '../components/Stickers'
import { Avatar } from '../components/Nav'
import { Check, Lock, Logout, Pencil } from '../components/Icons'

const tilt = [-6, 4, -3, 7, -8, 3, -4, 6, -2, 5]

export function Profile() {
  const { cooked, saved } = useStore()
  const total = CHALLENGE.length
  const n = cooked.length
  const pct = Math.round((n / total) * 100)
  const unlocked = CHALLENGE.filter((c) => cooked.includes(c.slug))
  const next = 10 - n

  return (
    <div className="grid gap-8 lg:grid-cols-[340px_1fr]">
      <aside className="h-fit space-y-5">
        <div className="rounded-[28px] bg-white p-7 text-center shadow-soft">
          <div className="mx-auto w-fit"><Avatar size={96} /></div>
          <h1 className="mt-4 text-[26px] font-black">Camila Rojas</h1>
          <p className="text-charcoal-soft">camila.rojas@uni.cl</p>
          <span className="mt-3 inline-block rounded-full bg-tomato-50 px-3 py-1 text-sm font-bold text-tomato-600">Cocinera en entrenamiento</span>
          <dl className="mt-6 grid grid-cols-3 divide-x divide-cream-200 rounded-2xl bg-cream p-3">
            {[[n, 'Cocinadas'], [saved.length, 'Guardadas'], [unlocked.length, 'Stickers']].map(([v, k]) => (
              <div key={k}>
                <dd className="font-display text-2xl font-black">{v}</dd>
                <dt className="text-xs text-charcoal-soft">{k}</dt>
              </div>
            ))}
          </dl>
        </div>
        <form method="POST" action="/perfil" className="space-y-4 rounded-[28px] bg-white p-6 shadow-soft" onSubmit={(e) => e.preventDefault()}>
          <h2 className="font-display text-lg font-extrabold">Mis datos</h2>
          {[['Nombre', 'Camila Rojas'], ['Correo', 'camila.rojas@uni.cl'], ['Miembro desde', 'Marzo 2026']].map(([k, v]) => (
            <div key={k} className="flex items-center justify-between border-b border-cream-200 pb-3 text-sm last:border-0">
              <span className="text-charcoal-soft">{k}</span>
              <span className="font-semibold">{v}</span>
            </div>
          ))}
          <div className="flex gap-2 pt-1">
            <button className="flex flex-1 items-center justify-center gap-2 rounded-xl border-2 border-cream-300 py-2.5 text-sm font-bold hover:border-tomato-200"><Pencil size={15} /> Editar</button>
            <a href="#/login" className="flex flex-1 items-center justify-center gap-2 rounded-xl py-2.5 text-sm font-bold text-charcoal-soft hover:bg-cream-200"><Logout size={15} /> Cerrar sesión</a>
          </div>
        </form>
      </aside>

      <div className="space-y-8">
        {/* Progreso */}
        <section className="relative overflow-hidden rounded-[32px] bg-charcoal p-7 text-white md:p-9">
          <div className="pointer-events-none absolute -top-6 right-6 hidden md:block"><Sticker kind="olla2" size={120} rotate={12} /></div>
          <p className="font-display text-sm font-extrabold tracking-wider text-[#FFC23D] uppercase">Reto YouCook</p>
          <h2 className="mt-2 max-w-md text-[30px] leading-tight font-black md:text-[38px]">
            {n} de {total} recetas cocinadas
          </h2>
          <p className="mt-2 text-white/75">{next > 0 ? `Te faltan ${next} para desbloquear la insignia «Chef de pensión».` : '¡Desbloqueaste «Chef de pensión»! Vamos por las 20.'}</p>
          <div className="mt-6 flex items-center gap-4">
            <div className="h-4 flex-1 overflow-hidden rounded-full bg-white/15" role="progressbar" aria-valuenow={n} aria-valuemin={0} aria-valuemax={total}>
              <div className="h-full rounded-full bg-gradient-to-r from-[#FFC23D] to-tomato-500 transition-all duration-700" style={{ width: `${pct}%` }} />
            </div>
            <span className="font-display text-xl font-black">{pct}%</span>
          </div>
          <div className="mt-3 flex justify-between text-xs text-white/60">
            <span>Inicio</span><span>5 · Aprendiz</span><span>10 · Chef de pensión</span><span>20 · Maestra</span>
          </div>
        </section>

        {/* Stickers */}
        <section>
          <div className="flex items-baseline justify-between">
            <h2 className="text-2xl font-black">Mis stickers</h2>
            <span className="text-sm text-charcoal-soft">{unlocked.length} de {total} desbloqueados</span>
          </div>
          <div className="mt-5 grid grid-cols-3 gap-3 rounded-[28px] bg-[radial-gradient(circle_at_1px_1px,#efdcc0_1px,transparent_0)] bg-white [background-size:18px_18px] p-5 shadow-soft sm:grid-cols-5 md:grid-cols-5 xl:grid-cols-10">
            {CHALLENGE.map((c, i) => {
              const on = cooked.includes(c.slug)
              return (
                <div key={c.slug} className="flex flex-col items-center gap-1 text-center" title={on ? STICKER_NAMES[c.sticker] : 'Bloqueado'}>
                  <div className={on ? 'pop' : 'opacity-80'} style={{ animationDelay: `${i * 40}ms` }}>
                    <Sticker kind={c.sticker} size={72} locked={!on} rotate={tilt[i % tilt.length]} />
                  </div>
                  <span className={`text-[11px] leading-tight font-semibold ${on ? 'text-charcoal' : 'text-[#B3A99D]'}`}>{on ? STICKER_NAMES[c.sticker] : '???'}</span>
                </div>
              )
            })}
          </div>
        </section>

        {/* Checklist */}
        <section className="rounded-[28px] bg-white p-6 shadow-soft md:p-8">
          <h2 className="text-2xl font-black">Checklist de recetas</h2>
          <p className="mt-1 text-sm text-charcoal-soft">Cada receta que marques como cocinada te da un sticker nuevo.</p>
          <ul className="mt-5 grid gap-x-8 sm:grid-cols-2">
            {CHALLENGE.map((c) => {
              const on = cooked.includes(c.slug)
              const link = RECIPES.some((r) => r.slug === c.slug)
              return (
                <li key={c.slug} className="flex items-center gap-3 border-b border-cream-200 py-3">
                  <span className={`grid size-6 shrink-0 place-items-center rounded-lg ${on ? 'bg-basil-500 text-white' : 'border-2 border-cream-300'}`}>
                    {on && <Check size={14} strokeWidth={3} />}
                  </span>
                  {link ? (
                    <a href={`#/recetas/${c.slug}`} className={`flex-1 hover:text-tomato-600 ${on ? 'text-charcoal-soft line-through decoration-cream-300' : 'font-medium'}`}>{c.title}</a>
                  ) : (
                    <span className={`flex-1 ${on ? 'text-charcoal-soft line-through decoration-cream-300' : 'font-medium'}`}>{c.title}</span>
                  )}
                  {!on && <Lock size={14} className="text-[#C9B394]" />}
                </li>
              )
            })}
          </ul>
        </section>
      </div>
    </div>
  )
}
