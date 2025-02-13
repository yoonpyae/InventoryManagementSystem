document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(".edit-warehouse-btn");

  editButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();

      const warehouseID = this.dataset.id;
      const warehouseName = this.dataset.name;
      const location = this.dataset.location;
      const status = this.dataset.status;

      // Populate modal fields
      document.getElementById("editwarehouseID").value = warehouseID;
      document.getElementById("editwarehouseName").value = warehouseName;
      document.getElementById("editLocation").value = location;

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
