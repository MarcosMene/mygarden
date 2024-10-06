// NAME PRODUCT
var productName = document.getElementById('product_name')
var productNameError = document.getElementById('productName_error')

// CATEGORY PRODUCT
var productCategory = document.getElementById('product_category')
var productCategoryError = document.getElementById('productCategory_error')

// IMAGE PRODUCT
var productImage = document.getElementById('product_imageFile_file')
var productImageError = document.getElementById('productImage_error')

// TAX OF PRODUCT
var productTva = document.getElementById('product_tva')
var productTvaError = document.getElementById('productTva_error')

// PRICE OF PRODUCT
var productPrice = document.getElementById('product_price')
var productPriceError = document.getElementById('productPrice_error')

// COLOR PRODUCT
var productColor = document.getElementById('product_colorProduct')
var productColorError = document.getElementById('productColor_error')

// PROMOTION PRODUCT
var productPromotion = document.getElementById('product_promotion')
var productPromotionError = document.getElementById('productPromotion_error')

// SUGGESTION PRODUCT
var productSuggestion = document.getElementById('product_isSuggestion')
var productSuggestionError = document.getElementById('productSuggestion_error')

// DESCRIPTION PRODUCT
var productDescription = document.getElementById('product_description')
var productDescriptionError = document.getElementById(
  'productDescription_error'
)

// SUBMIT BUTTON
var submitButton = document.getElementById('product_submit')

productName.addEventListener('input', (event) => {
  checkProductName()
})
productCategory.addEventListener('change', (event) => {
  checkProductCategory()
})
productPrice.addEventListener('input', (event) => {
  checkProductPrice()
})
productTva.addEventListener('change', (event) => {
  checkProductTva()
})
productColor.addEventListener('change', (event) => {
  checkProductColor()
})

productPromotion.addEventListener('change', (event) => {
  checkProductPromotion()
})
productSuggestion.addEventListener('change', (event) => {
  checkProductSuggestion()
})
productImage.addEventListener('change', (event) => {
  checkProductImage()
})

productDescription.addEventListener('input', (event) => {
  checkProductDescription()
})

submitButton.addEventListener('click', () => {
  if (
    !checkProductName() &&
    !checkProductCategory() &&
    !checkProductTva() &&
    !checkProductPrice() &&
    !checkProductColor() &&
    !checkProductPromotion() &&
    !checkProductSuggestion() &&
    !checkProductDescription() &&
    !checkProductImage()
  ) {
  }
})

// CHECK PRODUCT NAME
function checkProductName() {
  var patternProductName = /^[a-zA-ZÀ-ÿ0-9_\-\s]{2,40}$/
  var productname = productName.value
  var validProductName = patternProductName.test(productname)
  if (productname.trim() === '') {
    productNameError.style.display = 'block'
    productName.classList.add('error-message')
    productNameError.innerText = 'The name of the product is required.'
    return false
  }
  if (productname.length < 3 || productname.length > 40) {
    productNameError.style.display = 'block'
    productName.classList.add('error-message')
    productNameError.innerText =
      'The name of the product must be at least 3 characters, and maximum 40 characters.'
    return false
  }
  if (!validProductName) {
    productNameError.style.display = 'block'
    productName.classList.add('error-message')
    productNameError.innerText =
      'The name of the product can only contain letters, spaces, and hyphens'
    return false
  } else {
    productNameError.innerText = ''
    productNameError.style.display = 'none'
    productName.classList.remove('error-message')
    productName.classList.add('validated-message')
    return true
  }
}

// CHECK CATEGORY
function checkProductCategory() {
  if (productCategory.value == '') {
    productCategoryError.style.display = 'block'
    productCategory.classList.add('error-message')
    productCategoryError.innerText = 'The category of product is required.'
    return false
  } else {
    productCategoryError.innerText = ''
    productCategoryError.style.display = 'none'
    productCategory.classList.remove('error-message')
    productCategory.classList.add('validated-message')
    return true
  }
}

// CHECK IMAGE
function checkProductImage() {
  if (!productImage.files[0]) {
    productImageError.style.display = 'block'
    productImage.classList.add('error-message')
    productImageError.innerText = 'The image of the product is required.'
    return false
  }
  // Allowed file types
  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']

  // Check file type
  if (!allowedTypes.includes(productImage.files[0].type)) {
    productImageError.style.display = 'block'
    productImage.classList.add('error-message')
    productImageError.innerText =
      'Invalid file type! Please upload a JPG, JPEG, PNG, or WEBP image.'
    return false
  }

  // Check file size (max size set to 1MB in this example)
  const maxSizeInBytes = 1 * 1024 * 1024 // 1MB
  if (productImage.files[0].size > maxSizeInBytes) {
    productImageError.style.display = 'block'
    productImage.classList.add('error-message')
    productImageError.innerText = 'File is too large! Maximum size is 1MB.'
    return false
  } else {
    productImageError.innerText = ''
    productImageError.style.display = 'none'
    productImage.classList.remove('error-message')
    productImage.classList.add('validated-message')
    return true
  }
}

