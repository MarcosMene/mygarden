(function () {
  // EMAIL
  let Email = document.getElementById('username');
  let EmailError = document.getElementById('email_error');

  // PASSWORD
  let Password = document.getElementById('password');
  let PasswordError = document.getElementById('password_error');

  // CALL FUNCTIONS

  Email.addEventListener('input', () => {
    checkEmail();
  });
  Password.addEventListener('input', () => {
    checkPassword();
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

  // FUNCTION PASSWORD
  function checkPassword() {
    let patternPassword =
      /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,16}$/;
    let password = Password.value;
    let validPassword = patternPassword.test(password);
    if (password.trim() === '') {
      PasswordError.style.display = 'block';
      PasswordError.innerText = 'Your password is required.';
      return false;
    } else if (password.length < 8 || password.length > 16) {
      PasswordError.style.display = 'block';
      Password.classList.add('error-message');
      PasswordError.innerText =
        'Your password must be at least 8 characters long and maximum 16 characters.';
    } else if (!validPassword) {
      PasswordError.style.display = 'block';
      PasswordError.innerText =
        'Your password can only contain letters, symbols and numbers. An uppercase, a lowercase, a symbol and a number are required.';
    } else {
      PasswordError.innerText = '';
      PasswordError.style.display = 'none';
      Password.classList.remove('error-message');
      Password.classList.add('validated-message');
      return true;
    }
  }
})();
