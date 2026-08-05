import { useEffect, useRef } from 'react'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useReducedMotion } from '../hooks/useReducedMotion'

gsap.registerPlugin(ScrollTrigger)

type Page = 'home' | 'about' | 'registration' | 'contact' | 'login' | 'register'
interface Props { onNavigate: (p: Page) => void }

const SCHOOL_PHOTO = 'https://images.unsplash.com/photo-1591123120675-6f7f1aae0e5b?w=900&h=380&fit=crop&auto=format'
const FACILITIES = [
  { name: 'Ruang Kelas Nyaman', photo: 'https://images.unsplash.com/photo-1740635341299-3b8e3490f546?w=340&h=200&fit=crop&auto=format', icon: '🏫' },
  { name: 'Perpustakaan', photo: 'https://images.unsplash.com/photo-1629470937900-f9ef0f2a8086?w=340&h=200&fit=crop&auto=format', icon: '📚' },
  { name: 'Laboratorium', photo: 'https://images.unsplash.com/photo-1564981797816-1043664bf78d?w=340&h=200&fit=crop&auto=format', icon: '🔬' },
  { name: 'Playground', photo: 'https://images.unsplash.com/photo-1460788150444-d9dc07fa9dba?w=340&h=200&fit=crop&auto=format', icon: '🛝' },
]

const timeline = [
  { year: '2010', title: 'Yayasan Al-Hikmah 2 Didirikan', desc: 'Yayasan resmi berdiri dengan visi pendidikan Islami berkualitas.' },
  { year: '2012', title: 'TK Permata Dibuka', desc: 'Unit pertama mulai beroperasi dengan 30 murid angkatan perdana.' },
  { year: '2015', title: 'Akreditasi A Pertama', desc: 'Meraih akreditasi A dari Badan Akreditasi Nasional.' },
  { year: '2017', title: 'Pendirian Unit Sekolah TK Matahari', desc: 'Ekspansi unit kedua menjawab tingginya permintaan orang tua.' },
  { year: '2024', title: '250+ Alumni Berhasil', desc: '250+ alumni berhasil menyelesaikan pendidikan dan masuk SD favorit.' },
]

