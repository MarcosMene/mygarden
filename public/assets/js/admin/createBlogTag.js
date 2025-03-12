document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('tagForm');
  const submitButton = document.getElementById('tag_submit');
  const errorMessages = document.getElementById('error_messages');

  // VALIDATE TAG
  const tagInput = document.getElementById('blog_tag_name');
  const tagError = document.getElementById('blogTag_error');

  tagInput.addEventListener('input', () => checktag());

  function checktag() {
    //CLEAN ERROR MESSAGES
    tagInput.classList.remove('error-message', 'validated-message');
    tagError.style.display = 'none';

    //GET VALUE OF TAG
    let blogtag = tagInput.value;

    //DELETE SPACES OF TAG
    if (blogtag.trim() === '') {
      tagError.style.display = 'block';
      tagInput.classList.add('error-message');
      tagError.innerText = 'The tag is required.';
      return false;
    }

    //REGEX
    let patternBlogtag = /^[a-zA-ZÀ-ÿ0-9]{3,}$/;
    let validBlogtag = patternBlogtag.test(blogtag);

    if (!validBlogtag) {
      tagError.style.display = 'block';
      tagInput.classList.add('error-message');
      tagError.innerText = 'The tag can only contain letters or numbers';
      return false;
    }

    if (blogtag.length < 3 || blogtag.length > 15) {
      tagError.style.display = 'block';
      tagInput.classList.add('error-message');
      tagError.innerText =
        'The title should be between 3 and 15 characters long.';
      return false;
    }

    tagInput.classList.add('validated-message');
    return true;
  }

  // VALIDATE THE FORM
  function validateForm() {
    let isValid = true;

    if (!checktag()) {
      isValid = false;
    }
    return isValid;
  }

  // SAVE BUTTON
  submitButton.addEventListener('click', function (event) {
    event.preventDefault();
    if (validateForm()) {
      form.submit();
    } else {
      errorMessages.classList.remove('hidden');
      errorMessages.innerText = 'Please fill in all the fields';
    }
  });
});
