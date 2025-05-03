document.addEventListener('DOMContentLoaded', function () {
  // NAME PRODUCT
  const productName = document.getElementById('product_name');
  const productNameError = document.getElementById('productName_error');
  const errorMessages = document.getElementById('error_messages');

  // CATEGORY PRODUCT
  const productCategory = document.getElementById('product_category');
  const productCategoryError = document.getElementById('productCategory_error');

  // IMAGE PRODUCT
  const productImage = document.getElementById('product_imageFile_file');
  const productImageError = document.getElementById('productImage_error');

  // TAX OF PRODUCT
  const productTva = document.getElementById('product_tva');
  const productTvaError = document.getElementById('productTva_error');

  // PRICE OF PRODUCT
  const productPrice = document.getElementById('product_price');
  const productPriceError = document.getElementById('productPrice_error');

  // COLOR PRODUCT
  const productColor = document.getElementById('product_colorProduct');
  const productColorError = document.getElementById('productColor_error');

  // PROMOTION PRODUCT
  const productPromotion = document.getElementById('product_promotion');
  const productPromotionError = document.getElementById(
    'productPromotion_error'
  );

  // SUGGESTION PRODUCT
  const productSuggestion = document.getElementById('product_isSuggestion');
  const productSuggestionError = document.getElementById(
    'productSuggestion_error'
  );

  // DESCRIPTION PRODUCT
  const productDescription = document.getElementById('product_description');
  const productDescriptionError = document.getElementById(
    'productDescription_error'
  );

  // SUBMIT BUTTON
  const submitButton = document.getElementById('product_submit');

  productName.addEventListener('input', () => {
    checkProductName();
  });
  productCategory.addEventListener('change', () => {
    checkProductCategory();
  });
  productPrice.addEventListener('input', () => {
    checkProductPrice();
  });
  productTva.addEventListener('change', () => {
    checkProductTva();
  });
  productColor.addEventListener('change', () => {
    checkProductColor();
  });

  productPromotion.addEventListener('change', () => {
    checkProductPromotion();
  });
  productSuggestion.addEventListener('change', () => {
    checkProductSuggestion();
  });
  productImage.addEventListener('change', () => {
    checkProductImage();
  });

  productDescription.addEventListener('input', () => {
    checkProductDescription();
  });

  submitButton.addEventListener('click', (event) => {
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
      errorMessages.classList.remove('hidden');
      errorMessages.innerText = 'Please fill in all the fields';
    } else {
      errorMessages.classList.add('hidden');
    }

    if (!checkProductName()) {
      event.preventDefault();
      productNameError.style.display = 'block';
      productName.classList.add('error-message');
      productNameError.innerText = 'The name of product is required.';
    }
    if (!checkProductCategory()) {
      event.preventDefault();
      productCategoryError.style.display = 'block';
      productCategory.classList.add('error-message');
      productCategoryError.innerText = 'The name of the category is required.';
    }
    if (!checkProductTva()) {
      event.preventDefault();
      productTvaError.style.display = 'block';
      productTva.classList.add('error-message');
      productTvaError.innerText = 'The tax is required.';
    }
    if (!checkProductPrice()) {
      event.preventDefault();
      productPriceError.style.display = 'block';
      productPrice.classList.add('error-message');
      productPriceError.innerText = 'The price is required.';
    }
    if (!checkProductColor()) {
      event.preventDefault();
      productColorError.style.display = 'block';
      productColor.classList.add('error-message');
      productColorError.innerText = 'The color is required.';
    }
    if (!checkProductPromotion()) {
      event.preventDefault();
      productPromotionError.style.display = 'block';
      productPromotion.classList.add('error-message');
      productPromotionError.innerText = 'The promotion is required.';
    }
    if (!checkProductSuggestion()) {
      event.preventDefault();
      productSuggestionError.style.display = 'block';
      productSuggestion.classList.add('error-message');
      productSuggestionError.innerText = 'The suggestion is required.';
    }
    if (!checkProductDescription()) {
      event.preventDefault();
      productDescriptionError.style.display = 'block';
      productDescription.classList.add('error-message');
      productDescriptionError.innerText = 'The description is required.';
    }
    if (!checkProductImage()) {
      event.preventDefault();
      productImageError.style.display = 'block';
      productImage.classList.add('error-message');
      productImageError.innerText = 'The image is required.';
    }
  });

  // CHECK PRODUCT NAME
  function checkProductName() {
    productName.classList.remove('is-invalid');
    productName.classList.remove('error-message');

    //VALIDATION PRODUCT
    const patternProductName = /^[a-zA-ZÀ-ÿ0-9_\-\s]{2,40}$/;
    const productname = productName.value;
    const validProductName = patternProductName.test(productname);
    if (productname.trim() === '') {
      productNameError.style.display = 'block';
      productName.classList.add('error-message');
      productNameError.innerText = 'The name of the product is required.';
      return false;
    } else if (productname.length < 3 || productname.length > 40) {
      productNameError.style.display = 'block';
      productName.classList.add('error-message');
      productNameError.innerText =
        'The name of the product must be at least 3 characters, and maximum 40 characters.';
      return false;
    } else if (!validProductName) {
      productNameError.style.display = 'block';
      productName.classList.add('error-message');
      productNameError.innerText =
        'The name of the product can only contain constters, spaces, or hyphens';
      return false;
    } else {
      productNameError.innerText = '';
      productNameError.style.display = 'none';
      productName.classList.remove('error-message');
      productName.classList.add('validated-message');
      return true;
    }
  }

  // CHECK CATEGORY
  function checkProductCategory() {
    // CATEGORY PLACEHOLDER
    if (productCategory.value && productCategory.length != 0) {
      productCategory.style.color = '#161616';
    } else {
      productCategory.style.color = '#989898';
    }

    if (productCategory.value == '') {
      productCategoryError.style.display = 'block';
      productCategory.classList.add('error-message');
      productCategoryError.innerText = 'The category of product is required.';
      return false;
    } else {
      productCategoryError.innerText = '';
      productCategoryError.style.display = 'none';
      productCategory.classList.remove('error-message');
      productCategory.classList.add('validated-message');
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
      if (!productImage.files[0]) {
        productImageError.style.display = 'block';
        productImage.classList.add('error-message');
        productImageError.innerText = 'The image of the product is required.';
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
      if (!allowedTypes.includes(productImage.files[0].type)) {
        productImageError.style.display = 'block';
        productImage.classList.add('error-message');
        productImageError.innerText =
          'Invalid file type! Please upload a JPG, JPEG, PNG, or WEBP image.';
        return false;
      }

      // CHECK FILE SIZE (MAX SIZE SET TO 1MB IN THIS EXAMPLE)
      const maxSizeInBytes = 1 * 1024 * 1024; // 1MB
      if (productImage.files[0].size > maxSizeInBytes) {
        productImageError.style.display = 'block';
        productImage.classList.add('error-message');
        productImageError.innerText = 'File is too large! Maximum size is 1MB.';
        return false;
      } else {
        productImageError.innerText = '';
        productImageError.style.display = 'none';
        productImage.classList.remove('error-message');
        productImage.classList.add('validated-message');
        return true;
      }
    }
  }

  // CHECK PRODUCT PRICE
  function checkProductPrice() {
    const patternProductPrice = /^[1-9]\d{1,3}([\,\.]\d+)?$/;
    const productprice = productPrice.value;
    const validProductPrice = patternProductPrice.test(productprice);
    if (productprice.trim() === '') {
      productPriceError.style.display = 'block';
      productPrice.classList.add('error-message');
      productPriceError.innerText = 'The price of the product is required.';
      return false;
    } else if (!validProductPrice) {
      productPriceError.style.display = 'block';
      productPrice.classList.add('error-message');
      productPriceError.innerText =
        'The price of the product can only contain numbers and must be positive';
      return false;
    } else if (productprice.length < 2 || productprice.length > 7) {
      productPriceError.style.display = 'block';
      productPrice.classList.add('error-message');
      productPriceError.innerText =
        'The price of the product must be at least 2 characters, and maximum 7 characters.';
      return false;
    } else {
      productPriceError.innerText = '';
      productPriceError.style.display = 'none';
      productPrice.classList.remove('error-message');
      productPrice.classList.add('validated-message');
      return true;
    }
  }

  // CHECK TVA
  function checkProductTva() {
    // TVA PLACEHOLDER
    if (productTva.value) {
      productTva.style.color = '#161616';
    } else {
      productTva.style.color = '#989898';
    }
    if (productTva.value == '') {
      productTvaError.style.display = 'block';
      productTva.classList.add('error-message');
      productTvaError.innerText = 'The TVA of product is required.';
      return false;
    } else {
      productTvaError.innerText = '';
      productTvaError.style.display = 'none';
      productTva.classList.remove('error-message');
      productTva.classList.add('validated-message');
      return true;
    }
  }

  // CHECK COLOR
  function checkProductColor() {
    // COLOR PLACEHOLDER
    if (productColor.value) {
      productColor.style.color = '#161616';
    } else {
      productColor.style.color = '#989898';
    }
    if (productColor.value == '') {
      productColorError.style.display = 'block';
      productColor.classList.add('error-message');
      productColorError.innerText = 'The color of product is required.';
      return false;
    } else {
      productColorError.innerText = '';
      productColorError.style.display = 'none';
      productColor.classList.remove('error-message');
      productColor.classList.add('validated-message');
      return true;
    }
  }

  // CHECK PROMOTION
  function checkProductPromotion() {
    // PROMOTION PLACEHOLDER
    if (productPromotion.value) {
      productPromotion.style.color = '#161616';
    } else {
      productPromotion.style.color = '#989898';
    }
    if (productPromotion.value == '') {
      productPromotionError.style.display = 'block';
      productPromotion.classList.add('error-message');
      productPromotionError.innerText = 'The promotion of product is required.';
      return false;
    } else {
      productPromotionError.innerText = '';
      productPromotionError.style.display = 'none';
      productPromotion.classList.remove('error-message');
      productPromotion.classList.add('validated-message');
      return true;
    }
  }

  // CHECK SUGGESTION
  function checkProductSuggestion() {
    // SUGGESTION PLACEHOLDER
    if (productSuggestion.value) {
      productSuggestion.style.color = '#161616';
    } else {
      productSuggestion.style.color = '#989898';
    }
    if (productSuggestion.value == '') {
      productSuggestionError.style.display = 'block';
      productSuggestion.classList.add('error-message');
      productSuggestionError.innerText =
        'The suggestion of product is required.';
      return false;
    } else {
      productSuggestionError.innerText = '';
      productSuggestionError.style.display = 'none';
      productSuggestion.classList.remove('error-message');
      productSuggestion.classList.add('validated-message');
      return true;
    }
  }

  // CHECK  DESCRIPTION
  function checkProductDescription() {
    const patternProductDescription = /^[a-zA-ZÀ-ÿ0-9_\.,!?\-\s]{19,199}$/;
    const productdescription = productDescription.value;
    const validProductDescription =
      patternProductDescription.test(productdescription);
    if (productdescription.trim() === '') {
      productDescriptionError.style.display = 'block';
      productDescription.classList.add('error-message');
      productDescriptionError.innerText =
        'The description of the product is required.';
      return false;
    } else if (
      productdescription.length < 20 ||
      productdescription.length > 200
    ) {
      productDescriptionError.style.display = 'block';
      productDescription.classList.add('error-message');
      productDescriptionError.innerText =
        'The description of the product must be at least 20 characters, and maximum 200 characters.';
      return false;
    } else if (!validProductDescription) {
      productDescriptionError.style.display = 'block';
      productDescription.classList.add('error-message');
      productDescriptionError.innerText =
        'The description of the product can only contain constters, spaces, or hyphens';
      return false;
    } else {
      productDescriptionError.innerText = '';
      productDescriptionError.style.display = 'none';
      productDescription.classList.remove('error-message');
      productDescription.classList.add('validated-message');
      return true;
    }
  }
});
