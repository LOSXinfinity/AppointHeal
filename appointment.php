<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['PID'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
$pid = $_SESSION['PID'];
$message = "";

// Handle booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'book') {
        $day = trim($_POST['day']);
        $time = trim($_POST['time']);

        // Check if slot already booked
        $stmt = $conn->prepare("SELECT 1 FROM appointment WHERE DID = ? AND day = ? AND time = ? AND status = 1");
        $stmt->bind_param("iss", $did, $day, $time);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $message = "❌ This slot is already booked.";
        } else {
            $stmt = $conn->prepare("INSERT INTO appointment (PID, DID, day, time, status) VALUES (?, ?, ?, ?, 1)");
            $stmt->bind_param("iiss", $pid, $did, $day, $time);
            if ($stmt->execute()) {
                $update = $conn->prepare("UPDATE doctor_time SET isBooked = 1 WHERE DID = ? AND time = ?");
                $update->bind_param("is", $did, $time);
                $update->execute();
                $message = "✅ Appointment booked successfully!";
            } else {
                $message = "⚠️ Booking failed. Please try again.";
            }
        }
    }

    // Handle cancellation
    if (isset($_POST['action']) && $_POST['action'] === 'cancel') {
        $stmt = $conn->prepare("SELECT day, time FROM appointment WHERE PID = ? AND DID = ? AND status = 1");
        $stmt->bind_param("ii", $pid, $did);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if ($res) {
            $day = $res['day'];
            $time = $res['time'];

            $stmt = $conn->prepare("DELETE FROM appointment WHERE PID = ? AND DID = ?");
            $stmt->bind_param("ii", $pid, $did);
            $stmt->execute();

            $update = $conn->prepare("UPDATE doctor_time SET isBooked = 0 WHERE DID = ? AND time = ?");
            $update->bind_param("is", $did, $time);
            $update->execute();

            $message = "✅ Appointment cancelled.";
        } else {
            $message = "⚠️ No appointment found.";
        }
    }
}

// Fetch doctor info
$stmt = $conn->prepare("SELECT * FROM doctor WHERE DID = ?");
$stmt->bind_param("i", $did);
$stmt->execute();
$doctor = $stmt->get_result()->fetch_assoc();

// Available days
$stmt = $conn->prepare("SELECT days FROM doctor_available_days WHERE DID = ?");
$stmt->bind_param("i", $did);
$stmt->execute();
$days_result = $stmt->get_result();
$available_days = [];
while ($row = $days_result->fetch_assoc()) {
    $available_days[] = $row['days'];
}

// Available times
$stmt = $conn->prepare("SELECT time FROM doctor_time WHERE DID = ? AND isBooked = 0");
$stmt->bind_param("i", $did);
$stmt->execute();
$times_result = $stmt->get_result();
$available_times = [];
while ($row = $times_result->fetch_assoc()) {
    $available_times[] = $row['time'];
}

// Check existing appointment
$stmt = $conn->prepare("SELECT * FROM appointment WHERE PID = ? AND DID = ? AND status = 1");
$stmt->bind_param("ii", $pid, $did);
$stmt->execute();
$appointment = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: auto auto 1fr auto;
            gap: 25px;
            min-height: 100vh;
            padding: 30px;
            box-sizing: border-box;
        }

        .item {
            box-sizing: border-box;
        }

        .item.header, .item.footer {
            grid-column: 1 / -1;
        }

        .item.header h1 {
            margin: 0;
            font-size: inherit;
            font-weight: inherit;
        }

        #sidebar {
            grid-row: 2 / 4;
            grid-column: 1 / 4;
        }

        #navigation {
            grid-column: 4 / 10;
        }

        #ads {
            grid-row: 2 / 4;
            grid-column: 10 / 13;
        }

        #main {
            grid-column: 4 / 10;
            grid-row: 3 / 4;
        }

        /* Luxurious Glass Panels */
        #sidebar, #navigation, #ads, #main {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            padding: 30px;
            color: #fff;
        }

        h3, h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
            text-shadow: 0 2px 5px rgba(0,0,0,0.2);
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding-bottom: 12px;
        }

        p, label {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            display: block;
            text-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }

        select {
            width: 100%;
            padding: 14px;
            margin-bottom: 25px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.3);
            color: #0f172a;
            font-size: 16px;
            font-weight: 700;
            box-sizing: border-box;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        select:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.6);
            border-color: #fff;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }

        select option {
            color: #000;
        }

        button {
            width: 100%;
            background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
            color: #1fa2ff; 
            border: none;
            font-size: 16px;
            font-weight: 900;
            cursor: pointer;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 16px;
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.6);
            background: #fff;
        }

        #logoutBtn, #cancelBtn {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: white;
            box-shadow: 0 5px 15px rgba(255, 65, 108, 0.4);
        }

        #logoutBtn:hover, #cancelBtn:hover {
            background: linear-gradient(135deg, #ff4b2b, #ff416c);
            box-shadow: 0 8px 30px rgba(255, 65, 108, 0.8);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="item header">
            <h1><span class="red">Appoint</span><span class="green">Heal</span></h1>
        </div>

        <!-- Sidebar -->
        <div class="item" id="sidebar">
            <h3>Status</h3>
            <p><?php echo $message ?: "Select a time slot to book an appointment."; ?></p>
            <?php if ($appointment): ?>
                <form method="POST">
                    <input type="hidden" name="action" value="cancel">
                    <button type="submit" id="cancelBtn">Cancel Appointment</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Navigation -->
        <div class="item" id="navigation">
            <h3>Appointment with</h3>
            <p><?php echo htmlspecialchars($doctor['Name']); ?></p>
        </div>

        <!-- Ads -->
        <div class="item" id="ads">
            <h3>Notice</h3>
            <p>Check slot availability before booking!</p>
            <form action="logout.php" method="post">
                <button type="submit" id="logoutBtn">Logout</button>
            </form>
        </div>

        <!-- Main -->
        <div class="item" id="main">
            <?php if (!$appointment): ?>
                <form method="POST">
                    <input type="hidden" name="action" value="book">
                    <label for="day">Select Day:</label>
                    <select name="day" id="day" required>
                        <option value="">--Select Day--</option>
                        <?php foreach ($available_days as $day): ?>
                            <option value="<?php echo htmlspecialchars($day); ?>"><?php echo htmlspecialchars($day); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="time">Select Time:</label>
                    <select name="time" id="time" required>
                        <option value="">--Select Time--</option>
                        <?php foreach ($available_times as $time): ?>
                            <option value="<?php echo htmlspecialchars($time); ?>"><?php echo htmlspecialchars($time); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">Make Appointment</button>
                </form>
            <?php else: ?>
                <p>Appointment already booked. You can cancel it from the sidebar.</p>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="item footer">
            AppointHeal connects patients with the best doctors. Your health, our priority. ✅
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
