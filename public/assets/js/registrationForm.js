(function () {
  // FIRST NAME
  let FirstName = document.getElementById('registration_form_firstname');
  let FirstNameError = document.getElementById('firstname_error');

  // LAST NAME
  let LastName = document.getElementById('registration_form_lastname');
  let LastNameError = document.getElementById('lastname_error');

  // EMAIL
  let Email = document.getElementById('registration_form_email');
  let EmailError = document.getElementById('email_error');

  // PASSWORD
  let Password = document.getElementById('registration_form_password_first');
  let PasswordError = document.getElementById('password_error');

  // CONFIRM PASSWORD
  let ConfirmPsw = document.getElementById('registration_form_password_second');
  let ConfirmPswError = document.getElementById('password_error');

  // CALL FUNCTIONS
  FirstName.addEventListener('input', () => {
    checkFirstName();
  });
  LastName.addEventListener('input', () => {
    checkLastName();
  });
  Email.addEventListener('input', () => {
    checkEmail();
  });
  Password.addEventListener('input', () => {
    checkPassword();
  });
  ConfirmPsw.addEventListener('input', () => {
    checkConfirmPsw();
  });

  // FUNCTION FIRST NAME
  function checkFirstName() {
    let patternFirstName = /[a-zA-ZÀ-ÿ\s\-]{3,25}/;
    let firstname = FirstName.value;
    let validFirstName = patternFirstName.test(firstname);
    if (firstname.trim() === '') {
      FirstNameError.style.display = 'block';
      FirstName.classList.add('error-message');
      FirstNameError.innerText = 'Your name is required.';
      return false;
    } else if (firstname.length < 3 || firstname.length > 25) {
      FirstNameError.style.display = 'block';
      FirstName.classList.add('error-message');
      FirstNameError.innerText =
        'Your first name must be at least 3 characters long';
      return false;
    } else if (!validFirstName) {
      FirstNameError.style.display = 'block';
      FirstNameError.innerText =
        'Your first name can only contain letters, spaces, or hyphens';
      return false;
    } else {
      FirstNameError.innerText = '';
      FirstNameError.style.display = 'none';
      FirstName.classList.remove('error-message');
      FirstName.classList.add('validated-message');
      return true;
    }
  }

  // FUNCTION LAST NAME
  function checkLastName() {
    let patternLastName = /[a-zA-ZÀ-ÿ]{5,25}/;
    let lastname = LastName.value;
    let validLastName = patternLastName.test(lastname);
    if (lastname.trim() === '') {
      LastNameError.style.display = 'block';
      LastNameError.innerText = 'Your last name is required.';
      return false;
    } else if (lastname.length < 5 || lastname.length > 25) {
      LastNameError.style.display = 'block';
      LastName.classList.add('error-message');
      LastNameError.innerText =
        'Your last name must be at least 5 characters long and maximum 25 characters.';
      return false;
    } else if (!validLastName) {
      LastNameError.style.display = 'block';
      LastNameError.innerText =
        'Your last name can only contain letters, spaces, or hyphens';
      return false;
    } else {
      LastNameError.innerText = '';
      LastNameError.style.display = 'none';
      LastName.classList.remove('error-message');
      LastName.classList.add('validated-message');
      return true;
    }
  }

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
      /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,16}$/;
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

  // FUNCTION CONFIRM PASSWORD
  function checkConfirmPsw() {
    let patternConfirmPsw =
      /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{6,16}$/;
    let password = Password.value;
    let confirmPsw = ConfirmPsw.value;
    let validConfirmPsw = patternConfirmPsw.test(confirmPsw);
    if (confirmPsw.trim() === '') {
      ConfirmPswError.style.display = 'block';
      ConfirmPswError.innerText = 'Your confirm password is required.';
      return false;
    } else if (password !== confirmPsw) {
      ConfirmPswError.style.display = 'block';
      ConfirmPsw.classList.add('error-message');
      ConfirmPswError.innerText =
        'Your password must match with confirm password';
      return false;
    } else {
      ConfirmPswError.innerText = '';
      ConfirmPswError.style.display = 'none';
      ConfirmPsw.classList.remove('error-message');
      ConfirmPsw.classList.add('validated-message');
      return true;
    }
  }
})();
