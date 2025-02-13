document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(".edit-customer-btn");

  editButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault(); // Prevent default behavior of anchor tag

      const customerID = this.dataset.id;
      const customerName = this.dataset.name;
      const address = this.dataset.address; // Fixed from undefined variable
      const phone = this.dataset.phone;

      // Populate modal fields
      document.getElementById("editCustomerID").value = customerID;
      document.getElementById("editCustomerName").value = customerName;
      document.getElementById("editAddress").value = address;
      document.getElementById("editPhoneNumber").value = phone;

      // Initialize and show the Bootstrap modal
      const editModal = new bootstrap.Modal(
        document.getElementById("editModal")
      );
      editModal.show();
    });
  });
});
