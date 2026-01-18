<?php
session_start();
include 'includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($user['fullname']); ?>!</h2>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

<hr>

<h3>Available Actions:</h3>
<ul>
    <li><a href="trips.php">View Trips</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

</body>
</html>
