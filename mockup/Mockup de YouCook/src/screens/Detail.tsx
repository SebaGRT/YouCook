import { useEffect, useState } from 'react'
import { RECIPES, STICKER_NAMES, type Ingredient } from '../data'
import { useStore } from '../store'
import { BookmarkButton, DifficultyPill } from '../components/RecipeCard'
import { Alert, ArrowLeft, ArrowRight, Bookmark, Check, Chat, Clock, Refresh, Sparkle, Swap, Users, Wallet, X } from '../components/Icons'

type AiState = 'idle' | 'loading' | 'done' | 'error'

export function AiLoading({ label }: { label: string }) {
  return (
    <div role="status" className="space-y-3">
      <p className="flex items-center gap-2 text-sm font-semibold text-basil-600">
        <span className="flex gap-1">
          <span className="dot size-1.5 rounded-full bg-basil-500" />
          <span className="dot size-1.5 rounded-full bg-basil-500 [animation-delay:.15s]" />
          <span className="dot size-1.5 rounded-full bg-basil-500 [animation-delay:.3s]" />
        </span>
        {label}
      </p>
      <div className="skeleton h-3.5 w-11/12 rounded-full" />
      <div className="skeleton h-3.5 w-2/3 rounded-full" />
    </div>
  )
}

export function AiError({ onRetry }: { onRetry: () => void }) {
  return (
    <div role="alert" className="flex items-start gap-3">
      <span className="grid size-9 shrink-0 place-items-center rounded-full bg-tomato-100 text-tomato-600"><Alert size={18} /></span>
      <div className="flex-1">
        <p className="font-semibold text-charcoal">El asistente no está disponible</p>
        <p className="text-sm text-charcoal-soft">Intenta de nuevo en un momento.</p>
      </div>
      <button onClick={onRetry} className="flex items-center gap-1.5 rounded-full border-2 border-tomato-200 px-3.5 py-1.5 text-sm font-bold text-tomato-600 hover:bg-tomato-50">
        <Refresh size={15} /> Reintentar
      </button>
    </div>
  )
}

function IngredientRow({ ing, recipe }: { ing: Ingredient; recipe: string }) {
  const { aiDown } = useStore()
  const [state, setState] = useState<AiState>('idle')
  const [have, setHave] = useState(false)

  const ask = () => {
    setState('loading')
    setTimeout(() => setState(aiDown ? 'error' : 'done'), 1400)
  }

  return (
    <li className="border-b border-cream-200 last:border-0">
      <div className="flex items-center gap-3 py-3.5">
        <button
          onClick={() => setHave(!have)}
          aria-pressed={have}
          aria-label={`Marcar ${ing.name}`}
          className={`grid size-6 shrink-0 place-items-center rounded-lg border-2 transition ${have ? 'border-basil-500 bg-basil-500 text-white' : 'border-cream-300 bg-white'}`}
        >
          {have && <Check size={14} strokeWidth={3} />}
        </button>
        <div className={`flex-1 ${have ? 'text-charcoal-soft line-through decoration-cream-300' : ''}`}>
          <span className="font-semibold">{ing.name}</span>
          <span className="ml-2 text-charcoal-soft">{ing.qty}</span>
        </div>
        {state === 'idle' ? (
          <button onClick={ask} className="flex shrink-0 items-center gap-1.5 rounded-full bg-cream-200 px-3 py-1.5 text-[13px] font-bold text-charcoal transition hover:bg-basil-100 hover:text-basil-600">
            <Swap size={14} /> No lo tengo
          </button>
        ) : (
          <button onClick={() => setState('idle')} className="grid size-8 place-items-center rounded-full text-charcoal-soft hover:bg-cream-200" aria-label="Cerrar alternativas">
            <X size={16} />
          </button>
        )}
      </div>
      {state !== 'idle' && (
        <div className="rise mb-4 rounded-2xl border border-basil-100 bg-basil-50 p-4">
          <p className="mb-3 flex items-center gap-1.5 text-xs font-extrabold tracking-wide text-basil-600 uppercase">
            <Sparkle size={14} /> Alternativas sugeridas por IA
          </p>
          {state === 'loading' && <AiLoading label={`Buscando reemplazos para ${ing.name.toLowerCase()}…`} />}
          {state === 'error' && <AiError onRetry={ask} />}
          {state === 'done' && (
            <>
              <ul className="space-y-2">
                {ing.alts.map((a) => {
                  const [from, to] = a.includes('→') ? a.split('→').map((s) => s.trim()) : [null, a]
                  return (
                    <li key={a} className="flex items-start gap-3 rounded-xl bg-white p-3 text-[15px] shadow-soft">
                      <ArrowRight size={16} className="mt-1 shrink-0 text-basil-500" />
                      <span>
                        {from && <span className="text-charcoal-soft">{from} → </span>}
                        <span className="font-semibold">{to}</span>
                      </span>
                    </li>
                  )
                })}
              </ul>
              <p className="mt-3 text-xs text-charcoal-soft">Sugerencia generada por IA para «{recipe}». Revisa alérgenos antes de reemplazar.</p>
            </>
          )}
        </div>
      )}
    </li>
  )
}

