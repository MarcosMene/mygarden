;(function () {
  // NAME PRODUCT
  let galleryName = document.getElementById('gallery_name')
  let galleryNameError = document.getElementById('galleryName_error')
  let errorMessages = document.getElementById('error_messages')

  // IMAGE PRODUCT
  let galleryImage = document.getElementById('gallery_imageFile_file')
  let galleryImageError = document.getElementById('galleryImage_error')

  // SUBMIT BUTTON
  let submitButton = document.getElementById('gallery_submit')

  galleryName.addEventListener('input', () => {
    checkProductName()
  })

  galleryImage.addEventListener('change', () => {
    checkProductImage()
  })

  submitButton.addEventListener('click', (event) => {
    if (!checkProductName() && !checkProductImage()) {
      errorMessages.classList.remove('hidden')
      errorMessages.innerText = 'Please fill in all the fields'
    } else {
      errorMessages.classList.add('hidden')
    }

    if (!checkProductName()) {
      event.preventDefault()
      galleryNameError.style.display = 'block'
      galleryName.classList.add('error-message')
      galleryNameError.innerText = 'The name of gallery is required.'
    }

    if (!checkProductImage()) {
      event.preventDefault()
      galleryImageError.style.display = 'block'
      galleryImage.classList.add('error-message')
      galleryImageError.innerText = 'The image is required.'
    }
  })

  // CHECK PRODUCT NAME
  function checkProductName() {
    galleryName.classList.remove('is-invalid')
    galleryName.classList.remove('error-message')

    //VALIDATION PRODUCT
    let patternProductName = /^[a-zA-ZÀ-ÿ0-9_\-\s]{2,40}$/
    let galleryname = galleryName.value
    let validProductName = patternProductName.test(galleryname)
    if (galleryname.trim() === '') {
      galleryNameError.style.display = 'block'
      galleryName.classList.add('error-message')
      galleryNameError.innerText = 'The name of the gallery is required.'
      return false
    }
    if (galleryname.length < 3 || galleryname.length > 40) {
      galleryNameError.style.display = 'block'
      galleryName.classList.add('error-message')
      galleryNameError.innerText =
        'The name of the gallery must be at least 3 characters, and maximum 40 characters.'
      return false
    }
    if (!validProductName) {
      galleryNameError.style.display = 'block'
      galleryName.classList.add('error-message')
      galleryNameError.innerText =
        'The name of the gallery can only contain letters, spaces, or hyphens'
      return false
    } else {
      galleryNameError.innerText = ''
      galleryNameError.style.display = 'none'
      galleryName.classList.remove('error-message')
      galleryName.classList.add('validated-message')
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
      if (!galleryImage.files[0]) {
        galleryImageError.style.display = 'block'
        galleryImage.classList.add('error-message')
        galleryImageError.innerText = 'The image of the gallery is required.'
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
      if (!allowedTypes.includes(galleryImage.files[0].type)) {
        galleryImageError.style.display = 'block'
        galleryImage.classList.add('error-message')
        galleryImageError.innerText =
          'Invalid file type! Please upload a JPG, JPEG, PNG, or WEBP image.'
        return false
      }

      // Check file size (max size set to 1MB in this example)
      const maxSizeInBytes = 1 * 1024 * 1024 // 1MB
      if (galleryImage.files[0].size > maxSizeInBytes) {
        galleryImageError.style.display = 'block'
        galleryImage.classList.add('error-message')
        galleryImageError.innerText = 'File is too large! Maximum size is 1MB.'
        return false
      } else {
        galleryImageError.innerText = ''
        galleryImageError.style.display = 'none'
        galleryImage.classList.remove('error-message')
        galleryImage.classList.add('validated-message')
        return true
      }
    }
  }
})()
