(function () {
  //ERROR MESSAGES OF FORM
  let errorMessages = document.getElementById('error_messages');

  // NEW PASSWORD
  let newPassword = document.getElementById(
    'reset_password_form_new_password_first'
  );
  let newPasswordError = document.getElementById('newPassword_error');

  // CONFIRM PASSWORD
  let confirmPassword = document.getElementById(
    'reset_password_form_new_password_second'
  );
  let confirmPasswordError = document.getElementById('confirmPassword_error');

  // SUBMIT BUTTON
  let submitButton = document.getElementById('reset_password_form_submit');

  newPassword.addEventListener('input', () => {
    checkNewPassword();
  });
  confirmPassword.addEventListener('input', () => {
    checkConfirmPassword();
  });

  submitButton.addEventListener('click', (event) => {
    if (!checkNewPassword() && !checkConfirmPassword()) {
      errorMessages.classList.remove('hidden');
      errorMessages.innerText = 'Please fill in all the fields';
    } else {
      errorMessages.classList.add('hidden');
    }

    if (!checkNewPassword()) {
      event.preventDefault();
      newPasswordError.style.display = 'block';
      newPassword.classList.add('error-message');
      newPasswordError.innerText = 'New password is required.';
    }

    if (!checkConfirmPassword()) {
      event.preventDefault();
      confirmPasswordError.style.display = 'block';
      confirmPassword.classList.add('error-message');
      confirmPasswordError.innerText =
        "Confirm password doesn't match. Please try again.";
    }
  });

  // CHECK NEW PASSWORD
  function checkNewPassword() {
    let patternNewPassword =
      /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,16}$/;
    let newpassword = newPassword.value;
    let validNewPassword = patternNewPassword.test(newpassword);
    if (newpassword.trim() === '') {
      newPasswordError.style.display = 'block';
      newPassword.classList.add('error-message');
      newPasswordError.innerText = 'Your new password is required.';
      return false;
    } else if (newpassword.length < 8 || newpassword.length > 16) {
      newPasswordError.style.display = 'block';
      newPassword.classList.add('error-message');
      newPasswordError.innerText =
        'Current password must be at least 8 characters long and 16 characters maximum.';
      return false;
    } else if (!validNewPassword) {
      newPasswordError.style.display = 'block';
      newPassword.classList.add('error-message');
      newPasswordError.innerText =
        'Your new password can only contain letters, symbols and numbers. An uppercase, a lowercase and a number.';
    } else {
      newPasswordError.innerText = '';
      newPasswordError.style.display = 'none';
      newPassword.classList.remove('error-message');
      newPassword.classList.add('validated-message');
      return true;
    }
  }

  // CHECK CONFIRM PASSWORD
  function checkConfirmPassword() {
    let patternConfirmPassword =
      /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,16}$/;
    let confirmpassword = confirmPassword.value;
    let newpassword = newPassword.value;
    let validConfirmPassword = patternConfirmPassword.test(confirmpassword);
    if (confirmpassword.trim() === '') {
      confirmPasswordError.style.display = 'block';
      confirmPassword.classList.add('error-message');
      confirmPasswordError.innerText =
        'Passwords do not match. Please try again.';
      return false;
    } else if (confirmpassword.length < 8 || confirmpassword.length > 16) {
      confirmPasswordError.style.display = 'block';
      confirmPassword.classList.add('error-message');
      confirmPasswordError.innerText =
        'Passwords do not match. Please try again.';
      return false;
    } else if (!validConfirmPassword) {
      confirmPasswordError.style.display = 'block';
      confirmPassword.classList.add('error-message');
      confirmPasswordError.innerText =
        'Passwords do not match. Please try again.';
    } else if (confirmpassword !== newpassword) {
      confirmPasswordError.style.display = 'block';
      confirmPassword.classList.add('error-message');
      confirmPasswordError.innerText =
        'Passwords do not match. Please try again.';
    } else {
      confirmPasswordError.innerText = '';
      confirmPasswordError.style.display = 'none';
      confirmPassword.classList.remove('error-message');
      confirmPassword.classList.add('validated-message');
      return true;
    }
  }
})();