export function Detail({ slug }: { slug: string }) {
  const r = RECIPES.find((x) => x.slug === slug) ?? RECIPES[0]
  const { cooked, markCooked, saved, toggleSave, showToast } = useStore()
  const isCooked = cooked.includes(r.slug)
  const isSaved = saved.includes(r.slug)
  const [done, setDone] = useState<number[]>([])
  useEffect(() => setDone([]), [slug])

  const onCooked = () => {
    if (!isCooked) showToast({ title: '¡Receta cocinada!', body: `Desbloqueaste el sticker «${STICKER_NAMES[r.sticker] ?? 'Nuevo'}» · ${cooked.length + 1} de 20`, sticker: r.sticker })
    markCooked(r.slug)
  }

  return (
    <article>
      <a href="#/recetas" className="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-charcoal-soft hover:text-charcoal">
        <ArrowLeft size={17} /> Volver a recetas
      </a>

      <div className="grid gap-8 lg:grid-cols-[1.15fr_1fr] lg:gap-12">
        <div className="relative overflow-hidden rounded-[32px] bg-cream-200 shadow-soft">
          <img src={r.image} alt={r.title} className="aspect-[4/3] size-full object-cover lg:aspect-auto lg:h-[520px]" />
          <BookmarkButton slug={r.slug} className="absolute top-5 right-5 size-12 lg:hidden" />
        </div>

        <div className="flex flex-col">
          <span className="w-fit rounded-full bg-tomato-50 px-3 py-1 text-sm font-bold text-tomato-600">{r.category}</span>
          <h1 className="mt-4 text-[38px] leading-[1.05] font-black md:text-[52px]">{r.title}</h1>
          <p className="mt-4 text-lg leading-relaxed text-charcoal-soft">{r.description}</p>

          <dl className="mt-7 grid grid-cols-2 gap-y-4 gap-2 rounded-3xl sm:grid-cols-4 bg-white p-4 shadow-soft">
            {[
              { i: Clock, k: 'Tiempo', v: `${r.minutes} min` },
              { i: null, k: 'Dificultad', v: <DifficultyPill level={r.difficulty} /> },
              { i: Users, k: 'Porciones', v: r.servings },
              { i: Wallet, k: 'Costo aprox.', v: r.cost },
            ].map((m) => (
              <div key={m.k} className="text-center">
                <dt className="text-xs text-charcoal-soft">{m.k}</dt>
                <dd className="mt-1 flex items-center justify-center gap-1 font-display text-[17px] font-extrabold">
                  {m.i && <m.i size={15} className="text-tomato-500" />}
                  {m.v}
                </dd>
              </div>
            ))}
          </dl>

          <div className="mt-5 flex gap-3">
            <button
              onClick={onCooked}
              className={`flex h-14 flex-1 items-center justify-center gap-2 rounded-2xl font-display text-[17px] font-extrabold transition active:translate-y-px ${
                isCooked ? 'bg-basil-100 text-basil-600' : 'bg-basil-500 text-white shadow-[inset_0_-4px_0_rgb(0_0_0/0.14)] hover:bg-basil-600'
              }`}
            >
              <Check size={20} strokeWidth={2.6} />
              {isCooked ? '¡Ya la cocinaste!' : 'Marcar como cocinada'}
            </button>
            <button
              onClick={() => toggleSave(r.slug)}
              aria-pressed={isSaved}
              className={`flex h-14 items-center gap-2 rounded-2xl border-2 px-5 font-display font-extrabold transition ${
                isSaved ? 'border-tomato-500 bg-tomato-50 text-tomato-600' : 'border-cream-300 bg-white hover:border-tomato-200'
              }`}
            >
              <Bookmark size={19} filled={isSaved} />
              <span className="hidden sm:inline">{isSaved ? 'Guardada' : 'Guardar'}</span>
            </button>
          </div>

          <div className="mt-5 flex gap-3 rounded-2xl border border-[#F3D18A] bg-[#FFF4D6] p-4">
            <Alert size={20} className="mt-0.5 shrink-0 text-[#B07A0B]" />
            <div className="text-[15px]">
              <p className="font-bold text-[#7A5304]">Alérgenos</p>
              <p className="mt-0.5 text-[#7A5304]/90">
                {r.allergens.join(' · ')}. Revisa las etiquetas de tus productos: pueden contener trazas.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div className="mt-12 grid gap-8 lg:grid-cols-[1fr_1.15fr] lg:gap-12">
        <section className="h-fit rounded-[28px] bg-white p-6 shadow-soft md:p-8">
          <div className="flex items-baseline justify-between">
            <h2 className="text-2xl font-black">Ingredientes</h2>
            <span className="text-sm text-charcoal-soft">{r.servings} porciones</span>
          </div>
          <p className="mt-1 mb-3 text-sm text-charcoal-soft">¿Te falta algo? Toca «No lo tengo» y la IA te sugiere un reemplazo.</p>
          <ul>{r.ingredients.map((i) => <IngredientRow key={r.slug + i.name} ing={i} recipe={r.title} />)}</ul>
        </section>

        <section>
          <div className="flex items-baseline justify-between">
            <h2 className="text-2xl font-black">Preparación</h2>
            <span className="text-sm text-charcoal-soft">{done.length} de {r.steps.length} pasos</span>
          </div>
          <ol className="mt-5 space-y-3">
            {r.steps.map((s, idx) => {
              const ok = done.includes(idx)
              return (
                <li key={idx}>
                  <button
                    onClick={() => setDone(ok ? done.filter((d) => d !== idx) : [...done, idx])}
                    className={`flex w-full gap-4 rounded-2xl p-4 text-left transition ${ok ? 'bg-basil-50' : 'bg-white shadow-soft hover:shadow-lift'}`}
                  >
                    <span className={`grid size-10 shrink-0 place-items-center rounded-full font-display text-lg font-black ${ok ? 'bg-basil-500 text-white' : 'bg-tomato-50 text-tomato-600'}`}>
                      {ok ? <Check size={18} strokeWidth={3} /> : idx + 1}
                    </span>
                    <span className={`pt-1.5 text-[16px] leading-relaxed ${ok ? 'text-charcoal-soft' : ''}`}>{s}</span>
                  </button>
                </li>
              )
            })}
          </ol>

          <a href="#/asistente" className="mt-6 flex items-center gap-4 rounded-2xl border-2 border-dashed border-basil-100 bg-basil-50/60 p-5 transition hover:border-basil-500">
            <span className="grid size-11 place-items-center rounded-xl bg-basil-500 text-white"><Chat size={20} /></span>
            <span className="flex-1">
              <span className="block font-display font-extrabold">¿Dudas con algún paso?</span>
              <span className="block text-sm text-charcoal-soft">Pregúntale al asistente sobre {r.title.toLowerCase()}.</span>
            </span>
            <ArrowRight size={18} className="text-basil-600" />
          </a>
        </section>
      </div>
    </article>
  )
}
