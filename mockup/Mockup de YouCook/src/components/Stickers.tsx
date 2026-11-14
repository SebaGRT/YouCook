import { useId, type ReactNode } from 'react'

const K = '#2E2B28'
const sw = 3

function Face({ x = 50, y = 55, s = 1 }: { x?: number; y?: number; s?: number }) {
  return (
    <g transform={`translate(${x} ${y}) scale(${s})`} stroke="none">
      <circle cx={-8} cy={-2} r={2.8} fill={K} />
      <circle cx={8} cy={-2} r={2.8} fill={K} />
      <circle cx={-7} cy={-3} r={0.9} fill="#fff" />
      <circle cx={9} cy={-3} r={0.9} fill="#fff" />
      <path d="M-4 4 q4 4 8 0" stroke={K} strokeWidth={2.4} fill="none" strokeLinecap="round" />
      <ellipse cx={-13} cy={4} rx={3.5} ry={2.2} fill="#FF8C8C" opacity={0.7} />
      <ellipse cx={13} cy={4} rx={3.5} ry={2.2} fill="#FF8C8C" opacity={0.7} />
    </g>
  )
}

const art: Record<string, ReactNode> = {
  tomate: (
    <g stroke={K} strokeWidth={sw} strokeLinejoin="round">
      <path d="M50 26c20 0 32 12 32 30S68 86 50 86 18 74 18 56s12-30 32-30z" fill="#E8503A" />
      <path d="M30 44c3-6 9-10 15-11" stroke="#fff" strokeWidth={4} strokeLinecap="round" fill="none" opacity={0.6} />
      <path d="M50 30 40 20l8 3 2-9 3 9 8-3-9 10z" fill="#3F8F4E" />
      <Face y={60} />
    </g>
  ),
  huevo: (
    <g stroke={K} strokeWidth={sw} strokeLinejoin="round">
      <path d="M22 50c-6-16 10-30 26-26 10-12 32-4 30 12 12 8 8 30-8 32-6 12-30 14-36 2-14 0-18-12-12-20z" fill="#fff" />
      <circle cx={52} cy={52} r={16} fill="#FFC23D" />
      <path d="M44 44a9 9 0 0 1 8-4" stroke="#fff" strokeWidth={3} fill="none" strokeLinecap="round" />
      <Face x={52} y={54} s={0.8} />
    </g>
  ),
  huevo2: (
    <g stroke={K} strokeWidth={sw} strokeLinejoin="round">
      <path d="M50 14c16 0 28 26 28 44S66 88 50 88 22 76 22 58s12-44 28-44z" fill="#FFF3DD" />
      <path d="M24 50l8-6 7 6 7-7 8 7 7-6 8 6 7-4" fill="none" />
      <circle cx={74} cy={24} r={9} fill="#FFC23D" />
      <Face y={66} s={0.9} />
    </g>
  ),
  tallarin: (
    <g stroke={K} strokeWidth={sw} strokeLinejoin="round" strokeLinecap="round">
      <path d="M30 40c4-14 10-4 14-16M48 38c2-14 10-8 10-22M62 40c6-10 10-6 12-16" fill="none" stroke="#E8A93A" strokeWidth={5} />
      <path d="M30 40c4-14 10-4 14-16M48 38c2-14 10-8 10-22M62 40c6-10 10-6 12-16" fill="none" strokeWidth={1.5} opacity={0.5} />
      <path d="M14 44h72c0 22-16 38-36 38S14 66 14 44z" fill="#FFB020" />
      <path d="M14 44h72" />
      <ellipse cx={50} cy={44} rx={36} ry={6} fill="#E8503A" />
      <Face y={64} />
    </g>
  ),
  panqueque: (
    <g stroke={K} strokeWidth={sw} strokeLinejoin="round">
      <ellipse cx={50} cy={78} rx={34} ry={8} fill="#fff" />
      <rect x={20} y={58} width={60} height={14} rx={7} fill="#F2B26B" />
      <rect x={22} y={46} width={56} height={14} rx={7} fill="#F6C27E" />
      <rect x={24} y={34} width={52} height={14} rx={7} fill="#F2B26B" />
      <path d="M28 36c6-6 38-6 44 0 0 6-4 4-5 10-1 4-5 4-5 0 0-4-6-4-8 0-2 6-6 6-6 0 0-4-8-2-10 2-2 3-6 2-5-3 1-4-5-4-5-9z" fill="#8A4B1E" />
      <rect x={44} y={22} width={12} height={9} rx={2} fill="#FFE9A8" />
      <Face y={62} s={0.85} />
    </g>
  ),
  palta: (
    <g stroke={K} strokeWidth={sw} strokeLinejoin="round">
      <path d="M50 12c14 0 18 18 24 30 8 16 4 44-24 44S18 58 26 42c6-12 10-30 24-30z" fill="#3F8F4E" />
      <path d="M50 20c10 0 13 14 18 24 6 13 3 34-18 34S26 57 32 44c5-10 8-24 18-24z" fill="#CDE69A" />
      <circle cx={50} cy={58} r={11} fill="#9A5A2C" />
      <Face y={42} s={0.75} />
    </g>
  ),
  pan: (
    <g stroke={K} strokeWidth={sw} strokeLinejoin="round" strokeLinecap="round">
      <path d="M14 58c0-18 16-28 36-28s36 10 36 28c0 12-10 18-36 18S14 70 14 58z" fill="#E7A35B" />
      <path d="M34 36c4 6 4 12 0 18M50 32v22M66 36c-4 6-4 12 0 18" fill="none" stroke="#B86E2C" />
      <Face y={64} s={0.85} />
    </g>
  ),
  olla: (
    <g stroke={K} strokeWidth={sw} strokeLinejoin="round" strokeLinecap="round">
      <path d="M34 26c-4-6 4-8 0-14M50 24c-4-6 4-8 0-14M66 26c-4-6 4-8 0-14" fill="none" opacity={0.5} />
      <path d="M10 46h10M80 46h10" />
      <path d="M20 40h60v26c0 10-8 18-18 18H38c-10 0-18-8-18-18z" fill="#E8503A" />
      <rect x={16} y={34} width={68} height={10} rx={5} fill="#FF7A5C" />
      <rect x={44} y={28} width={12} height={6} rx={3} fill={K} />
      <Face y={64} />
    </g>
  ),
  olla2: (
    <g stroke={K} strokeWidth={sw} strokeLinejoin="round" strokeLinecap="round">
      <path d="M38 24l3-8 3 8-8-3 8-1zM60 18l2-6 2 6-6-2 6-1z" fill="#FFC23D" />
      <path d="M10 46h10M80 46h10" />
      <path d="M20 40h60v26c0 10-8 18-18 18H38c-10 0-18-8-18-18z" fill="#7C6CD8" />
      <rect x={16} y={34} width={68} height={10} rx={5} fill="#A99CF0" />
      <Face y={64} />
    </g>
  ),
  choclo: (
    <g stroke={K} strokeWidth={sw} strokeLinejoin="round">
      <path d="M50 12c12 0 16 18 16 36S62 84 50 84 34 66 34 48s4-36 16-36z" fill="#FFD233" />
      <path d="M40 30h20M37 42h26M36 54h28M38 66h24M50 14v68" fill="none" stroke="#E0A800" strokeWidth={2} />
      <path d="M34 50C22 56 20 74 32 88c6-4 14-10 18-4-6-10-10-24-16-34zM66 50c12 6 14 24 2 38-6-4-14-10-18-4 6-10 10-24 16-34z" fill="#3F8F4E" />
      <Face y={46} s={0.75} />
    </g>
  ),
  zapallo: (
    <g stroke={K} strokeWidth={sw} strokeLinejoin="round">
      <path d="M50 18c-2-6 4-8 8-6" fill="none" stroke="#3F8F4E" strokeWidth={4} strokeLinecap="round" />
      <ellipse cx={34} cy={56} rx={18} ry={26} fill="#F28A2E" />
      <ellipse cx={66} cy={56} rx={18} ry={26} fill="#F28A2E" />
      <ellipse cx={50} cy={56} rx={16} ry={28} fill="#FF9F43" />
      <Face y={60} s={0.9} />
    </g>
  ),
}

