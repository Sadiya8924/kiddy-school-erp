import { useState, useEffect, useRef } from 'react'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useReducedMotion } from '../hooks/useReducedMotion'

gsap.registerPlugin(ScrollTrigger)

type Page = 'home' | 'about' | 'registration' | 'contact' | 'login' | 'register'
interface Props { onNavigate: (p: Page) => void }

// ── Photo URLs ─────────────────────────────────────────────────────────────────
const PH = 'https://images.unsplash.com'
const PROG_PERMATA = `${PH}/photo-1534361960057-19f073b7c1c2?w=600&h=400&fit=crop&auto=format`
const PROG_MATAHARI = `${PH}/photo-1503676260728-1c00da094a0b?w=600&h=400&fit=crop&auto=format`
const NEWS1 = `${PH}/photo-1577896851231-70ef18881754?w=420&h=240&fit=crop&auto=format`
const NEWS2 = `${PH}/photo-1460788150444-d9dc07fa9dba?w=420&h=240&fit=crop&auto=format`
const NEWS3 = `${PH}/photo-1591123120675-6f7f1aae0e5b?w=420&h=240&fit=crop&auto=format`
const GAL1 = `${PH}/photo-1591123120675-6f7f1aae0e5b?w=560&h=400&fit=crop&auto=format`
const GAL2 = `${PH}/photo-1740635341299-3b8e3490f546?w=360&h=190&fit=crop&auto=format`
const GAL3 = `${PH}/photo-1629470937900-f9ef0f2a8086?w=360&h=190&fit=crop&auto=format`
const GAL5 = `${PH}/photo-1564981797816-1043664bf78d?w=360&h=190&fit=crop&auto=format`
const GAL6 = `${PH}/photo-1613896527026-f195d5c818ed?w=360&h=190&fit=crop&auto=format`

// ── Data ───────────────────────────────────────────────────────────────────────
const FEATURES = [
  { icon: '👩‍🏫', color: '#2563EB', bg: '#EFF6FF', title: 'Guru Profesional', desc: 'Tenaga pendidik bersertifikat dengan pengalaman dan dedikasi tinggi.' },
  { icon: '📖', color: '#10B981', bg: '#ECFDF5', title: 'Kurikulum Islami', desc: 'Kurikulum terintegrasi nilai Islam, Tahfidz, dan pendidikan karakter.' },
  { icon: '🏟️', color: '#F59E0B', bg: '#FFFBEB', title: 'Fasilitas Lengkap', desc: 'Ruang kelas nyaman, perpustakaan, lab sains, dan taman bermain.' },
  { icon: '❤️', color: '#EF4444', bg: '#FFF1F2', title: 'Pengembangan Karakter', desc: 'Program akhlak mulia, kreativitas, dan kecerdasan emosional anak.' },
  { icon: '📿', color: '#8B5CF6', bg: '#F5F3FF', title: 'Tahfidz Qur\'an', desc: 'Program hafalan Al-Quran dengan metode yang menyenangkan dan efektif.' },
  { icon: '⚽', color: '#F97316', bg: '#FFF7ED', title: 'Ekstrakurikuler', desc: 'Seni, olahraga, dan kegiatan kreatif untuk mengembangkan bakat anak.' },
]

const NEWS = [
  { img: NEWS1, cat: 'Pengumuman', date: '28 Juli 2026', title: 'Penerimaan Peserta Didik Baru Tahun Ajaran 2027/2028', desc: 'Pendaftaran PPDB Tahun Ajaran 2027/2028 resmi dibuka. Daftarkan putra-putri Anda sekarang dan amankan kursi terbatas.' },
  { img: NEWS2, cat: 'Kegiatan', date: '15 Juni 2026', title: 'Wisuda Angkatan XI TK Permata & TK Matahari Al-Hikmah 2', desc: 'Alhamdulillah, 87 murid berhasil menyelesaikan pendidikan dan siap melanjutkan ke jenjang Sekolah Dasar.' },
  { img: NEWS3, cat: 'Event', date: '2 Juni 2026', title: 'Peringatan Maulid Nabi Muhammad SAW 1447 H', desc: 'Seluruh sivitas akademika merayakan Maulid Nabi dengan penuh kekhusyukan, lomba hafalan, dan shalawat bersama.' },
]

const PARTNERS = [
  { name: 'Kemendikbud RI', type: 'Pemerintah', icon: '🏛️' },
  { name: 'Dinas Pendidikan Kota Malang', type: 'Pemerintah Daerah', icon: '🏫' },
  { name: 'MUI Kota Malang', type: 'Keagamaan', icon: '🕌' },
  { name: 'LP Ma\'arif NU', type: 'Pendidikan Islam', icon: '📚' },
  { name: 'Yayasan Pendidikan Islam', type: 'Yayasan', icon: '🤝' },
  { name: 'BAN PAUD & PNF', type: 'Akreditasi', icon: '⭐' },
]

const TESTIS = [
  { name: 'Bunda Rina Hartati', role: 'Orang Tua TK Permata', init: 'RH', bg: '#FEF3C7', color: '#92400E', text: 'Anak kami berkembang sangat pesat dalam hafalan dan akhlaknya. Ibu guru sangat sabar dan profesional. Pilihan terbaik untuk anak kami!', rating: 5 },
  { name: 'Bapak Andi Prasetyo', role: 'Orang Tua TK Matahari', init: 'AP', bg: '#EDE9FE', color: '#5B21B6', text: 'Suasana sekolah Islami yang hangat membuat anak betah belajar. Fasilitas sangat lengkap dan modern. Sangat puas sekali dengan Al-Hikmah 2!', rating: 5 },
  { name: 'Bunda Siti Aminah', role: 'Orang Tua TK Permata', init: 'SA', bg: '#DCFCE7', color: '#166534', text: 'Program tahfidz-nya luar biasa! Anak sudah hafal 5 surat dalam 3 bulan pertama. Alhamdulillah, ini adalah pilihan terbaik untuk anak kami.', rating: 5 },
]

