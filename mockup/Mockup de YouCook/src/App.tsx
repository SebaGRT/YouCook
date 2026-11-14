import { useState } from 'react'
import { StoreProvider, useStore } from './store'
import { Nav } from './components/Nav'
import { Sticker } from './components/Stickers'
import { Sliders, X } from './components/Icons'
import { Auth } from './screens/Auth'
import { Catalog } from './screens/Catalog'
import { Detail } from './screens/Detail'
import { Assistant } from './screens/Assistant'
import { Saved } from './screens/Saved'
import { Profile } from './screens/Profile'

function Toast() {
  const { toast } = useStore()
  if (!toast) return null
  return (
    <div role="status" className="rise fixed bottom-24 left-1/2 z-50 flex w-[calc(100%-2rem)] max-w-sm -translate-x-1/2 items-center gap-4 rounded-3xl bg-charcoal p-4 pr-6 text-white shadow-lift md:bottom-8">
      {toast.sticker && <div className="pop"><Sticker kind={toast.sticker} size={64} rotate={-8} /></div>}
      <div>
        <p className="font-display text-lg font-black">{toast.title}</p>
        {toast.body && <p className="text-sm text-white/75">{toast.body}</p>}
      </div>
    </div>
  )
}

/** Panel solo del mockup para revisar pantallas y estados. */
function MockupPanel() {
  const { aiDown, setAiDown, emptySaved, setEmptySaved } = useStore()
  const [open, setOpen] = useState(false)
  const screens = [
    ['#/registro', 'Registro'],
    ['#/login', 'Inicio de sesión'],
    ['#/recetas', 'Catálogo'],
    ['#/recetas/charquican', 'Detalle de receta'],
    ['#/asistente', 'Asistente'],
    ['#/guardados', 'Guardados'],
    ['#/perfil', 'Perfil'],
  ]
  return (
    <div className="fixed bottom-20 left-4 z-50 md:bottom-6 md:left-6">
      {open ? (
        <div className="rise w-64 rounded-3xl bg-white p-4 text-sm shadow-lift ring-1 ring-cream-300">
          <div className="mb-2 flex items-center justify-between">
            <p className="font-display font-extrabold">Mockup · pantallas</p>
            <button onClick={() => setOpen(false)} aria-label="Cerrar" className="text-charcoal-soft"><X size={16} /></button>
          </div>
          <ul className="space-y-0.5">
            {screens.map(([h, l]) => (
              <li key={h}><a href={h} className="block rounded-lg px-2 py-1.5 hover:bg-cream-200">{l}</a></li>
            ))}
          </ul>
          <p className="mt-3 mb-1.5 font-display font-extrabold">Estados</p>
          {[
            ['IA no disponible (error)', aiDown, setAiDown],
            ['Guardados vacío', emptySaved, setEmptySaved],
          ].map(([l, v, set]) => (
            <label key={l as string} className="flex cursor-pointer items-center justify-between rounded-lg px-2 py-1.5 hover:bg-cream-200">
              {l as string}
              <input type="checkbox" checked={v as boolean} onChange={(e) => (set as (b: boolean) => void)(e.target.checked)} className="size-4 accent-tomato-500" />
            </label>
          ))}
          <p className="mt-2 px-2 text-xs text-charcoal-soft">La carga de IA se ve al tocar «No lo tengo» o enviar un mensaje.</p>
        </div>
      ) : (
        <button onClick={() => setOpen(true)} className="flex items-center gap-2 rounded-full bg-white px-4 py-2.5 text-sm font-bold shadow-lift ring-1 ring-cream-300">
          <Sliders size={16} /> Mockup
        </button>
      )}
    </div>
  )
}

function Screens() {
  const { route } = useStore()
  if (route.name === 'login' || route.name === 'registro') return <Auth mode={route.name} />
  return (
    <>
      <Nav />
      <main className="mx-auto max-w-[1280px] px-5 pt-[96px] pb-28 md:px-10 md:pt-[104px] md:pb-16">
        {route.name === 'recetas' && <Catalog />}
        {route.name === 'receta' && <Detail slug={route.slug} />}
        {route.name === 'asistente' && <Assistant />}
        {route.name === 'guardados' && <Saved />}
        {route.name === 'perfil' && <Profile />}
      </main>
      <Toast />
    </>
  )
}

export default function App() {
  return (
    <StoreProvider>
      <Screens />
      <MockupPanel />
    </StoreProvider>
  )
}
