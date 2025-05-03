document.addEventListener('scroll', () => {
  const button = document.getElementById('goToTop');
  const scrollY = window.scrollY;

  // if scroll greater than 100px, the bottom will be show slowly
  if (scrollY > 300) {
    // Opacity increases from 0 to 1 based on how far you scroll
    button.style.opacity = Math.min(1, scrollY / 200); // Divide by 200 to control the rate of increase
  } else {
    // If the scroll is less than 100px, the button becomes invisible.
    button.style.opacity = 0;
  }
});
