import { createContext, useCallback, useContext, useEffect, useState, type ReactNode } from 'react'
import { INITIAL_COOKED, INITIAL_SAVED } from './data'

export type Route =
  | { name: 'login' | 'registro' | 'recetas' | 'guardados' | 'asistente' | 'perfil' }
  | { name: 'receta'; slug: string }

function parse(hash: string): Route {
  const parts = hash.replace(/^#\/?/, '').split('/').filter(Boolean)
  const [a, b] = parts
  if (a === 'recetas' && b) return { name: 'receta', slug: b }
  if (a === 'login' || a === 'registro' || a === 'guardados' || a === 'asistente' || a === 'perfil') return { name: a }
  return { name: 'recetas' }
}

type Store = {
  route: Route
  saved: string[]
  cooked: string[]
  aiDown: boolean
  emptySaved: boolean
  toast: { title: string; body?: string; sticker?: string } | null
  toggleSave: (slug: string) => void
  markCooked: (slug: string) => void
  setAiDown: (v: boolean) => void
  setEmptySaved: (v: boolean) => void
  showToast: (t: Store['toast']) => void
}

const Ctx = createContext<Store | null>(null)

export function StoreProvider({ children }: { children: ReactNode }) {
  const [route, setRoute] = useState<Route>(() => parse(location.hash))
  const [saved, setSaved] = useState(INITIAL_SAVED)
  const [cooked, setCooked] = useState(INITIAL_COOKED)
  const [aiDown, setAiDown] = useState(false)
  const [emptySaved, setEmptySaved] = useState(false)
  const [toast, setToast] = useState<Store['toast']>(null)

  useEffect(() => {
    const on = () => {
      setRoute(parse(location.hash))
      window.scrollTo({ top: 0 })
    }
    window.addEventListener('hashchange', on)
    return () => window.removeEventListener('hashchange', on)
  }, [])

  useEffect(() => {
    if (!toast) return
    const t = setTimeout(() => setToast(null), 3800)
    return () => clearTimeout(t)
  }, [toast])

  const toggleSave = useCallback((slug: string) => {
    setEmptySaved(false)
    setSaved((s) => (s.includes(slug) ? s.filter((x) => x !== slug) : [...s, slug]))
  }, [])
  const markCooked = useCallback((slug: string) => {
    setCooked((c) => (c.includes(slug) ? c.filter((x) => x !== slug) : [...c, slug]))
  }, [])

  return (
    <Ctx.Provider
      value={{ route, saved: emptySaved ? [] : saved, cooked, aiDown, emptySaved, toast, toggleSave, markCooked, setAiDown, setEmptySaved, showToast: setToast }}
    >
      {children}
    </Ctx.Provider>
  )
}

export const useStore = () => useContext(Ctx)!