// ── Hero Illustration SVG ──────────────────────────────────────────────────────
function HeroIllustration() {
  return (
    <svg viewBox="0 0 540 530" fill="none" xmlns="http://www.w3.org/2000/svg"
      style={{ width: '100%', height: 'auto', maxHeight: 580 }}>
      <defs>
        <radialGradient id="bgOval" cx="50%" cy="38%" r="62%">
          <stop offset="0%" stopColor="#E0F2FE" />
          <stop offset="60%" stopColor="#BAE6FD" />
          <stop offset="100%" stopColor="#7DD3FC" />
        </radialGradient>
        <radialGradient id="sunGlow" cx="50%" cy="50%" r="50%">
          <stop offset="0%" stopColor="#FEF9C3" stopOpacity="0.9" />
          <stop offset="100%" stopColor="#FEF9C3" stopOpacity="0" />
        </radialGradient>
        <linearGradient id="grassG" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stopColor="#86EFAC" />
          <stop offset="100%" stopColor="#4ADE80" />
        </linearGradient>
        <linearGradient id="bldgG" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stopColor="#F0F9FF" />
          <stop offset="100%" stopColor="#E0F2FE" />
        </linearGradient>
        <filter id="softShadow" x="-20%" y="-20%" width="140%" height="140%">
          <feDropShadow dx="0" dy="4" stdDeviation="6" floodColor="#1E40AF" floodOpacity="0.2" />
        </filter>
      </defs>

      {/* ── Sky oval background ── */}
      <ellipse cx="270" cy="262" rx="262" ry="257" fill="url(#bgOval)" />
      {/* Soft inner circles for depth */}
      <circle cx="110" cy="350" r="70" fill="#BFDBFE" opacity="0.18" />
      <circle cx="440" cy="200" r="55" fill="#C7D2FE" opacity="0.15" />

      {/* ── SUN ── */}
      <circle cx="440" cy="72" r="52" fill="url(#sunGlow)" />
      <circle cx="440" cy="72" r="30" fill="#FDE047" />
      <circle cx="440" cy="72" r="23" fill="#FEF08A" />
      <g className="hero-sun-rays">
        {Array.from({ length: 8 }).map((_, i) => (
          <rect key={i} transform={`rotate(${i * 45} 440 72)`} x="438" y="28" width="4" height="16" rx="2" fill="#FCD34D" />
        ))}
      </g>

      {/* ── STARS ── */}
      <g className="hero-stars">
        {([
          [158, 84, '#FDE047'], [208, 58, '#A78BFA'], [356, 88, '#F9A8D4'],
          [478, 132, '#FDE047'], [50, 168, '#34D399'], [312, 120, '#FCD34D'],
          [452, 158, '#A78BFA'], [125, 145, '#F472B6'], [498, 88, '#FDE047'],
          [78, 255, '#93C5FD'], [505, 310, '#FCD34D'],
        ] as [number, number, string][]).map(([x, y, c], i) => (
          <path key={i}
            d={`M${x} ${y - 5.5}L${x + 2} ${y - 2}L${x + 5.5} ${y}L${x + 2} ${y + 2}L${x} ${y + 5.5}L${x - 2} ${y + 2}L${x - 5.5} ${y}L${x - 2} ${y - 2}Z`}
            fill={c} opacity={0.65 + (i % 5) * 0.07}
          />
        ))}
      </g>

      {/* ── CLOUD 1 (left) ── */}
      <g className="hero-cloud">
        <ellipse cx="92" cy="100" rx="40" ry="21" fill="white" opacity="0.95" />
        <ellipse cx="122" cy="91" rx="30" ry="18" fill="white" opacity="0.95" />
        <ellipse cx="66" cy="95" rx="24" ry="15" fill="white" opacity="0.95" />
        <ellipse cx="92" cy="101" rx="38" ry="18" fill="rgba(255,255,255,0.4)" />
      </g>
      {/* ── CLOUD 2 (top center) ── */}
      <g className="hero-cloud">
        <ellipse cx="282" cy="74" rx="34" ry="18" fill="white" opacity="0.9" />
        <ellipse cx="308" cy="66" rx="25" ry="15" fill="white" opacity="0.9" />
        <ellipse cx="259" cy="69" rx="20" ry="13" fill="white" opacity="0.9" />
      </g>
      {/* ── CLOUD 3 (right) ── */}
      <g className="hero-cloud">
        <ellipse cx="392" cy="104" rx="28" ry="15" fill="white" opacity="0.88" />
        <ellipse cx="414" cy="97" rx="21" ry="13" fill="white" opacity="0.88" />
        <ellipse cx="373" cy="99" rx="17" ry="11" fill="white" opacity="0.88" />
      </g>

      {/* ── HOT AIR BALLOON ── */}
      <g className="hero-balloon">
        <ellipse cx="88" cy="168" rx="44" ry="52" fill="#EF4444" />
        {/* Stripes */}
        <path d="M46 158 Q88 118 130 158 Q108 174 88 178 Q68 174 46 158Z" fill="#F97316" opacity="0.88" />
        <path d="M50 182 Q88 196 126 182 Q107 198 88 201 Q69 198 50 182Z" fill="#FCD34D" opacity="0.82" />
        <path d="M46 170 Q88 155 130 170" fill="none" stroke="#DC2626" strokeWidth="1.2" opacity="0.5" />
        <ellipse cx="72" cy="150" rx="13" ry="20" fill="rgba(255,255,255,0.18)" />
        {/* Ropes */}
        <line x1="68" y1="216" x2="78" y2="235" stroke="#92400E" strokeWidth="1.6" />
        <line x1="108" y1="216" x2="98" y2="235" stroke="#92400E" strokeWidth="1.6" />
        <line x1="88" y1="218" x2="88" y2="235" stroke="#92400E" strokeWidth="1.2" />
        {/* Basket */}
        <rect x="76" y="233" width="24" height="18" rx="4" fill="#D97706" />
        <rect x="79" y="236" width="18" height="13" rx="3" fill="#F59E0B" />
        <line x1="82" y1="233" x2="82" y2="251" stroke="#92400E" strokeWidth="1" />
        <line x1="88" y1="233" x2="88" y2="251" stroke="#92400E" strokeWidth="1" />
        <line x1="94" y1="233" x2="94" y2="251" stroke="#92400E" strokeWidth="1" />
      </g>

      {/* ── SECOND BALLOON (small, back right) ── */}
      <g className="hero-balloon">
        <ellipse cx="478" cy="190" rx="24" ry="28" fill="#8B5CF6" />
        <path d="M456 184 Q478 162 500 184 Q489 192 478 195 Q467 192 456 184Z" fill="#7C3AED" opacity="0.8" />
        <line x1="470" y1="216" x2="474" y2="226" stroke="#5B21B6" strokeWidth="1.2" />
        <line x1="486" y1="216" x2="482" y2="226" stroke="#5B21B6" strokeWidth="1.2" />
        <rect x="472" y="225" width="12" height="9" rx="2" fill="#6D28D9" />
      </g>

      {/* ── PAPER AIRPLANES ── */}
      <g className="hero-plane">
        <path d="M408 142 L462 118 L440 148 L428 141Z" fill="white" opacity="0.92" />
        <path d="M408 142 L440 148 L434 160Z" fill="#DBEAFE" opacity="0.88" />
        <path d="M408 142 L462 118 L445 127Z" fill="#BFDBFE" opacity="0.65" />
        <line x1="428" y1="141" x2="434" y2="160" stroke="rgba(255,255,255,0.5)" strokeWidth="0.8" />
      </g>
      {/* Second smaller plane */}
      <g className="hero-plane">
        <path d="M160 168 L196 155 L183 170 L174 166Z" fill="white" opacity="0.75" />
        <path d="M160 168 L183 170 L179 178Z" fill="#BFDBFE" opacity="0.7" />
      </g>

      {/* ── FLOATING BOOKS ── */}
      <g className="hero-books">
        <g transform="rotate(-14 220 138)">
          <rect x="204" y="124" width="26" height="34" rx="3" fill="#3B82F6" />
          <rect x="207" y="127" width="20" height="28" rx="2" fill="#93C5FD" opacity="0.45" />
          <line x1="209" y1="133" x2="225" y2="133" stroke="white" strokeWidth="1.3" />
          <line x1="209" y1="139" x2="225" y2="139" stroke="white" strokeWidth="1.3" />
          <line x1="209" y1="145" x2="222" y2="145" stroke="white" strokeWidth="1.3" />
        </g>
        <g transform="rotate(9 370 158)">
          <rect x="356" y="146" width="24" height="30" rx="3" fill="#EF4444" />
          <rect x="359" y="149" width="18" height="24" rx="2" fill="#FCA5A5" opacity="0.45" />
          <line x1="361" y1="154" x2="375" y2="154" stroke="white" strokeWidth="1.2" />
          <line x1="361" y1="160" x2="375" y2="160" stroke="white" strokeWidth="1.2" />
          <line x1="361" y1="166" x2="373" y2="166" stroke="white" strokeWidth="1.2" />
        </g>
        <g transform="rotate(-7 490 228)">
          <rect x="478" y="217" width="22" height="28" rx="3" fill="#8B5CF6" />
          <rect x="481" y="220" width="16" height="22" rx="2" fill="#C4B5FD" opacity="0.45" />
          <line x1="483" y1="225" x2="495" y2="225" stroke="white" strokeWidth="1.2" />
          <line x1="483" y1="231" x2="495" y2="231" stroke="white" strokeWidth="1.2" />
        </g>
      </g>

      {/* ── LEAVES ── */}
      <g opacity="0.82">
        <ellipse cx="52" cy="315" rx="11" ry="6" fill="#4ADE80" transform="rotate(30 52 315)" />
        <ellipse cx="496" cy="255" rx="10" ry="5.5" fill="#34D399" transform="rotate(-42 496 255)" />
        <ellipse cx="168" cy="188" rx="9" ry="5" fill="#86EFAC" transform="rotate(18 168 188)" />
        <ellipse cx="376" cy="178" rx="8" ry="4.5" fill="#4ADE80" transform="rotate(-28 376 178)" />
        <ellipse cx="56" cy="225" rx="9" ry="5" fill="#22C55E" transform="rotate(48 56 225)" />
        <ellipse cx="502" cy="325" rx="8" ry="4.5" fill="#86EFAC" transform="rotate(-32 502 325)" />
        <ellipse cx="510" cy="380" rx="7" ry="4" fill="#34D399" transform="rotate(22 510 380)" />
        <ellipse cx="38" cy="400" rx="8" ry="4.5" fill="#4ADE80" transform="rotate(-18 38 400)" />
      </g>

      {/* ── DIAMOND SHAPES ── */}
      <path d="M516 178 L522 168 L528 178 L522 188Z" fill="#F472B6" opacity="0.72" />
      <path d="M42 200 L48 190 L54 200 L48 210Z" fill="#A78BFA" opacity="0.68" />
      <path d="M498 405 L503 397 L508 405 L503 413Z" fill="#34D399" opacity="0.65" />
      <path d="M50 438 L55 431 L60 438 L55 445Z" fill="#FCD34D" opacity="0.6" />

      {/* ── GROUND ── */}
      <ellipse cx="270" cy="498" rx="258" ry="42" fill="url(#grassG)" />
      <ellipse cx="270" cy="490" rx="258" ry="26" fill="#A3F0B3" opacity="0.45" />

      {/* ── LEFT TREE (large) ── */}
      <g className="hero-trees">
        <rect x="88" y="362" width="15" height="58" rx="4" fill="#92400E" />
        <ellipse cx="96" cy="352" rx="34" ry="30" fill="#22C55E" />
        <ellipse cx="96" cy="342" rx="26" ry="22" fill="#16A34A" />
        <circle cx="112" cy="340" r="11" fill="#22C55E" opacity="0.75" />
        <circle cx="78" cy="346" r="10" fill="#15803D" opacity="0.82" />
      </g>

      {/* ── RIGHT TREE (large) ── */}
      <g className="hero-trees">
        <rect x="485" y="370" width="13" height="48" rx="4" fill="#92400E" />
        <ellipse cx="491" cy="362" rx="28" ry="25" fill="#22C55E" />
        <ellipse cx="491" cy="353" rx="20" ry="18" fill="#16A34A" />
        <circle cx="504" cy="351" r="9" fill="#22C55E" opacity="0.72" />
        <circle cx="479" cy="357" r="8" fill="#15803D" opacity="0.8" />
      </g>

      {/* ── SMALL BUSHES ── */}
      <ellipse cx="142" cy="400" rx="20" ry="13" fill="#22C55E" />
      <ellipse cx="157" cy="396" rx="15" ry="11" fill="#16A34A" />
      <ellipse cx="400" cy="400" rx="20" ry="13" fill="#22C55E" />
      <ellipse cx="386" cy="397" rx="15" ry="10" fill="#16A34A" />

      {/* ══ BUILDING (center, no text, no logo) ══ */}
      {/* Shadow */}
      <ellipse cx="268" cy="400" rx="136" ry="11" fill="#93C5FD" opacity="0.22" />
      {/* Side 3D panel */}
      <polygon points="390,215 406,205 406,393 390,403" fill="#DBEAFE" />
      {/* Main body */}
      <rect x="148" y="215" width="242" height="188" rx="5" fill="url(#bldgG)" filter="url(#softShadow)" />
      {/* Floor 1–2 divider */}
      <rect x="148" y="300" width="242" height="4" fill="#BFDBFE" />

      {/* ROOF ARCH */}
      <path d="M143 224 Q270 144 398 224" fill="#2563EB" />
      <path d="M143 224 Q270 148 398 224 Q270 216 143 224Z" fill="#1D4ED8" opacity="0.35" />
      {/* Roof ridge */}
      <rect x="180" y="222" width="180" height="6" rx="3" fill="#1E40AF" opacity="0.5" />

      {/* CENTRAL TOWER */}
      <rect x="248" y="150" width="44" height="72" rx="4" fill="#2563EB" />
      <rect x="248" y="150" width="44" height="10" rx="4" fill="#1D4ED8" />
      <path d="M244 164 Q270 136 296 164" fill="#1E40AF" />
      <ellipse cx="270" cy="164" rx="27" ry="10" fill="#1D4ED8" opacity="0.45" />
      {/* Flagpole */}
      <rect x="268" y="126" width="4" height="28" fill="#FCD34D" />
      {/* Decorative flag — solid color shape, no text */}
      <path d="M272 128 L294 136 L272 144Z" fill="#EF4444" />

      {/* WINDOWS — ROW 1 (top floor) */}
      {([168, 247, 326] as number[]).map((x) => (
        <g key={x}>
          <rect x={x} y={238} width={47} height={44} rx={4} fill="#BAE6FD" opacity={0.9} />
          <path d={`M${x} ${254} Q${x + 23.5} ${242} ${x + 47} ${254}`} fill="#7DD3FC" opacity={0.55} />
          <line x1={x + 23.5} y1={238} x2={x + 23.5} y2={282} stroke="#93C5FD" strokeWidth={1.5} />
          <line x1={x} y1={261} x2={x + 47} y2={261} stroke="#93C5FD" strokeWidth={1.5} />
        </g>
      ))}
      {/* Flower boxes under row 1 */}
      {([168, 326] as number[]).map((x, fi) => (
        <g key={x}>
          <rect x={x} y={279} width={47} height={9} rx={3} fill="#F97316" />
          {[4, 13, 22, 31, 40].map((ox, ci) => (
            <circle key={ci} cx={x + ox + 3} cy={277} r={5}
              fill={['#EF4444', '#FCD34D', '#EC4899', '#EF4444', '#34D399'][fi === 0 ? ci : (ci + 2) % 5]} />
          ))}
        </g>
      ))}

      {/* WINDOWS — ROW 2 (bottom floor, only sides) */}
      {([168, 326] as number[]).map((x) => (
        <g key={x}>
          <rect x={x} y={307} width={47} height={36} rx={3} fill="#BAE6FD" opacity={0.75} />
          <line x1={x + 23.5} y1={307} x2={x + 23.5} y2={343} stroke="#93C5FD" strokeWidth={1.2} />
          <line x1={x} y1={325} x2={x + 47} y2={325} stroke="#93C5FD" strokeWidth={1.2} />
        </g>
      ))}

      {/* DOOR — arched teal */}
      <path d="M235 403 L235 347 Q270 322 305 347 L305 403Z" fill="#0D9488" />
      {/* Door arch frame */}
      <path d="M231 347 Q270 318 309 347" fill="none" stroke="#F59E0B" strokeWidth={4} strokeLinecap="round" />
      <rect x={231} y={347} width={78} height={4} fill="#F59E0B" />
      <rect x={231} y={399} width={78} height={4} fill="#D97706" />
      {/* Door panels */}
      <rect x={239} y={352} width={27} height={50} rx={3} fill="#0F766E" opacity={0.6} />
      <rect x={274} y={352} width={27} height={50} rx={3} fill="#0F766E" opacity={0.6} />
      {/* Door knobs */}
      <circle cx={265} cy={378} r={3.5} fill="#FCD34D" />
      <circle cx={275} cy={378} r={3.5} fill="#FCD34D" />
      {/* Awning over door */}
      <path d="M224 333 Q270 317 316 333" fill="#F59E0B" opacity={0.72} />
      <path d="M222 335 Q270 321 318 335 Q270 325 222 335Z" fill="#D97706" opacity={0.38} />
      {/* Steps */}
      <rect x={220} y={401} width={100} height={7} rx={2} fill="#94A3B8" />
      <rect x={212} y={406} width={116} height={6} rx={2} fill="#CBD5E1" />

      {/* ── SWING SET (left) ── */}
      {/* Frame */}
      <line x1={118} y1={296} x2={96} y2={380} stroke="#92400E" strokeWidth={5} strokeLinecap="round" />
      <line x1={150} y1={296} x2={172} y2={380} stroke="#92400E" strokeWidth={5} strokeLinecap="round" />
      <rect x={113} y={291} width={42} height={8} rx={4} fill="#D97706" />
      {/* Chains */}
      <line x1={122} y1={299} x2={122} y2={348} stroke="#9CA3AF" strokeWidth={1.6} />
      <line x1={136} y1={299} x2={136} y2={348} stroke="#9CA3AF" strokeWidth={1.6} />
      {/* Seat */}
      <rect x={117} y={346} width={24} height={7} rx={3} fill="#2563EB" />
      {/* Child on swing */}
      <circle cx={129} cy={333} r={11} fill="#FDE68A" />
      <path d="M120 337 Q129 345 138 337" fill="none" stroke="#1C1917" strokeWidth={1.3} strokeLinecap="round" />
      <circle cx={125} cy={332} r={2.2} fill="#1C1917" />
      <circle cx={133} cy={332} r={2.2} fill="#1C1917" />
      <rect x={122} y={343} width={14} height={18} rx={5} fill="#EC4899" />
      <rect x={124} y={358} width={5} height={14} rx={3} fill="#DB2777" />
      <rect x={129} y={359} width={5} height={14} rx={3} fill="#DB2777" />

      {/* ── PLAYGROUND SLIDE (right) ── */}
      <rect x={432} y={292} width={11} height={106} rx={5} fill="#F97316" />
      <rect x={418} y={288} width={36} height={14} rx={4} fill="#FB923C" />
      <path d="M432 300 Q478 354 458 406" stroke="#F97316" strokeWidth={11} strokeLinecap="round" fill="none" />
      <path d="M432 300 Q478 354 458 406" stroke="#FDBA74" strokeWidth={7} strokeLinecap="round" fill="none" opacity={0.45} />
      {/* Rungs */}
      {[310, 326, 342, 358].map((y) => (
        <line key={y} x1={429} y1={y} x2={447} y2={y} stroke="#C2410C" strokeWidth={2.5} strokeLinecap="round" />
      ))}
      {/* Child on slide */}
      <circle cx={450} cy={348} r={11} fill="#FDE68A" />
      <path d="M441 352 Q450 360 459 352" fill="none" stroke="#1C1917" strokeWidth={1.3} strokeLinecap="round" />
      <circle cx={446} cy={347} r={2.2} fill="#1C1917" />
      <circle cx={454} cy={347} r={2.2} fill="#1C1917" />
      <rect x={443} y={359} width={14} height={18} rx={5} fill="#7C3AED" />
      <rect x={445} y={374} width={5} height={14} rx={3} fill="#6D28D9" />
      <rect x={451} y={375} width={5} height={13} rx={3} fill="#6D28D9" />

      {/* ── TEACHER (near building left) ── */}
      <g className="hero-children">
        {/* Body */}
        <rect x={106} y={337} width={30} height={55} rx={7} fill="#1D4ED8" />
        {/* Head */}
        <circle cx={121} cy={326} r={17} fill="#FDE68A" />
        {/* Hijab */}
        <path d="M104 330 Q108 309 121 308 Q134 309 138 330 Q134 346 121 348 Q108 346 104 330Z" fill="white" />
        <circle cx={121} cy={328} r={12} fill="#FDE68A" />
        {/* Face */}
        <circle cx={117} cy={326} r={2.5} fill="white" />
        <circle cx={125} cy={326} r={2.5} fill="white" />
        <circle cx={118} cy={326.5} r={1.4} fill="#1C1917" />
        <circle cx={126} cy={326.5} r={1.4} fill="#1C1917" />
        <path d="M117 333 Q121 337 125 333" stroke="#1C1917" strokeWidth={1.3} fill="none" strokeLinecap="round" />
        <circle cx={114} cy={330} r={4} fill="#FCA5A5" opacity={0.4} />
        <circle cx={128} cy={330} r={4} fill="#FCA5A5" opacity={0.4} />
        {/* Left arm — holding book */}
        <rect x={84} y={342} width={24} height={14} rx={5} fill="#1D4ED8" />
        <rect x={66} y={344} width={21} height={27} rx={3} fill="#EF4444" />
        <rect x={69} y={347} width={15} height={21} rx={2} fill="#FEF2F2" />
        <line x1={71} y1={351} x2={82} y2={351} stroke="#FECACA" strokeWidth={1.3} />
        <line x1={71} y1={356} x2={82} y2={356} stroke="#FECACA" strokeWidth={1.3} />
        <line x1={71} y1={361} x2={80} y2={361} stroke="#FECACA" strokeWidth={1.3} />
        {/* Right arm — waving */}
        <rect x={136} y={340} width={22} height={14} rx={5} fill="#1D4ED8" />
        <path d="M155 341 Q167 330 170 338 Q173 346 162 350" fill="#FDE68A" stroke="#FDE68A" strokeWidth={0.5} />
        {/* Skirt */}
        <rect x={106} y={374} width={30} height={18} rx={4} fill="#2563EB" opacity={0.7} />
        {/* Legs */}
        <rect x={108} y={388} width={12} height={18} rx={4} fill="#1D4ED8" />
        <rect x={122} y={388} width={12} height={18} rx={4} fill="#1D4ED8" />
        <ellipse cx={114} cy={406} rx={9} ry={5} fill="#1C1917" />
        <ellipse cx={128} cy={406} rx={9} ry={5} fill="#1C1917" />
      </g>

      {/* ── CHILD 1 — near entrance (hijab) ── */}
      <g className="hero-children">
        <rect x={177} y={358} width={22} height={40} rx={6} fill="#7C3AED" />
        <circle cx={188} cy={348} r={13} fill="#FDE68A" />
        <path d="M175 352 Q178 338 188 337 Q198 338 201 352 Q198 363 188 365 Q178 363 175 352Z" fill="#BAE6FD" />
        <circle cx={188} cy={350} r={9} fill="#FDE68A" />
        <circle cx={184} cy={349} r={2} fill="white" />
        <circle cx={192} cy={349} r={2} fill="white" />
        <circle cx={184.5} cy={349.5} r={1.2} fill="#1C1917" />
        <circle cx={192.5} cy={349.5} r={1.2} fill="#1C1917" />
        <path d="M184 356 Q188 360 192 356" stroke="#1C1917" strokeWidth={1.2} fill="none" strokeLinecap="round" />
        <circle cx={183} cy={353} r={3.5} fill="#FCA5A5" opacity={0.45} />
        <circle cx={193} cy={353} r={3.5} fill="#FCA5A5" opacity={0.45} />
        <rect x={179} y={394} width={10} height={16} rx={4} fill="#6D28D9" />
        <rect x={191} y={394} width={10} height={16} rx={4} fill="#6D28D9" />
        <ellipse cx={184} cy={410} rx={8} ry={4} fill="#1C1917" />
        <ellipse cx={196} cy={410} rx={8} ry={4} fill="#1C1917" />
      </g>

      {/* ── CHILD 2 — center right, playing with ball ── */}
      <g className="hero-children">
        <rect x={336} y={355} width={22} height={38} rx={6} fill="#EF4444" />
        <circle cx={347} cy={346} r={12} fill="#FDE68A" />
        <path d="M335 348 Q338 336 347 334 Q356 336 359 348" fill="#292524" />
        <circle cx={343} cy={345} r={2} fill="white" />
        <circle cx={351} cy={345} r={2} fill="white" />
        <circle cx={343.5} cy={345.5} r={1.2} fill="#1C1917" />
        <circle cx={351.5} cy={345.5} r={1.2} fill="#1C1917" />
        <path d="M343 352 Q347 356 351 352" stroke="#1C1917" strokeWidth={1.2} fill="none" strokeLinecap="round" />
        <rect x={338} y={389} width={10} height={15} rx={4} fill="#DC2626" />
        <rect x={350} y={390} width={10} height={15} rx={4} fill="#DC2626" />
        <ellipse cx={343} cy={404} rx={8} ry={4} fill="#1C1917" />
        <ellipse cx={355} cy={404} rx={8} ry={4} fill="#1C1917" />
        {/* Ball */}
        <circle cx={318} cy={380} r={11} fill="#F97316" />
        <path d="M309 376 Q318 371 327 376" stroke="#EA580C" strokeWidth={1.2} fill="none" />
        <path d="M312 384 Q318 380 324 384" stroke="#EA580C" strokeWidth={1} fill="none" opacity={0.6} />
        <circle cx={315} cy={378} r={3.5} fill="#EA580C" opacity={0.45} />
      </g>
    </svg>
  )
}

