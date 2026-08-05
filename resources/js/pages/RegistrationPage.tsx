import { useState, useEffect, useRef } from 'react'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useReducedMotion } from '../hooks/useReducedMotion'

gsap.registerPlugin(ScrollTrigger)

type Page = 'home' | 'about' | 'registration' | 'contact' | 'login' | 'register'
interface Props { onNavigate: (p: Page) => void }

const steps = [
  { num: 1, label: 'Pilih Sekolah', desc: 'Pilih unit program yang diinginkan' },
  { num: 2, label: 'Isi Formulir', desc: 'Lengkapi data calon murid dan orang tua' },
  { num: 3, label: 'Upload Dokumen', desc: 'Unggah berkas dan persyaratan' },
  { num: 4, label: 'Konfirmasi', desc: 'Tunggu verifikasi dan konfirmasi kami' },
]

const requirements = [
  { icon: '📋', title: 'Kartu Keluarga', desc: 'Scan atau foto KK yang masih berlaku' },
  { icon: '📄', title: 'Akta Kelahiran', desc: 'Scan atau foto akta kelahiran anak' },
  { icon: '📍', title: 'Titik Koordinat Rumah', desc: 'Salin titik koordinat dari Google Maps' },
  { icon: '📏', title: 'Data Fisik Anak', desc: 'Tinggi & berat badan untuk medis' },
]

const faqs = [
  { q: 'Kapan pendaftaran dibuka?', a: 'Pendaftaran tahun ajaran 2027/2028 sudah dibuka. Segera amankan tempat karena kuota terbatas.' },
  { q: 'Bagaimana cara mendapatkan kuota tersedia?', a: 'Kuota dapat dilihat langsung di halaman ini. Segera daftar untuk mengamankan kursi yang tersedia.' },
  { q: 'Apakah bisa mendaftar online?', a: 'Ya, pendaftaran dapat dilakukan secara online melalui portal ini atau langsung ke kantor yayasan.' },
  { q: 'Kapan pengumuman diterima?', a: 'Pengumuman disampaikan maksimal 7 hari kerja setelah berkas pendaftaran lengkap diterima.' },
]

