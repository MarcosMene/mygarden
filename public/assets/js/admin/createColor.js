// NAME COLOR
var colorName = document.getElementById('color_color')
var colorNameError = document.getElementById('colorName_error')
var errorMessages = document.getElementById('error_messages')

// SUBMIT BUTTON
var submitButton = document.getElementById('color_submit')

colorName.addEventListener('input', () => {
  checkcolorName()
})

submitButton.addEventListener('click', (e) => {
  if (!checkcolorName()) {
    e.preventDefault()
    errorMessages.classList.remove('hidden')
    errorMessages.innerText = 'Please fill the color field'
    colorNameError.style.display = 'block'
    colorName.classList.add('error-message')
    colorNameError.innerText = 'The color is required.'
  } else {
    errorMessages.classList.add('hidden')
  }
})

// CHECK COLOR NAME
function checkcolorName() {
  colorName.classList.remove('is-invalid')
  colorName.classList.remove('error-message')

  //VALIDATION COLOR
  let patterncolorName = /^[a-zA-ZÀ-ÿ0-9_\-\s]{2,40}$/
  let colorname = colorName.value
  let validcolorName = patterncolorName.test(colorname)
  if (colorname.trim() === '') {
    colorNameError.style.display = 'block'
    colorName.classList.add('error-message')
    colorNameError.innerText = 'The name of the color is required.'
    return false
  }
  if (colorname.length < 3 || colorname.length > 40) {
    colorNameError.style.display = 'block'
    colorName.classList.add('error-message')
    colorNameError.innerText =
      'The name of the color must be at least 3 characters, and maximum 40 characters.'
    return false
  }
  if (!validcolorName) {
    colorNameError.style.display = 'block'
    colorName.classList.add('error-message')
    colorNameError.innerText =
      'The name of the color can only contain letters, spaces, and hyphens'
    return false
  } else {
    colorNameError.innerText = ''
    colorNameError.style.display = 'none'
    colorName.classList.remove('error-message')
    colorName.classList.add('validated-message')
    return true
  }
}
