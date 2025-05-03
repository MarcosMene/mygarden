document.addEventListener('DOMContentLoaded', function () {
  const carousel = document.getElementById('carousel');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');

  const originalSlides = Array.from(
    document.querySelectorAll('#carousel > div')
  );
  if (originalSlides.length <= 1) return;

  const firstSlideClone = originalSlides[0].cloneNode(true);
  const lastSlideClone =
    originalSlides[originalSlides.length - 1].cloneNode(true);

  carousel.appendChild(firstSlideClone);
  carousel.insertBefore(lastSlideClone, originalSlides[0]);

  let slides = Array.from(document.querySelectorAll('#carousel > div'));
  let currentIndex = 1;
  let slideWidth = slides[0].offsetWidth;
  let isAnimating = false;
  let autoSlideInterval;
  const autoSlideDelay = 5000;
  const transitionDuration = 700;

  carousel.style.transition = 'none';
  carousel.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
  carousel.offsetHeight;
  carousel.style.transition = `transform ${transitionDuration}ms ease-in-out`;

  function updateCarousel() {
    carousel.style.transition = isAnimating
      ? `transform ${transitionDuration}ms ease-in-out`
      : 'none';
    carousel.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
  }

  function disableButtons() {
    prevBtn.disabled = true;
    nextBtn.disabled = true;
    prevBtn.classList.add('opacity-50', 'pointer-events-none');
    nextBtn.classList.add('opacity-50', 'pointer-events-none');
  }

  function enableButtons() {
    prevBtn.disabled = false;
    nextBtn.disabled = false;
    prevBtn.classList.remove('opacity-50', 'pointer-events-none');
    nextBtn.classList.remove('opacity-50', 'pointer-events-none');
  }

  function nextSlide() {
    if (isAnimating) return;
    isAnimating = true;
    disableButtons();
    currentIndex++;
    updateCarousel();

    setTimeout(() => {
      if (currentIndex === slides.length - 1) {
        currentIndex = 1;
        carousel.style.transition = 'none';
        carousel.style.transform = `translateX(-${
          currentIndex * slideWidth
        }px)`;
        carousel.offsetHeight;
        carousel.style.transition = `transform ${transitionDuration}ms ease-in-out`;
      }
      isAnimating = false;
      enableButtons();
    }, transitionDuration);
  }

  function prevSlide() {
    if (isAnimating) return;
    isAnimating = true;
    disableButtons();
    currentIndex--;
    updateCarousel();

    setTimeout(() => {
      if (currentIndex === 0) {
        currentIndex = slides.length - 2;
        carousel.style.transition = 'none';
        carousel.style.transform = `translateX(-${
          currentIndex * slideWidth
        }px)`;
        carousel.offsetHeight;
        carousel.style.transition = `transform ${transitionDuration}ms ease-in-out`;
      }
      isAnimating = false;
      enableButtons();
    }, transitionDuration);
  }

  function startAutoSlide() {
    autoSlideInterval = setInterval(nextSlide, autoSlideDelay);
  }

  function stopAutoSlide() {
    clearInterval(autoSlideInterval);
  }

  prevBtn.addEventListener('click', () => {
    stopAutoSlide();
    prevSlide();
    startAutoSlide();
  });

  nextBtn.addEventListener('click', () => {
    stopAutoSlide();
    nextSlide();
    startAutoSlide();
  });

  carousel.addEventListener('mouseover', stopAutoSlide);
  carousel.addEventListener('mouseout', startAutoSlide);

  window.addEventListener('resize', () => {
    slideWidth = slides[0].offsetWidth;
    updateCarousel();
  });

  startAutoSlide();
});
