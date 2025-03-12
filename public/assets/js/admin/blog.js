let offset = 10; // THE FIRST 10 ARTICLES

document
  .querySelector('#load-more')
  .addEventListener('click', async function () {
    try {
      const response = await fetch(`/load-more-articles?offset=${offset}`, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Content-Type': 'application/json',
        },
      });

      if (!response.ok) {
        throw new Error('An error occurred: ' + response.status);
      }

      const data = await response.json();
      const articlesContainer = document.querySelector('#articles-container');

      //ADD NEW ARTICLES TO THE CONTAINER
      articlesContainer.innerHTML += data.html;

      //UPDATE THE OFFSET
      offset += 10;

      //IF LESS THAN 10 ARTICLES, NO MORE ARTICLES
      if (data.html.trim() === '') {
        document.querySelector('#load-more').style.display = 'none';
      }
    } catch (error) {
      console.error('E:', error);
    }
  });
