;(function () {
  // ADDRESS NAME
  let addressName = document.getElementById('address_user_name')
  let addressNameError = document.getElementById('addressName_error')

  // ADDRESS USER PHONE
  let addressUserPhone = document.getElementById('address_user_phone')
  let addressUserPhoneError = document.getElementById('addressPhone_error')

  // FIRST NAME USER
  let addressUserFirstName = document.getElementById('address_user_firstname')
  let addressUserFirstNameError = document.getElementById(
    'addressUserFirstName_error'
  )

  // LAST NAME USER
  let addressUserLastName = document.getElementById('address_user_lastname')
  let addressUserLastNameError = document.getElementById(
    'addressUserLastName_error'
  )

  // USER COMPANY
  let addressUserCompany = document.getElementById('address_user_company')
  let addressUserCompanyError = document.getElementById(
    'addressUserCompany_error'
  )

  // USER ADDRESS
  let addressUserAddress = document.getElementById('address_user_address')
  let addressUserAddressError = document.getElementById(
    'addressUserAddress_error'
  )

  // USER POSTAL
  let addressUserPostal = document.getElementById('address_user_postal')
  let addressUserPostalError = document.getElementById(
    'addressUserPostal_error'
  )

  // USER CITY
  let addressUserCity = document.getElementById('address_user_city')
  let addressUserCityError = document.getElementById('addressUserCity_error')

  let errorMessages = document.getElementById('error_messages')

  // SUBMIT BUTTON
  let submitButton = document.getElementById('addressUser_submit')

  addressName.addEventListener('input', () => {
    checkAddressName()
  })

  addressUserPhone.addEventListener('input', () => {
    checkAddressUserPhone()
  })

  addressUserFirstName.addEventListener('input', () => {
    checkAddressUserFirstName()
  })

  addressUserLastName.addEventListener('input', () => {
    checkAddressUserLastName()
  })

  addressUserCompany.addEventListener('input', () => {
    checkAddressUserCompany()
  })

  addressUserAddress.addEventListener('input', () => {
    checkAddressUserAddress()
  })

  addressUserPostal.addEventListener('input', () => {
    checkAddressUserPostal()
  })

  addressUserCity.addEventListener('input', () => {
    checkAddressUserCity()
  })

  submitButton.addEventListener('click', (event) => {
    if (
      !checkAddressName() &&
      !checkAddressUserPhone() &&
      !checkAddressUserFirstName() &&
      !checkAddressUserLastName() &&
      !checkAddressUserAddress() &&
      !checkAddressUserPostal() &&
      !checkAddressUserCity()
    ) {
      errorMessages.classList.remove('hidden')
      errorMessages.innerText = 'Please fill in all the red fields. Company name is optional.'
    } else {
      errorMessages.classList.add('hidden')
    }

    if (!checkAddressName()) {
      event.preventDefault()
      addressNameError.style.display = 'block'
      addressName.classList.add('error-message')
      addressNameError.innerText = 'The address name is required.'
    }
    if (!checkAddressUserPhone()) {
      event.preventDefault()
      addressUserPhoneError.style.display = 'block'
      addressUserPhone.classList.add('error-message')
      addressUserPhoneError.innerText = 'The phone number is required.'
    }
    if (!checkAddressUserFirstName()) {
      event.preventDefault()
      addressUserFirstNameError.style.display = 'block'
      addressUserFirstName.classList.add('error-message')
      addressUserFirstNameError.innerText = 'The first name is required.'
    }
    if (!checkAddressUserLastName()) {
      event.preventDefault()
      addressUserLastNameError.style.display = 'block'
      addressUserLastName.classList.add('error-message')
      addressUserLastNameError.innerText = 'The last name is required.'
    }

    if (!checkAddressUserCompany()) {
      event.preventDefault()
      addressUserCompanyError.style.display = 'block'
      addressUserCompany.classList.add('error-message')
      addressUserCompanyError.innerText = 'The image is required.'
    }
    if (!checkAddressUserAddress()) {
      event.preventDefault()
      addressUserAddressError.style.display = 'block'
      addressUserAddress.classList.add('error-message')
      addressUserAddressError.innerText = 'The address is required.'
    }
    if (!checkAddressUserPostal()) {
      event.preventDefault()
      addressUserPostalError.style.display = 'block'
      addressUserPostal.classList.add('error-message')
      addressUserPostalError.innerText = 'The zip code is required.'
    }
    if (!checkAddressUserCity()) {
      event.preventDefault()
      addressUserCityError.style.display = 'block'
      addressUserCity.classList.add('error-message')
      addressUserCityError.innerText = 'The city is required.'
    }
  })

  // FUNCTION FIRST NAME
  function checkAddressName() {
    let patternAddressName = /^[a-zA-ZÀ-ÿ\s\-]{3,25}$/
    let patternaddressname = addressName.value
    let valideAddresName = patternAddressName.test(patternaddressname)
    if (patternaddressname.trim() === '') {
      addressNameError.style.display = 'block'
      addressName.classList.add('error-message')
      addressNameError.innerText = 'The address name is required.'
      return false
    } else if (
      patternaddressname.length < 3 ||
      patternaddressname.length > 25
    ) {
      addressNameError.style.display = 'block'
      addressName.classList.add('error-message')
      addressNameError.innerText =
        'The address name must be at least 3 characters long and 25 characters maximum'
      return false
    } else if (!valideAddresName) {
      addressNameError.style.display = 'block'
      addressNameError.innerText =
        'Address name can only contain letters, spaces, or hyphens'
      return false
    } else {
      addressNameError.innerText = ''
      addressNameError.style.display = 'none'
      addressName.classList.remove('error-message')
      addressName.classList.add('validated-message')
      return true
    }
  }

  // FUNCTION USER FIRST NAME
  function checkAddressUserFirstName() {
    let patternUserFirstName = /^[a-zA-ZÀ-ÿ\s\-]{3,25}$/

    let patternuserfirstname = addressUserFirstName.value
    let validUserFirstName = patternUserFirstName.test(patternuserfirstname)
    if (patternuserfirstname.trim() === '') {
      addressUserFirstNameError.style.display = 'block'
      addressUserFirstName.classList.add('error-message')
      addressUserFirstNameError.innerText = 'The first name is required.'
      return false
    } else if (
      patternuserfirstname.length < 3 ||
      patternuserfirstname.length > 25
    ) {
      addressUserFirstNameError.style.display = 'block'
      addressUserFirstName.classList.add('error-message')
      addressUserFirstNameError.innerText =
        'The first name must be at least 3 characters long and 25 characters maximum'
      return false
    } else if (!validUserFirstName) {
      addressUserFirstNameError.style.display = 'block'
      addressUserFirstNameError.innerText =
        'First name can only contain letters, spaces, or hyphens'
      return false
    } else {
      addressUserFirstNameError.innerText = ''
      addressUserFirstNameError.style.display = 'none'
      addressUserFirstName.classList.remove('error-message')
      addressUserFirstName.classList.add('validated-message')
      return true
    }
  }

  // FUNCTION USER LAST NAME
  function checkAddressUserLastName() {
    let patternUserLastName = /^[a-zA-ZÀ-ÿ\s]{3,25}$/
    let patternuserlastname = addressUserLastName.value
    let validUserLastName = patternUserLastName.test(patternuserlastname)
    if (patternuserlastname.trim() === '') {
      addressUserLastNameError.style.display = 'block'
      addressUserLastName.classList.add('error-message')
      addressUserLastNameError.innerText = 'The last name is required.'
      return false
    } else if (
      patternuserlastname.length < 3 ||
      patternuserlastname.length > 25
    ) {
      addressUserLastNameError.style.display = 'block'
      addressUserLastName.classList.add('error-message')
      addressUserLastNameError.innerText =
        'The last name must be at least 3 characters long and 25 characters maximum'
      return false
    } else if (!validUserLastName) {
      addressUserLastNameError.style.display = 'block'
      addressUserLastNameError.innerText =
        'Last name can only contain letters, spaces, or hyphens'
      return false
    } else {
      addressUserLastNameError.innerText = ''
      addressUserLastNameError.style.display = 'none'
      addressUserLastName.classList.remove('error-message')
      addressUserLastName.classList.add('validated-message')
      return true
    }
  }

  // FUNCTION USER COMPANY
  function checkAddressUserCompany() {
    let patternUserCompany = /^[a-zA-ZÀ-ÿ\s\-\0-9]{0,25}$/
    let patternusercompany = addressUserCompany.value
    let validUserCompany = patternUserCompany.test(patternusercompany)
    if (patternusercompany.length < 0 || patternusercompany.length > 25) {
      addressUserCompanyError.style.display = 'block'
      addressUserCompany.classList.add('error-message')
      addressUserCompanyError.innerText =
        'The company name must be at least 3 characters long and 25 characters maximum'
      return false
    } else if (!validUserCompany) {
      addressUserCompanyError.style.display = 'block'
      addressUserCompanyError.innerText =
        'Company name can only contain letters, spaces, or hyphens'
      return false
    } else {
      addressUserCompanyError.innerText = ''
      addressUserCompanyError.style.display = 'none'
      addressUserCompany.classList.remove('error-message')
      addressUserCompany.classList.add('validated-message')
      return true
    }
  }

  // FUNCTION USER ADDRESS
  function checkAddressUserAddress() {
    let patternAddressUserAddress = /^[a-zA-ZÀ-ÿ\s\-\0-9]{15,60}$/
    let patternaddressuseraddress = addressUserAddress.value
    let validAddressUserAddress = patternAddressUserAddress.test(
      patternaddressuseraddress
    )
    if (patternaddressuseraddress.trim() === '') {
      addressUserAddressError.style.display = 'block'
      addressUserAddress.classList.add('error-message')
      addressUserAddressError.innerText = 'The address is required.'
      return false
    } else if (
      patternaddressuseraddress.length < 15 ||
      patternaddressuseraddress.length > 60
    ) {
      addressUserAddressError.style.display = 'block'
      addressUserAddress.classList.add('error-message')
      addressUserAddressError.innerText =
        'The address must be at least 15 characters long and 60 characters maximum'
      return false
    } else if (!validAddressUserAddress) {
      addressUserAddressError.style.display = 'block'
      addressUserAddressError.innerText =
        'Address can only contain letters, spaces, or hyphens'
      return false
    } else {
      addressUserAddressError.innerText = ''
      addressUserAddressError.style.display = 'none'
      addressUserAddress.classList.remove('error-message')
      addressUserAddress.classList.add('validated-message')
      return true
    }
  }

  // FUNCTION USER POSTAL
  function checkAddressUserPostal() {
    let patternAddressUserPostal = /^[a-zA-Z0-9]{5,10}$/
    let patternaddressuserpostal = addressUserPostal.value
    let validAddressUserPostal = patternAddressUserPostal.test(
      patternaddressuserpostal
    )
    if (patternaddressuserpostal.trim() === '') {
      addressUserPostalError.style.display = 'block'
      addressUserPostal.classList.add('error-message')
      addressUserPostalError.innerText = 'The postal is required.'
      return false
    } else if (
      patternaddressuserpostal.length < 5 ||
      patternaddressuserpostal.length > 10
    ) {
      addressUserPostalError.style.display = 'block'
      addressUserPostal.classList.add('error-message')
      addressUserPostalError.innerText =
        'The postal must be at least 5 characters long and 10 characters maximum'
      return false
    } else if (!validAddressUserPostal) {
      addressUserPostalError.style.display = 'block'
      addressUserPostalError.innerText =
        'Postal can only contain letters, spaces, or hyphens'
      return false
    } else {
      addressUserPostalError.innerText = ''
      addressUserPostalError.style.display = 'none'
      addressUserPostal.classList.remove('error-message')
      addressUserPostal.classList.add('validated-message')
      return true
    }
  }

  // FUNCTION USER CITY
  function checkAddressUserCity() {
    let patternAddressUserCity = /^[a-zA-ZÀ-ÿ\s\-]{3,25}$/
    let patternaddressusercity = addressUserCity.value
    let validAddressUserCity = patternAddressUserCity.test(
      patternaddressusercity
    )
    if (patternaddressusercity.trim() === '') {
      addressUserCityError.style.display = 'block'
      addressUserCity.classList.add('error-message')
      addressUserCityError.innerText = 'The city is required.'
      return false
    } else if (
      patternaddressusercity.length < 3 ||
      patternaddressusercity.length > 25
    ) {
      addressUserCityError.style.display = 'block'
      addressUserCity.classList.add('error-message')
      addressUserCityError.innerText =
        'The city must be at least 3 characters long and 25 characters characters maximum'
      return false
    } else if (!validAddressUserCity) {
      addressUserCityError.style.display = 'block'
      addressUserCityError.innerText =
        'City can only contain letters, spaces, or hyphens'
      return false
    } else {
      addressUserCityError.innerText = ''
      addressUserCityError.style.display = 'none'
      addressUserCity.classList.remove('error-message')
      addressUserCity.classList.add('validated-message')
      return true
    }
  }

  // FUNCTION USER PHONE
  function checkAddressUserPhone() {
    let patternUserPhone = /^[\+\(\s.\-\/\d\)]{10,25}$/
    let patternuserphone = addressUserPhone.value
    let valideUserPhone = patternUserPhone.test(patternuserphone)
    if (patternuserphone.trim() === '') {
      addressUserPhoneError.style.display = 'block'
      addressUserPhone.classList.add('error-message')
      addressUserPhoneError.innerText = 'The phone is required.'
      return false
    } else if (patternuserphone.length < 10 || patternuserphone.length > 25) {
      addressUserPhoneError.style.display = 'block'
      addressUserPhone.classList.add('error-message')
      addressUserPhoneError.innerText =
        'The phone must be at least 10 characters long and 25 characters maximum'
      return false
    } else if (!valideUserPhone) {
      addressUserPhoneError.style.display = 'block'
      addressUserPhoneError.innerText =
        'Phone can only contain numbers, + symbol or hyphens'
      return false
    } else {
      addressUserPhoneError.innerText = ''
      addressUserPhoneError.style.display = 'none'
      addressUserPhone.classList.remove('error-message')
      addressUserPhone.classList.add('validated-message')
      return true
    }
  }
})()
