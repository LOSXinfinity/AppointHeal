<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}  
include 'db.php';

// Fetch doctors
$doctors = $conn->query("SELECT * FROM doctor");

// Fetch unique specializations
$specializations = $conn->query("SELECT DISTINCT Specialize FROM doctor");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Dashboard</title>
  <link rel="stylesheet" href="doctor_dashboard.css">
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="item header">
      <h1><span class="red">Appoint</span><span class="green">Heal</span></h1>
    </div>

    <!-- Sidebar -->
    <div class="item sidebar">
      <h3>Search Doctor</h3>
      <input type="text" id="search" placeholder="Enter doctor's name">
      <h3>Specializations</h3>
      <button class="filter-btn all-docs-btn" style="background: linear-gradient(135deg, #FFD700, #FDB931); color: #000;">🌟 All Doctors</button>
      <?php while($spec = $specializations->fetch_assoc()): ?>
        <button class="filter-btn" data-specialize="<?= $spec['Specialize'] ?>"><?= $spec['Specialize'] ?></button>
      <?php endwhile; ?>
    </div>

    <!-- Content -->
    <div class="item content">
      <table id="doctorTable">
        <thead>
          <tr>
            <th>Name</th>
            <th>Specialization</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while($doc = $doctors->fetch_assoc()): ?>
            <tr>
              <td><?= $doc['Name'] ?></td>
              <td><?= $doc['Specialize'] ?></td>
              <td>
                <button class="view-info" data-id="<?= $doc['DID'] ?>">View Info</button>
                <a href="appointment.php?did=<?= $doc['DID'] ?>"><button class="book-btn">Book Appointment</button></a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Footer -->
    <div class="item footer">
      AppointHeal connects patients with the best doctors. Your health our priority. ✅
    </div>
  </div>

  <!-- Modal -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <p id="modalText"></p>
    </div>
  </div>

  <script src="doctor_dashboard.js"></script>
</body>
</html>
