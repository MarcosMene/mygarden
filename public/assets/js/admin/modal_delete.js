if (typeof cancelButton === 'undefined') {
  const cancelButton = document.getElementById('cancel-button')

  // Function to open the delete modal
  function openDeleteModal(productId, productName) {
    // Show the modal
    document.getElementById('modal_delete').classList.remove('hidden')

    // Update the product name in the modal
    document.getElementById('delete-product-name').textContent = productName

    // Add click event to confirm delete button
    document.getElementById('confirm-button').onclick = function () {
      // Submit the delete form for the selected product
      document.getElementById('delete-form-' + productId).submit()
    }
  }
  // Function to close the delete modal
  cancelButton.addEventListener('click', () => {
    document.getElementById('modal_delete').classList.add('hidden')
  })
}