// CHECK PRODUCT PRICE
function checkProductPrice() {
  var patternProductPrice = /^[1-9]\d{1,3}([\,\.]\d+)?$/
  var productprice = productPrice.value
  var validProductPrice = patternProductPrice.test(productprice)
  if (productprice.trim() === '') {
    productPriceError.style.display = 'block'
    productPrice.classList.add('error-message')
    productPriceError.innerText = 'The price of the product is required.'
    return false
  } else if (!validProductPrice) {
    productPriceError.style.display = 'block'
    productPrice.classList.add('error-message')
    productPriceError.innerText =
      'The price of the product can only contain numbers and must be positive'
    return false
  } else if (productprice.length < 2 || productprice.length > 7) {
    productPriceError.style.display = 'block'
    productPrice.classList.add('error-message')
    productPriceError.innerText =
      'The price of the product must be at least 2 characters, and maximum 7 characters.'
    return false
  } else {
    productPriceError.innerText = ''
    productPriceError.style.display = 'none'
    productPrice.classList.remove('error-message')
    productPrice.classList.add('validated-message')
    return true
  }
}

// CHECK TVA
function checkProductTva() {
  if (productTva.value == '') {
    productTvaError.style.display = 'block'
    productTva.classList.add('error-message')
    productTvaError.innerText = 'The TVA of product is required.'
    return false
  } else {
    productTvaError.innerText = ''
    productTvaError.style.display = 'none'
    productTva.classList.remove('error-message')
    productTva.classList.add('validated-message')
    return true
  }
}

// CHECK COLOR
function checkProductColor() {
  if (productColor.value == '') {
    productColorError.style.display = 'block'
    productColor.classList.add('error-message')
    productColorError.innerText = 'The color of product is required.'
    return false
  } else {
    productColorError.innerText = ''
    productColorError.style.display = 'none'
    productColor.classList.remove('error-message')
    productColor.classList.add('validated-message')
    return true
  }
}

// CHECK PROMOTION
function checkProductPromotion() {
  if (productPromotion.value == '') {
    productPromotionError.style.display = 'block'
    productPromotion.classList.add('error-message')
    productPromotionError.innerText = 'The promotion of product is required.'
    return false
  } else {
    productPromotionError.innerText = ''
    productPromotionError.style.display = 'none'
    productPromotion.classList.remove('error-message')
    productPromotion.classList.add('validated-message')
    return true
  }
}

// CHECK SUGGESTION
function checkProductSuggestion() {
  if (productSuggestion.value == '') {
    productSuggestionError.style.display = 'block'
    productSuggestion.classList.add('error-message')
    productSuggestionError.innerText = 'The suggestion of product is required.'
    return false
  } else {
    productSuggestionError.innerText = ''
    productSuggestionError.style.display = 'none'
    productSuggestion.classList.remove('error-message')
    productSuggestion.classList.add('validated-message')
    return true
  }
}

// CHECK  DESCRIPTION
function checkProductDescription() {
  var patternProductDescription = /^[a-zA-ZÀ-ÿ0-9_\-\s]{19,199}$/
  var productdescription = productDescription.value
  var validProductDescription =
    patternProductDescription.test(productdescription)
  if (productdescription.trim() === '') {
    productDescriptionError.style.display = 'block'
    productDescription.classList.add('error-message')
    productDescriptionError.innerText =
      'The description of the product is required.'
    return false
  } else if (
    productdescription.length < 20 ||
    productdescription.length > 200
  ) {
    productDescriptionError.style.display = 'block'
    productDescription.classList.add('error-message')
    productDescriptionError.innerText =
      'The description of the product must be at least 3 characters, and maximum 40 characters.'
    return false
  } else if (!validProductDescription) {
    productDescriptionError.style.display = 'block'
    productDescription.classList.add('error-message')
    productDescriptionError.innerText =
      'The description of the product can only contain letters, spaces, and hyphens'
    return false
  } else {
    productDescriptionError.innerText = ''
    productDescriptionError.style.display = 'none'
    productDescription.classList.remove('error-message')
    productDescription.classList.add('validated-message')
    return true
  }
}
