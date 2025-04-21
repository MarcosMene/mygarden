document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.toggle-user-btn').forEach((button) => {
    button.addEventListener('click', async () => {
      const userId = button.dataset.userId;
      const url = `/user/toggle/${userId}`;

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest', // ENSURES IT'S AN AJAX REQUEST
          },
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
          // UPDATE BUTTON TEXT AND STYLING DYNAMICALLY
          button.textContent = data.isActive ? 'Active' : 'Deactivate';
          button.classList.toggle('bg-green-500', data.isActive);
          button.classList.toggle('bg-red-500', !data.isActive);
        } else {
          alert('An error occurred. Please try again.');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Something went wrong!');
      }
    });
  });
});
