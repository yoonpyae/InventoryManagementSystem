document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(".edit-sale-btn");

  editButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();

      const SaleID = this.dataset.id;
      const status = this.dataset.status;

      document.getElementById("editsaleID").value = SaleID;

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
