;(function () {
  // CONTENT HEADER
  let headerContent = document.getElementById('header_content')
  let headerContentError = document.getElementById(
    'headerContent_error'
  )
  let errorMessages = document.getElementById('error_messages')

  // CATEGORY HEADER
  let headerCategory = document.getElementById('header_categoryProduct')
  let headerCategoryError = document.getElementById('headerCategory_error')

  // TITLE BUTTON HEADER
  let headerTitleButton = document.getElementById('header_buttonTitle')
  let headerTitleButtonError = document.getElementById(
    'headerButtonTitle_error'
  )

  // ORDER APPEAR
  let headerOrderAppear = document.getElementById('header_orderAppear')
  let headerOrderAppearError = document.getElementById(
    'headerOrderAppear_error'
  )

  // COLOR HEADER
  let headerColor = document.getElementById('header_backgroundColor')
  let headerColorError = document.getElementById('headerBackgroundColor_error')

  // IMAGE POSITION HEADER
  let headerSideImage = document.getElementById('header_sideImage')
  let headerSideImageError = document.getElementById('headerSideImage_error')

  // IMAGE HEADER
  let headerImage = document.getElementById('header_imageFile_file')
  let headerImageError = document.getElementById('headerImage_error')

  // SUBMIT BUTTON
  let submitButton = document.getElementById('header_submit')

  headerContent.addEventListener('input', () => {
    checkHeaderContent()
  })
  headerCategory.addEventListener('change', () => {
    checkCategory()
  })
  headerTitleButton.addEventListener('input', () => {
    checkTitleButton()
  })
  headerOrderAppear.addEventListener('change', () => {
    checkOrderAppear()
  })
  headerColor.addEventListener('change', () => {
    checkProductColor()
  })

  headerSideImage.addEventListener('change', () => {
    checkSideImage()
  })

  headerImage.addEventListener('change', () => {
    checkProductImage()
  })

  submitButton.addEventListener('click', (event) => {
    if (
      !checkHeaderContent() &&
      !checkCategory() &&
      !checkTitleButton() &&
      !checkOrderAppear() &&
      !checkProductColor() &&
      !checkSideImage() &&
      !checkProductImage()
    ) {
      errorMessages.classList.remove('hidden')
      errorMessages.innerText = 'Please fill in all the fields'
    } else {
      errorMessages.classList.add('hidden')
    }

    if (!checkHeaderContent()) {
      event.preventDefault()
      headerContentError.style.display = 'block'
      headerContent.classList.add('error-message')
      headerContentError.innerText = 'The content of header is required.'
    }
    if (!checkCategory()) {
      event.preventDefault()
      headerCategoryError.style.display = 'block'
      headerCategory.classList.add('error-message')
      headerCategoryError.innerText = 'The category is required.'
    }
    if (!checkOrderAppear()) {
      event.preventDefault()
      headerOrderAppearError.style.display = 'block'
      headerOrderAppear.classList.add('error-message')
      headerOrderAppearError.innerText = 'The order is required.'
    }
    if (!checkTitleButton()) {
      event.preventDefault()
      headerTitleButtonError.style.display = 'block'
      headerTitleButton.classList.add('error-message')
      headerTitleButtonError.innerText = 'The title button is required.'
    }
    if (!checkProductColor()) {
      event.preventDefault()
      headerColorError.style.display = 'block'
      headerColor.classList.add('error-message')
      headerColorError.innerText = 'The color is required.'
    }
    if (!checkSideImage()) {
      event.preventDefault()
      headerSideImageError.style.display = 'block'
      headerSideImage.classList.add('error-message')
      headerSideImageError.innerText = 'The side image is required.'
    }
    if (!checkProductImage()) {
      event.preventDefault()
      headerImageError.style.display = 'block'
      headerImage.classList.add('error-message')
      headerImageError.innerText = 'The image is required.'
    }
  })

  // CHECK HEADER CONTENT
  function checkHeaderContent() {
    headerContent.classList.remove('is-invalid')
    headerContent.classList.remove('error-message')

    //VALIDATION HEADER
    let patternHeaderContent = /^[a-zA-ZÀ-ÿ0-9_?!\-\s]{10,50}$/
    let headercontent = headerContent.value
    let validHeaderContent = patternHeaderContent.test(headercontent)
    if (headercontent.trim() === '') {
      headerContentError.style.display = 'block'
      headerContent.classList.add('error-message')
      headerContentError.innerText = 'The content of the header is required.'
      return false
    }
    if (headercontent.length < 10 || headercontent.length > 50) {
      headerContentError.style.display = 'block'
      headerContent.classList.add('error-message')
      headerContentError.innerText =
        'The content of the header must be at least 10 characters, and maximum 50 characters.'
      return false
    }
    if (!validHeaderContent) {
      headerContentError.style.display = 'block'
      headerContent.classList.add('error-message')
      headerContentError.innerText =
        'The content of the header can only contain letters, spaces, and hyphens'
      return false
    } else {
      headerContentError.innerText = ''
      headerContentError.style.display = 'none'
      headerContent.classList.remove('error-message')
      headerContent.classList.add('validated-message')
      return true
    }
  }

  // CHECK CATEGORY
  function checkCategory() {
    // CATEGORY PLACEHOLDER
    if (headerCategory.value && headerCategory.length != 0) {
      headerCategory.style.color = '#161616'
    } else {
      headerCategory.style.color = '#989898'
    }

    if (headerCategory.value == '') {
      headerCategoryError.style.display = 'block'
      headerCategory.classList.add('error-message')
      headerCategoryError.innerText = 'The category of header is required.'
      return false
    } else {
      headerCategoryError.innerText = ''
      headerCategoryError.style.display = 'none'
      headerCategory.classList.remove('error-message')
      headerCategory.classList.add('validated-message')
      return true
    }
  }

  // CHECK IMAGE
  function checkProductImage() {
    let vichImage = document.querySelector('.vich-image')

    // Check if the vichImage contains any element with an href attribute (like an <a> tag)
    let hasHrefChild = vichImage.querySelector('[href]') !== null

    if (hasHrefChild) {
      return true
    } else {
      if (!headerImage.files[0]) {
        headerImageError.style.display = 'block'
        headerImage.classList.add('error-message')
        headerImageError.innerText = 'The image of the header is required.'
        return false
      }
      // Allowed file types
      const allowedTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
      ]

      // Check file type
      if (!allowedTypes.includes(headerImage.files[0].type)) {
        headerImageError.style.display = 'block'
        headerImage.classList.add('error-message')
        headerImageError.innerText =
          'Invalid file type! Please upload a JPG, JPEG, PNG, or WEBP image.'
        return false
      }

      // Check file size (max size set to 1MB in this example)
      const maxSizeInBytes = 1 * 1024 * 1024 // 1MB
      if (headerImage.files[0].size > maxSizeInBytes) {
        headerImageError.style.display = 'block'
        headerImage.classList.add('error-message')
        headerImageError.innerText = 'File is too large! Maximum size is 1MB.'
        return false
      } else {
        headerImageError.innerText = ''
        headerImageError.style.display = 'none'
        headerImage.classList.remove('error-message')
        headerImage.classList.add('validated-message')
        return true
      }
    }
  }

  // CHECK HEADER PRICE
  function checkTitleButton() {
    let patternTitleButton = /^[a-zA-ZÀ-ÿ0-9_\-\s]{3,15}$/
    let headerprice = headerTitleButton.value
    let validTitleButton = patternTitleButton.test(headerprice)
    if (headerprice.trim() === '') {
      headerTitleButtonError.style.display = 'block'
      headerTitleButton.classList.add('error-message')
      headerTitleButtonError.innerText = 'The title button of the header is required.'
      return false
    } else if (!validTitleButton) {
      headerTitleButtonError.style.display = 'block'
      headerTitleButton.classList.add('error-message')
      headerTitleButtonError.innerText =
        'The title button of the header must be at least 3 characters, and maximum 15 characters.'
      return false
    } else if (headerprice.length < 3 || headerprice.length > 15) {
      headerTitleButtonError.style.display = 'block'
      headerTitleButton.classList.add('error-message')
      headerTitleButtonError.innerText =
        'The title number of the header can only contain letters, spaces, and hyphens'
      return false
    } else {
      headerTitleButtonError.innerText = ''
      headerTitleButtonError.style.display = 'none'
      headerTitleButton.classList.remove('error-message')
      headerTitleButton.classList.add('validated-message')
      return true
    }
  }

  // CHECK TVA
  function checkOrderAppear() {
    // TVA PLACEHOLDER
    if (headerOrderAppear.value) {
      headerOrderAppear.style.color = '#161616'
    } else {
      headerOrderAppear.style.color = '#989898'
    }
    if (headerOrderAppear.value == '') {
      headerOrderAppearError.style.display = 'block'
      headerOrderAppear.classList.add('error-message')
      headerOrderAppearError.innerText = 'The TVA of header is required.'
      return false
    } else {
      headerOrderAppearError.innerText = ''
      headerOrderAppearError.style.display = 'none'
      headerOrderAppear.classList.remove('error-message')
      headerOrderAppear.classList.add('validated-message')
      return true
    }
  }

  // CHECK COLOR
  function checkProductColor() {
    // COLOR PLACEHOLDER
    if (headerColor.value) {
      headerColor.style.color = '#161616'
    } else {
      headerColor.style.color = '#989898'
    }
    if (headerColor.value == '') {
      headerColorError.style.display = 'block'
      headerColor.classList.add('error-message')
      headerColorError.innerText = 'The color of header is required.'
      return false
    } else {
      headerColorError.innerText = ''
      headerColorError.style.display = 'none'
      headerColor.classList.remove('error-message')
      headerColor.classList.add('validated-message')
      return true
    }
  }

  // CHECK PROMOTION
  function checkSideImage() {
    // PROMOTION PLACEHOLDER
    if (headerSideImage.value) {
      headerSideImage.style.color = '#161616'
    } else {
      headerSideImage.style.color = '#989898'
    }
    if (headerSideImage.value == '') {
      headerSideImageError.style.display = 'block'
      headerSideImage.classList.add('error-message')
      headerSideImageError.innerText = 'The promotion of header is required.'
      return false
    } else {
      headerSideImageError.innerText = ''
      headerSideImageError.style.display = 'none'
      headerSideImage.classList.remove('error-message')
      headerSideImage.classList.add('validated-message')
      return true
    }
  }

  // CHECK SUGGESTION
  function checkProductSuggestion() {
    // SUGGESTION PLACEHOLDER
    if (headerSuggestion.value) {
      headerSuggestion.style.color = '#161616'
    } else {
      headerSuggestion.style.color = '#989898'
    }
    if (headerSuggestion.value == '') {
      headerSuggestionError.style.display = 'block'
      headerSuggestion.classList.add('error-message')
      headerSuggestionError.innerText = 'The suggestion of header is required.'
      return false
    } else {
      headerSuggestionError.innerText = ''
      headerSuggestionError.style.display = 'none'
      headerSuggestion.classList.remove('error-message')
      headerSuggestion.classList.add('validated-message')
      return true
    }
  }

  // CHECK  TITLE BUTTON
  function checkProductTitleButton() {
    let patternProductTitleButton = /^[a-zA-ZÀ-ÿ0-9_\.,!?\-\s]{19,199}$/
    let headerdescription = headerTitleButton.value
    let validProductTitleButton =
      patternProductTitleButton.test(headerdescription)
    if (headerdescription.trim() === '') {
      headerTitleButtonError.style.display = 'block'
      headerTitleButton.classList.add('error-message')
      headerTitleButtonError.innerText =
        'The description of the header is required.'
      return false
    } else if (
      headerdescription.length < 20 ||
      headerdescription.length > 200
    ) {
      headerTitleButtonError.style.display = 'block'
      headerTitleButton.classList.add('error-message')
      headerTitleButtonError.innerText =
        'The description of the header must be at least 20 characters, and maximum 200 characters.'
      return false
    } else if (!validProductTitleButton) {
      headerTitleButtonError.style.display = 'block'
      headerTitleButton.classList.add('error-message')
      headerTitleButtonError.innerText =
        'The description of the header can only contain letters, spaces, and hyphens'
      return false
    } else {
      headerTitleButtonError.innerText = ''
      headerTitleButtonError.style.display = 'none'
      headerTitleButton.classList.remove('error-message')
      headerTitleButton.classList.add('validated-message')
      return true
    }
  }
})()