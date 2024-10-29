;(function () {
  //ERROR MESSAGES
  let errorMessages = document.getElementById('error_messages')

  // RATE OF PRODUCT
  let rateProduct = document.getElementById('review_rate')
  let rateProductError = document.getElementById('rateProduct_error')

  // DESCRIPTION PRODUCT
  let reviewProduct = document.getElementById('review_review')
  let reviewProductError = document.getElementById('reviewProduct_error')

  // SUBMIT BUTTON
  let submitButton = document.getElementById('review_submit')

  //VERIFY IF EXIST BECAUSE FORM DISAPEAR AFTER SUBMIT
  if (rateProduct !== null) {
    rateProduct.addEventListener('change', () => {
      checkRateProduct()
    })
  }
  //VERIFY IF EXIST BECAUSE FORM DISAPEAR AFTER SUBMIT
  if (reviewProduct !== null) {
    reviewProduct.addEventListener('input', () => {
      checkReviewProduct()
    })
  }

  if (submitButton !== null) {
    submitButton.addEventListener('click', (event) => {
      if (!checkRateProduct() && !checkReviewProduct()) {
        errorMessages.classList.remove('hidden')
        errorMessages.innerText = 'Please fill in all the fields'
      } else {
        errorMessages.classList.add('hidden')
      }

      if (!checkRateProduct()) {
        event.preventDefault()
        rateProductError.style.display = 'block'
        rateProduct.classList.add('error-message')
        rateProductError.innerText = 'The rate is required.'
      }

      if (!checkReviewProduct()) {
        event.preventDefault()
        reviewProductError.style.display = 'block'
        reviewProduct.classList.add('error-message')
        reviewProductError.innerText = 'Your review is required.'
      }
    

    })
  }
  // CHECK RATE
  function checkRateProduct() {
    // RATE PLACEHOLDER
    if (rateProduct.value) {
      rateProduct.style.color = '#161616'
    } else {
      rateProduct.style.color = '#989898'
    }
    if (rateProduct.value == '') {
      rateProductError.style.display = 'block'
      rateProduct.classList.add('error-message')
      rateProductError.innerText = 'The rate of product is required.'
      return false
    } else {
      rateProductError.innerText = ''
      rateProductError.style.display = 'none'
      rateProduct.classList.remove('error-message')
      rateProduct.classList.add('validated-message')
      return true
    }
  }

  // CHECK  DESCRIPTION
  function checkReviewProduct() {
    let patternProductDescription = /^[a-zA-ZÀ-ÿ0-9_\.,!?\-\s]{19,199}$/
    let productdescription = reviewProduct.value
    let validProductDescription =
      patternProductDescription.test(productdescription)
    if (productdescription.trim() === '') {
      reviewProductError.style.display = 'block'
      reviewProduct.classList.add('error-message')
      reviewProductError.innerText =
        'The description of the product is required.'
      return false
    } else if (!validProductDescription) {
      reviewProductError.style.display = 'block'
      reviewProduct.classList.add('error-message')
      reviewProductError.innerText =
        'The description of the product can only contain letters, spaces, and hyphens, and must be at least 20 characters, and maximum 200 characters.'
      return false
    } else {
      reviewProductError.innerText = ''
      reviewProductError.style.display = 'none'
      reviewProduct.classList.remove('error-message')
      reviewProduct.classList.add('validated-message')
      return true
    }
  }
})()
