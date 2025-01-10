(function () {
  // EMAIL
  let Email = document.getElementById('forgot_password_email');
  let EmailError = document.getElementById('email_error');

  // CALL FUNCTIONS
  Email.addEventListener('input', () => {
    checkEmail();
  });

  // FUNCTION EMAIL
  function checkEmail() {
    let patternEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
    let email = Email.value;
    let validEmail = patternEmail.test(email);
    if (email.trim() === '') {
      EmailError.style.display = 'block';
      EmailError.innerText = 'Your email is required.';
      return false;
    } else if (email.length < 10 || email.length > 50) {
      EmailError.style.display = 'block';
      Email.classList.add('error-message');
      EmailError.innerText =
        'Your email must be at least 10 characters long and maximum 50 characters.';
      return false;
    } else if (!validEmail) {
      EmailError.style.display = 'block';
      EmailError.innerText =
        'Your email can only contain letters, @, hyphens and numbers';
      return false;
    } else {
      EmailError.innerText = '';
      EmailError.style.display = 'none';
      Email.classList.remove('error-message');
      Email.classList.add('validated-message');
      return true;
    }
  }
})();
