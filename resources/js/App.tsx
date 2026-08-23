import { useState, useEffect } from 'react'
import Navbar from './components/Navbar'
import Footer from './components/Footer'
import HomePage from './pages/HomePage'
import AboutPage from './pages/AboutPage'
import RegistrationPage from './pages/RegistrationPage'
import ContactPage from './pages/ContactPage'
import LoginPage from './pages/LoginPage'
import RegisterPage from './pages/RegisterPage'

type Page = 'home' | 'about' | 'registration' | 'contact' | 'login' | 'register'

export default function App() {
  const getInitialPage = (): Page => {
    if (typeof window === 'undefined') return 'home'
    const path = window.location.pathname.toLowerCase()
    if (path.includes('/register') || path.includes('/registration')) {
      window.location.href = '/daftar-yayasan'
      return 'home'
    }
    if (path.includes('/login')) return 'login'
    if (path.includes('/about')) return 'about'
    if (path.includes('/contact')) return 'contact'
    return 'home'
  }

  const [currentPage, setCurrentPage] = useState<Page>(getInitialPage)

  const navigate = (page: Page) => {
    if (page === 'register' || page === 'registration') {
      window.location.href = '/daftar-yayasan'
      return
    }
    if (page === 'home') {
      window.location.href = '/'
      return
    }
    setCurrentPage(page)
    const pagePaths: Record<Page, string> = {
      home: '/',
      about: '/about',
      registration: '/daftar-yayasan',
      contact: '/contact',
      login: '/login',
      register: '/daftar-yayasan'
    }
    if (typeof window !== 'undefined') {
      window.history.pushState(null, '', pagePaths[page] || '/')
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }
  }

  useEffect(() => {
    if (typeof window !== 'undefined') {
      const isLogged = (window as any).isAuth || !!(window as any).AUTH_USER
      if (isLogged && (currentPage === 'login' || currentPage === 'register')) {
        window.location.href = '/dashboard'
      }
    }
  }, [currentPage])

  useEffect(() => {
    const handlePopState = () => {
      const path = window.location.pathname.toLowerCase()
      if (path.includes('/login')) setCurrentPage('login')
      else if (path.includes('/register')) setCurrentPage('register')
      else if (path.includes('/about')) setCurrentPage('about')
      else if (path.includes('/registration')) setCurrentPage('registration')
      else if (path.includes('/contact')) setCurrentPage('contact')
      else setCurrentPage('home')
    }

    window.addEventListener('popstate', handlePopState)
    return () => window.removeEventListener('popstate', handlePopState)
  }, [])

  useEffect(() => {
    const titles: Record<Page, string> = {
      home: 'Kiddy School ERP — Platform Manajemen Yayasan TK & PAUD',
      about: 'Tentang Kami — Kiddy School ERP',
      registration: 'Pendaftaran Yayasan — Kiddy School ERP',
      contact: 'Hubungi Kami — Kiddy School ERP',
      login: 'Login Platform — Kiddy School ERP',
      register: 'Daftar Yayasan Baru — Kiddy School ERP',
    }
    document.title = titles[currentPage]
  }, [currentPage])

  const noFooter = currentPage === 'login' || currentPage === 'register'
  const noNavbar = currentPage === 'login' || currentPage === 'register'

  return (
    <div style={{ background: '#F7F9FC', minHeight: '100vh', fontFamily: "'Plus Jakarta Sans', sans-serif" }}>
      {!noNavbar && <Navbar currentPage={currentPage} onNavigate={navigate} />}
      <main>
        {currentPage === 'home' && <HomePage onNavigate={navigate} />}
        {currentPage === 'about' && <AboutPage onNavigate={navigate} />}
        {currentPage === 'registration' && <RegistrationPage onNavigate={navigate} />}
        {currentPage === 'contact' && <ContactPage onNavigate={navigate} />}
        {currentPage === 'login' && <LoginPage onNavigate={navigate} />}
        {currentPage === 'register' && <RegisterPage onNavigate={navigate} />}
      </main>
      {!noFooter && <Footer onNavigate={navigate} />}
    </div>
  )
}
