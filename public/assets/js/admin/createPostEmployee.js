document.addEventListener('DOMContentLoaded', function () {
  // NAME POST
  const postName = document.getElementById('posts_name');
  const postNameError = document.getElementById('postName_error');
  const errorMessages = document.getElementById('error_messages');

  // SUBMIT BUTTON
  const submitButton = document.getElementById('posts_submit');

  postName.addEventListener('input', () => {
    checkPostEmployeeName();
  });

  submitButton.addEventListener('click', (e) => {
    if (!checkPostEmployeeName()) {
      e.preventDefault();
      errorMessages.classList.remove('hidden');
      errorMessages.innerText = 'Please fill the post field';
      postNameError.style.display = 'block';
      postName.classList.add('error-message');
      postNameError.innerText = 'The post is required.';
    } else {
      errorMessages.classList.add('hidden');
    }
  });

  // CHECK POST NAME
  function checkPostEmployeeName() {
    postName.classList.remove('is-invalid');
    postName.classList.remove('error-message');

    // VALIDATION POST
    const patternPostEmployeeName = /^[a-zA-ZÀ-ÿ0-9_\-\s]{2,40}$/;
    const postname = postName.value;
    const validcolorName = patternPostEmployeeName.test(postname);
    if (postname.trim() === '') {
      postNameError.style.display = 'block';
      postName.classList.add('error-message');
      postNameError.innerText = 'The name of the post is required.';
      return false;
    } else if (postname.length < 3 || postname.length > 40) {
      postNameError.style.display = 'block';
      postName.classList.add('error-message');
      postNameError.innerText =
        'The post must be at least 3 characters, and maximum 40 characters.';
      return false;
    } else if (!validcolorName) {
      postNameError.style.display = 'block';
      postName.classList.add('error-message');
      postNameError.innerText =
        'The post can only contain constters, spaces, or hyphens';
      return false;
    } else {
      postNameError.innerText = '';
      postNameError.style.display = 'none';
      postName.classList.remove('error-message');
      postName.classList.add('validated-message');
      return true;
    }
  }
});
