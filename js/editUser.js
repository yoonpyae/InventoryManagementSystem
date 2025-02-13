document.addEventListener("DOMContentLoaded", () => {
  const editButtons = document.querySelectorAll(".edit-btn");

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const employeeID = this.dataset.id;
      const employeeName = this.dataset.name;
      const position = this.dataset.position;
      const address = this.dataset.address;
      const phone = this.dataset.phone;
      const email = this.dataset.email;
      const username = this.dataset.username;

      // Populate modal fields
      document.getElementById("editEmployeeID").value = employeeID;
      document.getElementById("editEmployeeName").value = employeeName;
      document.getElementById("editPosition").value = position;
      document.getElementById("editAddress").value = address;
      document.getElementById("editPhoneNumber").value = phone;
      document.getElementById("editEmail").value = email;
      document.getElementById("editUsername").value = username;

      // Show the modal
      const editModal = new bootstrap.Modal(
        document.getElementById("editModal")
      );
      editModal.show();
    });
  });
});
