import { RECIPES } from '../data'
import { useStore } from '../store'
import { RecipeCard } from '../components/RecipeCard'
import { Sticker } from '../components/Stickers'
import { Bookmark } from '../components/Icons'

export function Saved() {
  const { saved } = useStore()
  const list = RECIPES.filter((r) => saved.includes(r.slug))
  return (
    <div>
      <div className="flex flex-wrap items-end justify-between gap-4">
        <div>
          <h1 className="text-[34px] leading-tight font-black md:text-[44px]">Guardados</h1>
          <p className="mt-1 text-charcoal-soft">{list.length ? `${list.length} recetas para cuando te dé hambre.` : 'Tu recetario personal.'}</p>
        </div>
      </div>

      {list.length ? (
        <div className="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          {list.map((r) => <RecipeCard key={r.slug} r={r} />)}
        </div>
      ) : (
        <div className="mt-8 flex flex-col items-center rounded-[32px] bg-white px-6 py-16 text-center shadow-soft md:py-24">
          <div className="relative h-32 w-48">
            <div className="absolute left-2 top-6"><Sticker kind="pan" size={84} rotate={-14} /></div>
            <div className="absolute right-2 top-0"><Sticker kind="tomate" size={96} rotate={10} /></div>
          </div>
          <h2 className="mt-4 text-[26px] font-black">Aún no guardas recetas</h2>
          <p className="mt-2 max-w-sm text-charcoal-soft">
            Toca el ícono <Bookmark size={16} className="inline -translate-y-px text-tomato-500" /> en cualquier receta y aparecerá aquí para tenerla a mano.
          </p>
          <a href="#/recetas" className="mt-7 rounded-2xl bg-tomato-500 px-6 py-3.5 font-display font-extrabold text-white shadow-[inset_0_-4px_0_rgb(0_0_0/0.14)] hover:bg-tomato-600">
            Explorar recetas
          </a>
        </div>
      )}
    </div>
  )
}
