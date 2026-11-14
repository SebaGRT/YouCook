import { useState } from 'react'
import { Logo } from '../components/Nav'
import { Sticker } from '../components/Stickers'
import { Eye, Lock, Mail, User } from '../components/Icons'

function Field({ label, type = 'text', name, placeholder, icon: Icon, hint }: { label: string; type?: string; name: string; placeholder: string; icon: typeof Mail; hint?: string }) {
  const [show, setShow] = useState(false)
  const isPw = type === 'password'
  return (
    <label className="block">
      <span className="mb-2 block text-sm font-semibold">{label}</span>
      <span className="flex h-13 items-center gap-3 rounded-2xl border-2 border-cream-300 bg-white px-4 transition focus-within:border-tomato-500 focus-within:ring-4 focus-within:ring-tomato-100">
        <Icon size={19} className="text-charcoal-soft" />
        <input name={name} type={isPw && show ? 'text' : type} placeholder={placeholder} required className="h-12 w-full bg-transparent text-[15px] outline-none placeholder:text-[#B3A99D]" />
        {isPw && (
          <button type="button" onClick={() => setShow(!show)} className="text-charcoal-soft hover:text-charcoal" aria-label="Mostrar contraseña">
            <Eye size={19} />
          </button>
        )}
      </span>
      {hint && <span className="mt-1.5 block text-xs text-charcoal-soft">{hint}</span>}
    </label>
  )
}

export function Auth({ mode }: { mode: 'login' | 'registro' }) {
  const isReg = mode === 'registro'
  return (
    <div className="grid min-h-screen lg:grid-cols-[1.05fr_1fr]">
      <aside className="relative hidden overflow-hidden lg:block">
        <img
          src="https://images.unsplash.com/photo-1653233797467-1a528819fd4f?crop=entropy&cs=tinysrgb&fit=crop&fm=jpg&q=80&w=1400"
          alt="Persona picando verduras en una tabla"
          className="absolute inset-0 size-full object-cover"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-[#2E2B28]/85 via-[#2E2B28]/25 to-transparent" />
        <div className="absolute top-10 left-10 rounded-2xl bg-cream px-4 py-3"><Logo /></div>
        <div className="absolute right-12 bottom-12 left-12 text-white">
          <div className="mb-6 flex gap-2">
            <Sticker kind="huevo" size={76} rotate={-8} />
            <Sticker kind="tallarin" size={76} rotate={6} />
            <Sticker kind="palta" size={76} rotate={-4} />
          </div>
          <h2 className="max-w-md text-[44px] leading-[1.05] font-black">Cocina rico con lo que tienes en el refri.</h2>
          <p className="mt-4 max-w-md text-lg text-white/85">Recetas simples, alternativas para ingredientes que te faltan y un asistente que responde en segundos.</p>
        </div>
      </aside>

      <main className="flex items-center justify-center px-6 py-12 sm:px-12">
        <div className="w-full max-w-[420px]">
          <Logo className="mb-10 lg:hidden" />
          <h1 className="text-[34px] leading-tight font-black">{isReg ? 'Crea tu cuenta' : '¡Hola de nuevo!'}</h1>
          <p className="mt-2 text-charcoal-soft">{isReg ? 'Guarda recetas, colecciona stickers y pregúntale todo al asistente.' : 'Ingresa para ver tus recetas guardadas.'}</p>

          <form
            method="POST"
            action={isReg ? '/register' : '/login'}
            className="mt-8 space-y-5"
            onSubmit={(e) => {
              e.preventDefault()
              location.hash = '#/recetas'
            }}
          >
            {isReg && <Field label="Nombre" name="name" placeholder="Camila Rojas" icon={User} />}
            <Field label="Correo" type="email" name="email" placeholder="camila@uni.cl" icon={Mail} />
            <Field label="Contraseña" type="password" name="password" placeholder="••••••••" icon={Lock} hint={isReg ? 'Mínimo 8 caracteres.' : undefined} />
            {!isReg && (
              <div className="flex items-center justify-between text-sm">
                <label className="flex items-center gap-2"><input type="checkbox" name="remember" className="size-4 accent-tomato-500" /> Recordarme</label>
                <a href="#/login" className="font-semibold text-tomato-600 hover:underline">¿Olvidaste tu contraseña?</a>
              </div>
            )}
            <button className="h-13 w-full rounded-2xl bg-tomato-500 font-display text-[17px] font-extrabold text-white shadow-[inset_0_-4px_0_rgb(0_0_0/0.14)] transition hover:bg-tomato-600 active:translate-y-px">
              {isReg ? 'Crear cuenta' : 'Iniciar sesión'}
            </button>
          </form>

          <p className="mt-8 text-center text-[15px] text-charcoal-soft">
            {isReg ? '¿Ya tienes cuenta? ' : '¿Primera vez en YouCook? '}
            <a href={isReg ? '#/login' : '#/registro'} className="font-bold text-tomato-600 hover:underline">
              {isReg ? 'Inicia sesión' : 'Regístrate gratis'}
            </a>
          </p>
        </div>
      </main>
    </div>
  )
}
