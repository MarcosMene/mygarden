document.addEventListener('DOMContentLoaded', () => {
  const isAdmin = window.location.href.includes('admin');
  if (isAdmin) return; // if url has admin, it doesnt work

  const navbar = document.getElementById('navbar');
  let lastScrollY = window.scrollY;
  let scrollTimeout;

  window.addEventListener('scroll', () => {
    clearTimeout(scrollTimeout); //reset

    scrollTimeout = setTimeout(() => {
      if (window.scrollY > lastScrollY) {
        navbar.classList.add('-translate-y-full'); //hide
      } else {
        navbar.classList.remove('-translate-y-full'); //show
      }

      lastScrollY = window.scrollY;
    }, 20); // delay
  });
});