// ── Theme Config ───────────────────────────────────────────────────────────────
const THEMES: Record<string, {
  heroGradient: string
  heroBlob1: string
  heroBlob2: string
  heroBadgeBg: string
  heroBadgeBorder: string
  accentColor: string
  accentLight: string
  accentMid: string
  sectionBg: string
  sectionAlt: string
  statsBg: string
  ctaBg: string
  btnBg: string
  btnHover: string
  tagColor: string
  tagBg: string
  headingColor: string
}> = {
  ocean: {
    heroGradient: 'linear-gradient(135deg, #060F2E 0%, #0B1E4E 28%, #1E3A8A 62%, #1D4ED8 100%)',
    heroBlob1: 'radial-gradient(circle, rgba(59,130,246,0.38) 0%, transparent 70%)',
    heroBlob2: 'radial-gradient(circle, rgba(99,102,241,0.35) 0%, transparent 70%)',
    heroBadgeBg: 'rgba(59,130,246,0.25)',
    heroBadgeBorder: 'rgba(59,130,246,0.5)',
    accentColor: '#2563EB',
    accentLight: '#EFF6FF',
    accentMid: '#BFDBFE',
    sectionBg: '#FFFFFF',
    sectionAlt: '#F8FAFF',
    statsBg: 'linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%)',
    ctaBg: 'linear-gradient(135deg, #0F172A 0%, #1E3A8A 50%, #2563EB 100%)',
    btnBg: '#2563EB',
    btnHover: '#1D4ED8',
    tagColor: '#2563EB',
    tagBg: '#EFF6FF',
    headingColor: '#0F172A',
  },
  ceria: {
    heroGradient: 'linear-gradient(135deg, #7A3F00 0%, #B45309 30%, #D97706 65%, #F59E0B 100%)',
    heroBlob1: 'radial-gradient(circle, rgba(251,191,36,0.4) 0%, transparent 70%)',
    heroBlob2: 'radial-gradient(circle, rgba(249,115,22,0.35) 0%, transparent 70%)',
    heroBadgeBg: 'rgba(251,191,36,0.25)',
    heroBadgeBorder: 'rgba(251,191,36,0.55)',
    accentColor: '#D97706',
    accentLight: '#FFFBEB',
    accentMid: '#FDE68A',
    sectionBg: '#FDFBF7',
    sectionAlt: '#FFF8ED',
    statsBg: 'linear-gradient(135deg, #7C2D12 0%, #EA580C 50%, #F97316 100%)',
    ctaBg: 'linear-gradient(135deg, #451A03 0%, #92400E 50%, #D97706 100%)',
    btnBg: '#D97706',
    btnHover: '#B45309',
    tagColor: '#92400E',
    tagBg: '#FEF3C7',
    headingColor: '#78350F',
  },
  trustworthy: {
    heroGradient: 'linear-gradient(135deg, #0C1A2E 0%, #1A3253 30%, #185FA5 65%, #1A73C0 100%)',
    heroBlob1: 'radial-gradient(circle, rgba(26,115,192,0.4) 0%, transparent 70%)',
    heroBlob2: 'radial-gradient(circle, rgba(14,78,145,0.35) 0%, transparent 70%)',
    heroBadgeBg: 'rgba(26,115,192,0.22)',
    heroBadgeBorder: 'rgba(26,115,192,0.5)',
    accentColor: '#185FA5',
    accentLight: '#EBF4FF',
    accentMid: '#BFDBFE',
    sectionBg: '#FFFFFF',
    sectionAlt: '#F5F9FF',
    statsBg: 'linear-gradient(135deg, #0C1A2E 0%, #185FA5 100%)',
    ctaBg: 'linear-gradient(135deg, #0C1A2E 0%, #185FA5 60%, #1A73C0 100%)',
    btnBg: '#185FA5',
    btnHover: '#0E4891',
    tagColor: '#185FA5',
    tagBg: '#EBF4FF',
    headingColor: '#0C1A2E',
  },
  natural: {
    heroGradient: 'linear-gradient(135deg, #1A2E0A 0%, #2D5016 30%, #4A7C1E 65%, #639922 100%)',
    heroBlob1: 'radial-gradient(circle, rgba(99,153,34,0.4) 0%, transparent 70%)',
    heroBlob2: 'radial-gradient(circle, rgba(74,124,30,0.38) 0%, transparent 70%)',
    heroBadgeBg: 'rgba(99,153,34,0.22)',
    heroBadgeBorder: 'rgba(99,153,34,0.5)',
    accentColor: '#4A7C1E',
    accentLight: '#F0F7E6',
    accentMid: '#C5E39A',
    sectionBg: '#F6F3EC',
    sectionAlt: '#EDF5E0',
    statsBg: 'linear-gradient(135deg, #1A2E0A 0%, #4A7C1E 60%, #639922 100%)',
    ctaBg: 'linear-gradient(135deg, #1A2E0A 0%, #355312 50%, #4A7C1E 100%)',
    btnBg: '#4A7C1E',
    btnHover: '#355312',
    tagColor: '#355312',
    tagBg: '#ECFCCB',
    headingColor: '#1A2E0A',
  },
}

