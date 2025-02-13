document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(".edit-supplier-btn");

  editButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault(); // Prevent default behavior of anchor tag

      const SupplierID = this.dataset.id;
      const SupplierName = this.dataset.name;
      const ContactPerson = this.dataset.person;
      const address = this.dataset.address;
      const phone = this.dataset.phone;

      // Populate modal fields
      document.getElementById("editSupplierID").value = SupplierID;
      document.getElementById("editSupplierName").value = SupplierName;
      document.getElementById("editContactPerson").value = ContactPerson;
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
