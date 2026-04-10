document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('search');
  const filterButtons = document.querySelectorAll('.filter-btn');
  const tableRows = document.querySelectorAll('#doctorTable tbody tr');
  const modal = document.getElementById('modal');
  const modalText = document.getElementById('modalText');
  const closeModal = document.querySelector('.close');

  // Search functionality
  searchInput.addEventListener('keyup', function() {
    const query = this.value.toLowerCase();
    tableRows.forEach(row => {
      const name = row.cells[0].textContent.toLowerCase();
      row.style.display = name.includes(query) ? '' : 'none';
    });
  });

  // Show all doctors
  const allDocsBtn = document.querySelector('.all-docs-btn');
  if (allDocsBtn) {
    allDocsBtn.addEventListener('click', function() {
      searchInput.value = '';
      tableRows.forEach(row => {
        row.style.display = '';
      });
    });
  }

  // Filter by specialization
  filterButtons.forEach(button => {
    button.addEventListener('click', function() {
      const spec = this.getAttribute('data-specialize').toLowerCase();
      tableRows.forEach(row => {
        const specialization = row.cells[1].textContent.toLowerCase();
        row.style.display = specialization === spec ? '' : 'none';
      });
    });
  });

  // View Info functionality
  const viewButtons = document.querySelectorAll('.view-info');
  viewButtons.forEach(button => {
    button.addEventListener('click', function() {
      const did = this.getAttribute('data-id');
      fetch(`get_doctor_info.php?did=${did}`)
        .then(response => response.json())
        .then(data => {
          modalText.innerHTML = `
            <strong>Name:</strong> ${data.Name}<br>
            <strong>Specialization:</strong> ${data.Specialize}<br>
            <strong>Phone:</strong> ${data.Phone}<br>
            <strong>Email:</strong> ${data.Email}
          `;
          modal.style.display = 'block';
        });
    });
  });

  // Close modal
  closeModal.addEventListener('click', function() {
    modal.style.display = 'none';
  });

  // Close modal when clicking outside
  window.addEventListener('click', function(event) {
    if (event.target == modal) {
      modal.style.display = 'none';
    }
  });
});
