import { useEffect, useRef, type ReactNode } from 'react'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { useReducedMotion } from '../hooks/useReducedMotion'

gsap.registerPlugin(ScrollTrigger)

interface Props {
  children: ReactNode
  className?: string
  style?: React.CSSProperties
  delay?: number
  y?: number
}

export default function AnimatedSection({ children, className, style, delay = 0, y = 28 }: Props) {
  const ref = useRef<HTMLDivElement>(null)
  const reduced = useReducedMotion()

  useEffect(() => {
    if (!ref.current || reduced) return
    const ctx = gsap.context(() => {
      gsap.fromTo(
        ref.current,
        { opacity: 0, y },
        {
          opacity: 1,
          y: 0,
          duration: 0.75,
          delay,
          ease: 'power3.out',
          scrollTrigger: {
            trigger: ref.current,
            start: 'top 82%',
            once: true,
          },
        }
      )
    }, ref)
    return () => ctx.revert()
  }, [reduced, delay, y])

  return (
    <div ref={ref} className={className} style={{ opacity: reduced ? 1 : 0, ...style }}>
      {children}
    </div>
  )
}
