document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.toggle-user-btn').forEach((button) => {
    button.addEventListener('click', async () => {
      const userId = button.dataset.userId;
      const url = `/user/toggle/${userId}`;

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest', // Ensures it's an AJAX request
          },
        });

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
          // Update button text and styling dynamically
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
