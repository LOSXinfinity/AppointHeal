<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "doctor_appointment";

// Connect to DB
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => '❌ DB connection failed.']);
    exit();
}

// Validate input
if (!isset($_SESSION['PID'], $_POST['did'], $_POST['day'], $_POST['time'])) {
    echo json_encode(['success' => false, 'message' => '⚠️ Incomplete data received.']);
    exit();
}

$pid = $_SESSION['PID'];
$did = intval($_POST['did']);
$day = trim($_POST['day']);
$time = trim($_POST['time']);

// Check if slot already booked
$check = $conn->prepare("SELECT 1 FROM appointment WHERE DID = ? AND day = ? AND time = ? AND status = 1");
$check->bind_param("iss", $did, $day, $time);
$check->execute();
$res = $check->get_result();

if ($res && $res->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => '❌ This slot is already booked.']);
    exit();
}

// Book appointment
$insert = $conn->prepare("INSERT INTO appointment (PID, DID, day, time, status) VALUES (?, ?, ?, ?, 1)");
$insert->bind_param("iiss", $pid, $did, $day, $time);

if ($insert->execute()) {
    // Update doctor_time
    $update = $conn->prepare("UPDATE doctor_time SET isBooked = 1 WHERE DID = ? AND time = ?");
    $update->bind_param("is", $did, $time);
    $update->execute();

    // Fetch doctor name
    $doctor = $conn->prepare("SELECT name FROM doctor WHERE DID = ?");
    $doctor->bind_param("i", $did);
    $doctor->execute();
    $result = $doctor->get_result();
    $doctor_name = $result && $result->num_rows > 0 ? $result->fetch_assoc()['name'] : 'Doctor';

    echo json_encode([
        'success' => true,
        'doctor_name' => $doctor_name,
        'day' => $day,
        'time' => $time
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => '⚠️ Booking failed. Please try again.',
        'error' => $conn->error
    ]);
}

$conn->close();
?>
