import { useState, useEffect, useRef } from 'react'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useReducedMotion } from '../hooks/useReducedMotion'

gsap.registerPlugin(ScrollTrigger)

type Page = 'home' | 'about' | 'registration' | 'contact' | 'login' | 'register'
interface Props { onNavigate: (p: Page) => void }

const contacts = [
  { icon: '📞', label: 'Telepon', value: '(0341) 567-8901' },
  { icon: '💬', label: 'WhatsApp', value: '0812-3456-78902' },
  { icon: '✉️', label: 'Email', value: 'admin@alhikmah.sch.id' },
  { icon: '📍', label: 'Alamat', value: 'Jl. KH. Wahid Hasyim No. 45, Sukun, Malang' },
  { icon: '🕐', label: 'Jam Operasional', value: 'Senin – Sabtu, 07.00 – 15.00 WIB' },
]

export default function ContactPage({ onNavigate }: Props) {
  const reduced = useReducedMotion()
  const [form, setForm] = useState({ name: '', email: '', message: '' })
  const [sent, setSent] = useState(false)
  const heroRef = useRef<HTMLElement>(null)

  useEffect(() => {
    if (reduced || !heroRef.current) return
    const decors = heroRef.current.querySelectorAll('[data-hero-decor]')
    const ctx = gsap.context(() => {
      decors.forEach((el, i) => {
        gsap.fromTo(el, { opacity: 0, scale: 0 }, { opacity: 1, scale: 1, duration: 0.55, delay: 0.25 + i * 0.12, ease: 'back.out(2)' })
        gsap.to(el, { y: i % 2 === 0 ? -10 : 9, x: i % 3 === 0 ? 6 : -5, duration: 3 + i * 0.4, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.35 })
      })
    }, heroRef)
    return () => ctx.revert()
  }, [reduced])

  useEffect(() => {
    if (reduced) return
    const els = document.querySelectorAll('.js-cfade')
    const triggers: ScrollTrigger[] = []
    els.forEach((el) => {
      gsap.set(el, { opacity: 0, y: 20 })
      triggers.push(ScrollTrigger.create({ trigger: el, start: 'top 84%', once: true, onEnter: () => gsap.to(el, { opacity: 1, y: 0, duration: 0.65, ease: 'power3.out' }) }))
    })
    return () => triggers.forEach((t) => t.kill())
  }, [reduced])

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    setSent(true)
    setForm({ name: '', email: '', message: '' })
    setTimeout(() => setSent(false), 4000)
  }

  return (
    <div>
      {/* Hero */}
      <section ref={heroRef} className="pt-16 relative overflow-hidden" style={{ background: 'linear-gradient(135deg, #1B3A8C 0%, #2D55E8 55%, #4F8BFF 100%)', minHeight: 240 }}>
        <div className="absolute inset-0 pointer-events-none" style={{ backgroundImage: 'radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px)', backgroundSize: '28px 28px' }} />
        {/* Floating decoratives */}
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '18%', right: '10%', opacity: 0 }}>
          <svg width="34" height="34" viewBox="0 0 24 24" fill="#FCD34D"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '55%', right: '20%', opacity: 0 }}>
          <svg width="20" height="20" viewBox="0 0 20 20"><polygon points="10,1 19,10 10,19 1,10" fill="#34D399" opacity="0.85" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '32%', right: '32%', opacity: 0 }}>
          <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" strokeWidth="1.5" strokeLinecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" /><circle cx="12" cy="10" r="3" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ bottom: '30%', right: '8%', opacity: 0 }}>
          <svg width="26" height="26" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13" fill="none" stroke="rgba(255,255,255,0.3)" strokeWidth="2" /></svg>
        </div>
        {/* Envelope illustration */}
        <div data-hero-decor className="absolute pointer-events-none hidden lg:block" style={{ bottom: '28px', right: '55px', opacity: 0 }}>
          <svg width="110" height="80" viewBox="0 0 110 80" fill="none">
            <rect x="8" y="18" width="94" height="56" rx="6" fill="rgba(255,255,255,0.15)" />
            <path d="M8 24 L55 50 L102 24" stroke="rgba(255,255,255,0.5)" strokeWidth="2.5" fill="none" strokeLinecap="round" />
            <line x1="8" y1="74" x2="35" y2="50" stroke="rgba(255,255,255,0.3)" strokeWidth="1.5" />
            <line x1="102" y1="74" x2="75" y2="50" stroke="rgba(255,255,255,0.3)" strokeWidth="1.5" />
            <circle cx="90" cy="12" r="10" fill="#FCD34D" opacity="0.7" />
            <path d="M86 12 L89 15 L95 9" stroke="white" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </div>
        <div className="max-w-[1320px] mx-auto px-6 sm:px-10 pt-12 pb-16 relative z-10">
          <div className="text-white/60 text-xs mb-3 flex items-center gap-1.5">
            <button onClick={() => onNavigate('home')} className="hover:text-white transition-colors">Beranda</button>
            <span>›</span><span className="text-white">Hubungi Kami</span>
          </div>
          <h1 className="text-4xl font-800 text-white mb-2" style={{ fontWeight: 800, letterSpacing: '-0.025em' }}>Hubungi Kami</h1>
          <p className="text-white/75 text-sm">Kami siap membantu Anda.</p>
        </div>
        <div className="absolute bottom-0 left-0 right-0" style={{ lineHeight: 0 }}>
          <svg viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style={{ display: 'block', width: '100%', height: 48 }}>
            <path d="M0 48L1440 48L1440 24C1200 48 960 0 720 0C480 0 240 48 0 24L0 48Z" fill="#F7F9FC" />
          </svg>
        </div>
      </section>

      <div className="max-w-[1320px] mx-auto px-6 sm:px-10 py-10">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          {/* Left — contact info */}
          <div className="space-y-4 js-cfade">
            <h2 className="font-700 text-gray-900 mb-1" style={{ fontWeight: 700, fontSize: 18 }}>Informasi Kontak</h2>
            <p className="text-sm mb-4" style={{ color: '#6B7280' }}>Hubungi kami melalui salah satu saluran berikut atau kunjungi langsung sekolah kami.</p>
            {contacts.map((c) => (
              <div key={c.label} className="flex gap-4 p-4 rounded-2xl bg-white border" style={{ borderColor: '#F3F4F6', boxShadow: '0 2px 8px rgba(0,0,0,0.04)' }}>
                <div className="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0" style={{ background: '#EEF1FE' }}>{c.icon}</div>
                <div>
                  <div className="text-xs font-600 text-gray-400 mb-0.5" style={{ fontWeight: 600 }}>{c.label}</div>
                  <div className="text-sm font-600 text-gray-900" style={{ fontWeight: 600 }}>{c.value}</div>
                </div>
              </div>
            ))}
          </div>

          {/* Right — map + form */}
          <div className="space-y-5 js-cfade">
            <div className="rounded-2xl overflow-hidden" style={{ height: 260, boxShadow: '0 4px 16px rgba(0,0,0,0.08)' }}>
              <iframe
                title="Lokasi Yayasan Al-Hikmah 2"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.649!2d112.6065!3d-7.9835!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7882000000001%3A0x1!2zWWF5YXNhbiBBbC1IaWttYWggMg!5e0!3m2!1sen!2sid!4v1000000000000"
                width="100%" height="100%" style={{ border: 0 }} allowFullScreen loading="lazy" referrerPolicy="no-referrer-when-downgrade"
              />
            </div>

            {/* Form */}
            <div className="bg-white rounded-2xl p-6 border" style={{ borderColor: '#F3F4F6', boxShadow: '0 2px 8px rgba(0,0,0,0.04)' }}>
              <h3 className="font-700 text-gray-900 mb-4" style={{ fontWeight: 700, fontSize: 16 }}>Kirim Pesan</h3>
              {sent && (
                <div className="mb-4 p-3 rounded-xl flex items-center gap-2 text-sm" style={{ background: '#D1FAE5', color: '#065F46' }}>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round"><polyline points="20 6 9 17 4 12" /></svg>
                  Pesan berhasil terkirim!
                </div>
              )}
              <form onSubmit={handleSubmit} className="space-y-3">
                <div>
                  <label className="block text-xs font-600 text-gray-600 mb-1.5" style={{ fontWeight: 600 }}>Nama Lengkap</label>
                  <input type="text" placeholder="Nama Anda" required value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-xl text-sm border outline-none"
                    style={{ borderColor: '#E5E7EB', fontFamily: 'inherit', color: '#111827' }} />
                </div>
                <div>
                  <label className="block text-xs font-600 text-gray-600 mb-1.5" style={{ fontWeight: 600 }}>Email</label>
                  <input type="email" placeholder="nama@email.com" required value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-xl text-sm border outline-none"
                    style={{ borderColor: '#E5E7EB', fontFamily: 'inherit', color: '#111827' }} />
                </div>
                <div>
                  <label className="block text-xs font-600 text-gray-600 mb-1.5" style={{ fontWeight: 600 }}>Pesan</label>
                  <textarea rows={4} placeholder="Tulis pesan Anda..." required value={form.message} onChange={(e) => setForm({ ...form, message: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-xl text-sm border outline-none resize-none"
                    style={{ borderColor: '#E5E7EB', fontFamily: 'inherit', color: '#111827' }} />
                </div>
                <button type="submit" className="w-full py-3 rounded-xl text-white text-sm font-600 transition-all hover:opacity-90"
                  style={{ background: 'linear-gradient(135deg, #365CF5, #5B7CFA)', fontWeight: 600 }}>
                  Kirim Pesan
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
