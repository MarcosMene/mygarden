;(function () {
  // DAY WEEK
  let shopDay = document.getElementById('shop_time_day')
  let shopDayError = document.getElementById('shopDay_error')
  let errorMessages = document.getElementById('error_messages')

  // START TIME HOUR
  let openTimeHour = document.getElementById('shop_time_openTime_hour')
  let openTimeHourError = document.getElementById('openTime_error')
  let openHour = document.getElementById('shop_time_openTime')

  // START TIME MINUTE
  let openTimeMinute = document.getElementById('shop_time_openTime_minute')
  // let openTimeMinuteError = document.getElementById('openTime_error')

  // CLOSE TIME HOUR
  let closeTimeHour = document.getElementById('shop_time_closeTime_hour')
  let closeTimeHourError = document.getElementById('closeTime_error')
  let closeHour = document.getElementById('shop_time_closeTime')

  // CLOSE TIME MINUTE
  let closeTimeMinute = document.getElementById('shop_time_closeTime_minute')
  // let closeTimeHourError = document.getElementById('closeTime_error')

  // SUBMIT BUTTON
  let submitButton = document.getElementById('schedule_submit')

  shopDay.addEventListener('change', () => {
    checkDay()
  })
  openTimeHour.addEventListener('change', () => {
    checkOpenTimeHour()
  })
  openTimeMinute.addEventListener('change', () => {
    checkOpenTimeMinute()
  })
  closeTimeHour.addEventListener('change', () => {
    checkCloseTimeHour()
  })
  closeTimeMinute.addEventListener('change', () => {
    checkCloseTimeMinute()
  })

  submitButton.addEventListener('click', (event) => {
    if (
      !checkDay() &&
      !checkOpenTimeHour() &&
      !checkOpenTimeMinute() &&
      !checkCloseTimeHour() &&
      !checkCloseTimeMinute()
    ) {
      errorMessages.classList.remove('hidden')
      errorMessages.innerText = 'Please fill in all the fields'
    } else {
      errorMessages.classList.add('hidden')
    }

    if (!checkDay()) {
      event.preventDefault()
      shopDayError.style.display = 'block'
      shopDay.classList.add('error-message')
      shopDayError.innerText = 'The day is required.'
    }

    if (!checkOpenTimeHour()) {
      event.preventDefault()
      openTimeHourError.style.display = 'block'
      openHour.classList.add('error-message')
      openTimeHourError.innerText = 'Hour and minutes are required.'
    }

    if (!checkCloseTimeHour()) {
      event.preventDefault()
      closeTimeHourError.style.display = 'block'
      closeHour.classList.add('error-message')
      closeTimeHourError.innerText = 'Hour and minutes are required.'
    }
    if (!checkOpenTimeMinute()) {
      event.preventDefault()
      openTimeHourError.style.display = 'block'
      openHour.classList.add('error-message')
      openTimeHourError.innerText = 'Hour and minutes are required.'
    }
    if (!checkCloseTimeMinute()) {
      event.preventDefault()
      closeTimeHourError.style.display = 'block'
      closeHour.classList.add('error-message')
      closeTimeHourError.innerText = 'Hour and minutes are required.'
    }
  })

  // CHECK DAY
  function checkDay() {
    // DAY PLACEHOLDER
    if (shopDay.value && shopDay.length != 0) {
      shopDay.style.color = '#161616'
    } else {
      shopDay.style.color = '#989898'
    }

    if (shopDay.value == '') {
      shopDayError.style.display = 'block'
      shopDay.classList.add('error-message')
      shopDayError.innerText = 'The day of week is required.'
      return false
    } else {
      shopDayError.innerText = ''
      shopDayError.style.display = 'none'
      shopDay.classList.remove('error-message')
      shopDay.classList.add('validated-message')
      return true
    }
  }

  // CHECK OPEN TIME HOUR
  function checkOpenTimeHour() {
    // OPEN TIME HOUR PLACEHOLDER
    if (openTimeHour.value && openTimeHour.length != 0) {
      openTimeHour.style.color = '#161616'
    } else {
      openTimeHour.style.color = '#989898'
    }

    if (openTimeHour.value == '') {
      openTimeHourError.style.display = 'block'
      openHour.classList.add('error-message')
      openTimeHourError.innerText = 'The hour and minutes are required.'
      return false
    } else {
      openTimeHourError.innerText = ''
      openTimeHourError.style.display = 'none'
      openHour.classList.remove('error-message')
      openHour.classList.add('validated-message')
      return true
    }
  }
  // CHECK OPEN TIME MINUTE
  function checkOpenTimeMinute() {
    // OPEN TIME HOUR PLACEHOLDER
    if (openTimeMinute.value && openTimeMinute.length != 0) {
      openTimeMinute.style.color = '#161616'
    } else {
      openTimeMinute.style.color = '#989898'
    }

    if (openTimeMinute.value == '') {
      openTimeHourError.style.display = 'block'
      openHour.classList.add('error-message')
      openTimeHourError.innerText = 'The hour and minutes are required.'
      return false
    } else {
      openTimeHourError.innerText = ''
      openTimeHourError.style.display = 'none'
      openHour.classList.remove('error-message')
      openHour.classList.add('validated-message')
      return true
    }
  }

  // CHECK CLOSE TIME HOUR
  function checkCloseTimeHour() {
    // CLOSE TIME HOUR PLACEHOLDER
    if (closeTimeHour.value && closeTimeHour.length != 0) {
      closeTimeHour.style.color = '#161616'
    } else {
      closeTimeHour.style.color = '#989898'
    }

    if (closeTimeHour.value == '') {
      closeTimeHourError.style.display = 'block'
      closeHour.classList.add('error-message')
      closeTimeHourError.innerText = 'The hour and minutes are required.'
      return false
    } else {
      closeTimeHourError.innerText = ''
      closeTimeHourError.style.display = 'none'
      closeHour.classList.remove('error-message')
      closeHour.classList.add('validated-message')
      return true
    }
  }
  // CHECK CLOSE TIME MINUTE
  function checkCloseTimeMinute() {
    // CLOSE TIME MINUTE PLACEHOLDER
    if (closeTimeMinute.value && closeTimeMinute.length != 0) {
      closeTimeMinute.style.color = '#161616'
    } else {
      closeTimeMinute.style.color = '#989898'
    }

    if (closeTimeMinute.value == '') {
      closeTimeHourError.style.display = 'block'
      closeHour.classList.add('error-message')
      closeTimeHourError.innerText = 'The hour and minutes are required.'
      return false
    } else {
      closeTimeHourError.innerText = ''
      closeTimeHourError.style.display = 'none'
      closeHour.classList.remove('error-message')
      closeHour.classList.add('validated-message')
      return true
    }
  }
})()