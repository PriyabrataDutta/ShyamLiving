const init = () => {
    const marquee = document.querySelectorAll('.marquee')
    
    if(!marquee) return
    
    marquee.forEach(item => {
    const marqueeInner = item.querySelector('.marquee__inner')
    const marqueeContent = marqueeInner.querySelector('.marquee__content')
    
    // Duration
    const duration = item.getAttribute('data-marquee-duration')
    
    // Element Clone
    const marqueeContentClone = marqueeContent.cloneNode(true)
    marqueeInner.append(marqueeContentClone)
    
    // Marquee animation
    const marqueeContentAll = marqueeInner.querySelectorAll('.marquee__content')
    marqueeContentAll.forEach(element => {
      gsap.to(element, {
          x: "-101%",
          repeat: -1,
          duration: duration,
          ease: 'linear'
      })
    })
    })
    }
    
    document.addEventListener('DOMContentLoaded', init)