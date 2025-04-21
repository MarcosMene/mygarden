document.addEventListener('DOMContentLoaded', function () {
  // NAME COLOR
  const colorName = document.getElementById('color_color');
  const colorNameError = document.getElementById('colorName_error');
  const errorMessages = document.getElementById('error_messages');

  // SUBMIT BUTTON
  const submitButton = document.getElementById('color_submit');

  colorName.addEventListener('input', () => {
    checkcolorName();
  });

  submitButton.addEventListener('click', (e) => {
    if (!checkcolorName()) {
      e.preventDefault();
      errorMessages.classList.remove('hidden');
      errorMessages.innerText = 'Please fill the color field';
      colorNameError.style.display = 'block';
      colorName.classList.add('error-message');
      colorNameError.innerText = 'The color is required.';
    } else {
      errorMessages.classList.add('hidden');
    }
  });

  // CHECK COLOR NAME
  function checkcolorName() {
    colorName.classList.remove('is-invalid');
    colorName.classList.remove('error-message');

    // VALIDATION COLOR
    const patterncolorName = /^[a-zA-ZÀ-ÿ0-9_\-\s]{2,40}$/;
    const colorname = colorName.value;
    const validcolorName = patterncolorName.test(colorname);
    if (colorname.trim() === '') {
      colorNameError.style.display = 'block';
      colorName.classList.add('error-message');
      colorNameError.innerText = 'The name of the color is required.';
      return false;
    }
    if (colorname.length < 3 || colorname.length > 40) {
      colorNameError.style.display = 'block';
      colorName.classList.add('error-message');
      colorNameError.innerText =
        'The name of the color must be at least 3 characters, and maximum 40 characters.';
      return false;
    }
    if (!validcolorName) {
      colorNameError.style.display = 'block';
      colorName.classList.add('error-message');
      colorNameError.innerText =
        'The name of the color can only contain constters, spaces, or hyphens';
      return false;
    } else {
      colorNameError.innerText = '';
      colorNameError.style.display = 'none';
      colorName.classList.remove('error-message');
      colorName.classList.add('validated-message');
      return true;
    }
  }
});
