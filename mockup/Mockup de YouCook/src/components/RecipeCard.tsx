import type { Recipe } from '../data'
import { useStore } from '../store'
import { Bookmark, Clock, Flame } from './Icons'

export function DifficultyPill({ level }: { level: Recipe['difficulty'] }) {
  return (
    <span className="inline-flex items-center gap-1">
      <Flame size={15} className={level === 'Fácil' ? 'text-basil-500' : 'text-[#E39A1B]'} />
      {level}
    </span>
  )
}

export function BookmarkButton({ slug, className = '' }: { slug: string; className?: string }) {
  const { saved, toggleSave } = useStore()
  const on = saved.includes(slug)
  return (
    <button
      type="button"
      onClick={(e) => {
        e.preventDefault()
        toggleSave(slug)
      }}
      aria-pressed={on}
      aria-label={on ? 'Quitar de guardados' : 'Guardar receta'}
      className={`grid size-10 place-items-center rounded-full shadow-soft transition active:scale-90 ${
        on ? 'bg-tomato-500 text-white' : 'bg-white/95 text-charcoal hover:text-tomato-500'
      } ${className}`}
    >
      <Bookmark size={18} filled={on} />
    </button>
  )
}

export function RecipeCard({ r }: { r: Recipe }) {
  const { cooked } = useStore()
  return (
    <a href={`#/recetas/${r.slug}`} className="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-soft transition duration-300 hover:-translate-y-1 hover:shadow-lift">
      <div className="relative aspect-[4/3] overflow-hidden bg-cream-200">
        <img src={r.image} alt={r.title} loading="lazy" className="size-full object-cover transition duration-500 group-hover:scale-105" />
        <BookmarkButton slug={r.slug} className="absolute top-3 right-3" />
        <span className="absolute bottom-3 left-3 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-charcoal">{r.category}</span>
        {cooked.includes(r.slug) && (
          <span className="absolute top-3 left-3 rounded-full bg-basil-500 px-2.5 py-1 text-[11px] font-bold text-white">Cocinada</span>
        )}
      </div>
      <div className="flex flex-1 flex-col gap-3 p-5">
        <h3 className="text-[19px] leading-snug font-extrabold">{r.title}</h3>
        <div className="mt-auto flex items-center gap-4 text-[13px] font-medium text-charcoal-soft">
          <span className="inline-flex items-center gap-1">
            <Clock size={15} />
            {r.minutes} min
          </span>
          <DifficultyPill level={r.difficulty} />
          <span className="ml-auto font-semibold text-charcoal">{r.cost}</span>
        </div>
      </div>
    </a>
  )
}
