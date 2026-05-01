<?php
// ──────────────────────────────────────────────────────────
//  book_appointment.php
//  Security: Uses include db.php (single source of truth),
//            all user input bound via prepared statements.
//  Debug errors suppressed in output (log-only in prod).
// ──────────────────────────────────────────────────────────
header("Content-Type: application/json");
session_start();

include "db.php"; // centralised, parameterised connection

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed."]);
    exit();
}

// Validate session + POST fields
if (!isset($_SESSION["PID"], $_POST["did"], $_POST["day"], $_POST["time"])) {
    echo json_encode(["success" => false, "message" => "Incomplete data received."]);
    exit();
}

$pid  = intval($_SESSION["PID"]);
$did  = intval($_POST["did"]);
$day  = trim($_POST["day"]);
$time = trim($_POST["time"]);

// Reject obviously invalid values
if ($did <= 0 || $pid <= 0 || empty($day) || empty($time)) {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit();
}

// Check if slot already booked (prepared statement)
$check = $conn->prepare("SELECT 1 FROM appointment WHERE DID = ? AND day = ? AND time = ? AND status = 1");
$check->bind_param("iss", $did, $day, $time);
$check->execute();
$res = $check->get_result();

if ($res && $res->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "This slot is already booked."]);
    exit();
}
$check->close();

// Insert appointment (prepared statement)
$insert = $conn->prepare("INSERT INTO appointment (PID, DID, day, time, status) VALUES (?, ?, ?, ?, 1)");
$insert->bind_param("iiss", $pid, $did, $day, $time);

if ($insert->execute()) {
    $insert->close();

    // Mark time slot as booked
    $update = $conn->prepare("UPDATE doctor_time SET isBooked = 1 WHERE DID = ? AND time = ?");
    $update->bind_param("is", $did, $time);
    $update->execute();
    $update->close();

    // Fetch doctor name safely
    $doc = $conn->prepare("SELECT Name FROM doctor WHERE DID = ?");
    $doc->bind_param("i", $did);
    $doc->execute();
    $docResult   = $doc->get_result();
    $doctor_name = ($docResult && $docResult->num_rows > 0) ? $docResult->fetch_assoc()["Name"] : "Doctor";
    $doc->close();

    echo json_encode([
        "success"     => true,
        "doctor_name" => htmlspecialchars($doctor_name),
        "day"         => htmlspecialchars($day),
        "time"        => htmlspecialchars($time)
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Booking failed. Please try again."]);
}

$conn->close();
?>
