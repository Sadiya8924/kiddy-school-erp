import { useEffect, useRef, useState } from 'react'
import gsap from 'gsap'
import { useReducedMotion } from '../hooks/useReducedMotion'

type Page = 'home' | 'about' | 'registration' | 'contact' | 'login' | 'register'

interface NavbarProps {
  currentPage: Page
  onNavigate: (page: Page) => void
}

const navLinks: { label: string; page: Page }[] = [
  { label: 'Beranda', page: 'home' },
  { label: 'Tentang Kami', page: 'about' },
  { label: 'Pendaftaran', page: 'registration' },
  { label: 'Hubungi Kami', page: 'contact' },
]

export default function Navbar({ currentPage, onNavigate }: NavbarProps) {
  const reduced = useReducedMotion()
  const navRef = useRef<HTMLElement>(null)
  const [scrolled, setScrolled] = useState(false)

  const settings = (typeof window !== 'undefined' && (window as any).APP_SETTINGS) || {}
  const authUser = (typeof window !== 'undefined' && (window as any).AUTH_USER) || null

  const siteTitle = settings.site_title || 'Yayasan Al-Hikmah 2'
  const siteLogo = settings.site_logo

  useEffect(() => {
    const handleScroll = () => {
      const isScrolled = window.scrollY > 20
      setScrolled(isScrolled)
      if (!reduced && navRef.current) {
        gsap.to(navRef.current, { height: isScrolled ? 56 : 64, duration: 0.35, ease: 'power2.out' })
      }
    }
    window.addEventListener('scroll', handleScroll, { passive: true })
    return () => window.removeEventListener('scroll', handleScroll)
  }, [reduced])

  useEffect(() => {
    if (!navRef.current || reduced) return
    gsap.fromTo(navRef.current, { y: -20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, ease: 'power3.out', delay: 0.1 })
  }, [reduced])

  return (
    <header
      ref={navRef}
      className="fixed top-0 left-0 right-0 z-50 flex items-center"
      style={{
        height: 64,
        background: scrolled ? 'rgba(255,255,255,0.95)' : 'rgba(255,255,255,0.9)',
        backdropFilter: 'blur(16px)',
        WebkitBackdropFilter: 'blur(16px)',
        borderBottom: '1px solid rgba(229,231,235,0.8)',
        boxShadow: scrolled ? '0 2px 20px rgba(0,0,0,0.07)' : 'none',
        transition: 'background 0.3s, box-shadow 0.3s',
        opacity: reduced ? 1 : 0,
      }}
    >
      <div className="max-w-[1320px] mx-auto px-6 sm:px-10 w-full flex items-center justify-between">
        {/* Logo */}
        <button onClick={() => onNavigate('home')} className="flex items-center gap-2.5">
          {siteLogo ? (
            <img src={siteLogo} alt={siteTitle} style={{ height: 36, width: 'auto', objectFit: 'contain', borderRadius: 6 }} />
          ) : (
            <div className="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-sm"
              style={{ background: 'linear-gradient(135deg, #365CF5, #5B7CFA)' }}>AH</div>
          )}
          <span className="font-700 text-sm text-gray-900 hidden sm:block" style={{ fontWeight: 700 }}>{siteTitle}</span>
        </button>

        {/* Nav */}
        <nav className="hidden md:flex items-center gap-0.5">
          {navLinks.map(({ label, page }) => (
            <button key={page} onClick={() => onNavigate(page)}
              className="px-3.5 py-2 rounded-xl text-sm transition-all duration-150"
              style={{ fontWeight: currentPage === page ? 600 : 500, color: currentPage === page ? '#365CF5' : '#374151', background: currentPage === page ? '#EEF1FE' : 'transparent' }}>
              {label}
            </button>
          ))}
        </nav>

        {/* Right buttons */}
        <div className="flex items-center gap-2">
          {(authUser || (typeof window !== 'undefined' && (window as any).isAuth)) ? (
            <a href="/dashboard"
              className="px-4 py-2 rounded-xl text-sm text-white font-600 transition-all hover:opacity-90 active:scale-95 flex items-center gap-1.5"
              style={{ background: 'linear-gradient(135deg, #10B981, #059669)', fontWeight: 600, boxShadow: '0 3px 12px rgba(16,185,129,0.3)', textDecoration: 'none' }}>
              <span>🚀</span> Ke Dashboard {authUser ? `(${authUser.name})` : ''}
            </a>
          ) : (
            <>
              <button onClick={() => onNavigate('login')}
                className="hidden sm:flex px-3.5 py-2 rounded-xl text-sm border transition-colors hover:bg-gray-50"
                style={{ fontWeight: 500, color: '#374151', borderColor: '#E5E7EB' }}>
                Login Portal
              </button>
              <button onClick={() => onNavigate('registration')}
                className="px-4 py-2 rounded-xl text-sm text-white font-600 transition-all hover:opacity-90 active:scale-95"
                style={{ background: 'linear-gradient(135deg, #365CF5, #5B7CFA)', fontWeight: 600, boxShadow: '0 3px 12px rgba(54,92,245,0.3)' }}>
                Daftar Sekarang
              </button>
            </>
          )}
        </div>
      </div>
    </header>
  )
}