export default function AboutPage({ onNavigate }: Props) {
  const reduced = useReducedMotion()
  const heroRef = useRef<HTMLElement>(null)
  useScrollFade('.js-fade', reduced)

  useEffect(() => {
    if (reduced || !heroRef.current) return
    const decors = heroRef.current.querySelectorAll('[data-hero-decor]')
    const ctx = gsap.context(() => {
      decors.forEach((el, i) => {
        gsap.fromTo(el, { opacity: 0, scale: 0, rotate: -20 }, { opacity: 1, scale: 1, rotate: 0, duration: 0.6, delay: 0.3 + i * 0.12, ease: 'back.out(2)' })
        gsap.to(el, { y: i % 2 === 0 ? -12 : 12, x: i % 3 === 0 ? 8 : -6, duration: 2.5 + i * 0.4, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.3 })
      })
    }, heroRef)
    return () => ctx.revert()
  }, [reduced])

  return (
    <div>
      {/* Hero Banner */}
      <section ref={heroRef} className="pt-16 relative overflow-hidden" style={{ background: 'linear-gradient(135deg, #1B3A8C 0%, #2D55E8 55%, #4F8BFF 100%)', minHeight: 280 }}>
        <div className="absolute inset-0 pointer-events-none" style={{ backgroundImage: 'radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px)', backgroundSize: '28px 28px' }} />
        {/* Floating decoratives */}
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '18%', right: '12%', opacity: 0 }}>
          <svg width="38" height="38" viewBox="0 0 24 24" fill="#FCD34D"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '55%', right: '22%', opacity: 0 }}>
          <svg width="22" height="22" viewBox="0 0 24 24" fill="#A78BFA"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '25%', right: '35%', opacity: 0 }}>
          <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" strokeWidth="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" /><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '60%', right: '8%', opacity: 0 }}>
          <svg width="32" height="32" viewBox="0 0 32 32"><polygon points="16,1 29,9 29,23 16,31 3,23 3,9" fill="none" stroke="rgba(255,255,255,0.3)" strokeWidth="2" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '35%', right: '5%', opacity: 0 }}>
          <svg width="18" height="18" viewBox="0 0 20 20"><polygon points="10,1 19,10 10,19 1,10" fill="#34D399" opacity="0.8" /></svg>
        </div>
        {/* Illustration — school figure */}
        <div data-hero-decor className="absolute pointer-events-none hidden lg:block" style={{ bottom: '32px', right: '80px', opacity: 0 }}>
          <svg width="120" height="110" viewBox="0 0 120 110" fill="none">
            <rect x="30" y="28" width="60" height="62" rx="4" fill="rgba(255,255,255,0.15)" />
            <path d="M30 38 Q60 14 90 38" fill="rgba(255,255,255,0.2)" />
            <circle cx="60" cy="26" r="6" fill="#FCD34D" opacity="0.8" />
            <rect x="43" y="46" width="14" height="12" rx="2" fill="rgba(255,255,255,0.4)" />
            <rect x="63" y="46" width="14" height="12" rx="2" fill="rgba(255,255,255,0.4)" />
            <rect x="48" y="73" width="24" height="17" rx="3" fill="#FCD34D" opacity="0.6" />
            <ellipse cx="22" cy="86" rx="10" ry="9" fill="rgba(74,222,128,0.7)" />
            <rect x="18" y="88" width="6" height="14" rx="2" fill="#92400E" opacity="0.6" />
            <ellipse cx="98" cy="90" rx="8" ry="7" fill="rgba(74,222,128,0.6)" />
            <rect x="95" y="92" width="5" height="12" rx="2" fill="#92400E" opacity="0.5" />
          </svg>
        </div>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10 pt-14 pb-20 relative z-10">
          <div className="text-white/60 text-xs mb-3 flex items-center gap-1.5">
            <button onClick={() => onNavigate('home')} className="hover:text-white transition-colors">Beranda</button>
            <span>›</span><span className="text-white">Tentang Kami</span>
          </div>
          <h1 className="text-4xl font-800 text-white mb-2" style={{ fontWeight: 800, letterSpacing: '-0.025em' }}>Tentang Kami</h1>
          <p className="text-white/75 text-sm max-w-md">Mengenal lebih dekat Yayasan Al-Hikmah 2, lembaga pendidikan Islam terpercaya di Kota Malang.</p>
        </div>
        <div className="absolute bottom-0 left-0 right-0" style={{ lineHeight: 0 }}>
          <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style={{ display: 'block', width: '100%', height: 48 }}>
            <path d="M0 48L1440 48L1440 24C1200 48 960 0 720 0C480 0 240 48 0 24L0 48Z" fill="white" />
          </svg>
        </div>
      </section>

      {/* School Photo */}
      <section className="py-10" style={{ background: 'white' }}>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10">
          <div className="rounded-2xl overflow-hidden js-fade" style={{ boxShadow: '0 12px 40px rgba(0,0,0,0.1)' }}>
            <img src={SCHOOL_PHOTO} alt="Sekolah Al-Hikmah 2" className="w-full object-cover" style={{ height: 280 }} />
          </div>
        </div>
      </section>

      {/* Sejarah + Timeline */}
      <section className="py-12" style={{ background: 'white' }}>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10">
          <SectionHead badge="SEJARAH" title="Sejarah Yayasan Al-Hikmah 2" />
          <p className="text-sm leading-relaxed mb-8 mt-3 max-w-2xl" style={{ color: '#6B7280' }}>
            Yayasan kami berdiri sejak tahun 2010 dengan komitmen penuh untuk mencetak generasi Islami yang unggul, berakhlak mulia, dan siap menghadapi tantangan masa depan.
          </p>
          {/* Horizontal timeline */}
          <div className="relative js-fade">
            <div className="absolute top-5 left-0 right-0 h-0.5" style={{ background: '#E5E7EB' }} />
            <div className="grid grid-cols-2 md:grid-cols-5 gap-4 relative">
              {timeline.map((t, i) => (
                <div key={t.year} className="relative pt-10">
                  <div className="absolute top-2.5 left-0 -translate-x-1/2 w-5 h-5 rounded-full border-2 border-white shadow-sm flex items-center justify-center"
                    style={{ background: i === timeline.length - 1 ? '#365CF5' : '#94A3B8', left: '50%' }}>
                    {i === timeline.length - 1 && <div className="w-2 h-2 rounded-full bg-white" />}
                  </div>
                  <div className="text-center">
                    <div className="font-700 text-sm mb-1" style={{ color: '#365CF5', fontWeight: 700 }}>{t.year}</div>
                    <div className="font-600 text-xs text-gray-900 mb-1" style={{ fontWeight: 600 }}>{t.title}</div>
                    <div className="text-xs" style={{ color: '#9CA3AF' }}>{t.desc}</div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Visi & Misi */}
      <section className="py-12" style={{ background: '#F7F9FC' }}>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10">
          <SectionHead badge="VISI & MISI" title="Visi & Misi Kami" />
          <div className="grid grid-cols-1 md:grid-cols-2 gap-5 mt-8 js-fade">
            <div className="p-6 rounded-2xl bg-white border" style={{ borderColor: '#F3F4F6', boxShadow: '0 2px 8px rgba(0,0,0,0.04)' }}>
              <div className="flex items-center gap-3 mb-4">
                <div className="w-9 h-9 rounded-xl flex items-center justify-center text-lg" style={{ background: '#EEF1FE' }}>🌟</div>
                <h3 className="font-700 text-gray-900" style={{ fontWeight: 700 }}>Visi</h3>
              </div>
              <p className="text-sm leading-relaxed" style={{ color: '#6B7280' }}>
                Menjadi lembaga pendidikan Islam yang terpadu dalam mencetak generasi yang cerdas, ceria, berakhlak mulia, dan berdaya saing tinggi untuk masa depan.
              </p>
            </div>
            <div className="p-6 rounded-2xl bg-white border" style={{ borderColor: '#F3F4F6', boxShadow: '0 2px 8px rgba(0,0,0,0.04)' }}>
              <div className="flex items-center gap-3 mb-4">
                <div className="w-9 h-9 rounded-xl flex items-center justify-center text-lg" style={{ background: '#DCFCE7' }}>🎯</div>
                <h3 className="font-700 text-gray-900" style={{ fontWeight: 700 }}>Misi</h3>
              </div>
              <div className="space-y-2">
                {[
                  'Mencetak generasi yang memiliki nilai karakter Islami yang kuat',
                  'Mengembangkan potensi anak secara optimal dan menyeluruh',
                  'Menciptakan lingkungan belajar yang aman, nyaman, dan menyenangkan',
                ].map((m) => (
                  <div key={m} className="flex gap-2 text-sm" style={{ color: '#6B7280' }}>
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" className="flex-shrink-0 mt-0.5"><circle cx="8" cy="8" r="8" fill="#EEF1FE" /><path d="M5 8l2 2 4-4" stroke="#365CF5" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" /></svg>
                    <span>{m}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Ketua Yayasan */}
      <section className="py-12" style={{ background: 'white' }}>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10">
          <SectionHead badge="PIMPINAN" title="Ketua Yayasan" />
          <div className="mt-8 js-fade">
            <div className="flex flex-col md:flex-row gap-6 p-6 rounded-2xl border bg-white items-start" style={{ borderColor: '#F3F4F6', boxShadow: '0 4px 16px rgba(0,0,0,0.06)', maxWidth: 680 }}>
              <div className="w-24 h-24 rounded-2xl flex items-center justify-center text-2xl font-800 flex-shrink-0" style={{ background: 'linear-gradient(135deg, #EEF1FE, #C7D2FE)', color: '#365CF5', fontWeight: 800, minWidth: 96 }}>FK</div>
              <div>
                <h3 className="font-800 text-gray-900 text-lg mb-0.5" style={{ fontWeight: 800 }}>Dr. H. Fulan, M.Pd</h3>
                <p className="text-sm text-blue-600 font-600 mb-3" style={{ color: '#365CF5', fontWeight: 600 }}>Ketua Yayasan Al-Hikmah 2</p>
                <p className="text-sm leading-relaxed mb-4" style={{ color: '#6B7280' }}>
                  Berkomitmen mewujudkan pendidikan Islam yang bermanfaat, membentuk generasi yang cerdas dan berakhlak mulia untuk masa depan yang gemilang.
                </p>
                <div className="flex flex-wrap gap-3">
                  {[{ icon: '📅', label: 'Sejak 2010' }, { icon: '⏱', label: '15+ Tahun' }, { icon: '🏫', label: '2 Unit Sekolah' }, { icon: '🏅', label: 'Akreditasi Terpercaya' }].map((b) => (
                    <div key={b.label} className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-500" style={{ background: '#F9FAFB', color: '#374151', fontWeight: 500 }}>
                      <span>{b.icon}</span><span>{b.label}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Fasilitas */}
      <section className="py-12" style={{ background: '#F7F9FC' }}>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10">
          <SectionHead badge="FASILITAS" title="Fasilitas Kami" />
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 js-fade">
            {FACILITIES.map((f) => (
              <div key={f.name} className="rounded-2xl overflow-hidden bg-white" style={{ boxShadow: '0 4px 16px rgba(0,0,0,0.07)' }}>
                <div className="h-36 relative overflow-hidden">
                  <img src={f.photo} alt={f.name} className="w-full h-full object-cover" />
                  <div className="absolute inset-0" style={{ background: 'linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 60%)' }} />
                </div>
                <div className="px-4 py-3 flex items-center gap-2">
                  <span>{f.icon}</span>
                  <span className="text-sm font-600" style={{ fontWeight: 600, color: '#111827' }}>{f.name}</span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Pencapaian */}
      <section className="py-12" style={{ background: 'white' }}>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10">
          <SectionHead badge="PENCAPAIAN" title="Pencapaian Kami" />
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 js-fade">
            {[
              { val: 'A', label: 'Kedua Sekolah', sub: 'Akreditasi', color: '#365CF5', bg: '#EEF1FE' },
              { val: '350+', label: 'Lulusan Berhasil', sub: 'Alumni', color: '#10B981', bg: '#DCFCE7' },
              { val: '50+', label: 'Penghargaan', sub: 'Prestasi', color: '#F59E0B', bg: '#FEF3C7' },
              { val: '15+', label: 'Tahun Berdiri', sub: 'Pengalaman', color: '#8B5CF6', bg: '#F5F3FF' },
            ].map((s) => (
              <div key={s.label} className="p-5 rounded-2xl text-center border" style={{ borderColor: '#F3F4F6', background: s.bg }}>
                <div className="text-4xl font-800 mb-1" style={{ fontWeight: 800, color: s.color }}>{s.val}</div>
                <div className="font-600 text-sm" style={{ fontWeight: 600, color: '#374151' }}>{s.sub}</div>
                <div className="text-xs mt-0.5" style={{ color: '#9CA3AF' }}>{s.label}</div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-12" style={{ background: '#F7F9FC' }}>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10 js-fade">
          <div className="rounded-3xl p-10 text-center relative overflow-hidden" style={{ background: 'linear-gradient(135deg, #1B3A8C 0%, #365CF5 60%, #4F8BFF 100%)' }}>
            <div className="absolute inset-0 pointer-events-none" style={{ backgroundImage: 'radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px)', backgroundSize: '24px 24px' }} />
            <div className="relative z-10">
              <h2 className="text-3xl font-800 text-white mb-3" style={{ fontWeight: 800, letterSpacing: '-0.02em' }}>Bersama Al-Hikmah 2, Wujudkan Generasi Terbaik</h2>
              <p className="text-sm text-white/80 mb-6">Daftarkan putra-putri Anda dan jadilah bagian keluarga besar Al-Hikmah 2.</p>
              <button onClick={() => onNavigate('registration')}
                className="px-7 py-3 rounded-2xl font-700 text-sm shadow-lg transition-all hover:opacity-90"
                style={{ background: 'white', color: '#1D3A8A', fontWeight: 700 }}>
                Daftar Sekarang
              </button>
            </div>
          </div>
        </div>
      </section>
    </div>
  )
}

function SectionHead({ badge, title }: { badge: string; title: string }) {
  return (
    <div>
      <div className="inline-flex px-3 py-1 rounded-full text-xs font-600 mb-2" style={{ background: '#EEF1FE', color: '#365CF5', fontWeight: 600 }}>{badge}</div>
      <h2 className="font-800" style={{ fontWeight: 800, color: '#111827', fontSize: 24, letterSpacing: '-0.02em' }}>{title}</h2>
    </div>
  )
}

function useScrollFade(selector: string, reduced: boolean) {
  useEffect(() => {
    if (reduced) return
    const els = document.querySelectorAll(selector)
    const triggers: ScrollTrigger[] = []
    els.forEach((el) => {
      gsap.set(el, { opacity: 0, y: 20 })
      triggers.push(ScrollTrigger.create({ trigger: el, start: 'top 84%', once: true, onEnter: () => gsap.to(el, { opacity: 1, y: 0, duration: 0.65, ease: 'power3.out' }) }))
    })
    return () => triggers.forEach((t) => t.kill())
  }, [reduced, selector])
}
