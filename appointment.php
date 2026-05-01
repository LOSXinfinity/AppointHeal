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
            $message = "Ã¢ÂÅ’ This slot is already booked.";
        } else {
            $stmt = $conn->prepare("INSERT INTO appointment (PID, DID, day, time, status) VALUES (?, ?, ?, ?, 1)");
            $stmt->bind_param("iiss", $pid, $did, $day, $time);
            if ($stmt->execute()) {
                $update = $conn->prepare("UPDATE doctor_time SET isBooked = 1 WHERE DID = ? AND time = ?");
                $update->bind_param("is", $did, $time);
                $update->execute();
                $message = "Ã¢Å“â€¦ Appointment booked successfully!";
            } else {
                $message = "Ã¢Å¡Â Ã¯Â¸Â Booking failed. Please try again.";
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

            $message = "Ã¢Å“â€¦ Appointment cancelled.";
        } else {
            $message = "Ã¢Å¡Â Ã¯Â¸Â No appointment found.";
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
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap");

        :root {
            --surface:       rgba(255,255,255,0.05);
            --surface-hover: rgba(255,255,255,0.09);
            --border:        rgba(255,255,255,0.10);
            --text-primary:  #f8fafc;
            --text-muted:    #94a3b8;
            --accent:        #f59e0b;
            --accent-light:  #fbbf24;
            --danger:        #ef4444;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "Inter", sans-serif;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: auto auto 1fr auto;
            gap: 24px;
            min-height: 100vh;
            padding: 24px;
        }

        .item { box-sizing: border-box; }

        .item.header, .item.footer { grid-column: 1 / -1; }

        .item.header h1 { margin: 0; font-size: inherit; font-weight: inherit; }

        #sidebar    { grid-row: 2 / 4; grid-column: 1 / 4; }
        #navigation { grid-column: 4 / 10; }
        #ads        { grid-row: 2 / 4; grid-column: 10 / 13; }
        #main       { grid-column: 4 / 10; grid-row: 3 / 4; }

        /* Glass panels */
        #sidebar, #navigation, #ads, #main {
            background: var(--surface);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            padding: 24px;
            color: var(--text-primary);
        }

        h3, h2 {
            font-family: "Inter", sans-serif;
            margin-top: 0;
            margin-bottom: 16px;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 1px solid var(--border);
            padding-bottom: 8px;
            color: var(--text-primary);
        }

        p, label {
            font-family: "Inter", sans-serif;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            display: block;
            color: var(--text-primary);
        }

        select {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 16px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.07);
            color: var(--text-primary);
            font-size: 16px;
            font-family: "Inter", sans-serif;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        select:focus {
            outline: none;
            background: rgba(255,255,255,0.12);
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(245,158,11,0.2);
        }
        select option { background: #1e293b; color: #f8fafc; }

        button {
            width: 100%;
            background: var(--accent);
            color: #0f172a;
            border: none;
            font-size: 16px;
            font-family: "Inter", sans-serif;
            font-weight: 900;
            cursor: pointer;
            border-radius: 48px;
            box-shadow: 0 4px 16px rgba(245,158,11,0.4);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 16px;
            margin-top: 8px;
        }
        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(245,158,11,0.6);
            background: var(--accent-light);
        }

        #logoutBtn, #cancelBtn {
            background: var(--danger);
            color: #fff;
            box-shadow: 0 4px 16px rgba(239,68,68,0.4);
        }
        #logoutBtn:hover, #cancelBtn:hover {
            background: #f87171;
            box-shadow: 0 8px 24px rgba(239,68,68,0.6);
        }

        #backBtn {
            background: #334155;
            color: #fff;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            margin-top: 16px;
        }
        #backBtn:hover {
            background: #475569;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
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
            <a href="doctor_dashboard.php"><button type="button" id="backBtn">Back to Dashboard</button></a>
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
            AppointHeal connects patients with the best doctors. Your health, our priority. Ã¢Å“â€¦
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>


