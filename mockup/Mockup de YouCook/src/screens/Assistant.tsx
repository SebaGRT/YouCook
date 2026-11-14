import { useEffect, useRef, useState } from 'react'
import { RECIPES } from '../data'
import { useStore } from '../store'
import { AiError } from './Detail'
import { Info, Send, Sparkle } from '../components/Icons'
import { Avatar } from '../components/Nav'

type Msg = { id: number; from: 'user' | 'ai'; text: string; status?: 'loading' | 'error' }

const ANSWERS: Record<string, string> = {
  '¿Cómo sé si está cocido?':
    'Para el charquicán: pincha un trozo de papa y zapallo con un tenedor. Si entra sin esfuerzo y se deshace un poco, están listos (unos 15 min hirviendo). La carne está cocida cuando ya no se ve rosada.',
  '¿Puedo congelarlo?':
    'Sí. Déjalo enfriar, guárdalo en porciones en potes herméticos y congélalo hasta por 2 meses. Para recalentarlo, ponle un chorrito de agua y calienta a fuego bajo o 3 min en el microondas, revolviendo a la mitad.',
  '¿Con qué lo acompaño?': 'Lo típico es un huevo frito encima y una ensalada chilena al lado. Si quieres algo más barato, con pan tostado también queda bien.',
  '¿Lo puedo hacer sin carne?': 'Claro. Reemplaza la carne por 1 lata de porotos o lentejas cocidas y escurridas. Agrégalas en el paso 4 para que no se deshagan.',
}

const CHIPS = Object.keys(ANSWERS)

const INITIAL: Msg[] = [
  { id: 1, from: 'ai', text: '¡Hola, Camila! Soy tu asistente de cocina. Estás viendo el charquicán: pregúntame lo que necesites, te respondo corto y al grano.' },
  { id: 2, from: 'user', text: 'No tengo zapallo, ¿igual me queda bien?' },
  { id: 3, from: 'ai', text: 'Sí, te queda bien. Usa 2 zanahorias ralladas en vez del zapallo: le dan el mismo color y un toque dulce. Cuécelas junto con las papas.' },
]

function Bubble({ m, onRetry }: { m: Msg; onRetry: () => void }) {
  if (m.from === 'user')
    return (
      <div className="rise flex justify-end gap-3">
        <p className="max-w-[78%] rounded-3xl rounded-br-md bg-tomato-500 px-5 py-3 text-[15px] leading-relaxed text-white">{m.text}</p>
        <Avatar size={34} />
      </div>
    )
  return (
    <div className="rise flex gap-3">
      <span className="grid size-[34px] shrink-0 place-items-center rounded-full bg-basil-500 text-white"><Sparkle size={17} /></span>
      <div className={`max-w-[78%] rounded-3xl rounded-bl-md px-5 py-3 text-[15px] leading-relaxed ${m.status === 'error' ? 'border border-tomato-100 bg-tomato-50/60' : 'bg-white shadow-soft'}`}>
        {m.status === 'loading' ? (
          <span role="status" className="flex items-center gap-3 py-1 text-sm text-charcoal-soft">
            <span className="flex gap-1">
              <span className="dot size-2 rounded-full bg-basil-500" />
              <span className="dot size-2 rounded-full bg-basil-500 [animation-delay:.15s]" />
              <span className="dot size-2 rounded-full bg-basil-500 [animation-delay:.3s]" />
            </span>
            Pensando la respuesta…
          </span>
        ) : m.status === 'error' ? (
          <AiError onRetry={onRetry} />
        ) : (
          m.text
        )}
      </div>
    </div>
  )
}

