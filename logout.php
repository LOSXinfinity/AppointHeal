<?php
session_start();
session_destroy(); // Destroys all session data
header("Location: login.html"); // Redirect to login page
exit();
?>
