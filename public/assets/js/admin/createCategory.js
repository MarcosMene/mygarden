document.addEventListener('DOMContentLoaded', function () {
  // NAME CATEGORY
  const categoryName = document.getElementById('category_name');
  const categoryNameError = document.getElementById('categoryName_error');
  const errorMessages = document.getElementById('error_messages');

  // IMAGE CATEGORY
  const categoryImage = document.getElementById('category_imageFile_file');
  const categoryImageError = document.getElementById('categoryImage_error');

  // SUBMIT BUTTON
  const submitButton = document.getElementById('category_submit');

  categoryName.addEventListener('input', () => {
    checkcategoryName();
  });

  categoryImage.addEventListener('change', () => {
    checkcategoryImage();
  });

  submitButton.addEventListener('click', (event) => {
    if (!checkcategoryName() && !checkcategoryImage()) {
      errorMessages.classList.remove('hidden');
      errorMessages.innerText = 'Please fill in all the fields';
    } else {
      errorMessages.classList.add('hidden');
    }

    if (!checkcategoryName()) {
      event.preventDefault();
      categoryNameError.style.display = 'block';
      categoryName.classList.add('error-message');
      categoryNameError.innerText = 'The name of the category is required.';
    }
    if (!checkcategoryImage()) {
      event.preventDefault();
      categoryImageError.style.display = 'block';
      categoryImage.classList.add('error-message');
      categoryImageError.innerText = 'The image of the category is required.';
    }
  });

  // CHECK CATEGORY NAME
  function checkcategoryName() {
    categoryName.classList.remove('is-invalid');
    categoryName.classList.remove('error-message');

    //VALIDATION CATEGORY
    const patterncategoryName = /^[a-zA-ZÀ-ÿ0-9_\'\-\s]{2,40}$/;
    const categoryname = categoryName.value;
    const validcategoryName = patterncategoryName.test(categoryname);
    if (categoryname.trim() === '') {
      categoryNameError.style.display = 'block';
      categoryName.classList.add('error-message');
      categoryNameError.innerText = 'The name of the category is required.';
      return false;
    }
    if (categoryname.length < 3 || categoryname.length > 40) {
      categoryNameError.style.display = 'block';
      categoryName.classList.add('error-message');
      categoryNameError.innerText =
        'The name of the category must be at least 3 characters, and maximum 40 characters.';
      return false;
    }
    if (!validcategoryName) {
      categoryNameError.style.display = 'block';
      categoryName.classList.add('error-message');
      categoryNameError.innerText =
        'The name of the category can only contain constters, spaces, or hyphens';
      return false;
    } else {
      categoryNameError.innerText = '';
      categoryNameError.style.display = 'none';
      categoryName.classList.remove('error-message');
      categoryName.classList.add('validated-message');
      return true;
    }
  }

  // CHECK IMAGE
  function checkcategoryImage() {
    const vichImage = document.querySelector('.vich-image');

    // CHECK IF THE VICHIMAGE CONTAINS ANY ELEMENT WITH AN HREF ATTRIBUTE (LIKE AN <A> TAG)
    const hasHrefChild = vichImage.querySelector('[href]') !== null;

    if (hasHrefChild) {
      return true;
    } else {
      if (!categoryImage.files[0]) {
        categoryImageError.style.display = 'block';
        categoryImage.classList.add('error-message');
        categoryImageError.innerText = 'The image of the category is required.';
        return false;
      }
      // ALLOWED FILE TYPES
      const allowedTypes = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
      ];

      // CHECK FILE TYPE
      if (!allowedTypes.includes(categoryImage.files[0].type)) {
        categoryImageError.style.display = 'block';
        categoryImage.classList.add('error-message');
        categoryImageError.innerText =
          'Invalid file type! Please upload a JPG, JPEG, PNG, or WEBP image.';
        return false;
      }

      // CHECK FILE SIZE (MAX SIZE SET TO 1MB IN THIS EXAMPLE)
      const maxSizeInBytes = 1 * 1024 * 1024; // 1MB
      if (categoryImage.files[0].size > maxSizeInBytes) {
        categoryImageError.style.display = 'block';
        categoryImage.classList.add('error-message');
        categoryImageError.innerText =
          'File is too large! Maximum size is 1MB.';
        return false;
      } else {
        categoryImageError.innerText = '';
        categoryImageError.style.display = 'none';
        categoryImage.classList.remove('error-message');
        categoryImage.classList.add('validated-message');
        return true;
      }
    }
  }
});
