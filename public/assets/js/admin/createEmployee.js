document.addEventListener('DOMContentLoaded', function () {
  // FIRST NAME EMPLOYEE
  const employeeFirstName = document.getElementById('employee_firstName');
  const employeeFirstNameError = document.getElementById(
    'employeeFirstName_error'
  );
  const errorMessages = document.getElementById('error_messages');

  // LAST NAME EMPLOYEE
  const employeeLastName = document.getElementById('employee_lastName');
  const employeeLastNameError = document.getElementById(
    'employeeLastName_error'
  );

  // IMAGE EMPLOYEE
  const employeeImage = document.getElementById('employee_imageFile_file');
  const employeeImageError = document.getElementById('employeeImage_error');

  // ORDER APPEAR EMPLOYEE
  const employeeOrderAppear = document.getElementById('employee_orderAppear');
  const employeeOrderAppearError = document.getElementById(
    'employeeOrderAppear_error'
  );

  // POST EMPLOYEE
  const employeePost = document.getElementById('employee_employeePost');
  const employeePostError = document.getElementById('employeePost_error');

  // SUBMIT BUTTON
  const submitButton = document.getElementById('employee_submit');

  employeeFirstName.addEventListener('input', () => {
    checkEmployeeFirstName();
  });
  employeeLastName.addEventListener('input', () => {
    checkEmployeeLastName();
  });

  employeeImage.addEventListener('change', () => {
    checkEmployeeImage();
  });

  employeeOrderAppear.addEventListener('change', () => {
    checkEmployeeOrderAppear();
  });
  employeePost.addEventListener('change', () => {
    checkEmployeePost();
  });

  submitButton.addEventListener('click', (event) => {
    if (
      !checkEmployeeFirstName() &&
      !checkEmployeeLastName() &&
      !checkEmployeeImage() &&
      !checkEmployeeOrderAppear() &&
      !checkEmployeePost()
    ) {
      errorMessages.classList.remove('hidden');
      errorMessages.innerText = 'Please fill in all the fields';
    } else {
      errorMessages.classList.add('hidden');
    }

    if (!checkEmployeeFirstName()) {
      event.preventDefault();
      employeeFirstNameError.style.display = 'block';
      employeeFirstName.classList.add('error-message');
      employeeFirstNameError.innerText =
        'The first name of employee is required.';
    }
    if (!checkEmployeeLastName()) {
      event.preventDefault();
      employeeLastNameError.style.display = 'block';
      employeeLastName.classList.add('error-message');
      employeeLastNameError.innerText =
        'The last name of the employee is required.';
    }
    if (!checkEmployeeOrderAppear()) {
      event.preventDefault();
      employeeOrderAppearError.style.display = 'block';
      employeeOrderAppear.classList.add('error-message');
      employeeOrderAppearError.innerText = 'The order appear is required.';
    }
    if (!checkEmployeePost()) {
      event.preventDefault();
      employeePostError.style.display = 'block';
      employeePost.classList.add('error-message');
      employeePostError.innerText = 'The post is required.';
    }

    if (!checkEmployeeImage()) {
      event.preventDefault();
      employeeImageError.style.display = 'block';
      employeeImage.classList.add('error-message');
      employeeImageError.innerText = 'The image is required.';
    }
  });

  // FUNCTION FIRST NAME
  function checkEmployeeFirstName() {
    const patternEmployeeFirstName = /[a-zA-ZÀ-ÿ\s\-]{3,26}/;
    const employfirstname = employeeFirstName.value;
    const validEmployeeFirstName =
      patternEmployeeFirstName.test(employfirstname);
    if (employfirstname.trim() === '') {
      employeeFirstNameError.style.display = 'block';
      employeeFirstName.classList.add('error-message');
      employeeFirstNameError.innerText = 'The employee first name is required.';
      return false;
    } else if (employfirstname.length < 3 || employfirstname.length > 25) {
      employeeFirstNameError.style.display = 'block';
      employeeFirstName.classList.add('error-message');
      employeeFirstNameError.innerText =
        'The employee first name must be at least 3 characters long';
      return false;
    } else if (!validEmployeeFirstName) {
      employeeFirstNameError.style.display = 'block';
      employeeFirstNameError.innerText =
        'First name can only contain constters, spaces, or hyphens';
      return false;
    } else {
      employeeFirstNameError.innerText = '';
      employeeFirstNameError.style.display = 'none';
      employeeFirstName.classList.remove('error-message');
      employeeFirstName.classList.add('validated-message');
      return true;
    }
  }

  // FUNCTION LAST NAME
  function checkEmployeeLastName() {
    const patternEmployeeLastName = /[a-zA-ZÀ-ÿ\s\-]{3,26}/;
    const employfirstname = employeeLastName.value;
    const validEmployeeLastName = patternEmployeeLastName.test(employfirstname);
    if (employfirstname.trim() === '') {
      employeeLastNameError.style.display = 'block';
      employeeLastName.classList.add('error-message');
      employeeLastNameError.innerText = 'The employee first name is required.';
      return false;
    } else if (employfirstname.length < 3 || employfirstname.length > 25) {
      employeeLastNameError.style.display = 'block';
      employeeLastName.classList.add('error-message');
      employeeLastNameError.innerText =
        'The employee first name must be at least 3 characters long';
      return false;
    } else if (!validEmployeeLastName) {
      employeeLastNameError.style.display = 'block';
      employeeLastNameError.innerText =
        'Last name can only contain constters, spaces, or hyphens';
      return false;
    } else {
      employeeLastNameError.innerText = '';
      employeeLastNameError.style.display = 'none';
      employeeLastName.classList.remove('error-message');
      employeeLastName.classList.add('validated-message');
      return true;
    }
  }

  // CHECK IMAGE
  function checkEmployeeImage() {
    const vichImage = document.querySelector('.vich-image');

    // CHECK IF THE VICHIMAGE CONTAINS ANY ELEMENT WITH AN HREF ATTRIBUTE (LIKE AN <A> TAG)
    const hasHrefChild = vichImage.querySelector('[href]') !== null;

    if (hasHrefChild) {
      return true;
    } else {
      if (!employeeImage.files[0]) {
        employeeImageError.style.display = 'block';
        employeeImage.classList.add('error-message');
        employeeImageError.innerText = 'The image of the employee is required.';
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
      if (!allowedTypes.includes(employeeImage.files[0].type)) {
        employeeImageError.style.display = 'block';
        employeeImage.classList.add('error-message');
        employeeImageError.innerText =
          'Invalid file type! Please upload a JPG, JPEG, PNG, or WEBP image.';
        return false;
      }

      // CHECK FILE SIZE (MAX SIZE SET TO 1MB IN THIS EXAMPLE)
      const maxSizeInBytes = 1 * 1024 * 1024; // 1MB
      if (employeeImage.files[0].size > maxSizeInBytes) {
        employeeImageError.style.display = 'block';
        employeeImage.classList.add('error-message');
        employeeImageError.innerText =
          'File is too large! Maximum size is 1MB.';
        return false;
      } else {
        employeeImageError.innerText = '';
        employeeImageError.style.display = 'none';
        employeeImage.classList.remove('error-message');
        employeeImage.classList.add('validated-message');
        return true;
      }
    }
  }

  // CHECK ORDER APPEAR
  function checkEmployeeOrderAppear() {
    // ORDER APPEAR PLACEHOLDER
    if (employeeOrderAppear.value) {
      employeeOrderAppear.style.color = '#161616';
    } else {
      employeeOrderAppear.style.color = '#989898';
    }
    if (employeeOrderAppear.value == '') {
      employeeOrderAppearError.style.display = 'block';
      employeeOrderAppear.classList.add('error-message');
      employeeOrderAppearError.innerText = 'The order appear is required.';
      return false;
    } else {
      employeeOrderAppearError.innerText = '';
      employeeOrderAppearError.style.display = 'none';
      employeeOrderAppear.classList.remove('error-message');
      employeeOrderAppear.classList.add('validated-message');
      return true;
    }
  }

  // CHECK POST
  function checkEmployeePost() {
    // POST PLACEHOLDER
    if (employeePost.value) {
      employeePost.style.color = '#161616';
    } else {
      employeePost.style.color = '#989898';
    }
    if (employeePost.value == '') {
      employeePostError.style.display = 'block';
      employeePost.classList.add('error-message');
      employeePostError.innerText = 'The color of employee is required.';
      return false;
    } else {
      employeePostError.innerText = '';
      employeePostError.style.display = 'none';
      employeePost.classList.remove('error-message');
      employeePost.classList.add('validated-message');
      return true;
    }
  }
});
