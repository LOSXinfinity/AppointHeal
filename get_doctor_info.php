<?php
include 'db.php';

if (isset($_GET['did'])) {
  $did = intval($_GET['did']);
  $stmt = $conn->prepare("SELECT Name, Specialize, Phone, Email FROM doctor WHERE DID = ?");
  $stmt->bind_param("i", $did);
  $stmt->execute();
  $result = $stmt->get_result();
  $doctor = $result->fetch_assoc();
  echo json_encode($doctor);
}
?>
