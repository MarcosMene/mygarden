document.addEventListener('DOMContentLoaded', function () {
  // NAME PRODUCT
  const galleryName = document.getElementById('gallery_name');
  const galleryNameError = document.getElementById('galleryName_error');
  const errorMessages = document.getElementById('error_messages');

  // IMAGE PRODUCT
  const galleryImage = document.getElementById('gallery_imageFile_file');
  const galleryImageError = document.getElementById('galleryImage_error');

  // SUBMIT BUTTON
  const submitButton = document.getElementById('gallery_submit');

  galleryName.addEventListener('input', () => {
    checkProductName();
  });

  galleryImage.addEventListener('change', () => {
    checkProductImage();
  });

  submitButton.addEventListener('click', (event) => {
    if (!checkProductName() && !checkProductImage()) {
      errorMessages.classList.remove('hidden');
      errorMessages.innerText = 'Please fill in all the fields';
    } else {
      errorMessages.classList.add('hidden');
    }

    if (!checkProductName()) {
      event.preventDefault();
      galleryNameError.style.display = 'block';
      galleryName.classList.add('error-message');
      galleryNameError.innerText = 'The name of gallery is required.';
    }

    if (!checkProductImage()) {
      event.preventDefault();
      galleryImageError.style.display = 'block';
      galleryImage.classList.add('error-message');
      galleryImageError.innerText = 'The image is required.';
    }
  });

  // CHECK PRODUCT NAME
  function checkProductName() {
    galleryName.classList.remove('is-invalid');
    galleryName.classList.remove('error-message');

    //VALIDATION PRODUCT
    const patternProductName = /^[a-zA-ZÀ-ÿ0-9_\-\s]{2,40}$/;
    const galleryname = galleryName.value;
    const validProductName = patternProductName.test(galleryname);
    if (galleryname.trim() === '') {
      galleryNameError.style.display = 'block';
      galleryName.classList.add('error-message');
      galleryNameError.innerText = 'The name of the gallery is required.';
      return false;
    }
    if (galleryname.length < 3 || galleryname.length > 40) {
      galleryNameError.style.display = 'block';
      galleryName.classList.add('error-message');
      galleryNameError.innerText =
        'The name of the gallery must be at least 3 characters, and maximum 40 characters.';
      return false;
    }
    if (!validProductName) {
      galleryNameError.style.display = 'block';
      galleryName.classList.add('error-message');
      galleryNameError.innerText =
        'The name of the gallery can only contain constters, spaces, or hyphens';
      return false;
    } else {
      galleryNameError.innerText = '';
      galleryNameError.style.display = 'none';
      galleryName.classList.remove('error-message');
      galleryName.classList.add('validated-message');
      return true;
    }
  }

  // CHECK IMAGE
  function checkProductImage() {
    const vichImage = document.querySelector('.vich-image');

    // CHECK IF THE VICHIMAGE CONTAINS ANY ELEMENT WITH AN HREF ATTRIBUTE (LIKE AN <A> TAG)
    const hasHrefChild = vichImage.querySelector('[href]') !== null;

    if (hasHrefChild) {
      return true;
    } else {
      if (!galleryImage.files[0]) {
        galleryImageError.style.display = 'block';
        galleryImage.classList.add('error-message');
        galleryImageError.innerText = 'The image of the gallery is required.';
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
      if (!allowedTypes.includes(galleryImage.files[0].type)) {
        galleryImageError.style.display = 'block';
        galleryImage.classList.add('error-message');
        galleryImageError.innerText =
          'Invalid file type! Please upload a JPG, JPEG, PNG, or WEBP image.';
        return false;
      }

      // CHECK FILE SIZE (MAX SIZE SET TO 1MB IN THIS EXAMPLE)
      const maxSizeInBytes = 1 * 1024 * 1024; // 1MB
      if (galleryImage.files[0].size > maxSizeInBytes) {
        galleryImageError.style.display = 'block';
        galleryImage.classList.add('error-message');
        galleryImageError.innerText = 'File is too large! Maximum size is 1MB.';
        return false;
      } else {
        galleryImageError.innerText = '';
        galleryImageError.style.display = 'none';
        galleryImage.classList.remove('error-message');
        galleryImage.classList.add('validated-message');
        return true;
      }
    }
  }
});
