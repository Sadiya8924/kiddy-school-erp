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
  const [currentPage, setCurrentPage] = useState<Page>('home')

  const navigate = (page: Page) => {
    setCurrentPage(page)
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  useEffect(() => {
    const titles: Record<Page, string> = {
      home: 'Beranda — Yayasan Al-Hikmah 2',
      about: 'Tentang Kami — Yayasan Al-Hikmah 2',
      registration: 'Pendaftaran — Yayasan Al-Hikmah 2',
      contact: 'Hubungi Kami — Yayasan Al-Hikmah 2',
      login: 'Login Portal — Yayasan Al-Hikmah 2',
      register: 'Daftar Akun — Yayasan Al-Hikmah 2',
    }
    document.title = titles[currentPage]
  }, [currentPage])

  const noFooter = currentPage === 'login' || currentPage === 'register'

  return (
    <div style={{ background: '#F7F9FC', minHeight: '100vh', fontFamily: "'Plus Jakarta Sans', sans-serif" }}>
      <Navbar currentPage={currentPage} onNavigate={navigate} />
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
