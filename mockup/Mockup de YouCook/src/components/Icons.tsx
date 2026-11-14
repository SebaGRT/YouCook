import type { SVGProps } from 'react'

type P = SVGProps<SVGSVGElement> & { size?: number }
const base = ({ size = 20, ...p }: P) => ({
  width: size,
  height: size,
  viewBox: '0 0 24 24',
  fill: 'none',
  stroke: 'currentColor',
  strokeWidth: 2,
  strokeLinecap: 'round' as const,
  strokeLinejoin: 'round' as const,
  'aria-hidden': true,
  ...p,
})

export const Search = (p: P) => (<svg {...base(p)}><circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" /></svg>)
export const Bookmark = ({ filled, ...p }: P & { filled?: boolean }) => (
  <svg {...base(p)} fill={filled ? 'currentColor' : 'none'}><path d="M6 4.5A1.5 1.5 0 0 1 7.5 3h9A1.5 1.5 0 0 1 18 4.5V21l-6-4-6 4z" /></svg>
)
export const Clock = (p: P) => (<svg {...base(p)}><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" /></svg>)
export const Flame = (p: P) => (<svg {...base(p)}><path d="M12 3s5 4.5 5 10a5 5 0 0 1-10 0c0-2.5 1.5-4 1.5-4s.5 2 2 2c0-3 1.5-6 1.5-8z" /></svg>)
export const Users = (p: P) => (<svg {...base(p)}><circle cx="9" cy="8" r="3.5" /><path d="M2.5 20a6.5 6.5 0 0 1 13 0M16 4.5a3.5 3.5 0 0 1 0 7M21.5 20a6.5 6.5 0 0 0-4-6" /></svg>)
export const Wallet = (p: P) => (<svg {...base(p)}><rect x="3" y="6" width="18" height="14" rx="3" /><path d="M16 13h2M3 10h18M6 6l9-3 1.5 3" /></svg>)
export const Check = (p: P) => (<svg {...base(p)}><path d="m5 12.5 4.5 4.5L19 7.5" /></svg>)
export const Sparkle = (p: P) => (<svg {...base(p)}><path d="M12 3c.6 4.2 2.8 6.4 7 7-4.2.6-6.4 2.8-7 7-.6-4.2-2.8-6.4-7-7 4.2-.6 6.4-2.8 7-7zM19 16c.3 1.6 1 2.4 2.5 2.5-1.6.3-2.3 1-2.5 2.5-.3-1.6-1-2.3-2.5-2.5 1.6-.2 2.3-1 2.5-2.5z" /></svg>)
export const Send = (p: P) => (<svg {...base(p)}><path d="M4 12 20 4l-6 16-3-7z" /><path d="m11 13 9-9" /></svg>)
export const Alert = (p: P) => (<svg {...base(p)}><path d="M12 3.5 22 20H2z" /><path d="M12 10v4M12 17h.01" /></svg>)
export const Info = (p: P) => (<svg {...base(p)}><circle cx="12" cy="12" r="9" /><path d="M12 11v5M12 8h.01" /></svg>)
export const ArrowLeft = (p: P) => (<svg {...base(p)}><path d="M19 12H5M11 6l-6 6 6 6" /></svg>)
export const ArrowRight = (p: P) => (<svg {...base(p)}><path d="M5 12h14M13 6l6 6-6 6" /></svg>)
export const X = (p: P) => (<svg {...base(p)}><path d="M6 6l12 12M18 6 6 18" /></svg>)
export const Refresh = (p: P) => (<svg {...base(p)}><path d="M20 11a8 8 0 0 0-14.3-4.6L4 8M4 4v4h4M4 13a8 8 0 0 0 14.3 4.6L20 16M20 20v-4h-4" /></svg>)
export const Lock = (p: P) => (<svg {...base(p)}><rect x="5" y="11" width="14" height="10" rx="2.5" /><path d="M8 11V8a4 4 0 0 1 8 0v3" /></svg>)
export const Home = (p: P) => (<svg {...base(p)}><path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-4.5v-6h-5v6H5a1 1 0 0 1-1-1z" /></svg>)
export const Chat = (p: P) => (<svg {...base(p)}><path d="M20 12a8 8 0 0 1-11.8 7L4 20l1.2-3.8A8 8 0 1 1 20 12z" /></svg>)
export const User = (p: P) => (<svg {...base(p)}><circle cx="12" cy="8" r="4" /><path d="M4 21a8 8 0 0 1 16 0" /></svg>)
export const Swap = (p: P) => (<svg {...base(p)}><path d="M7 4 3 8l4 4M3 8h14M17 20l4-4-4-4M21 16H7" /></svg>)
export const Mail = (p: P) => (<svg {...base(p)}><rect x="3" y="5" width="18" height="14" rx="3" /><path d="m4 7 8 6 8-6" /></svg>)
export const Eye = (p: P) => (<svg {...base(p)}><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z" /><circle cx="12" cy="12" r="3" /></svg>)
export const Pencil = (p: P) => (<svg {...base(p)}><path d="M4 20h4L19 9l-4-4L4 16z" /><path d="m13.5 6.5 4 4" /></svg>)
export const Logout = (p: P) => (<svg {...base(p)}><path d="M15 4h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-3M10 16l-4-4 4-4M6 12h10" /></svg>)
export const Sliders = (p: P) => (<svg {...base(p)}><path d="M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12M20 18h0" /><circle cx="16" cy="6" r="2" /><circle cx="10" cy="12" r="2" /><circle cx="18" cy="18" r="2" /></svg>)
