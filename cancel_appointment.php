<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "doctor_appointment");

if (!isset($_SESSION['PID'], $_POST['did'])) {
    echo json_encode(['success' => false, 'message' => 'Missing data.']);
    exit;
}

$pid = $_SESSION['PID'];
$did = $_POST['did'];

// Get the appointment
$check = $conn->prepare("SELECT * FROM appointment WHERE pid = ? AND did = ? AND status = 1");
$check->bind_param("ii", $pid, $did);
$check->execute();
$appointment = $check->get_result()->fetch_assoc();

if (!$appointment) {
    echo json_encode(['success' => false, 'message' => 'No active appointment found.']);
    exit;
}

$time = $appointment['time'];

// Delete appointment
$delete = $conn->prepare("DELETE FROM appointment WHERE pid = ? AND did = ?");
$delete->bind_param("ii", $pid, $did);
$delete->execute();

// Update doctor_time to available
$update = $conn->prepare("UPDATE doctor_time SET isBooked = 0 WHERE did = ? AND time = ?");
$update->bind_param("is", $did, $time);
$update->execute();

echo json_encode(['success' => true]);
?>