/** Sticker ilustrado con borde blanco tipo "die-cut". */
export function Sticker({ kind, size = 88, locked = false, rotate = 0 }: { kind: string; size?: number; locked?: boolean; rotate?: number }) {
  const id = useId().replace(/:/g, '')
  if (locked || !art[kind]) {
    return (
      <svg width={size} height={size} viewBox="0 0 100 100" aria-hidden>
        <circle cx={50} cy={50} r={38} fill="#F3E7D4" stroke="#D9C5A6" strokeWidth={3} strokeDasharray="6 6" />
        <text x={50} y={62} textAnchor="middle" fontFamily="Nunito" fontWeight={900} fontSize={34} fill="#C9B394">?</text>
      </svg>
    )
  }
  return (
    <svg width={size} height={size} viewBox="0 0 100 100" style={{ transform: `rotate(${rotate}deg)`, filter: 'drop-shadow(0 4px 6px rgb(46 43 40 / .18))' }} aria-hidden>
      <defs>
        <filter id={`cut${id}`} x="-20%" y="-20%" width="140%" height="140%">
          <feMorphology in="SourceAlpha" operator="dilate" radius={5} result="d" />
          <feFlood floodColor="#fff" />
          <feComposite in2="d" operator="in" result="o" />
          <feMerge><feMergeNode in="o" /><feMergeNode in="SourceGraphic" /></feMerge>
        </filter>
      </defs>
      <g filter={`url(#cut${id})`}>{art[kind]}</g>
    </svg>
  )
}