export function Assistant() {
  const { aiDown } = useStore()
  const [msgs, setMsgs] = useState<Msg[]>(INITIAL)
  const [text, setText] = useState('')
  const end = useRef<HTMLDivElement>(null)
  const busy = msgs.some((m) => m.status === 'loading')
  const recipe = RECIPES[0]

  useEffect(() => end.current?.scrollIntoView({ behavior: 'smooth', block: 'nearest' }), [msgs])

  const respond = (q: string, id: number) => {
    setTimeout(() => {
      setMsgs((ms) =>
        ms.map((m) =>
          m.id === id
            ? aiDown
              ? { ...m, status: 'error', text: q }
              : { ...m, status: undefined, text: ANSWERS[q] ?? 'Buena pregunta. En corto: cocina a fuego medio, prueba la sal al final y si algo se pega, agrega un chorrito de agua y raspa el fondo.' }
            : m,
        ),
      )
    }, 1600)
  }

  const send = (q: string) => {
    if (!q.trim() || busy) return
    const id = Date.now()
    setMsgs((ms) => [...ms, { id: id - 1, from: 'user', text: q }, { id, from: 'ai', text: q, status: 'loading' }])
    setText('')
    respond(q, id)
  }

  const retry = (id: number, q: string) => {
    setMsgs((ms) => ms.map((m) => (m.id === id ? { ...m, status: 'loading' } : m)))
    respond(q, id)
  }

  return (
    <div className="grid gap-8 lg:grid-cols-[300px_1fr]">
      <aside className="hidden space-y-5 lg:block">
        <div>
          <h1 className="text-[32px] leading-tight font-black">Asistente</h1>
          <p className="mt-1 text-charcoal-soft">Respuestas cortas, sin videos de 15 minutos.</p>
        </div>
        <a href={`#/recetas/${recipe.slug}`} className="block overflow-hidden rounded-3xl bg-white shadow-soft">
          <img src={recipe.image} alt="" className="aspect-[16/10] w-full object-cover" />
          <div className="p-4">
            <p className="text-xs font-bold tracking-wide text-charcoal-soft uppercase">Hablando sobre</p>
            <p className="font-display text-lg font-extrabold">{recipe.title}</p>
          </div>
        </a>
        <div className="rounded-3xl bg-cream-200 p-5 text-sm">
          <p className="font-display font-extrabold">Puedes preguntar cosas como</p>
          <ul className="mt-2 list-disc space-y-1.5 pl-4 text-charcoal-soft">
            <li>¿Qué hago con arroz, huevo y cebolla?</li>
            <li>¿Cuánto dura en el refri?</li>
            <li>Se me pasó de sal, ¿cómo lo arreglo?</li>
          </ul>
        </div>
      </aside>

      <section className="flex h-[calc(100vh-170px)] min-h-[560px] flex-col overflow-hidden rounded-[32px] bg-cream-200/60 ring-1 ring-cream-300 md:h-[calc(100vh-150px)]">
        <header className="flex items-center gap-3 border-b border-cream-300 bg-white/70 px-6 py-4">
          <span className="grid size-11 place-items-center rounded-2xl bg-basil-500 text-white"><Sparkle size={22} /></span>
          <div className="flex-1">
            <p className="font-display text-lg font-extrabold">Asistente YouCook</p>
            <p className={`flex items-center gap-1.5 text-xs font-semibold ${aiDown ? 'text-tomato-600' : 'text-basil-600'}`}>
              <span className={`size-2 rounded-full ${aiDown ? 'bg-tomato-500' : 'bg-basil-500'}`} />
              {aiDown ? 'Sin conexión con la IA' : 'En línea · responde en segundos'}
            </p>
          </div>
          <button onClick={() => setMsgs(INITIAL.slice(0, 1))} className="rounded-full px-3 py-1.5 text-sm font-semibold text-charcoal-soft hover:bg-cream-200">Nueva conversación</button>
        </header>

        <div className="flex-1 space-y-5 overflow-y-auto px-4 py-6 md:px-8" aria-live="polite">
          <p className="mx-auto w-fit rounded-full bg-white/80 px-3 py-1 text-xs text-charcoal-soft">Hoy, 19:42</p>
          {msgs.map((m) => <Bubble key={m.id} m={m} onRetry={() => retry(m.id, m.text)} />)}
          <div ref={end} />
        </div>

        <div className="border-t border-cream-300 bg-white px-4 pt-4 pb-3 md:px-6">
          <div className="-mx-1 mb-3 flex gap-2 overflow-x-auto px-1 pb-1">
            {CHIPS.map((c) => (
              <button
                key={c}
                disabled={busy}
                onClick={() => send(c)}
                className="shrink-0 rounded-full border-2 border-basil-100 bg-basil-50 px-4 py-2 text-sm font-semibold text-basil-600 transition hover:border-basil-500 disabled:opacity-50"
              >
                {c}
              </button>
            ))}
          </div>
          <form
            method="POST"
            action="/asistente"
            onSubmit={(e) => {
              e.preventDefault()
              send(text)
            }}
            className="flex items-center gap-2 rounded-2xl border-2 border-cream-300 bg-cream/60 p-1.5 pl-4 focus-within:border-tomato-500"
          >
            <input
              name="mensaje"
              value={text}
              onChange={(e) => setText(e.target.value)}
              placeholder="Escribe tu duda de cocina…"
              className="h-11 flex-1 bg-transparent text-[15px] outline-none placeholder:text-[#B3A99D]"
            />
            <button disabled={!text.trim() || busy} className="grid size-11 place-items-center rounded-xl bg-tomato-500 text-white transition hover:bg-tomato-600 disabled:bg-cream-300" aria-label="Enviar">
              <Send size={19} />
            </button>
          </form>
          <p className="mt-2.5 flex items-center justify-center gap-1.5 text-xs text-charcoal-soft">
            <Info size={13} /> La IA puede equivocarse. Verifica alérgenos y tiempos de cocción.
          </p>
        </div>
      </section>
    </div>
  )
}
