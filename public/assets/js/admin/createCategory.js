// NAME CATEGORY
var categoryName = document.getElementById('category_name')
var categoryNameError = document.getElementById('categoryName_error')

// IMAGE CATEGORY
var categoryImage = document.getElementById('category_imageFile_file')
var categoryImageError = document.getElementById('categoryImage_error')

// SUBMIT BUTTON
var submitButton = document.getElementById('category_submit')

categoryName.addEventListener('input', (event) => {
  checkcategoryName()
})

categoryImage.addEventListener('change', (event) => {
  checkcategoryImage()
})

submitButton.addEventListener('click', () => {
  if (!checkcategoryName() && !checkcategoryImage()) {
  }
})

// CHECK CATEGORY NAME
function checkcategoryName() {
  var patterncategoryName = /^[a-zA-ZÀ-ÿ0-9_\-\s]{2,40}$/
  var categoryname = categoryName.value
  var validcategoryName = patterncategoryName.test(categoryname)
  if (categoryname.trim() === '') {
    categoryNameError.style.display = 'block'
    categoryName.classList.add('error-message')
    categoryNameError.innerText = 'The name of the category is required.'
    return false
  }
  if (categoryname.length < 3 || categoryname.length > 40) {
    categoryNameError.style.display = 'block'
    categoryName.classList.add('error-message')
    categoryNameError.innerText =
      'The name of the category must be at least 3 characters, and maximum 40 characters.'
    return false
  }
  if (!validcategoryName) {
    categoryNameError.style.display = 'block'
    categoryName.classList.add('error-message')
    categoryNameError.innerText =
      'The name of the category can only contain letters, spaces, and hyphens'
    return false
  } else {
    categoryNameError.innerText = ''
    categoryNameError.style.display = 'none'
    categoryName.classList.remove('error-message')
    categoryName.classList.add('validated-message')
    return true
  }
}

// CHECK IMAGE
function checkcategoryImage() {
  if (!categoryImage.files[0]) {
    categoryImageError.style.display = 'block'
    categoryImage.classList.add('error-message')
    categoryImageError.innerText = 'The image of the category is required.'
    return false
  }
  // Allowed file types
  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']

  // Check file type
  if (!allowedTypes.includes(categoryImage.files[0].type)) {
    categoryImageError.style.display = 'block'
    categoryImage.classList.add('error-message')
    categoryImageError.innerText =
      'Invalid file type! Please upload a JPG, JPEG, PNG, or WEBP image.'
    return false
  }

  // Check file size (max size set to 1MB in this example)
  const maxSizeInBytes = 1 * 1024 * 1024 // 1MB
  if (categoryImage.files[0].size > maxSizeInBytes) {
    categoryImageError.style.display = 'block'
    categoryImage.classList.add('error-message')
    categoryImageError.innerText = 'File is too large! Maximum size is 1MB.'
    return false
  } else {
    categoryImageError.innerText = ''
    categoryImageError.style.display = 'none'
    categoryImage.classList.remove('error-message')
    categoryImage.classList.add('validated-message')
    return true
  }
}
