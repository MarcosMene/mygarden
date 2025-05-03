document.addEventListener('DOMContentLoaded', function () {
  // MOBILE MENU TOGGLE
  let mobileMenu = document.getElementById('mobileMenu');
  let mobileMenuButton = document.getElementById('mobileMenuButton');
  let closeMobileMenu = document.getElementById('closeMobileMenu');

  mobileMenuButton.addEventListener('click', function () {
    mobileMenu.classList.remove('hidden');
    mobileMenu.classList.remove('animate-slide-out');
    mobileMenu.classList.add('animate-slide-in');
  });

  closeMobileMenu.addEventListener('click', function () {
    mobileMenu.classList.remove('animate-slide-in');
    mobileMenu.classList.add('animate-slide-out');

    // DELAY HIDING THE MENU TO ALLOW THE ANIMATION TO COMPLETE
    setTimeout(() => {
      mobileMenu.classList.add('hidden');
    }, 300); // MATCH THIS DURATION TO YOUR ANIMATION'S DURATION
  });

  // CART DROPDOWN TOGGLE
  let cartIcon = document.getElementById('cart-button');
  let cartDropdown = document.getElementById('cart-dropdown');

  // TOGGLE CART DROPDOWN
  cartIcon.onclick = function (event) {
    event.stopPropagation();
    cartDropdown.classList.toggle('hidden');
  };

  // HIDE THE DIV WHEN CLICKING ANYWHERE ELSE ON THE DOCUMENT
  document.addEventListener('click', function (event) {
    // CHECK IF THE CLICK TARGET IS NOT THE BUTTON OR THE DIV
    if (event.target !== cartDropdown && event.target) {
      cartDropdown.classList.add('hidden');
    }
  });
});
