import { useState, useEffect, useRef } from 'react'
import gsap from 'gsap'
import { useReducedMotion } from '../hooks/useReducedMotion'

type Page = 'home' | 'about' | 'registration' | 'contact' | 'login' | 'register'
interface Props { onNavigate: (p: Page) => void }

export default function LoginPage({ onNavigate }: Props) {
  const [form, setForm] = useState({ email: '', password: '', remember: false })
  const [showPass, setShowPass] = useState(false)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const reduced = useReducedMotion()
  const leftRef = useRef<HTMLDivElement>(null)

  useEffect(() => {
    if (reduced || !leftRef.current) return
    const decors = leftRef.current.querySelectorAll('[data-hero-decor]')
    const ctx = gsap.context(() => {
      decors.forEach((el, i) => {
        gsap.fromTo(el, { opacity: 0, scale: 0, y: 10 }, { opacity: 1, scale: 1, y: 0, duration: 0.55, delay: 0.4 + i * 0.12, ease: 'back.out(2)' })
        gsap.to(el, { y: i % 2 === 0 ? -12 : 10, x: i % 3 === 0 ? 8 : -6, duration: 3 + i * 0.45, repeat: -1, yoyo: true, ease: 'sine.inOut', delay: i * 0.3 })
      })
    }, leftRef)
    return () => ctx.revert()
  }, [reduced])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError('')

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

    try {
      const formData = new FormData()
      formData.append('_token', csrfToken)
      formData.append('email', form.email)
      formData.append('password', form.password)
      if (form.remember) formData.append('remember', '1')

      const res = await fetch('/login', {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json, text/html, */*'
        },
        body: formData
      })

      if (res.redirected) {
        window.location.href = res.url
        return
      }

      if (res.ok) {
        window.location.href = '/dashboard'
        return
      }

      // If server returned error or back with session error
      const text = await res.text()
      if (text.includes('Email atau password salah') || res.status === 422 || res.status === 401) {
        setError('Email atau password salah. Silakan periksa kembali.')
      } else {
        // Fallback: programmatic form submit if backend redirects
        const formEl = document.createElement('form')
        formEl.method = 'POST'
        formEl.action = '/login'
        
        const csrfInput = document.createElement('input')
        csrfInput.type = 'hidden'
        csrfInput.name = '_token'
        csrfInput.value = csrfToken
        formEl.appendChild(csrfInput)

        const emailInput = document.createElement('input')
        emailInput.type = 'hidden'
        emailInput.name = 'email'
        emailInput.value = form.email
        formEl.appendChild(emailInput)

        const passInput = document.createElement('input')
        passInput.type = 'hidden'
        passInput.name = 'password'
        passInput.value = form.password
        formEl.appendChild(passInput)

        document.body.appendChild(formEl)
        formEl.submit()
        return
      }
    } catch (err) {
      setError('Terjadi kesalahan koneksi. Silakan coba lagi.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen flex" style={{ paddingTop: 64, background: '#F7F9FC' }}>
      {/* Left panel */}
      <div ref={leftRef} className="hidden lg:flex flex-1 flex-col justify-center p-12 relative overflow-hidden"
        style={{ background: 'linear-gradient(135deg, #1B3A8C 0%, #2D55E8 60%, #4F8BFF 100%)' }}>
        <div className="absolute inset-0 pointer-events-none" style={{ backgroundImage: 'radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px)', backgroundSize: '28px 28px' }} />
        {/* Floating decoratives */}
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '12%', right: '12%', opacity: 0 }}>
          <svg width="32" height="32" viewBox="0 0 24 24" fill="#FCD34D"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '35%', left: '8%', opacity: 0 }}>
          <svg width="18" height="18" viewBox="0 0 20 20"><polygon points="10,1 19,10 10,19 1,10" fill="#34D399" opacity="0.8" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ bottom: '18%', right: '10%', opacity: 0 }}>
          <svg width="22" height="22" viewBox="0 0 24 24" fill="#A78BFA"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" /></svg>
        </div>
        <div data-hero-decor className="absolute pointer-events-none" style={{ top: '60%', right: '20%', opacity: 0 }}>
          <svg width="30" height="30" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13" fill="none" stroke="rgba(255,255,255,0.25)" strokeWidth="2" /><circle cx="16" cy="16" r="7" fill="rgba(255,255,255,0.08)" /></svg>
        </div>
        {/* Key/lock illustration */}
        <div data-hero-decor className="absolute pointer-events-none" style={{ bottom: '10%', left: '50%', transform: 'translateX(-50%)', opacity: 0 }}>
          <svg width="130" height="80" viewBox="0 0 130 80" fill="none">
            <circle cx="40" cy="35" r="26" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.25)" strokeWidth="2.5" />
            <circle cx="40" cy="35" r="14" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.2)" strokeWidth="2" />
            <rect x="62" y="32" width="50" height="8" rx="4" fill="rgba(255,255,255,0.2)" />
            <rect x="100" y="40" width="10" height="8" rx="3" fill="rgba(255,255,255,0.2)" />
            <rect x="112" y="40" width="8" height="6" rx="2" fill="rgba(255,255,255,0.15)" />
            <circle cx="40" cy="35" r="5" fill="#FCD34D" opacity="0.6" />
          </svg>
        </div>
        <div className="relative z-10 max-w-sm">
          <div className="text-4xl mb-6">👋</div>
          <h2 className="text-3xl font-800 text-white mb-3" style={{ fontWeight: 800, letterSpacing: '-0.025em' }}>Selamat Datang Kembali!</h2>
          <p className="text-white/75 text-sm mb-8 leading-relaxed">Masuk ke portal orang tua untuk memantau perkembangan putra-putri Anda.</p>
          <div className="space-y-3">
            {[
              { icon: '📊', label: 'Informasi Akademik', desc: 'Pantau nilai dan perkembangan belajar' },
              { icon: '🔔', label: 'Pemberitahuan Sekolah', desc: 'Terima notifikasi kegiatan sekolah' },
              { icon: '📢', label: 'Pengumuman Guru', desc: 'Baca pesan langsung dari guru kelas' },
              { icon: '🔗', label: 'Terhubung', desc: 'Jalin komunikasi dengan pihak sekolah' },
            ].map((item) => (
              <div key={item.label} className="flex items-center gap-3 p-3 rounded-xl" style={{ background: 'rgba(255,255,255,0.1)', backdropFilter: 'blur(8px)' }}>
                <span className="text-xl flex-shrink-0">{item.icon}</span>
                <div>
                  <div className="text-white text-sm font-600" style={{ fontWeight: 600 }}>{item.label}</div>
                  <div className="text-white/60 text-xs">{item.desc}</div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Right — form */}
      <div className="flex-1 lg:max-w-md flex items-center justify-center p-8">
        <div className="w-full max-w-sm">
          <div className="lg:hidden flex items-center gap-2.5 mb-8">
            <div className="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-sm" style={{ background: 'linear-gradient(135deg, #365CF5, #5B7CFA)' }}>AH</div>
            <span className="font-700 text-sm text-gray-900" style={{ fontWeight: 700 }}>Yayasan Al-Hikmah 2</span>
          </div>

          <h1 className="text-2xl font-800 text-gray-900 mb-1" style={{ fontWeight: 800, letterSpacing: '-0.02em' }}>Login Portal</h1>
          <p className="text-sm mb-6" style={{ color: '#6B7280' }}>Masuk ke akun portal orang tua Anda.</p>

          {error && (
            <div className="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-500">
              ⚠️ {error}
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-xs font-600 text-gray-600 mb-1.5" style={{ fontWeight: 600 }}>Email</label>
              <input type="email" placeholder="nama@email.com" required value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })}
                className="w-full px-4 py-2.5 rounded-xl text-sm border outline-none bg-white"
                style={{ borderColor: '#E5E7EB', fontFamily: 'inherit', color: '#111827' }} />
            </div>
            <div>
              <label className="block text-xs font-600 text-gray-600 mb-1.5" style={{ fontWeight: 600 }}>Password</label>
              <div className="relative">
                <input type={showPass ? 'text' : 'password'} placeholder="Kata sandi Anda" required value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })}
                  className="w-full px-4 py-2.5 rounded-xl text-sm border outline-none bg-white pr-10"
                  style={{ borderColor: '#E5E7EB', fontFamily: 'inherit', color: '#111827' }} />
                <button type="button" onClick={() => setShowPass(!showPass)} className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    {showPass ? <><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" /><line x1="1" y1="1" x2="23" y2="23" /></> : <><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" /></>}
                  </svg>
                </button>
              </div>
            </div>
            <div className="flex items-center justify-between">
              <label className="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" checked={form.remember} onChange={(e) => setForm({ ...form, remember: e.target.checked })} style={{ accentColor: '#365CF5' }} />
                <span className="text-xs text-gray-600">Ingat Saya</span>
              </label>
              <button type="button" className="text-xs font-600" style={{ color: '#365CF5', fontWeight: 600 }}>Lupa Password</button>
            </div>
            <button type="submit" disabled={loading} className="w-full py-3 rounded-xl text-white text-sm font-600 transition-all hover:opacity-90 disabled:opacity-60 flex items-center justify-center gap-2 cursor-pointer"
              style={{ background: 'linear-gradient(135deg, #365CF5, #5B7CFA)', fontWeight: 600, boxShadow: '0 6px 20px rgba(54,92,245,0.35)' }}>
              {loading ? 'Memproses Login...' : 'Login'}
            </button>
          </form>

          <p className="text-center text-xs mt-5" style={{ color: '#6B7280' }}>
            Belum punya akun?{' '}
            <button onClick={() => onNavigate('register')} className="font-600" style={{ color: '#365CF5', fontWeight: 600 }}>Daftar sekarang</button>
          </p>
        </div>
      </div>
    </div>
  )
}
