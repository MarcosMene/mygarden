;(function () {
  // NAME CATEGORY
  let categoryName = document.getElementById('category_name')
  let categoryNameError = document.getElementById('categoryName_error')
  let errorMessages = document.getElementById('error_messages')

  // IMAGE CATEGORY
  let categoryImage = document.getElementById('category_imageFile_file')
  let categoryImageError = document.getElementById('categoryImage_error')

  // SUBMIT BUTTON
  let submitButton = document.getElementById('category_submit')

  categoryName.addEventListener('input', () => {
    checkcategoryName()
  })

  categoryImage.addEventListener('change', () => {
    checkcategoryImage()
  })

  submitButton.addEventListener('click', (event) => {
    if (!checkcategoryName() && !checkcategoryImage()) {
      errorMessages.classList.remove('hidden')
      errorMessages.innerText = 'Please fill in all the fields'
    } else {
      errorMessages.classList.add('hidden')
    }

    if (!checkcategoryName()) {
      event.preventDefault()
      categoryNameError.style.display = 'block'
      categoryName.classList.add('error-message')
      categoryNameError.innerText = 'The name of the category is required.'
    }
    if (!checkcategoryImage()) {
      event.preventDefault()
      categoryImageError.style.display = 'block'
      categoryImage.classList.add('error-message')
      categoryImageError.innerText = 'The image of the category is required.'
    }
  })

  // CHECK CATEGORY NAME
  function checkcategoryName() {
    categoryName.classList.remove('is-invalid')
    categoryName.classList.remove('error-message')

    //VALIDATION CATEGORY
    let patterncategoryName = /^[a-zA-ZÀ-ÿ0-9_\'\-\s]{2,40}$/
    let categoryname = categoryName.value
    let validcategoryName = patterncategoryName.test(categoryname)
    if (categoryname.trim() === '') {
      categoryNameError.style.display = 'block'
      categoryName.classList.add('error-message')
      categoryNameError.innerText = 'The name of the category is required.'
      return false
    }
    if (categoryname.length < 3 || categoryname.length > 40) {
      categoryNameError.style.display = 'block'
      categoryName.classList.add('error-message')
      categoryNameError.innerText =
        'The name of the category must be at least 3 characters, and maximum 40 characters.'
      return false
    }
    if (!validcategoryName) {
      categoryNameError.style.display = 'block'
      categoryName.classList.add('error-message')
      categoryNameError.innerText =
        'The name of the category can only contain letters, spaces, and hyphens'
      return false
    } else {
      categoryNameError.innerText = ''
      categoryNameError.style.display = 'none'
      categoryName.classList.remove('error-message')
      categoryName.classList.add('validated-message')
      return true
    }
  }

  // CHECK IMAGE
  function checkcategoryImage() {
    let vichImage = document.querySelector('.vich-image')

    // Check if the vichImage contains any element with an href attribute (like an <a> tag)
    let hasHrefChild = vichImage.querySelector('[href]') !== null

    if (hasHrefChild) {
      return true
    } else {
      if (!categoryImage.files[0]) {
        categoryImageError.style.display = 'block'
        categoryImage.classList.add('error-message')
        categoryImageError.innerText = 'The image of the category is required.'
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
      if (!allowedTypes.includes(categoryImage.files[0].type)) {
        categoryImageError.style.display = 'block'
        categoryImage.classList.add('error-message')
        categoryImageError.innerText =
          'Invalid file type! Please upload a JPG, JPEG, PNG, or WEBP image.'
        return false
      }

      // Check file size (max size set to 1MB in this example)
      const maxSizeInBytes = 1 * 1024 * 1024 // 1MB
      if (categoryImage.files[0].size > maxSizeInBytes) {
        categoryImageError.style.display = 'block'
        categoryImage.classList.add('error-message')
        categoryImageError.innerText = 'File is too large! Maximum size is 1MB.'
        return false
      } else {
        categoryImageError.innerText = ''
        categoryImageError.style.display = 'none'
        categoryImage.classList.remove('error-message')
        categoryImage.classList.add('validated-message')
        return true
      }
    }
  }
})()
