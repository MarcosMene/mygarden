;(function () {
  // NAME CARRIER
  let carrierName = document.getElementById('carrier_name')
  let carrierNameError = document.getElementById('carrierName_error')
  let errorMessages = document.getElementById('error_messages')

  // PRICE OF CARRIER
  let carrierPrice = document.getElementById('carrier_price')
  let carrierPriceError = document.getElementById('carrierPrice_error')

  // DESCRIPTION CARRIER
  let carrierDescription = document.getElementById('carrier_description')
  let carrierDescriptionError = document.getElementById(
    'carrierDescription_error'
  )

  // SUBMIT BUTTON
  let submitButton = document.getElementById('carrier_submit')

  carrierName.addEventListener('input', () => {
    checkProductName()
  })

  carrierPrice.addEventListener('input', () => {
    checkProductPrice()
  })

  carrierDescription.addEventListener('input', () => {
    checkProductDescription()
  })

  submitButton.addEventListener('click', (event) => {
    if (
      !checkProductName() &&
      !checkProductPrice() &&
      !checkProductDescription()
    ) {
      errorMessages.classList.remove('hidden')
      errorMessages.innerText = 'Please fill in all the fields'
    } else {
      errorMessages.classList.add('hidden')
    }

    if (!checkProductName()) {
      event.preventDefault()
      carrierNameError.style.display = 'block'
      carrierName.classList.add('error-message')
      carrierNameError.innerText = 'The name of carrier is required.'
    }
    if (!checkProductPrice()) {
      event.preventDefault()
      carrierPriceError.style.display = 'block'
      carrierPrice.classList.add('error-message')
      carrierPriceError.innerText = 'The price is required.'
    }
    if (!checkProductDescription()) {
      event.preventDefault()
      carrierDescriptionError.style.display = 'block'
      carrierDescription.classList.add('error-message')
      carrierDescriptionError.innerText = 'The description is required.'
    }
  })

  // CHECK CARRIER NAME
  function checkProductName() {
    carrierName.classList.remove('is-invalid')
    carrierName.classList.remove('error-message')

    //VALIDATION CARRIER
    let patternProductName = /^[a-zA-ZÀ-ÿ0-9_\-\s]{2,40}$/
    let carriername = carrierName.value
    let validProductName = patternProductName.test(carriername)
    if (carriername.trim() === '') {
      carrierNameError.style.display = 'block'
      carrierName.classList.add('error-message')
      carrierNameError.innerText = 'The name of the carrier is required.'
      return false
    }
    if (carriername.length < 3 || carriername.length > 40) {
      carrierNameError.style.display = 'block'
      carrierName.classList.add('error-message')
      carrierNameError.innerText =
        'The name of the carrier must be at least 3 characters, and maximum 40 characters.'
      return false
    }
    if (!validProductName) {
      carrierNameError.style.display = 'block'
      carrierName.classList.add('error-message')
      carrierNameError.innerText =
        'The name of the carrier can only contain letters, spaces, or hyphens'
      return false
    } else {
      carrierNameError.innerText = ''
      carrierNameError.style.display = 'none'
      carrierName.classList.remove('error-message')
      carrierName.classList.add('validated-message')
      return true
    }
  }

  // CHECK CARRIER PRICE
  function checkProductPrice() {
    let patternProductPrice = /^[1-9]\d{1,3}([\,\.]\d+)?$/
    let carrierprice = carrierPrice.value
    let validProductPrice = patternProductPrice.test(carrierprice)
    if (carrierprice.trim() === '') {
      carrierPriceError.style.display = 'block'
      carrierPrice.classList.add('error-message')
      carrierPriceError.innerText = 'The price of the carrier is required.'
      return false
    } else if (!validProductPrice) {
      carrierPriceError.style.display = 'block'
      carrierPrice.classList.add('error-message')
      carrierPriceError.innerText =
        'The price of the carrier can only contain numbers and must be positive'
      return false
    } else if (carrierprice.length < 2 || carrierprice.length > 7) {
      carrierPriceError.style.display = 'block'
      carrierPrice.classList.add('error-message')
      carrierPriceError.innerText =
        'The price of the carrier must be at least 2 characters, and maximum 7 characters.'
      return false
    } else {
      carrierPriceError.innerText = ''
      carrierPriceError.style.display = 'none'
      carrierPrice.classList.remove('error-message')
      carrierPrice.classList.add('validated-message')
      return true
    }
  }

  // CHECK  DESCRIPTION
  function checkProductDescription() {
    let patternProductDescription = /^[a-zA-ZÀ-ÿ0-9_\.,!?\-\s]{19,100}$/
    let carrierdescription = carrierDescription.value
    let validProductDescription =
      patternProductDescription.test(carrierdescription)
    if (carrierdescription.trim() === '') {
      carrierDescriptionError.style.display = 'block'
      carrierDescription.classList.add('error-message')
      carrierDescriptionError.innerText =
        'The description of the carrier is required.'
      return false
    } else if (
      carrierdescription.length < 20 ||
      carrierdescription.length > 100
    ) {
      carrierDescriptionError.style.display = 'block'
      carrierDescription.classList.add('error-message')
      carrierDescriptionError.innerText =
        'The description of the carrier must be at least 20 characters, and maximum 100 characters.'
      return false
    } else if (!validProductDescription) {
      carrierDescriptionError.style.display = 'block'
      carrierDescription.classList.add('error-message')
      carrierDescriptionError.innerText =
        'The description of the carrier can only contain letters, spaces, or hyphens'
      return false
    } else {
      carrierDescriptionError.innerText = ''
      carrierDescriptionError.style.display = 'none'
      carrierDescription.classList.remove('error-message')
      carrierDescription.classList.add('validated-message')
      return true
    }
  }
})()
