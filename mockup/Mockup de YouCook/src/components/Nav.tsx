import { useStore } from '../store'
import { Bookmark, Chat, Home, User } from './Icons'

export function Logo({ className = '' }: { className?: string }) {
  return (
    <a href="#/recetas" className={`flex items-center gap-2 ${className}`}>
      <span className="grid size-9 place-items-center rounded-xl bg-tomato-500 text-white shadow-[inset_0_-3px_0_rgb(0_0_0/0.15)]">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round" aria-hidden>
          <path d="M5 11h14v3a6 6 0 0 1-6 6h-2a6 6 0 0 1-6-6z" fill="currentColor" fillOpacity=".25" />
          <path d="M3 11h18M9 7c-1-1.5 1-2.5 0-4M14 7c-1-1.5 1-2.5 0-4" />
        </svg>
      </span>
      <span className="font-display text-[22px] font-black tracking-tight">
        You<span className="text-tomato-500">Cook</span>
      </span>
    </a>
  )
}

const links = [
  { to: 'recetas', label: 'Recetas', icon: Home },
  { to: 'guardados', label: 'Guardados', icon: Bookmark },
  { to: 'asistente', label: 'Asistente', icon: Chat },
] as const

export function Nav() {
  const { route, saved, cooked } = useStore()
  const active = route.name === 'receta' ? 'recetas' : route.name
  return (
    <>
      <header className="fixed inset-x-0 top-0 z-40 border-b border-cream-300/70 bg-cream/85 backdrop-blur-md">
        <nav className="mx-auto flex h-[72px] max-w-[1280px] items-center gap-8 px-5 md:px-10">
          <Logo />
          <ul className="ml-4 hidden items-center gap-1 md:flex">
            {links.map((l) => (
              <li key={l.to}>
                <a
                  href={`#/${l.to}`}
                  aria-current={active === l.to ? 'page' : undefined}
                  className={`relative flex items-center gap-2 rounded-full px-4 py-2 font-display text-[15px] font-bold transition ${
                    active === l.to ? 'bg-tomato-50 text-tomato-600' : 'text-charcoal-soft hover:bg-cream-200 hover:text-charcoal'
                  }`}
                >
                  {l.label}
                  {l.to === 'guardados' && saved.length > 0 && (
                    <span className="grid h-5 min-w-5 place-items-center rounded-full bg-tomato-500 px-1.5 text-[11px] font-bold text-white">{saved.length}</span>
                  )}
                  {l.to === 'asistente' && <span className="rounded-full bg-basil-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide text-basil-600">IA</span>}
                </a>
              </li>
            ))}
          </ul>
          <a
            href="#/perfil"
            className={`ml-auto flex items-center gap-3 rounded-full p-1 pr-1 transition md:pr-4 ${active === 'perfil' ? 'bg-tomato-50' : 'hover:bg-cream-200'}`}
            aria-label="Perfil"
          >
            <Avatar size={40} />
            <span className="hidden text-left leading-tight md:block">
              <span className="block font-display text-sm font-bold">Camila R.</span>
              <span className="block text-xs text-charcoal-soft">{cooked.length} de 20 recetas</span>
            </span>
          </a>
        </nav>
      </header>
      {/* Barra inferior en móvil */}
      <nav className="fixed inset-x-0 bottom-0 z-40 grid grid-cols-4 border-t border-cream-300 bg-white/95 pb-[env(safe-area-inset-bottom)] backdrop-blur md:hidden">
        {[...links, { to: 'perfil', label: 'Perfil', icon: User } as const].map((l) => (
          <a key={l.to} href={`#/${l.to}`} className={`flex flex-col items-center gap-1 py-2.5 text-[11px] font-semibold ${active === l.to ? 'text-tomato-600' : 'text-charcoal-soft'}`}>
            <l.icon size={22} />
            {l.label}
          </a>
        ))}
      </nav>
    </>
  )
}

export function Avatar({ size = 40 }: { size?: number }) {
  return (
    <span
      className="grid shrink-0 place-items-center rounded-full bg-gradient-to-br from-[#FFC23D] to-tomato-500 font-display font-black text-white ring-2 ring-white"
      style={{ width: size, height: size, fontSize: size * 0.4 }}
    >
      C
    </span>
  )
}