function getTheme() {
  const tpl = (typeof window !== 'undefined' && (window as any).APP_SETTINGS?.landing_template) || 'ocean'
  return THEMES[tpl] || THEMES.ocean
}

// ── Main Component ─────────────────────────────────────────────────────────────
export default function HomePage({ onNavigate }: Props) {

  const reduced = useReducedMotion()
  const theme = getTheme()
  const heroRef = useRef<HTMLElement>(null)
  const leftRef = useRef<HTMLDivElement>(null)
  const illusRef = useRef<HTMLDivElement>(null)
  const blob1Ref = useRef<HTMLDivElement>(null)
  const blob2Ref = useRef<HTMLDivElement>(null)
  const blob3Ref = useRef<HTMLDivElement>(null)

  useEffect(() => {
    if (reduced) return
    const ctx = gsap.context(() => {

      // ── Hero entrance ────────────────────────────────────────────────────
      if (leftRef.current) {
        gsap.fromTo(leftRef.current,
          { opacity: 0, x: -30 },
          { opacity: 1, x: 0, duration: 0.8, ease: 'power3.out', delay: 0.1 }
        )
      }
      gsap.fromTo(illusRef.current,
        { opacity: 0, x: 50, scale: 0.95 },
        { opacity: 1, x: 0, scale: 1, duration: 1, ease: 'expo.out', delay: 0.3 }
      )

      // ── Illustration breathing & floating ────────────────────────────────
      gsap.to(illusRef.current, { y: -22, rotation: 1.8, duration: 3.2, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 0.5, transformOrigin: 'center center' })

      // ── Blobs pulsing ─────────────────────────────────────────────────────
      gsap.to(blob1Ref.current, { scale: 1.15, x: 30, y: -20, duration: 7, repeat: -1, yoyo: true, ease: 'sine.inOut' })
      gsap.to(blob2Ref.current, { scale: 1.2, x: -25, y: 25, duration: 9, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 2 })
      gsap.to(blob3Ref.current, { scale: 1.1, x: 20, y: -15, duration: 8, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: 4 })

      // ── Illustration element animations ──────────────────────────────────
      // Clouds — float independently
      const clouds = document.querySelectorAll('.hero-cloud')
      clouds.forEach((el, i) => {
        gsap.to(el, { x: i % 2 === 0 ? 18 : -18, y: -14, duration: 3.2 + i * 0.6, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.6 })
      })
      // Balloon float
      document.querySelectorAll('.hero-balloon').forEach((el, i) => {
        gsap.to(el, { y: -28, x: i % 2 === 0 ? 12 : -10, rotation: i % 2 === 0 ? 4 : -4, duration: 3.6 + i * 0.8, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.8, transformOrigin: 'center center' })
      })
      // Stars twinkle
      const stars = document.querySelectorAll('.hero-stars path')
      stars.forEach((el, i) => {
        gsap.to(el, { opacity: 0.2, scale: 0.6, duration: 1 + (i % 5) * 0.3, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.15, transformOrigin: 'center center' })
      })
      // Sun rays rotation
      gsap.to('.hero-sun-rays', { rotation: 360, transformOrigin: '440px 72px', duration: 18, repeat: -1, ease: 'none' })
      // Books float
      document.querySelectorAll('.hero-books g').forEach((el, i) => {
        gsap.to(el, { y: i % 2 === 0 ? -16 : 12, rotation: i % 2 === 0 ? 6 : -6, duration: 2.5 + i * 0.5, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.4, transformOrigin: 'center center' })
      })
      // Paper planes path
      document.querySelectorAll('.hero-plane').forEach((el, i) => {
        gsap.to(el, { x: i % 2 === 0 ? 28 : -20, y: -14, rotation: i % 2 === 0 ? -10 : 8, duration: 3.2 + i, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.5, transformOrigin: 'center center' })
      })
      // Trees sway
      document.querySelectorAll('.hero-trees').forEach((el, i) => {
        gsap.to(el, { rotation: i % 2 === 0 ? 4 : -4, duration: 2.4 + i * 0.5, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.3, transformOrigin: 'bottom center' })
      })
      // Children gentle bob
      document.querySelectorAll('.hero-children').forEach((el, i) => {
        gsap.to(el, { y: i % 2 === 0 ? -8 : -5, duration: 1.8 + i * 0.4, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.4 })
      })

      // ── Mouse parallax ────────────────────────────────────────────────────
      const section = heroRef.current
      if (section && illusRef.current) {
        const parallaxLayers = [
          { selector: '.hero-cloud', speed: 0.04 },
          { selector: '.hero-balloon', speed: 0.06 },
          { selector: '.hero-plane', speed: 0.08 },
          { selector: '.hero-books g', speed: 0.05 },
          { selector: '.hero-stars', speed: 0.02 },
        ]
        const handleMouseMove = (e: MouseEvent) => {
          const rect = section.getBoundingClientRect()
          const cx = rect.width / 2
          const cy = rect.height / 2
          const dx = e.clientX - rect.left - cx
          const dy = e.clientY - rect.top - cy
          parallaxLayers.forEach(({ selector, speed }) => {
            gsap.to(selector, { x: dx * speed, y: dy * speed, duration: 1.2, ease: 'power2.out', overwrite: 'auto' })
          })
        }
        section.addEventListener('mousemove', handleMouseMove)
        return () => section.removeEventListener('mousemove', handleMouseMove)
      }

    }, heroRef)

    // ── Scroll sections ────────────────────────────────────────────────────
    const triggers: ScrollTrigger[] = []
    document.querySelectorAll('.js-section').forEach((el) => {
      gsap.set(el, { opacity: 0, y: 24 })
      triggers.push(ScrollTrigger.create({
        trigger: el, start: 'top 84%', once: true,
        onEnter: () => gsap.to(el, { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' }),
      }))
    })
    document.querySelectorAll('.gal-img').forEach((el) => {
      const img = el.querySelector('img')
      el.addEventListener('mouseenter', () => { if (img) gsap.to(img, { scale: 1.08, duration: 0.4, ease: 'power2.out' }) })
      el.addEventListener('mouseleave', () => { if (img) gsap.to(img, { scale: 1, duration: 0.4, ease: 'power2.out' }) })
    })

    return () => {
      ctx.revert()
      triggers.forEach((t) => t.kill())
    }
  }, [reduced])

  return (
    <div>
      {/* ════════════════════════════════════════════════════
          HERO — 100vh, dark blue gradient
      ════════════════════════════════════════════════════ */}
      <section ref={heroRef} style={{
        paddingTop: 84, paddingBottom: 0,
        background: theme.heroGradient,
        position: 'relative', overflow: 'hidden',
        display: 'flex', flexDirection: 'column',
      }}>
        {/* Animated blobs */}
        <div ref={blob1Ref} className="absolute pointer-events-none" style={{ top: '-15%', left: '-8%', width: 560, height: 560, borderRadius: '50%', background: theme.heroBlob1, filter: 'blur(50px)' }} />
        <div ref={blob2Ref} className="absolute pointer-events-none" style={{ bottom: '-18%', right: '-10%', width: 640, height: 640, borderRadius: '50%', background: theme.heroBlob2, filter: 'blur(60px)' }} />
        <div ref={blob3Ref} className="absolute pointer-events-none" style={{ top: '30%', left: '30%', width: 450, height: 450, borderRadius: '50%', background: theme.heroBlob1, filter: 'blur(70px)' }} />
        {/* Dot pattern */}
        <div className="absolute inset-0 pointer-events-none" style={{ backgroundImage: 'radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px)', backgroundSize: '26px 26px' }} />
        {/* Glowing accent line */}
        <div className="absolute pointer-events-none" style={{ top: 0, left: 0, right: 0, height: 2, background: 'linear-gradient(90deg, transparent, rgba(59,130,246,0.8), rgba(99,102,241,0.8), transparent)' }} />

        <div style={{ maxWidth: 1320, margin: '0 auto', padding: '24px 32px', width: '100%' }}>
          <div style={{ display: 'grid', gridTemplateColumns: '52% 48%', gap: 40, alignItems: 'center' }}>

            {/* ── LEFT COLUMN ── */}
            <div ref={leftRef} style={{ display: 'flex', flexDirection: 'column', gap: 0 }}>

              {/* BADGE */}
              <div style={{ marginBottom: 18 }}>
                <div style={{ display: 'inline-flex', alignItems: 'center', gap: 9, padding: '9px 20px', borderRadius: 999, background: theme.heroBadgeBg, border: `1px solid ${theme.heroBadgeBorder}`, backdropFilter: 'blur(10px)' }}>
                  <span style={{ width: 9, height: 9, borderRadius: '50%', background: '#4ADE80', boxShadow: '0 0 8px #4ADE80', flexShrink: 0, display: 'inline-block' }} />
                  <span style={{ fontSize: 14, fontWeight: 700, color: '#93C5FD', letterSpacing: '0.02em' }}>📢 Pendaftaran & Inden Dibuka — TA 2027/2028</span>
                </div>
              </div>

              {/* HEADING */}
              <h1 style={{ fontWeight: 800, lineHeight: 1.12, letterSpacing: '-0.035em', marginBottom: 20, fontSize: 'clamp(3.2rem, 5.2vw, 4.8rem)', color: 'white' }}>
                {((typeof window !== 'undefined' && (window as any).APP_SETTINGS?.hero_title_1) || 'Ciptakan Generasi Gen Z')}<br />
                <span style={{ color: '#60A5FA' }}>{((typeof window !== 'undefined' && (window as any).APP_SETTINGS?.hero_title_2) || 'Cerdas & Ceria')}</span>
              </h1>

              {/* DESCRIPTION */}
              <div style={{ fontSize: '1.25rem', lineHeight: 1.7, color: 'rgba(255,255,255,0.88)', marginBottom: 28, maxWidth: 580, fontWeight: 400 }}
                dangerouslySetInnerHTML={{ __html: (typeof window !== 'undefined' && (window as any).APP_SETTINGS?.hero_subtitle) || 'Memberikan pendidikan terbaik untuk generasi masa depan dengan fasilitas lengkap dan tenaga pengajar profesional. Amankan kuota putra-putri Anda sekarang.' }} />

              {/* BUTTONS */}
              <div style={{ display: 'flex', flexWrap: 'wrap', gap: 16, marginBottom: 28 }}>
                <button onClick={() => onNavigate('registration')}
                  style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '16px 36px', borderRadius: 18, background: 'white', color: theme.accentColor, fontSize: 18, fontWeight: 800, border: 'none', cursor: 'pointer', boxShadow: '0 10px 36px rgba(0,0,0,0.3)', transition: 'all 0.2s' }}
                  onMouseEnter={(e) => { if (!reduced) gsap.to(e.currentTarget, { scale: 1.05, boxShadow: '0 14px 44px rgba(0,0,0,0.4)', duration: 0.2 }) }}
                  onMouseLeave={(e) => { if (!reduced) gsap.to(e.currentTarget, { scale: 1, boxShadow: '0 10px 36px rgba(0,0,0,0.3)', duration: 0.2 }) }}>
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12" /><polyline points="12 5 19 12 12 19" /></svg>
                  Daftar Sekarang
                </button>
                <button onClick={() => onNavigate('about')}
                  style={{ display: 'flex', alignItems: 'center', gap: 10, padding: '16px 36px', borderRadius: 18, background: 'rgba(255,255,255,0.14)', color: 'white', fontSize: 18, fontWeight: 700, border: '1.5px solid rgba(255,255,255,0.4)', cursor: 'pointer', backdropFilter: 'blur(10px)', transition: 'all 0.2s' }}
                  onMouseEnter={(e) => { if (!reduced) gsap.to(e.currentTarget, { scale: 1.04, background: 'rgba(255,255,255,0.22)', duration: 0.2 }) }}
                  onMouseLeave={(e) => { if (!reduced) gsap.to(e.currentTarget, { scale: 1, background: 'rgba(255,255,255,0.14)', duration: 0.2 }) }}>
                  Kenali Kami
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><circle cx="12" cy="12" r="10" /><line x1="12" y1="16" x2="12" y2="12" /><line x1="12" y1="8" x2="12.01" y2="8" /></svg>
                </button>
              </div>

              {/* STATS — inline row with dividers */}
              <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 0, marginBottom: 24, padding: '22px 32px', borderRadius: 24, background: 'rgba(255,255,255,0.09)', backdropFilter: 'blur(14px)', border: '1px solid rgba(255,255,255,0.18)' }}>
                {[
                  { val: '2', label: 'Unit Sekolah' },
                  { val: '320+', label: 'Alumni' },
                  { val: '15+', label: 'Tahun Berdiri' },
                  { val: '95%', label: 'Kepuasan Ortu' },
                ].map((s, i) => (
                  <div key={s.label} style={{ display: 'flex', alignItems: 'center', flex: 1 }}>
                    {i > 0 && <div style={{ width: 1, height: 48, background: 'rgba(255,255,255,0.22)', marginRight: 24 }} />}
                    <div>
                      <div style={{ fontWeight: 800, fontSize: 34, color: 'white', letterSpacing: '-0.025em', lineHeight: 1 }}>{s.val}</div>
                      <div style={{ fontSize: 13, color: 'rgba(255,255,255,0.7)', fontWeight: 600, marginTop: 4 }}>{s.label}</div>
                    </div>
                  </div>
                ))}
              </div>

              {/* FEATURE BADGES */}
              <div style={{ display: 'flex', flexWrap: 'wrap', gap: 12 }}>
                {[
                  { icon: '✓', label: 'Guru Profesional', color: '#4ADE80' },
                  { icon: '✓', label: 'Kurikulum Islami', color: '#60A5FA' },
                  { icon: '✓', label: 'Fasilitas Modern', color: '#F472B6' },
                ].map((b) => (
                  <div key={b.label} style={{ display: 'inline-flex', alignItems: 'center', gap: 8, padding: '9px 18px', borderRadius: 999, background: 'rgba(255,255,255,0.1)', border: '1px solid rgba(255,255,255,0.2)', backdropFilter: 'blur(8px)' }}>
                    <span style={{ color: b.color, fontWeight: 800, fontSize: 15 }}>{b.icon}</span>
                    <span style={{ fontSize: 14, color: 'rgba(255,255,255,0.95)', fontWeight: 600 }}>{b.label}</span>
                  </div>
                ))}
              </div>
            </div>

            {/* ── RIGHT COLUMN — Illustration / Custom Uploaded Image ── */}
            <div ref={illusRef} style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', position: 'relative', opacity: reduced ? 1 : 0 }}>
              <div style={{ position: 'absolute', inset: '0%', borderRadius: '50%', background: 'radial-gradient(circle, rgba(96,165,250,0.3) 0%, transparent 70%)', filter: 'blur(30px)', zIndex: 0 }} />
              <div style={{ position: 'relative', zIndex: 1, width: '100%', display: 'flex', justifyContent: 'center' }}>
                {((typeof window !== 'undefined' && (window as any).APP_SETTINGS?.hero_image && String((window as any).APP_SETTINGS.hero_image).trim() !== '')) ? (
                  <img
                    src={(window as any).APP_SETTINGS.hero_image}
                    alt="Hero Promosi"
                    style={{ maxWidth: '100%', maxHeight: 480, objectFit: 'contain', borderRadius: 24, boxShadow: '0 20px 40px rgba(0,0,0,0.25)' }}
                  />
                ) : (
                  <div style={{ width: '100%', transform: 'scale(1.05)' }}>
                    <HeroIllustration />
                  </div>
                )}
              </div>
            </div>

          </div>
        </div>

        {/* Bottom wave */}
        <div style={{ lineHeight: 0, marginTop: 24 }}>
          <svg viewBox="0 0 1440 44" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style={{ display: 'block', width: '100%', height: 44 }}>
            <path d="M0 44L1440 44L1440 22C1200 44 960 0 720 0C480 0 240 44 0 22L0 44Z" fill={theme.sectionBg} />
          </svg>
        </div>
      </section>

      {/* ════════════════════════════════════════════════════
          PROFIL SEKOLAH
      ════════════════════════════════════════════════════ */}
      <section id="profil-sekolah" className="js-section" style={{ background: theme.sectionBg, padding: '24px 0 20px 0' }}>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10">
          <div className="mb-4 text-start">
            <span className="text-xs font-700 uppercase tracking-widest block mb-1" style={{ fontWeight: 700, color: theme.accentColor }}>
              PENDIDIKAN BERKUALITAS UNTUK ANANDA
            </span>
            <h2 className="font-800 text-gray-900 text-2xl sm:text-3xl tracking-tight mb-1" style={{ fontWeight: 800 }}>
              {(typeof window !== 'undefined' && (window as any).APP_SETTINGS?.profil_title) || 'Kenali Lebih Dekat'}
            </h2>
            <div className="text-xs sm:text-sm text-gray-600 font-medium"
              dangerouslySetInnerHTML={{ __html: (typeof window !== 'undefined' && (window as any).APP_SETTINGS?.profil_subtitle) || 'Pelajari lebih lanjut tentang institusi dan program unggulan kami untuk putra-putri Anda.' }} />
          </div>

          <ProfilSekolahSection />
        </div>
      </section>

      {/* ════════════════════════════════════════════════════
          INFORMASI SISA KUOTA PENDAFTARAN 
      ════════════════════════════════════════════════════ */}
      <section id="info-kuota" className="js-section" style={{ background: theme.sectionAlt, padding: '24px 0 28px 0', borderTop: '1px solid #E2E8F0' }}>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10">
          <div className="text-center mb-4">
            <span className="text-xs font-700 uppercase tracking-widest block mb-1" style={{ fontWeight: 700, color: theme.accentColor }}>
              INFO ONLINE
            </span>
            <h2 className="font-800 text-gray-900 text-2xl sm:text-3xl tracking-tight mb-1" style={{ fontWeight: 800 }}>
              Informasi Sisa Kuota Pendaftaran
            </h2>
            <p className="text-xs sm:text-sm text-gray-600">
              Pilih tahun ajaran untuk melihat sisa kuota yang tersedia di setiap cabang.
            </p>
          </div>

          <InfoKuotaSection onNavigate={onNavigate} />
        </div>
      </section>
    </div>
  )
}

