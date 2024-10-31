;(function () {
  let errorMessages = document.getElementById('error_messages')

  // NAME CLIENT
  let clientFirstName = document.getElementById('review_client_firstName')
  let clientFirstNameError = document.getElementById('clientFirstName_error')

  // RATE OF CLIENT
  let rateClient = document.getElementById('review_client_rate')
  let rateClientError = document.getElementById('rateClient_error')

  // COMMENT CLIENT
  let commentClient = document.getElementById('review_client_comment')
  let commentClientError = document.getElementById('commentClient_error')

  // SUBMIT BUTTON
  let submitButton = document.getElementById('review_submit')

  clientFirstName.addEventListener('input', () => {
    checkClientFirstName()
  })

  rateClient.addEventListener('change', () => {
    checkRateClient()
  })

  commentClient.addEventListener('input', () => {
    checkCommentClient()
  })

  submitButton.addEventListener('click', (event) => {
    if (
      !checkClientFirstName() &&
      !checkRateClient() &&
      !checkCommentClient()
    ) {
      errorMessages.classList.remove('hidden')
      errorMessages.innerText = 'Please fill in all the fields'
    } else {
      errorMessages.classList.add('hidden')
    }

    if (!checkClientFirstName()) {
      event.preventDefault()
      clientFirstNameError.style.display = 'block'
      clientFirstName.classList.add('error-message')
      clientFirstNameError.innerText = 'First name is required.'
    }

    if (!checkRateClient()) {
      event.preventDefault()
      rateClientError.style.display = 'block'
      rateClient.classList.add('error-message')
      rateClientError.innerText = 'The rate is required.'
    }

    if (!checkCommentClient()) {
      event.preventDefault()
      commentClientError.style.display = 'block'
      commentClient.classList.add('error-message')
      commentClientError.innerText = 'Your comment is required.'
    }
  })

  // CHECK CLIENT NAME
  function checkClientFirstName() {

    let patterClientFirstName = /^[a-zA-ZÀ-ÿ0-9_\-\s]{3,40}$/
    let clientfirstname = clientFirstName.value
    let validClientFirstName = patterClientFirstName.test(clientfirstname)
    if (clientfirstname.trim() === '') {
      clientFirstNameError.style.display = 'block'
      clientFirstName.classList.add('error-message')
      clientFirstNameError.innerText = 'Your name is required.'
      return false
    }
    else if (clientfirstname.length < 3 || clientfirstname.length > 40) {
      clientFirstNameError.style.display = 'block'
      clientFirstName.classList.add('error-message')
      clientFirstNameError.innerText =
        'Your name must be at least 3 characters, and maximum 40 characters.'
      return false
    }
    else if (!validClientFirstName) {
      clientFirstNameError.style.display = 'block'
      clientFirstName.classList.add('error-message')
      clientFirstNameError.innerText =
        'Your name can only contain letters, spaces, and hyphens'
      return false
    } else {
      clientFirstNameError.innerText = ''
      clientFirstNameError.style.display = 'none'
      clientFirstName.classList.remove('error-message')
      clientFirstName.classList.add('validated-message')
      return true
    }
  }

  // CHECK CLIENT RATE
  function checkRateClient() {
    // RATE PLACEHOLDER
    if (rateClient.value) {
      rateClient.style.color = '#161616'
    } else {
      rateClient.style.color = '#989898'
    }
    if (rateClient.value == '') {
      rateClientError.style.display = 'block'
      rateClient.classList.add('error-message')
      rateClientError.innerText = 'The rate is required.'
      return false
    } else {
      rateClientError.innerText = ''
      rateClientError.style.display = 'none'
      rateClient.classList.remove('error-message')
      rateClient.classList.add('validated-message')
      return true
    }
  }

  // CHECK CLIENT COMMENT
  function checkCommentClient() {
    let patternCommentClient = /^[a-zA-ZÀ-ÿ0-9_\.,!?\-\s]{20,150}$/
    let commentclient = commentClient.value
    let validCommentClient = patternCommentClient.test(commentclient)
    if (commentclient.trim() === '') {
      commentClientError.style.display = 'block'
      commentClient.classList.add('error-message')
      commentClientError.innerText =
        'Your comment is required.'
      return false
    } else if (commentclient.length < 20 || commentclient.length > 150) {
      commentClientError.style.display = 'block'
      commentClient.classList.add('error-message')
      commentClientError.innerText =
        'Your comment must be at least 20 characters, and maximum 150 characters.'
      return false
    }
    else if (!validCommentClient) {
      commentClientError.style.display = 'block'
      commentClient.classList.add('error-message')
      commentClientError.innerText =
        'Your comment can only contain letters, spaces, and hyphens.'
      return false
    } else {
      commentClientError.innerText = ''
      commentClientError.style.display = 'none'
      commentClient.classList.remove('error-message')
      commentClient.classList.add('validated-message')
      return true
    }
  }
})()
