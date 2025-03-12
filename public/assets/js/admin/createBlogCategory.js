document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('categoryForm');
  const submitButton = document.getElementById('category_submit');
  const errorMessages = document.getElementById('error_messages');

  // VALIDATE CATEGORY
  const categoryInput = document.getElementById('blog_category_name');
  const categoryError = document.getElementById('blogCategory_error');

  categoryInput.addEventListener('input', () => checkCategory());

  function checkCategory() {
    //CLEAN ERROR MESSAGES
    categoryInput.classList.remove('error-message', 'validated-message');
    categoryError.style.display = 'none';

    //GET VALUE OF category
    let blogcategory = categoryInput.value;

    //DELETE SPACES OF category
    if (blogcategory.trim() === '') {
      categoryError.style.display = 'block';
      categoryInput.classList.add('error-message');
      categoryError.innerText = 'The category is required.';
      return false;
    }

    //REGEX
    let patternBlogcategory = /^[a-zA-ZÀ-ÿ0-9_\-\s]{3,}$/;
    let validBlogcategory = patternBlogcategory.test(blogcategory);

    if (!validBlogcategory) {
      categoryError.style.display = 'block';
      categoryInput.classList.add('error-message');
      categoryError.innerText =
        'The category can only contain letters or numbers';
      return false;
    }

    if (blogcategory.length < 3 || blogcategory.length > 20) {
      categoryError.style.display = 'block';
      categoryInput.classList.add('error-message');
      categoryError.innerText =
        'The category must be between 3 and 20 characters long.';
      return false;
    }

    categoryInput.classList.add('validated-message');
    return true;
  }

  // VALIDATE THE FORM
  function validateForm() {
    let isValid = true;

    if (!checkCategory()) {
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
