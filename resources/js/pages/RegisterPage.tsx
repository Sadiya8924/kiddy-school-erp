import { useState, useEffect, useRef } from 'react'
import gsap from 'gsap'
import { useReducedMotion } from '../hooks/useReducedMotion'

type Page = 'home' | 'about' | 'registration' | 'contact' | 'login' | 'register'
interface Props { onNavigate: (p: Page) => void }

const REGISTER_PHOTO = 'https://images.unsplash.com/photo-1759143102544-790af882ff45?w=600&h=700&fit=crop&auto=format'

export default function RegisterPage({ onNavigate }: Props) {
  const [form, setForm] = useState({ name: '', email: '', password: '', confirm: '' })
  const [step, setStep] = useState(0)
  const reduced = useReducedMotion()
  const leftRef = useRef<HTMLDivElement>(null)
  const csrfToken = (typeof document !== 'undefined' && document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || ''

  useEffect(() => {
    if (reduced || !leftRef.current) return
    const decors = leftRef.current.querySelectorAll('[data-hero-decor]')
    const ctx = gsap.context(() => {
      decors.forEach((el, i) => {
        gsap.fromTo(el, { opacity: 0, scale: 0 }, { opacity: 1, scale: 1, duration: 0.55, delay: 0.4 + i * 0.12, ease: 'back.out(2)' })
        gsap.to(el, { y: i % 2 === 0 ? -11 : 9, x: i % 3 === 0 ? 7 : -5, duration: 3.2 + i * 0.4, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.35 })
      })
    }, leftRef)
    return () => ctx.revert()
  }, [reduced])

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    setStep(1)
  }

  return (
    <div className="min-h-screen flex" style={{ paddingTop: 64, background: '#F7F9FC' }}>
      {/* Left panel — illustration */}
      <div ref={leftRef} className="hidden lg:flex flex-1 relative overflow-hidden"
        style={{ background: 'linear-gradient(135deg, #1B3A8C 0%, #2D55E8 60%, #4F8BFF 100%)' }}>
        <div className="absolute inset-0 pointer-events-none" style={{ backgroundImage: 'radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px)', backgroundSize: '28px 28px' }} />
        <img src={REGISTER_PHOTO} alt="Siswa Al-Hikmah 2" className="absolute inset-0 w-full h-full object-cover opacity-25" style={{ objectPosition: 'top' }} />
        {/* Floating decoratives */}
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '10%', right: '14%', opacity: 0 }}>
          <svg width="36" height="36" viewBox="0 0 24 24" fill="#FCD34D"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '38%', left: '10%', opacity: 0 }}>
          <svg width="20" height="20" viewBox="0 0 20 20"><polygon points="10,1 19,10 10,19 1,10" fill="#F472B6" opacity="0.85"/></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '20%', right: '5%', opacity: 0 }}>
          <svg width="28" height="28" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13" fill="none" stroke="rgba(255,255,255,0.3)" strokeWidth="2"/><circle cx="16" cy="16" r="7" fill="rgba(255,255,255,0.1)"/></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ bottom: '22%', right: '12%', opacity: 0 }}>
          <svg width="22" height="22" viewBox="0 0 24 24" fill="#A78BFA"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <div className="absolute inset-0" style={{ background: 'linear-gradient(to bottom, rgba(29,58,140,0.5), rgba(29,58,140,0.85))' }} />
        <div className="relative z-10 flex flex-col justify-between p-12 w-full">
          <div />
          <div>
            <div className="text-5xl mb-5">✨</div>
            <h2 className="text-3xl font-800 text-white mb-3" style={{ fontWeight: 800, letterSpacing: '-0.025em' }}>Buat Akun Baru</h2>
            <p className="text-white/75 text-sm mb-8 leading-relaxed max-w-xs">
              Daftarkan akun untuk mengakses portal orang tua dan informasi sekolah secara lengkap.
            </p>
            <div className="space-y-2">
              {['✓ Aman', '✓ Mudah', '✓ Terhubung dengan sekolah'].map((item) => (
                <div key={item} className="text-white/90 text-sm font-500" style={{ fontWeight: 500 }}>{item}</div>
              ))}
            </div>
          </div>
          <div className="text-white/40 text-xs">© 2026 Yayasan Al-Hikmah 2</div>
        </div>
      </div>

      {/* Right — form */}
      <div className="flex-1 lg:max-w-md flex items-center justify-center p-8">
        <div className="w-full max-w-sm">
          <div className="lg:hidden flex items-center gap-2.5 mb-8">
            <div className="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm" style={{ background: 'linear-gradient(135deg, #365CF5, #5B7CFA)' }}>AH</div>
            <span className="font-700 text-sm text-gray-900" style={{ fontWeight: 700 }}>Yayasan Al-Hikmah 2</span>
          </div>

          {step === 0 ? (
            <>
              <h1 className="text-2xl font-800 text-gray-900 mb-1" style={{ fontWeight: 800, letterSpacing: '-0.02em' }}>Buat Akun Baru</h1>
              <p className="text-sm mb-6" style={{ color: '#6B7280' }}>Isi data berikut untuk membuat akun portal orang tua.</p>

              <form action="/register" method="POST" className="space-y-4">
                <input type="hidden" name="_token" value={csrfToken} />
                <input type="hidden" name="role" value="wali_murid" />
                <div>
                  <label className="block text-xs font-600 text-gray-600 mb-1.5" style={{ fontWeight: 600 }}>Nama Lengkap</label>
                  <input type="text" name="name" placeholder="Nama Lengkap Anda" required value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-xl text-sm border outline-none bg-white"
                    style={{ borderColor: '#E5E7EB', fontFamily: 'inherit', color: '#111827' }} />
                </div>
                <div>
                  <label className="block text-xs font-600 text-gray-600 mb-1.5" style={{ fontWeight: 600 }}>Alamat Email</label>
                  <input type="email" name="email" placeholder="nama@email.com" required value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-xl text-sm border outline-none bg-white"
                    style={{ borderColor: '#E5E7EB', fontFamily: 'inherit', color: '#111827' }} />
                </div>
                <div>
                  <label className="block text-xs font-600 text-gray-600 mb-1.5" style={{ fontWeight: 600 }}>Kata Sandi</label>
                  <input type="password" name="password" placeholder="Minimal 8 karakter" required minLength={8} value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-xl text-sm border outline-none bg-white"
                    style={{ borderColor: '#E5E7EB', fontFamily: 'inherit', color: '#111827' }} />
                </div>
                <div>
                  <label className="block text-xs font-600 text-gray-600 mb-1.5" style={{ fontWeight: 600 }}>Konfirmasi Sandi</label>
                  <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required value={form.confirm} onChange={(e) => setForm({ ...form, confirm: e.target.value })}
                    className="w-full px-4 py-2.5 rounded-xl text-sm border outline-none bg-white"
                    style={{ borderColor: '#E5E7EB', fontFamily: 'inherit', color: '#111827' }} />
                </div>
                <button type="submit" className="w-full py-3 rounded-xl text-white text-sm font-600 transition-all hover:opacity-90 cursor-pointer"
                  style={{ background: 'linear-gradient(135deg, #365CF5, #5B7CFA)', fontWeight: 600, boxShadow: '0 6px 20px rgba(54,92,245,0.35)' }}>
                  Daftar
                </button>
              </form>

              <p className="text-center text-xs mt-5" style={{ color: '#6B7280' }}>
                Sudah punya akun?{' '}
                <button onClick={() => onNavigate('login')} className="font-600" style={{ color: '#365CF5', fontWeight: 600 }}>Masuk di sini</button>
              </p>
            </>
          ) : (
            /* Success state */
            <div className="text-center">
              <div className="w-16 h-16 rounded-full flex items-center justify-center text-3xl mx-auto mb-4" style={{ background: '#DCFCE7' }}>✓</div>
              <h2 className="text-2xl font-800 text-gray-900 mb-2" style={{ fontWeight: 800 }}>Akun Berhasil Dibuat!</h2>
              <p className="text-sm mb-6" style={{ color: '#6B7280' }}>Selamat datang! Akun Anda telah berhasil didaftarkan. Silakan masuk untuk melanjutkan.</p>
              <button onClick={() => onNavigate('login')} className="w-full py-3 rounded-xl text-white text-sm font-600 transition-all hover:opacity-90"
                style={{ background: 'linear-gradient(135deg, #365CF5, #5B7CFA)', fontWeight: 600 }}>
                Masuk Sekarang
              </button>
            </div>
          )}
        </div>
      </div>
    </div>
  )
}
