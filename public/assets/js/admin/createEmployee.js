;(function () {
  // FIRST NAME EMPLOYEE
  let employeeFirstName = document.getElementById('employee_firstName')
  let employeeFirstNameError = document.getElementById(
    'employeeFirstName_error'
  )

  // LAST NAME EMPLOYEE
  let employeeLastName = document.getElementById('employee_lastName')
  let employeeLastNameError = document.getElementById('employeeLastName_error')

  // IMAGE EMPLOYEE
  let employeeImage = document.getElementById('employee_imageFile_file')
  let employeeImageError = document.getElementById('employeeImage_error')

  // ORDER APPEAR EMPLOYEE
  let employeeOrderAppear = document.getElementById('employee_orderAppear')
  let employeeOrderAppearError = document.getElementById(
    'employeeOrderAppear_error'
  )

  // POST EMPLOYEE
  let employeePost = document.getElementById('employee_employeePost')
  let employeePostError = document.getElementById('employeePost_error')

  // SUBMIT BUTTON
  let submitButton = document.getElementById('employee_submit')

  employeeFirstName.addEventListener('input', () => {
    checkEmployeeFirstName()
  })
  employeeLastName.addEventListener('input', () => {
    checkEmployeeLastName()
  })

  employeeImage.addEventListener('change', () => {
    checkEmployeeImage()
  })

  employeeOrderAppear.addEventListener('change', () => {
    checkEmployeeOrderAppear()
  })
  employeePost.addEventListener('change', () => {
    checkEmployeePost()
  })

  submitButton.addEventListener('click', (event) => {
    if (
      !checkEmployeeFirstName() &&
      !checkEmployeeLastName() &&
      !checkEmployeeImage() &&
      !checkEmployeeOrderAppear() &&
      !checkEmployeePost()
    ) {
      errorMessages.classList.remove('hidden')
      errorMessages.innerText = 'Please fill in all the fields'
    } else {
      errorMessages.classList.add('hidden')
    }

    if (!checkEmployeeFirstName()) {
      event.preventDefault()
      employeeFirstNameError.style.display = 'block'
      employeeFirstName.classList.add('error-message')
      employeeFirstNameError.innerText =
        'The first name of employee is required.'
    }
    if (!checkEmployeeLastName()) {
      event.preventDefault()
      employeeLastNameError.style.display = 'block'
      employeeLastName.classList.add('error-message')
      employeeLastNameError.innerText =
        'The last name of the employee is required.'
    }
    if (!checkEmployeeOrderAppear()) {
      event.preventDefault()
      employeeOrderAppearError.style.display = 'block'
      employeeOrderAppear.classList.add('error-message')
      employeeOrderAppearError.innerText = 'The order appear is required.'
    }
    if (!checkEmployeePost()) {
      event.preventDefault()
      employeePostError.style.display = 'block'
      employeePost.classList.add('error-message')
      employeePostError.innerText = 'The post is required.'
    }

    if (!checkEmployeeImage()) {
      event.preventDefault()
      employeeImageError.style.display = 'block'
      employeeImage.classList.add('error-message')
      employeeImageError.innerText = 'The image is required.'
    }
  })

  // FUNCTION FIRST NAME
  function checkEmployeeFirstName() {
    let patternEmployeeFirstName = /[a-zA-ZÀ-ÿ\s\-]{3,26}/
    let employfirstname = employeeFirstName.value
    let validEmployeeFirstName = patternEmployeeFirstName.test(employfirstname)
    if (employfirstname.trim() === '') {
      employeeFirstNameError.style.display = 'block'
      employeeFirstName.classList.add('error-message')
      employeeFirstNameError.innerText = 'The employee first name is required.'
      return false
    } else if (employfirstname.length < 3 || employfirstname.length > 25) {
      employeeFirstNameError.style.display = 'block'
      employeeFirstName.classList.add('error-message')
      employeeFirstNameError.innerText =
        'The employee first name must be at least 3 characters long'
      return false
    } else if (!validEmployeeFirstName) {
      employeeFirstNameError.style.display = 'block'
      employeeFirstNameError.innerText =
        'First name can only contain letters, spaces, and hyphens'
      return false
    } else {
      employeeFirstNameError.innerText = ''
      employeeFirstNameError.style.display = 'none'
      employeeFirstName.classList.remove('error-message')
      employeeFirstName.classList.add('validated-message')
      return true
    }
  }

  // FUNCTION LAST NAME
  function checkEmployeeLastName() {
    let patternEmployeeLastName = /[a-zA-ZÀ-ÿ\s\-]{3,26}/
    let employfirstname = employeeLastName.value
    let validEmployeeLastName = patternEmployeeLastName.test(employfirstname)
    if (employfirstname.trim() === '') {
      employeeLastNameError.style.display = 'block'
      employeeLastName.classList.add('error-message')
      employeeLastNameError.innerText = 'The employee first name is required.'
      return false
    } else if (employfirstname.length < 3 || employfirstname.length > 25) {
      employeeLastNameError.style.display = 'block'
      employeeLastName.classList.add('error-message')
      employeeLastNameError.innerText =
        'The employee first name must be at least 3 characters long'
      return false
    } else if (!validEmployeeLastName) {
      employeeLastNameError.style.display = 'block'
      employeeLastNameError.innerText =
        'Last name can only contain letters, spaces, and hyphens'
      return false
    } else {
      employeeLastNameError.innerText = ''
      employeeLastNameError.style.display = 'none'
      employeeLastName.classList.remove('error-message')
      employeeLastName.classList.add('validated-message')
      return true
    }
  }

  // CHECK IMAGE
  function checkEmployeeImage() {
    let vichImage = document.querySelector('.vich-image')

    // Check if the vichImage contains any element with an href attribute (like an <a> tag)
    let hasHrefChild = vichImage.querySelector('[href]') !== null

    if (hasHrefChild) {
      return true
    } else {
      if (!employeeImage.files[0]) {
        employeeImageError.style.display = 'block'
        employeeImage.classList.add('error-message')
        employeeImageError.innerText = 'The image of the employee is required.'
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
      if (!allowedTypes.includes(employeeImage.files[0].type)) {
        employeeImageError.style.display = 'block'
        employeeImage.classList.add('error-message')
        employeeImageError.innerText =
          'Invalid file type! Please upload a JPG, JPEG, PNG, or WEBP image.'
        return false
      }

      // Check file size (max size set to 1MB in this example)
      const maxSizeInBytes = 1 * 1024 * 1024 // 1MB
      if (employeeImage.files[0].size > maxSizeInBytes) {
        employeeImageError.style.display = 'block'
        employeeImage.classList.add('error-message')
        employeeImageError.innerText = 'File is too large! Maximum size is 1MB.'
        return false
      } else {
        employeeImageError.innerText = ''
        employeeImageError.style.display = 'none'
        employeeImage.classList.remove('error-message')
        employeeImage.classList.add('validated-message')
        return true
      }
    }
  }

  // CHECK ORDER APPEAR
  function checkEmployeeOrderAppear() {
    // ORDER APPEAR PLACEHOLDER
    if (employeeOrderAppear.value) {
      employeeOrderAppear.style.color = '#161616'
    } else {
      employeeOrderAppear.style.color = '#989898'
    }
    if (employeeOrderAppear.value == '') {
      employeeOrderAppearError.style.display = 'block'
      employeeOrderAppear.classList.add('error-message')
      employeeOrderAppearError.innerText = 'The order appear is required.'
      return false
    } else {
      employeeOrderAppearError.innerText = ''
      employeeOrderAppearError.style.display = 'none'
      employeeOrderAppear.classList.remove('error-message')
      employeeOrderAppear.classList.add('validated-message')
      return true
    }
  }

  // CHECK POST
  function checkEmployeePost() {
    // POST PLACEHOLDER
    if (employeePost.value) {
      employeePost.style.color = '#161616'
    } else {
      employeePost.style.color = '#989898'
    }
    if (employeePost.value == '') {
      employeePostError.style.display = 'block'
      employeePost.classList.add('error-message')
      employeePostError.innerText = 'The color of employee is required.'
      return false
    } else {
      employeePostError.innerText = ''
      employeePostError.style.display = 'none'
      employeePost.classList.remove('error-message')
      employeePost.classList.add('validated-message')
      return true
    }
  }
})()
