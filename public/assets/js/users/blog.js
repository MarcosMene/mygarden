document.addEventListener('DOMContentLoaded', function () {
  const loadMoreButton = document.getElementById('load-more');
  const searchButton = document.getElementById('searchButton');
  const searchInput = document.getElementById('search');
  const categorySelect = document.getElementById('category');
  const yearSelect = document.getElementById('year');
  const clearSearchButton = document.getElementById('clearSearch');
  const noArticlesMessage = document.getElementById('no-articles-message'); // NO ARTICLE MESSAGE
  let offset = 10;
  let noMoreArticles = false;

  // BY CLICKING ON "LOAD MORE"
  loadMoreButton.addEventListener('click', function () {
    if (!noMoreArticles) {
      loadArticles(offset);
    }
  });

  // BY CLICKING ON THE SEARCH BUTTON
  searchButton.addEventListener('click', function () {
    updateFilters();
  });

  clearSearchButton.addEventListener('click', function () {
    searchInput.value = ''; // CLEAN INPUT SEARCH FIELD
    updateFilters(); // UPDATES ARTICLES AFTER CLEARING THE FIELD
    clearSearchButton.classList.add('hidden');
  });

  // FILTERS CATEGORY AND YEAR
  categorySelect.addEventListener('change', updateFilters);
  yearSelect.addEventListener('change', updateFilters);

  function updateFilters() {
    offset = 0; // RESET PAGINATION FOR THE NEW FILTERS
    loadArticles(offset);
  }

  // DISPLAYS THE "X" WHEN THE SEARCH FIELD CONTAINS TEXT
  searchInput.addEventListener('input', function () {
    if (searchInput.value) {
      clearSearchButton.classList.remove('hidden');
    } else {
      clearSearchButton.classList.add('hidden');
    }
  });

  function loadArticles(offset) {
    const filters = {
      name: searchInput.value,
      category: categorySelect.value,
      year: yearSelect.value,
      offset: offset,
    };

    // DISPLAY LOADING IF THERE IS A REQUEST
    document.getElementById('loading').style.display = 'flex';
    noArticlesMessage.innerHTML = ''; //HIDES THE "NO ARTICLES" MESSAGE

    // AJAX REQUEST TO LOAD ARTICLES
    fetch('/blog/load-more?' + new URLSearchParams(filters))
      .then((response) => {
        if (!response.ok) {
          throw new Error('Request error');
        }
        return response.json();
      })
      .then((data) => {
        // HIDE LOADING
        document.getElementById('loading').style.display = 'none';

        if (data.html && data.html.trim() !== '') {
          // UPDATE THE ARTICLES ON THE PAGE
          const articlesContainer = document.getElementById('articles');
          if (offset === 0) {
            // CLEARS OLD ARTICLES WHEN SEARCHING AGAIN
            articlesContainer.innerHTML = data.html;
          } else {
            articlesContainer.innerHTML += data.html; // ADD THE NEW ARTICLES TO THE END
          }

          // CHECKS IF THERE ARE MORE ARTICLES TO BE UPLOADED
          if (data.no_more) {
            loadMoreButton.style.display = 'none';
            noMoreArticles = true;
          } else {
            loadMoreButton.style.display = 'block'; // REDISPLAY THE BUTTON IF THERE ARE STILL ARTICLES
            noMoreArticles = false;
            offset += 10; // UPDATES THE OFFSET TO LOAD MORE ARTICLES
          }
        } else {
          // IF THERE ARE NO ARTICLES, IT DISPLAYS THE MESSAGE
          noArticlesMessage.innerHTML = 'No articles found!';
        }
      })
      .catch((error) => {
        document.getElementById('loading').style.display = 'none';
        console.error('Error uploading articles:', error);
        noArticlesMessage.style.display = 'block'; // DISPLAYS THE MESSAGE IF THERE IS AN ERROR
      });
  }
});