{/* ── Sub-components: Profil Sekolah Tabs ── */ }
function ProfilSekolahSection() {
  const [tab, setTab] = useState<'yayasan' | 'tk' | 'paud'>('yayasan')
  const settings = (typeof window !== 'undefined' && (window as any).APP_SETTINGS) || {}

  const yayasanName = settings.yayasan_profile_name || 'Dr. H. Fulan, M.Pd'
  const yayasanRole = settings.yayasan_profile_title || 'Ketua Yayasan'
  const yayasanImg = settings.yayasan_image
  const yayasanHeader = settings.yayasan_title || 'Yayasan Pendidikan Al-Hikmah 2'
  const yayasanDesc = settings.yayasan_desc || 'Yayasan kami berdiri sejak tahun 2010 dengan komitmen penuh untuk mencetak generasi Islami yang unggul, berakhlak mulia, dan siap menghadapi tantangan masa depan. Kami menaungi berbagai cabang pendidikan dengan standar pengajaran terpadu, fasilitas belajar yang modern, dan tenaga pendidik profesional.'
  const yayasanVisi = settings.yayasan_visi || 'Mewujudkan lembaga pendidikan yang berpijak pada penanaman aqidah Islam dan kemuliaan karakter.'
  const yayasanMisi = settings.yayasan_misi || 'Menyelenggarakan proses pembelajaran yang berkualitas, kreatif, inovatif, dan bermakna.'

  const tkName = settings.tk_profile_name || 'Bunda Fatimah, S.Pd'
  const tkRole = settings.tk_profile_title || 'Kepala Sekolah TK'
  const tkImg = settings.tk_image
  const tkHeader = settings.tk_title || 'Taman Kanak-Kanak (TK)'
  const tkDesc = settings.tk_desc || 'Program Taman Kanak-Kanak dirancang untuk mengasah kognitif, motorik, dan sosio-emosional anak melalui pendekatan learning by playing. Tersedia pilihan jam belajar fleksibel: TK Pagi dan TK Siang untuk mendukung rutinitas keluarga Anda.'

  const paudName = settings.paud_profile_name || 'Bunda Aisyah, S.Psi'
  const paudRole = settings.paud_profile_title || 'Koordinator PAUD'
  const paudImg = settings.paud_image
  const paudHeader = settings.paud_title || 'Pendidikan Anak Usia Dini (PAUD)'
  const paudDesc = settings.paud_desc || 'Program PAUD/Kelompok Bermain kami memfasilitasi masa emas (golden age) anak. Lingkungan sekolah dibuat senyaman dan seaman mungkin seperti di rumah (homely environment) untuk merangsang rasa ingin tahu dan keberanian anak bersosialisasi.'

  return (
    <div>
      {/* Tabs */}
      <div className="flex gap-2 border-b mb-4 pb-2" style={{ borderColor: '#E2E8F0' }}>
        {[
          { id: 'yayasan', label: '🏠 Profil Yayasan' },
          { id: 'tk', label: '🎒 Program TK' },
          { id: 'paud', label: '🎨 Program PAUD' },
        ].map((t) => (
          <button key={t.id} onClick={() => setTab(t.id as any)}
            className="px-4 py-2 text-xs sm:text-sm rounded-xl font-600 transition-all cursor-pointer"
            style={{
              fontWeight: tab === t.id ? 700 : 500,
              color: tab === t.id ? '#2563EB' : '#64748B',
              background: tab === t.id ? '#EFF6FF' : 'transparent',
              borderBottom: tab === t.id ? '2px solid #2563EB' : 'none',
            }}>
            {t.label}
          </button>
        ))}
      </div>

      {/* Tab 1: Profil Yayasan */}
      {tab === 'yayasan' && (
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
          <div className="lg:col-span-4 p-4 rounded-2xl border text-center flex flex-col justify-center bg-gray-50" style={{ borderColor: '#F1F5F9' }}>
            {yayasanImg ? (
              <img src={yayasanImg} alt={yayasanName} className="w-20 h-20 rounded-full mx-auto mb-2 object-cover border-2 border-blue-200 shadow-sm" />
            ) : (
              <div className="w-16 h-16 rounded-full mx-auto mb-2 overflow-hidden border-2 border-blue-200 flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-lg">
                FK
              </div>
            )}
            <h4 className="font-700 text-gray-900 text-sm" style={{ fontWeight: 700 }}>{yayasanName}</h4>
            <p className="text-[11px] text-gray-500 mb-3">{yayasanRole}</p>
            <div className="text-xs text-gray-600 space-y-1.5 text-left pt-2.5 border-t border-gray-200">
              <div>✓ Berdiri sejak 2010</div>
              <div>✓ Akreditasi A</div>
              <div>✓ 2 Unit Sekolah</div>
              <div>✓ 320+ Alumni</div>
            </div>
          </div>
          <div className="lg:col-span-8 space-y-3">
            <h3 className="font-800 text-gray-900 text-lg" style={{ fontWeight: 800 }}>{yayasanHeader}</h3>
            <div className="text-xs sm:text-sm text-gray-600 leading-relaxed italic"
              dangerouslySetInnerHTML={{ __html: yayasanDesc }} />
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div className="p-3 rounded-xl bg-blue-50 border border-blue-100">
                <h5 className="font-700 text-blue-700 text-[11px] uppercase mb-1">🎯 Visi</h5>
                <div className="text-xs text-gray-700" dangerouslySetInnerHTML={{ __html: yayasanVisi }} />
              </div>
              <div className="p-3 rounded-xl bg-blue-50 border border-blue-100">
                <h5 className="font-700 text-blue-700 text-[11px] uppercase mb-1">🚀 Misi</h5>
                <div className="text-xs text-gray-700" dangerouslySetInnerHTML={{ __html: yayasanMisi }} />
              </div>
            </div>
            <div>
              <h5 className="font-700 text-gray-900 text-xs mb-1.5">Program Unggulan</h5>
              <div className="flex flex-wrap gap-2">
                {['Tahfidz Quran', 'Bahasa Arab & Inggris', 'Seni & Kreativitas', 'Pendidikan Karakter'].map((item) => (
                  <span key={item} className="px-2.5 py-0.5 rounded-full text-xs font-600 bg-blue-50 text-blue-700 border border-blue-200">
                    ⭐ {item}
                  </span>
                ))}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Tab 2: Program TK */}
      {tab === 'tk' && (
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
          <div className="lg:col-span-4 p-4 rounded-2xl border text-center flex flex-col justify-center bg-gray-50" style={{ borderColor: '#F1F5F9' }}>
            {tkImg ? (
              <img src={tkImg} alt={tkName} className="w-20 h-20 rounded-full mx-auto mb-2 object-cover border-2 border-blue-200 shadow-sm" />
            ) : (
              <div className="w-16 h-16 rounded-full mx-auto mb-2 overflow-hidden border-2 border-blue-200 flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-lg">
                BF
              </div>
            )}
            <h4 className="font-700 text-gray-900 text-sm" style={{ fontWeight: 700 }}>{tkName}</h4>
            <p className="text-[11px] text-gray-500 mb-3">{tkRole}</p>
            <div className="text-xs text-gray-600 space-y-1.5 text-left pt-2.5 border-t border-gray-200">
              <div>✓ Usia 4-6 Tahun</div>
              <div>✓ Kurikulum Tematik</div>
              <div>✓ Native Speaker</div>
            </div>
          </div>
          <div className="lg:col-span-8 space-y-3">
            <h3 className="font-800 text-gray-900 text-lg" style={{ fontWeight: 800 }}>{tkHeader}</h3>
            <div className="text-xs sm:text-sm text-gray-600 leading-relaxed"
              dangerouslySetInnerHTML={{ __html: tkDesc }} />
            <div className="p-3 rounded-xl bg-gray-50 border border-gray-200">
              <h5 className="font-700 text-gray-900 text-xs mb-1.5">📋 Fokus Pembelajaran</h5>
              <ul className="text-xs text-gray-700 space-y-1 list-disc list-inside">
                <li>Pengenalan dasar membaca, menulis, dan berhitung (Calistung) tanpa membebani anak.</li>
                <li>Penanaman nilai-nilai akhlak, adab makan, dan rutinitas ibadah harian.</li>
                <li>Pengembangan kreativitas melalui art and craft, musik, dan eksperimen sains sederhana.</li>
              </ul>
            </div>
            <div>
              <h5 className="font-700 text-gray-900 text-xs mb-1.5">Aktivitas Penunjang</h5>
              <div className="flex flex-wrap gap-2">
                {['Renang Ceria', 'Field Trip Edutainment', 'Cek Kesehatan Rutin'].map((item) => (
                  <span key={item} className="px-2.5 py-0.5 rounded-full text-xs font-600 bg-blue-50 text-blue-700 border border-blue-200">
                    ⭐ {item}
                  </span>
                ))}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Tab 3: Program PAUD */}
      {tab === 'paud' && (
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
          <div className="lg:col-span-4 p-4 rounded-2xl border text-center flex flex-col justify-center bg-gray-50" style={{ borderColor: '#F1F5F9' }}>
            {paudImg ? (
              <img src={paudImg} alt={paudName} className="w-20 h-20 rounded-full mx-auto mb-2 object-cover border-2 border-blue-200 shadow-sm" />
            ) : (
              <div className="w-16 h-16 rounded-full mx-auto mb-2 overflow-hidden border-2 border-blue-200 flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-lg">
                BA
              </div>
            )}
            <h4 className="font-700 text-gray-900 text-sm" style={{ fontWeight: 700 }}>{paudName}</h4>
            <p className="text-[11px] text-gray-500 mb-3">{paudRole}</p>
            <div className="text-xs text-gray-600 space-y-1.5 text-left pt-2.5 border-t border-gray-200">
              <div>✓ Usia 2-4 Tahun</div>
              <div>✓ Toilet Training</div>
              <div>✓ Indoor Playground</div>
            </div>
          </div>
          <div className="lg:col-span-8 space-y-3">
            <h3 className="font-800 text-gray-900 text-lg" style={{ fontWeight: 800 }}>{paudHeader}</h3>
            <div className="text-xs sm:text-sm text-gray-600 leading-relaxed"
              dangerouslySetInnerHTML={{ __html: paudDesc }} />
            <div className="p-3 rounded-xl bg-gray-50 border border-gray-200">
              <h5 className="font-700 text-gray-900 text-xs mb-1.5">🎯 Target Perkembangan</h5>
              <ul className="text-xs text-gray-700 space-y-1 list-disc list-inside">
                <li>Stimulasi komunikasi dan bahasa untuk memperkaya kosakata harian anak.</li>
                <li>Latihan kemandirian dasar seperti mencuci tangan, merapikan mainan, dan toilet training.</li>
                <li>Peningkatan sensorik dan motorik kasar melalui permainan edukatif yang diawasi langsung oleh guru.</li>
              </ul>
            </div>
            <div>
              <h5 className="font-700 text-gray-900 text-xs mb-1.5">Metode Stimulasi</h5>
              <div className="flex flex-wrap gap-2">
                {['Bernyanyi & Menari', 'Story Telling', 'Sensoric Play'].map((item) => (
                  <span key={item} className="px-2.5 py-0.5 rounded-full text-xs font-600 bg-blue-50 text-blue-700 border border-blue-200">
                    ⭐ {item}
                  </span>
                ))}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}

{/* ── Sub-components: Info Kuota Tabs ── */ }
function InfoKuotaSection({ onNavigate }: { onNavigate: (p: Page) => void }) {
  const [yearTab, setYearTab] = useState<number>(0)

  const years = [
    { year: '2027/2028', p1: 25, p2: 29, p3: 25, m1: 25, m2: 25, m3: 25 },
    { year: '2028/2029', p1: 24, p2: 30, p3: 25, m1: 25, m2: 25, m3: 25 },
    { year: '2029/2030', p1: 25, p2: 30, p3: 25, m1: 25, m2: 25, m3: 25 },
  ]

  const cur = years[yearTab]

  return (
    <div>
      {/* Year Tabs */}
      <div className="flex justify-center gap-2 mb-4">
        {years.map((y, idx) => (
          <button key={y.year} onClick={() => setYearTab(idx)}
            className="px-4 py-1.5 text-xs rounded-full font-700 transition-all cursor-pointer"
            style={{
              fontWeight: 700,
              background: yearTab === idx ? '#2563EB' : 'white',
              color: yearTab === idx ? 'white' : '#475569',
              border: '1px solid #CBD5E1',
            }}>
            Pendaftaran {y.year}
          </button>
        ))}
      </div>

      {/* Quota Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-4xl mx-auto">
        {/* TK Permata */}
        <div className="bg-white rounded-2xl overflow-hidden border shadow-xs" style={{ borderColor: '#E2E8F0' }}>
          <div className="bg-blue-600 px-4 py-2.5 flex justify-between items-center text-white">
            <h4 className="font-700 text-xs sm:text-sm" style={{ fontWeight: 700 }}>TK Permata</h4>
            <span className="text-[11px] text-blue-100">TA {cur.year}</span>
          </div>
          <div className="p-4 space-y-2">
            {[
              { label: 'TK Pagi', max: 25, rem: cur.p1 },
              { label: 'TK Siang', max: 30, rem: cur.p2 },
              { label: 'PAUD (Kelompok Bermain)', max: 25, rem: cur.p3 },
            ].map((cls) => (
              <div key={cls.label} className="flex justify-between items-center py-1.5 border-b border-gray-100 last:border-0">
                <div>
                  <div className="font-600 text-xs text-gray-900" style={{ fontWeight: 600 }}>{cls.label}</div>
                  <div className="text-[10px] text-gray-400">Maksimal {cls.max} Anak</div>
                </div>
                <span className="px-2.5 py-0.5 rounded-full text-[11px] font-700 bg-emerald-100 text-emerald-800">
                  Sisa {cls.rem} Kursi
                </span>
              </div>
            ))}
            <button onClick={() => onNavigate('registration')}
              className="w-full py-2 rounded-xl text-white text-xs font-700 transition-all bg-blue-600 hover:bg-blue-700 mt-1" style={{ fontWeight: 700 }}>
              Daftar Tahun {cur.year} →
            </button>
          </div>
        </div>

        {/* TK Matahari */}
        <div className="bg-white rounded-2xl overflow-hidden border shadow-xs" style={{ borderColor: '#E2E8F0' }}>
          <div className="bg-blue-600 px-4 py-2.5 flex justify-between items-center text-white">
            <h4 className="font-700 text-xs sm:text-sm" style={{ fontWeight: 700 }}>TK Matahari</h4>
            <span className="text-[11px] text-blue-100">TA {cur.year}</span>
          </div>
          <div className="p-4 space-y-2">
            {[
              { label: 'TK Pagi', max: 25, rem: cur.m1 },
              { label: 'TK Siang', max: 25, rem: cur.m2 },
              { label: 'PAUD (Kelompok Bermain)', max: 25, rem: cur.m3 },
            ].map((cls) => (
              <div key={cls.label} className="flex justify-between items-center py-1.5 border-b border-gray-100 last:border-0">
                <div>
                  <div className="font-600 text-xs text-gray-900" style={{ fontWeight: 600 }}>{cls.label}</div>
                  <div className="text-[10px] text-gray-400">Maksimal {cls.max} Anak</div>
                </div>
                <span className="px-2.5 py-0.5 rounded-full text-[11px] font-700 bg-emerald-100 text-emerald-800">
                  Sisa {cls.rem} Kursi
                </span>
              </div>
            ))}
            <button onClick={() => onNavigate('registration')}
              className="w-full py-2 rounded-xl text-white text-xs font-700 transition-all bg-blue-600 hover:bg-blue-700 mt-1" style={{ fontWeight: 700 }}>
              Daftar Tahun {cur.year} →
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}

// ── Sub-components ─────────────────────────────────────────────────────────────
function FeatureCard({ f, reduced }: { f: typeof FEATURES[0]; reduced: boolean }) {
  const ref = useRef<HTMLDivElement>(null)
  return (
    <div ref={ref} className="p-5 rounded-2xl border bg-white cursor-default" style={{ borderColor: '#F1F5F9', boxShadow: '0 2px 8px rgba(0,0,0,0.04)' }}
      onMouseEnter={() => { if (!reduced && ref.current) gsap.to(ref.current, { y: -6, boxShadow: '0 16px 40px rgba(0,0,0,0.1)', duration: 0.25, ease: 'power2.out' }) }}
      onMouseLeave={() => { if (!reduced && ref.current) gsap.to(ref.current, { y: 0, boxShadow: '0 2px 8px rgba(0,0,0,0.04)', duration: 0.25, ease: 'power2.out' }) }}>
      <div className="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl mb-3" style={{ background: f.bg }}>{f.icon}</div>
      <h3 className="text-sm mb-1.5" style={{ fontWeight: 700, color: '#0F172A' }}>{f.title}</h3>
      <p className="text-xs leading-relaxed" style={{ color: '#64748B' }}>{f.desc}</p>
    </div>
  )
}

function NewsCard({ n, reduced }: { n: typeof NEWS[0]; reduced: boolean }) {
  const ref = useRef<HTMLDivElement>(null)
  return (
    <div ref={ref} className="bg-white rounded-2xl overflow-hidden border cursor-pointer" style={{ borderColor: '#F1F5F9', boxShadow: '0 2px 8px rgba(0,0,0,0.05)' }}
      onMouseEnter={() => { if (!reduced && ref.current) gsap.to(ref.current, { y: -5, boxShadow: '0 16px 40px rgba(0,0,0,0.1)', duration: 0.25, ease: 'power2.out' }) }}
      onMouseLeave={() => { if (!reduced && ref.current) gsap.to(ref.current, { y: 0, boxShadow: '0 2px 8px rgba(0,0,0,0.05)', duration: 0.25, ease: 'power2.out' }) }}>
      <div className="overflow-hidden" style={{ height: 160 }}>
        <img src={n.img} alt={n.title} className="w-full h-full object-cover" style={{ transition: 'transform 0.4s' }}
          onMouseEnter={(e) => { if (!reduced) gsap.to(e.currentTarget, { scale: 1.06, duration: 0.4 }) }}
          onMouseLeave={(e) => { if (!reduced) gsap.to(e.currentTarget, { scale: 1, duration: 0.4 }) }} />
      </div>
      <div className="p-4">
        <div className="flex items-center gap-2 mb-2">
          <span className="px-2 py-0.5 rounded-full text-xs font-600" style={{ background: '#EFF6FF', color: '#2563EB', fontWeight: 600 }}>{n.cat}</span>
          <span className="text-xs" style={{ color: '#94A3B8' }}>{n.date}</span>
        </div>
        <h3 className="text-sm font-700 mb-2 leading-snug" style={{ fontWeight: 700, color: '#0F172A' }}>{n.title}</h3>
        <p className="text-xs leading-relaxed mb-3" style={{ color: '#64748B' }}>{n.desc}</p>
        <div className="flex items-center gap-1 text-xs font-600" style={{ color: '#2563EB', fontWeight: 600 }}>
          Baca Selengkapnya <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12" /><polyline points="12 5 19 12 12 19" /></svg>
        </div>
      </div>
    </div>
  )
}

function StatCard({ icon, val, label, sub, reduced }: { icon: string; val: string; label: string; sub: string; reduced: boolean }) {
  const ref = useRef<HTMLDivElement>(null)
  useEffect(() => {
    if (!ref.current || reduced) return
    const num = parseFloat(val.replace(/[^0-9.]/g, ''))
    const suf = val.replace(/[0-9.]/g, '')
    const el = ref.current.querySelector('[data-c]') as HTMLElement
    if (!el || isNaN(num)) return
    const o = { v: 0 }
    const ctx = gsap.context(() => {
      gsap.to(o, { v: num, duration: 2, ease: 'power2.out', scrollTrigger: { trigger: ref.current, start: 'top 85%', once: true }, onUpdate: () => { el.textContent = Math.round(o.v) + suf } })
    }, ref)
    return () => ctx.revert()
  }, [val, reduced])
  return (
    <div ref={ref} className="text-center text-white p-5 rounded-2xl" style={{ background: 'rgba(255,255,255,0.08)', backdropFilter: 'blur(8px)' }}>
      <div className="text-3xl mb-2">{icon}</div>
      <div style={{ fontWeight: 800, fontSize: 36, letterSpacing: '-0.03em', lineHeight: 1 }} data-c>{val}</div>
      <div style={{ fontWeight: 600, fontSize: 14, marginTop: 6 }}>{label}</div>
      <div style={{ color: 'rgba(255,255,255,0.6)', fontSize: 12, marginTop: 2 }}>{sub}</div>
    </div>
  )
}

function TestiCard({ t, reduced }: { t: typeof TESTIS[0]; reduced: boolean }) {
  const ref = useRef<HTMLDivElement>(null)
  return (
    <div ref={ref} className="p-5 rounded-2xl border bg-white" style={{ borderColor: '#F1F5F9', boxShadow: '0 2px 8px rgba(0,0,0,0.04)' }}
      onMouseEnter={() => { if (!reduced && ref.current) gsap.to(ref.current, { y: -4, boxShadow: '0 16px 40px rgba(0,0,0,0.09)', duration: 0.25, ease: 'power2.out' }) }}
      onMouseLeave={() => { if (!reduced && ref.current) gsap.to(ref.current, { y: 0, boxShadow: '0 2px 8px rgba(0,0,0,0.04)', duration: 0.25, ease: 'power2.out' }) }}>
      <div className="flex gap-0.5 mb-3">{Array(t.rating).fill(0).map((_, i) => <svg key={i} width="13" height="13" viewBox="0 0 24 24" fill="#F59E0B"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>)}</div>
      <p className="text-sm leading-relaxed mb-4" style={{ color: '#374151' }}>"{t.text}"</p>
      <div className="flex items-center gap-3">
        <div className="w-9 h-9 rounded-full flex items-center justify-center text-xs font-700 flex-shrink-0" style={{ background: t.bg, color: t.color, fontWeight: 700 }}>{t.init}</div>
        <div>
          <div className="text-sm font-700" style={{ fontWeight: 700, color: '#0F172A' }}>{t.name}</div>
          <div className="text-xs" style={{ color: '#94A3B8' }}>{t.role}</div>
        </div>
      </div>
    </div>
  )
}
