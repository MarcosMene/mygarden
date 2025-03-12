document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('articleForm');
  const saveDraftBtn = document.getElementById('saveDraft');
  const publishBtn = document.getElementById('publishArticle');
  const errorMessages = document.getElementById('error_messages');

  // IMAGE OF ARTICLE
  let articleImage = document.getElementById(
    'blog_article_articleImageFile_file'
  );
  let articleImageError = document.getElementById('blogArticleImage_error');

  // VALIDATE THE TITLE
  let titleInput = document.getElementById('blog_article_title');
  let titleError = document.getElementById('blogTitle_error');

  // CATEGORY
  let categoryInput = document.getElementById('blog_article_category');
  let categoryError = document.getElementById('blogCategory_error');

  // TAGS
  let blogTags = document.querySelector('.tags-container');
  let BlogTagError = document.getElementById('blogTags_error');
  let tagInputs = document.querySelectorAll(
    'input[name="blog_article[tags][]"]'
  );

  // CONTENT
  let contentInput = document.getElementById('blog_article_content');
  let contentError = document.getElementById('blogContent_error');

  titleInput.addEventListener('input', () => checkTitle());

  function checkTitle() {
    //CLEAN ERROR MESSAGES
    titleInput.classList.remove('error-message', 'validated-message');
    titleError.style.display = 'none';

    //GET VALUE OF TITLE
    let blogTitle = titleInput.value;

    //DELETE SPACES OF TITLE
    if (blogTitle.trim() === '') {
      titleError.style.display = 'block';
      titleInput.classList.add('error-message');
      titleError.innerText = 'The title is required.';
      return false;
    }

    //REGEX
    let patternBlogTitle = /^[a-zA-ZÀ-ÿ0-9?!_\-\s]{3,}$/;
    let validBlogTitle = patternBlogTitle.test(blogTitle);

    if (!validBlogTitle) {
      titleError.style.display = 'block';
      titleInput.classList.add('error-message');
      titleError.innerText =
        'The title can only contain letters, spaces, or hyphens';
      return false;
    }

    if (blogTitle.length < 3 || blogTitle.length > 80) {
      titleError.style.display = 'block';
      titleInput.classList.add('error-message');
      titleError.innerText =
        'The title should be between 3 and 100 characters.';
      return false;
    }

    titleInput.classList.add('validated-message');
    return true;
  }

  // FUNCTION TO VALIDATE CATEGORY
  categoryInput.addEventListener('input', () => checkCategory());

  function checkCategory() {
    categoryInput.classList.remove('error-message', 'validated-message');
    categoryError.style.display = 'none';

    if (categoryInput.value.trim() === '') {
      categoryError.style.display = 'block';
      categoryInput.classList.add('error-message');
      categoryError.innerText = 'The category is required.';
      return false;
    }

    categoryInput.classList.add('validated-message');
    return true;
  }

  // CHECK ARTICLE IMAGE
  articleImage.addEventListener('change', () => checkArticleImage());

  function checkArticleImage() {
    //CLEAN ERROR MESSAGES
    articleImage.classList.remove('error-message', 'validated-message');
    articleImageError.style.display = 'none';

    //SELECT IMAGE VICH
    let vichImage = document.querySelectorAll('.vich-image');
    let vichImageArticle = vichImage[vichImage.length - 1];

    // CHECK IF THE VICHIMAGE CONTAINS ANY ELEMENT WITH AN HREF ATTRIBUTE (LIKE AN <A> TAG)
    let hasHrefChildArticle = vichImageArticle.querySelector('[href]') !== null;

    if (hasHrefChildArticle) {
      return true;
    } else {
      if (!articleImage.files[0]) {
        articleImageError.style.display = 'block';
        articleImage.classList.add('error-message');
        articleImageError.innerText = 'The article image is required.';
        return false;
      }

      // ALLOWD FILE TYPES
      const allowedTypesArticle = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
      ];

      // CHECK FILE TYPE
      if (!allowedTypesArticle.includes(articleImage.files[0].type)) {
        articleImageError.style.display = 'block';
        articleImage.classList.add('error-message');
        articleImageError.innerText =
          'Invalid file type! Please upload a JPG, JPEG, PNG or WEBP image.';
        return false;
      }

      // CHECK FILE SIZE (MAX SIZE SET TO 1MB)
      const maxSizeInBytes = 1 * 1024 * 1024; // 1mb
      if (articleImage.files[0].size > maxSizeInBytes) {
        articleImageError.style.display = 'block';
        articleImage.classList.add('error-message');
        articleImageError.innerText = 'File is too large! Maximum size is 1MB.';
        return false;
      }
      articleImage.classList.add('validated-message');
      return true;
    }
  }

  // CHECK TAGS
  function checkTags() {
    //CLEAN ERROR MESSAGES
    blogTags.classList.remove('error-message', 'validated-message');
    BlogTagError.style.display = 'none';

    let selectedTags = document.querySelectorAll(
      'input[name="blog_article[tags][]"]:checked'
    );
    if (selectedTags.length === 0) {
      BlogTagError.style.display = 'block';
      blogTags.classList.add('error-message');
      BlogTagError.innerText = 'At least one tag is required.';
      blogTags.classList.add('error-message');
      return false;
    } else {
      blogTags.classList.remove('error-message');
      BlogTagError.style.display = 'none';
      blogTags.classList.remove('error-message');
      blogTags.classList.add('validated-message');
      return true;
    }
  }

  // ADDING EVENTS TO TAGS TO CHECK WHETHER THE RED BORDER NEEDS TO APPEAR OR NOT
  tagInputs.forEach((tagInput) => {
    tagInput.addEventListener('change', () => {
      checkTags(); // CHECK TAG VALIDATION IS OK
    });
  });

  // FUNCTION TO VALIDATE CONTENT
  contentInput.addEventListener('input', () => checkContent());

  function checkContent() {
    //CLEAR ERROR MESSAGES
    contentInput.classList.remove('error-message', 'validated-message');
    contentError.style.display = 'none';

    //GET VALUE OF CONTENT
    let blogContent = contentInput.value;

    //DELETE SPACES OF CONTENT
    if (blogContent.trim() === '') {
      contentError.style.display = 'block';
      contentInput.classList.add('error-message');
      contentError.innerText = 'The content is required.';
      return false;
    }

    //REGEX
    let patternBlogContent =
      /^(?=.*[a-zA-ZÀ-ÿ])[a-zA-ZÀ-ÿ0-9 ,.?!;:"'`()/\r\n\-]{3,}$/;
    let validBlogContent = patternBlogContent.test(blogContent);

    if (!validBlogContent) {
      contentError.style.display = 'block';
      contentInput.classList.add('error-message');
      contentError.innerText =
        'The content can only contain letters, spaces, or hyphens';
      return false;
    }

    if (blogContent.length < 10 || blogContent.length > 4000) {
      contentError.style.display = 'block';
      contentInput.classList.add('error-message');
      contentError.innerText =
        'The content should be between 10 and 4000 characters.';
      return false;
    }

    contentInput.classList.add('validated-message');
    return true;
  }

  // VALIDATE THE FORM
  function validateForm() {
    let isValid = true;

    if (!checkTitle()) {
      isValid = false;
    }

    if (!checkCategory()) {
      isValid = false;
    }

    if (!checkTags()) {
      isValid = false;
    }

    if (!checkContent()) {
      isValid = false;
    }

    if (!checkArticleImage()) {
      isValid = false;
    }

    return isValid;
  }

  // BUTTON TO SAVE AS DRAFT
  saveDraftBtn.addEventListener('click', function (event) {
    event.preventDefault();
    document.getElementById('statusInput').value = 'draft';
    form.submit();
  });

  // BUTTON TO PUBLISH ARTICLE
  publishBtn.addEventListener('click', function (event) {
    event.preventDefault();
    if (validateForm()) {
      document.getElementById('statusInput').value = 'published';
      errorMessages.classList.add('hidden');
      form.submit();
    } else {
      errorMessages.classList.remove('hidden');
      errorMessages.innerText = 'Please fill in all the fields';
    }
  });
});
