document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(".edit-delivery-btn");

  editButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();

      const DeliveryID = this.dataset.id;
      const status = this.dataset.status;

      document.getElementById("editdeliveryID").value = DeliveryID;

      // Set the selected value in the combo box
      const statusDropdown = document.getElementById("editStatus");
      statusDropdown.value = status; // Dynamically selects Active/Inactive

      // Show the modal
      const editModal = new bootstrap.Modal(
        document.getElementById("editModal")
      );
      editModal.show();
    });
  });
});
