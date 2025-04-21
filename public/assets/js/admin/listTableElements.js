// FUNCTION TO LOAD THE USERS TABLE VIA AJAX
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('search');
  const clearSearchButton = document.getElementById('clearSearch');
  const tableContainer = document.getElementById('table-container');
  const loadingSpinner = document.getElementById('loadingSpinner');
  const errorMessage = document.getElementById('error-message');

  function loadStoreReviews(page = 1, query = '') {
    const url = new URL(tableContainer.dataset.url, window.location.origin);
    url.searchParams.set('page', page);

    if (query.trim() !== '') {
      url.searchParams.set('query', query);
    }

    loadingSpinner.classList.remove('hidden');

    fetch(url, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error('Failed to fetch reviews');
        }
        return response.text();
      })
      .then((html) => {
        tableContainer.innerHTML = html;
        attachPaginationEvents();
      })
      .catch((error) => {
        console.error('Error fetching reviews:', error);
        errorMessage.innerText = 'Error loading reviews. Try again later';
      })
      .finally(() => {
        loadingSpinner.classList.add('hidden');
      });
  }

  function attachPaginationEvents() {
    document.querySelectorAll('.pagination a').forEach((link) => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const page = new URL(this.href).searchParams.get('page');
        loadStoreReviews(page, searchInput.value);
      });
    });
  }

  // SHOW OR HIDE THE "X" BUTTON AS THE USER TYPES
  searchInput.addEventListener('input', function () {
    errorMessage.innerText = '';
    searchInput.classList.remove('error-message');

    // remove spaces on input
    const inputValue = this.value.trim();

    // only 5 characters otherwise the request stop
    if (inputValue.length >= 5) {
      this.value = inputValue.slice(0, 5);
      errorMessage.innerText = 'Maximum 5 characters.';
      searchInput.classList.add('error-message');
      return; // stop the request
    }

    // only do the request if it has less than 5 characters
    if (inputValue !== '') {
      loadStoreReviews(1, inputValue);
      clearSearchButton.classList.remove('hidden');
    } else {
      clearSearchButton.classList.add('hidden');
      loadStoreReviews(1, '');
    }
  });

  // 'X' BUTTON TO CLEAR THE INPUT AND RELOAD ALL ARTICLES
  clearSearchButton.addEventListener('click', function () {
    searchInput.value = '';
    errorMessage.innerText = '';
    clearSearchButton.classList.add('hidden');
    searchInput.classList.remove('error-message');
    loadStoreReviews(1, '');
  });

  // APPENDS PAGING EVENTS WHEN LOADING THE PAGE
  attachPaginationEvents();
});
