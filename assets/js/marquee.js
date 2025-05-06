    document.addEventListener('DOMContentLoaded', function() {
    const marquees = document.querySelectorAll('.marquee-orange');
    
    marquees.forEach(marquee => {
      const inner = marquee.querySelector('.marquee__inner');
      const content = marquee.querySelector('.marquee__content');
      
      
      // Get duration from attribute but use much faster speeds
      // Convert from data attribute or use default (faster now)
      let duration = parseInt(marquee.getAttribute('data-marquee-duration')) || 10;
      
      // Make sure content fills the width
      const containerWidth = marquee.offsetWidth;
      const contentWidth = content.offsetWidth;
      
      // If content is shorter than container, repeat text to fill width
      if (contentWidth < containerWidth) {
        const repetitions = Math.ceil(containerWidth / contentWidth) + 1;
        const originalText = content.textContent;
        let repeatedText = '';
        
        for (let i = 0; i < repetitions; i++) {
          repeatedText += originalText + ' • ';
        }
        
        content.textContent = repeatedText;
      }
      
      // Clone the content for continuous scrolling
      const clone = content.cloneNode(true);
      inner.appendChild(clone);
      
      const direction = marquee.getAttribute('data-marquee-direction') || 'ltr';
      
      // Set initial position for RTL direction
      if (direction === 'rtl') {
        inner.style.transform = 'translateX(-50%)';
      }
      
      // Apply the animation based on direction
      const animationName = direction === 'rtl' ? 'marquee-rtl' : 'marquee-ltr';
      
      // Apply animation with faster speed
      inner.style.animation = `${animationName} ${duration}s linear infinite`;
      
      // Ensure the animation works properly on window resize
      window.addEventListener('resize', function() {
        inner.style.animation = 'none';
        
        if (direction === 'rtl') {
          inner.style.transform = 'translateX(-50%)';
        } else {
          inner.style.transform = '';
        }
        
        setTimeout(() => {
          inner.style.animation = `${animationName} ${duration}s linear infinite`;
        }, 10);
      });
    });
  });