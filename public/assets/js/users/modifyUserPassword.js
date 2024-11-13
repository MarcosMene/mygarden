;(function () {
  //ERROR MESSAGES OF FORM
  let errorMessages = document.getElementById('error_messages')

  // CURRENT PASSWORD
  let currentPassword = document.getElementById('change_password_current_password')
  let currentPasswordError = document.getElementById('currentPassword_error')

  currentPassword.addEventListener('focus', function() {
    currentPasswordError.style.display = 'none';
});

  // NEW PASSWORD
  let newPassword = document.getElementById('change_password_new_password_first')
  let newPasswordError = document.getElementById('newPassword_error')

  // CONFIRM PASSWORD
  let confirmPassword = document.getElementById('change_password_new_password_second')
  let confirmPasswordError = document.getElementById('confirmPassword_error')

  // SUBMIT BUTTON
  let submitButton = document.getElementById('updatePassword_submit')

  let formError = document.querySelector('.form-error ul')
  if(formError !== null){
    currentPassword.classList.add('error-message')
  }

  currentPassword.addEventListener('focus', () => {
  formError.classList.add('hidden')
  })
  currentPassword.addEventListener('input', () => {
    checkCurrentPassword()
  })
  newPassword.addEventListener('input', () => {
    checkNewPassword()
  })
  confirmPassword.addEventListener('input', () => {
    checkConfirmPassword()
  })

  submitButton.addEventListener('click', (event) => {
    if (
      !checkCurrentPassword() &&
      !checkNewPassword() &&
      !checkConfirmPassword()
    ) {
      errorMessages.classList.remove('hidden')
      errorMessages.innerText = 'Please fill in all the fields'
    } else {
      errorMessages.classList.add('hidden')
    }

    if (!checkCurrentPassword()) {
      event.preventDefault()
      currentPasswordError.style.display = 'block'
      currentPassword.classList.add('error-message')
      currentPasswordError.innerText = 'Current password must be at least 8 characters long and 16 characters maximum. Can only contain letters, symbols an uppercase, a lowercase and a number.'
    }

    if (!checkNewPassword()) {
      event.preventDefault()
      newPasswordError.style.display = 'block'
      newPassword.classList.add('error-message')
      newPasswordError.innerText = 'New password must be at least 8 characters long and 16 characters maximum. Can only contain letters, symbols an uppercase, a lowercase and a number.'
    }

    if (!checkConfirmPassword()) {
      event.preventDefault()
      confirmPasswordError.style.display = 'block'
      confirmPassword.classList.add('error-message')
      confirmPasswordError.innerText = 'Confirm password must be at least 8 characters long and 16 characters maximum. Can only contain letters, symbols an uppercase, a lowercase and a number.'
    }
  })

  // CHECK CURRENT PASSWORD
  function checkCurrentPassword() {
    
    let patternCurrentPassword =
      /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,16}$/
    let currentpassword = currentPassword.value
    let validCurrentPassword = patternCurrentPassword.test(currentpassword)
    if (currentpassword.trim() === '') {
      currentPasswordError.style.display = 'block'
      currentPassword.classList.add('error-message')
      currentPasswordError.innerText = 'Your current password is required.'
      return false
    } else if (currentpassword.length < 8 || currentpassword.length > 16) {
      currentPasswordError.style.display = 'block'
      currentPassword.classList.add('error-message')
      currentPasswordError.innerText =
        'Current password must be at least 8 characters long and 16 characters maximum'
      return false
    } else if (!validCurrentPassword) {
      currentPasswordError.style.display = 'block'
      currentPasswordError.innerText =
        'Your current password can only contain letters, symbols and numbers. An uppercase, a lowercase and a number.'
    } else {
      currentPasswordError.innerText = ''
      currentPasswordError.style.display = 'none'
      currentPassword.classList.remove('error-message')
      currentPassword.classList.add('validated-message')
      return true
    }
  }

  // CHECK NEW PASSWORD
  function checkNewPassword() {
    let patternNewPassword =
      /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,16}$/
    let newpassword = newPassword.value
    let validNewPassword = patternNewPassword.test(newpassword)
    if (newpassword.trim() === '') {
      newPasswordError.style.display = 'block'
      newPassword.classList.add('error-message')
      newPasswordError.innerText = 'Your new password is required.'
      return false
    } else if (newpassword.length < 8 || newpassword.length > 16) {
      newPasswordError.style.display = 'block'
      newPassword.classList.add('error-message')
      newPasswordError.innerText =
        'Current password must be at least 8 characters long and 16 characters maximum'
      return false
    } else if (!validNewPassword) {
      newPasswordError.style.display = 'block'
      newPassword.classList.add('error-message')
      newPasswordError.innerText =
        'Your new password can only contain letters, symbols and numbers. An uppercase, a lowercase and a number.'
    } else {
      newPasswordError.innerText = ''
      newPasswordError.style.display = 'none'
      newPassword.classList.remove('error-message')
      newPassword.classList.add('validated-message')
      return true
    }
  }

  // CHECK CONFIRM PASSWORD
  function checkConfirmPassword() {
    let patternConfirmPassword =
      /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,16}$/
    let confirmpassword = confirmPassword.value
    let newpassword = newPassword.value
    let validConfirmPassword = patternConfirmPassword.test(confirmpassword)
    if (confirmpassword.trim() === '') {
      confirmPasswordError.style.display = 'block'
      confirmPassword.classList.add('error-message')
      confirmPasswordError.innerText = 'Your new password is required.'
      return false
    } else if (confirmpassword.length < 8 || confirmpassword.length > 16) {
      confirmPasswordError.style.display = 'block'
      confirmPassword.classList.add('error-message')
      confirmPasswordError.innerText =
        'Current password must be at least 8 characters long and 16 characters maximum'
      return false
    } else if (!validConfirmPassword) {
      confirmPasswordError.style.display = 'block'
      confirmPassword.classList.add('error-message')
      confirmPasswordError.innerText =
        'Your new password can only contain letters, symbols and numbers. An uppercase, a lowercase and a number.'
    }else if (confirmpassword !== newpassword){
      confirmPasswordError.style.display = 'block'
      confirmPassword.classList.add('error-message')
      confirmPasswordError.innerText =
      'Passwords do not match. Please try again.'
    }
    
    else {
      confirmPasswordError.innerText = ''
      confirmPasswordError.style.display = 'none'
      confirmPassword.classList.remove('error-message')
      confirmPassword.classList.add('validated-message')
      return true
    }
  }
})()
