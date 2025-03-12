if (typeof cancelButton === 'undefined') {
  const cancelButton = document.getElementById('cancel-button');

  // FUNCTION TO OPEN THE DELETE MODAL
  function openDeleteModal(productId, productName) {
    // SHOW THE MODAL
    document.getElementById('modal_delete').classList.remove('hidden');

    // UPDATE THE PRODUCT NAME IN THE MODAL
    document.getElementById('delete-product-name').textContent = productName;

    // ADD CLICK EVENT TO CONFIRM DELETE BUTTON
    document.getElementById('confirm-button').onclick = function () {
      // SUBMIT THE DELETE FORM FOR THE SELECTED PRODUCT
      document.getElementById('delete-form-' + productId).submit();
    };
  }
  // FUNCTION TO CLOSE THE DELETE MODAL
  cancelButton.addEventListener('click', () => {
    document.getElementById('modal_delete').classList.add('hidden');
  });
}
