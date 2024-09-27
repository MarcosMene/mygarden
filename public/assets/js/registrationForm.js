// FIRST NAME
var FirstName = document.getElementById('registration_form_firstname')
var FirstNameError = document.getElementById('firstname_error')

// LAST NAME
var LastName = document.getElementById('registration_form_lastname')
var LastNameError = document.getElementById('lastname_error')

// EMAIL
var Email = document.getElementById('registration_form_email')
var EmailError = document.getElementById('email_error')

// PASSWORD
var Password = document.getElementById('registration_form_password_first')
var PasswordError = document.getElementById('password_error')

// CONFIRM PASSWORD
var ConfirmPsw = document.getElementById('registration_form_password_second')
var ConfirmPswError = document.getElementById('password_error')

// CALL FUNCTIONS
FirstName.addEventListener('input', (event) => {
  checkFirstName()
})
LastName.addEventListener('input', (event) => {
  checkLastName()
})
Email.addEventListener('input', (event) => {
  checkEmail()
})
Password.addEventListener('input', (event) => {
  checkPassword()
})
ConfirmPsw.addEventListener('input', (event) => {
  checkConfirmPsw()
})

// FUNCTION FIRST NAME
function checkFirstName() {
  var patternFirstName = /[a-zA-ZÀ-ÿ\s\-]{3,25}/
  var firstname = FirstName.value
  var validFirstName = patternFirstName.test(firstname)
  if (firstname.trim() === '') {
    FirstNameError.style.display = 'block'
    FirstName.classList.add('error-message')
    FirstNameError.innerText = 'Your name is required.'
    return false
  } else if (firstname.length < 3 || firstname.length > 25) {
    FirstNameError.style.display = 'block'
    FirstName.classList.add('error-message')
    FirstNameError.innerText =
      'Your first name must be at least 3 characters long'
    return false
  } else if (!validFirstName) {
    FirstNameError.style.display = 'block'
    FirstNameError.innerText =
      'Your first name can only contain letters, spaces, and hyphens'
    return false
  } else {
    FirstNameError.innerText = ''
    FirstNameError.style.display = 'none'
    FirstName.classList.remove('error-message')
    FirstName.classList.add('validated-message')
    return true
  }
}

// FUNCTION LAST NAME
function checkLastName() {
  var patternLastName = /[a-zA-ZÀ-ÿ]{5,25}/
  var lastname = LastName.value
  var validLastName = patternLastName.test(lastname)
  if (lastname.trim() === '') {
    LastNameError.style.display = 'block'
    LastNameError.innerText = 'Your last name is required.'
    return false
  } else if (lastname.length < 5 || lastname.length > 25) {
    LastNameError.style.display = 'block'
    LastName.classList.add('error-message')
    LastNameError.innerText =
      'Your last name must be at least 5 characters long and maximum 25 characters.'
    return false
  } else if (!validLastName) {
    LastNameError.style.display = 'block'
    LastNameError.innerText =
      'Your last name can only contain letters, spaces, and hyphens'
    return false
  } else {
    LastNameError.innerText = ''
    LastNameError.style.display = 'none'
    LastName.classList.remove('error-message')
    LastName.classList.add('validated-message')
    return true
  }
}

// FUNCTION EMAIL
function checkEmail() {
  var patternEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/
  var email = Email.value
  var validEmail = patternEmail.test(email)
  if (email.trim() === '') {
    EmailError.style.display = 'block'
    EmailError.innerText = 'Your email is required.'
    return false
  } else if (email.length < 10 || email.length > 50) {
    EmailError.style.display = 'block'
    Email.classList.add('error-message')
    EmailError.innerText =
      'Your email must be at least 10 characters long and maximum 50 characters.'
    return false
  } else if (!validEmail) {
    EmailError.style.display = 'block'
    EmailError.innerText =
      'Your email can only contain letters, @, hyphens and numbers'
    return false
  } else {
    EmailError.innerText = ''
    EmailError.style.display = 'none'
    Email.classList.remove('error-message')
    Email.classList.add('validated-message')
    return true
  }
}

// FUNCTION PASSWORD
function checkPassword() {
  var patternPassword =
    /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{6,16}$/
  var password = Password.value
  var validPassword = patternPassword.test(password)
  if (password.trim() === '') {
    PasswordError.style.display = 'block'
    PasswordError.innerText = 'Your password is required.'
    return false
  } else if (password.length < 6 || password.length > 16) {
    PasswordError.style.display = 'block'
    Password.classList.add('error-message')
    PasswordError.innerText =
      'Your password must be at least 6 characters long and maximum 16 characters.'
  } else if (!validPassword) {
    PasswordError.style.display = 'block'
    PasswordError.innerText =
      'Your password can only contain letters, symbols, hyphens and numbers'
  } else {
    PasswordError.innerText = ''
    PasswordError.style.display = 'none'
    Password.classList.remove('error-message')
    Password.classList.add('validated-message')
    return true
  }
}

// FUNCTION CONFIRM PASSWORD
function checkConfirmPsw() {
  var patternConfirmPsw =
    /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{6,16}$/
  var password = Password.value
  var confirmPsw = ConfirmPsw.value
  var validConfirmPsw = patternConfirmPsw.test(confirmPsw)
  if (confirmPsw.trim() === '') {
    ConfirmPswError.style.display = 'block'
    ConfirmPswError.innerText = 'Your confirm password is required.'
    return false
  } else if (password !== confirmPsw) {
    ConfirmPswError.style.display = 'block'
    ConfirmPsw.classList.add('error-message')
    ConfirmPswError.innerText = 'Your password must match with confirm password'
    return false
  } else {
    ConfirmPswError.innerText = ''
    ConfirmPswError.style.display = 'none'
    ConfirmPsw.classList.remove('error-message')
    ConfirmPsw.classList.add('validated-message')
    return true
  }
}