export default function RegistrationPage({ onNavigate }: Props) {
  const reduced = useReducedMotion()
  const [openFaq, setOpenFaq] = useState<number | null>(null)
  const heroRef = useRef<HTMLElement>(null)

  useEffect(() => {
    if (reduced || !heroRef.current) return
    const decors = heroRef.current.querySelectorAll('[data-hero-decor]')
    const ctx = gsap.context(() => {
      decors.forEach((el, i) => {
        gsap.fromTo(el, { opacity: 0, scale: 0 }, { opacity: 1, scale: 1, duration: 0.55, delay: 0.25 + i * 0.11, ease: 'back.out(2)' })
        gsap.to(el, { y: i % 2 === 0 ? -10 : 10, x: i % 3 === 0 ? 7 : -5, duration: 2.8 + i * 0.35, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.4 })
      })
    }, heroRef)
    return () => ctx.revert()
  }, [reduced])

  useEffect(() => {
    if (reduced) return
    const els = document.querySelectorAll('.js-rfade')
    const triggers: ScrollTrigger[] = []
    els.forEach((el) => {
      gsap.set(el, { opacity: 0, y: 20 })
      triggers.push(ScrollTrigger.create({ trigger: el, start: 'top 84%', once: true, onEnter: () => gsap.to(el, { opacity: 1, y: 0, duration: 0.65, ease: 'power3.out' }) }))
    })
    return () => triggers.forEach((t) => t.kill())
  }, [reduced])

  return (
    <div>
      {/* Hero */}
      <section ref={heroRef} className="pt-16 relative overflow-hidden" style={{ background: 'linear-gradient(135deg, #1B3A8C 0%, #2D55E8 55%, #4F8BFF 100%)', minHeight: 240 }}>
        <div className="absolute inset-0 pointer-events-none" style={{ backgroundImage: 'radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px)', backgroundSize: '28px 28px' }} />
        {/* Floating decoratives */}
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '20%', right: '10%', opacity: 0 }}>
          <svg width="36" height="36" viewBox="0 0 24 24" fill="#FCD34D"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '50%', right: '25%', opacity: 0 }}>
          <svg width="20" height="20" viewBox="0 0 20 20"><polygon points="10,1 19,10 10,19 1,10" fill="#F472B6" opacity="0.9" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '30%', right: '38%', opacity: 0 }}>
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.35)" strokeWidth="1.5"><rect x="2" y="3" width="9" height="18" rx="2" /><rect x="13" y="3" width="9" height="18" rx="2" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ bottom: '28%', right: '6%', opacity: 0 }}>
          <svg width="28" height="28" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13" fill="none" stroke="rgba(255,255,255,0.3)" strokeWidth="2" /><circle cx="16" cy="16" r="7" fill="rgba(255,255,255,0.12)" /></svg>
        </div>
        {/* Document/clipboard illustration */}
        <div data-hero-decor className="absolute pointer-events-none hidden lg:block" style={{ bottom: '28px', right: '60px', opacity: 0 }}>
          <svg width="100" height="110" viewBox="0 0 100 110" fill="none">
            <rect x="15" y="18" width="70" height="84" rx="6" fill="rgba(255,255,255,0.15)" />
            <rect x="35" y="10" width="30" height="16" rx="4" fill="rgba(255,255,255,0.2)" />
            <line x1="28" y1="42" x2="72" y2="42" stroke="rgba(255,255,255,0.4)" strokeWidth="2.5" strokeLinecap="round" />
            <line x1="28" y1="54" x2="72" y2="54" stroke="rgba(255,255,255,0.3)" strokeWidth="2" strokeLinecap="round" />
            <line x1="28" y1="66" x2="60" y2="66" stroke="rgba(255,255,255,0.3)" strokeWidth="2" strokeLinecap="round" />
            <circle cx="32" cy="80" r="6" fill="#FCD34D" opacity="0.7" />
            <path d="M29 80 L31 82 L36 77" stroke="white" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </div>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10 pt-12 pb-16 relative z-10">
          <div className="text-white/60 text-xs mb-3 flex items-center gap-1.5">
            <button onClick={() => onNavigate('home')} className="hover:text-white transition-colors">Beranda</button>
            <span>›</span><span className="text-white">Pendaftaran</span>
          </div>
          <h1 className="text-4xl font-800 text-white mb-2" style={{ fontWeight: 800, letterSpacing: '-0.025em' }}>Pendaftaran</h1>
          <p className="text-white/75 text-sm">Daftarkan putra-putri Anda sekarang. Kuota terbatas!</p>
        </div>
        <div className="absolute bottom-0 left-0 right-0" style={{ lineHeight: 0 }}>
          <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style={{ display: 'block', width: '100%', height: 48 }}>
            <path d="M0 48L1440 48L1440 24C1200 48 960 0 720 0C480 0 240 48 0 24L0 48Z" fill="#F7F9FC" />
          </svg>
        </div>
      </section>

      <div className="max-w-[1320px] mx-auto px-6 sm:px-10 py-10">
        <div className="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-8">
          {/* Main */}
          <div className="space-y-8">
            {/* Stepper */}
            <div className="bg-white rounded-2xl p-6 border js-rfade" style={{ borderColor: '#F3F4F6', boxShadow: '0 2px 8px rgba(0,0,0,0.04)' }}>
              <h2 className="font-700 text-gray-900 mb-5" style={{ fontWeight: 700, fontSize: 17 }}>Alur Pendaftaran</h2>
              <div className="flex items-start gap-0">
                {steps.map((step, i) => (
                  <div key={step.num} className="flex-1 flex flex-col items-center relative">
                    {i < steps.length - 1 && (
                      <div className="absolute top-5 left-1/2 right-0 h-0.5" style={{ background: '#E5E7EB', zIndex: 0 }} />
                    )}
                    <div className="relative z-10 w-10 h-10 rounded-full flex items-center justify-center text-sm font-700 mb-2 shadow-sm"
                      style={{ background: 'linear-gradient(135deg, #365CF5, #5B7CFA)', color: 'white', fontWeight: 700 }}>
                      {step.num}
                    </div>
                    <div className="font-600 text-xs text-center mb-0.5" style={{ fontWeight: 600, color: '#111827' }}>{step.label}</div>
                    <div className="text-xs text-center hidden sm:block" style={{ color: '#9CA3AF' }}>{step.desc}</div>
                  </div>
                ))}
              </div>
            </div>

            {/* Quota Cards */}
            <div className="js-rfade">
              <h2 className="font-700 text-gray-900 mb-4" style={{ fontWeight: 700, fontSize: 17 }}>Kuota Tersedia 2027/2028</h2>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                {[
                  { name: 'TK Permata', ta: 'Tahun Ajaran 2027/2028', classes: [{ name: 'TK Pagi', rem: 25 }, { name: 'TK Siang', rem: 29 }, { name: 'Kelompok Bermain', rem: 25 }] },
                  { name: 'TK Matahari', ta: 'Tahun Ajaran 2027/2028', classes: [{ name: 'TK Pagi', rem: 25 }, { name: 'TK Siang', rem: 25 }, { name: 'Kelompok Bermain', rem: 25 }] },
                ].map((school) => (
                  <div key={school.name} className="rounded-2xl overflow-hidden border bg-white" style={{ borderColor: '#F3F4F6', boxShadow: '0 2px 8px rgba(0,0,0,0.04)' }}>
                    <div className="px-5 py-3 flex items-center justify-between" style={{ background: 'linear-gradient(135deg, #365CF5, #5B7CFA)' }}>
                      <h3 className="text-white font-700 text-sm" style={{ fontWeight: 700 }}>{school.name}</h3>
                      <span className="text-white/80 text-xs">{school.ta}</span>
                    </div>
                    <div className="p-4 space-y-2">
                      {school.classes.map((cls) => (
                        <div key={cls.name} className="flex items-center justify-between py-2.5 px-3 rounded-xl" style={{ background: '#F9FAFB' }}>
                          <div>
                            <div className="font-600 text-sm text-gray-900" style={{ fontWeight: 600 }}>{cls.name}</div>
                            <div className="text-xs text-gray-400">Maksimal 25 Anak</div>
                          </div>
                          <span className="px-3 py-1 rounded-full text-xs font-700" style={{ background: '#D1FAE5', color: '#065F46', fontWeight: 700 }}>
                            Sisa {cls.rem} Kursi
                          </span>
                        </div>
                      ))}
                    </div>
                    <div className="px-4 pb-4">
                      <button className="w-full py-2.5 rounded-xl text-white text-sm font-600 transition-all hover:opacity-90"
                        style={{ background: 'linear-gradient(135deg, #365CF5, #5B7CFA)', fontWeight: 600 }}>
                        Daftar di {school.name}
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Requirements */}
            <div className="js-rfade">
              <h2 className="font-700 text-gray-900 mb-4" style={{ fontWeight: 700, fontSize: 17 }}>Persyaratan Pendaftaran</h2>
              <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                {requirements.map((r) => (
                  <div key={r.title} className="p-4 rounded-2xl border bg-white text-center" style={{ borderColor: '#F3F4F6', boxShadow: '0 2px 8px rgba(0,0,0,0.04)' }}>
                    <div className="text-2xl mb-2">{r.icon}</div>
                    <div className="font-600 text-xs text-gray-900 mb-1" style={{ fontWeight: 600 }}>{r.title}</div>
                    <div className="text-xs" style={{ color: '#9CA3AF' }}>{r.desc}</div>
                  </div>
                ))}
              </div>
            </div>

            {/* FAQ */}
            <div className="js-rfade">
              <h2 className="font-700 text-gray-900 mb-4" style={{ fontWeight: 700, fontSize: 17 }}>FAQ</h2>
              <div className="space-y-2">
                {faqs.map((faq, i) => (
                  <div key={i} className="rounded-2xl border bg-white overflow-hidden" style={{ borderColor: '#F3F4F6' }}>
                    <button onClick={() => setOpenFaq(openFaq === i ? null : i)}
                      className="w-full flex items-center justify-between px-5 py-4 text-left">
                      <span className="font-600 text-sm text-gray-900 pr-4" style={{ fontWeight: 600 }}>{faq.q}</span>
                      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"
                        style={{ flexShrink: 0, transform: openFaq === i ? 'rotate(180deg)' : 'rotate(0)', transition: 'transform 0.2s' }}>
                        <polyline points="6 9 12 15 18 9" />
                      </svg>
                    </button>
                    {openFaq === i && (
                      <div className="px-5 pb-4 text-sm leading-relaxed" style={{ color: '#6B7280', borderTop: '1px solid #F3F4F6', paddingTop: 12 }}>{faq.a}</div>
                    )}
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Sidebar CTA */}
          <div className="js-rfade">
            <div className="rounded-2xl overflow-hidden sticky top-20" style={{ boxShadow: '0 8px 30px rgba(54,92,245,0.15)' }}>
              <div className="p-6 text-center relative overflow-hidden" style={{ background: 'linear-gradient(135deg, #1B3A8C 0%, #365CF5 100%)' }}>
                <div className="absolute inset-0 pointer-events-none" style={{ backgroundImage: 'radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px)', backgroundSize: '20px 20px' }} />
                <div className="relative z-10">
                  <div className="text-4xl mb-3">🎓</div>
                  <h3 className="font-800 text-white mb-2" style={{ fontWeight: 800, fontSize: 17 }}>Pendaftaran Mudah & Cepat</h3>
                  <p className="text-xs text-white/75 mb-5">Daftarkan akun untuk mengakses portal orang tua dan informasi sekolah.</p>
                  <button onClick={() => onNavigate('register')}
                    className="w-full py-3 rounded-xl font-700 text-sm transition-all hover:opacity-90"
                    style={{ background: 'white', color: '#1D3A8A', fontWeight: 700 }}>
                    Daftar Sekarang
                  </button>
                  <button onClick={() => onNavigate('login')}
                    className="w-full py-2.5 rounded-xl font-600 text-sm text-white mt-2 border transition-all hover:bg-white/10"
                    style={{ fontWeight: 600, border: '1.5px solid rgba(255,255,255,0.35)' }}>
                    Sudah Punya Akun?
                  </button>
                </div>
              </div>
              <div className="p-4 bg-white space-y-2.5">
                {[
                  { icon: '📞', text: '(0341) 567-8901' },
                  { icon: '💬', text: '0812-3456-78902' },
                  { icon: '✉️', text: 'admin@alhikmah.sch.id' },
                  { icon: '🕐', text: 'Senin–Sabtu 07.00–15.00' },
                ].map((c) => (
                  <div key={c.text} className="flex items-center gap-2.5 text-xs" style={{ color: '#6B7280' }}>
                    <span>{c.icon}</span><span>{c.text}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
