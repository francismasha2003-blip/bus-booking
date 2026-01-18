<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f2f5;
        }

        .dashboard-card {
            border-radius: 20px;
            padding: 40px;
            max-width: 700px;
            margin: auto;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .btn-main {
            padding: 15px;
            font-size: 18px;
            border-radius: 12px;
            font-weight: bold;
        }

        .header-text {
            font-size: 32px;
            font-weight: 700;
            color: #333;
        }

        .sub-text {
            font-size: 18px;
            color: #555;
        }

        .topbar {
            background: #343a40;
            padding: 15px;
            color: white;
        }
    </style>
</head>

<body>

<div class="topbar text-center">
    <h3 class="m-0">🚌 Bus Booking System</h3>
</div>

<div class="container mt-5">
    <div class="dashboard-card">

        <h1 class="header-text text-center">Welcome, <?php echo $_SESSION['user_name']; ?> 👋</h1>
        <p class="sub-text text-center mb-4">
            What would you like to do today?
        </p>

        <div class="d-grid gap-4 mt-4">
            <a href="trips.php" class="btn btn-primary btn-main">🚌 View Available Trips</a>
            <a href="my_bookings.php" class="btn btn-success btn-main">📘 My Bookings</a>
            <a href="logout.php" class="btn btn-danger btn-main">🚪 Logout</a>
        </div>

    </div>
</div>

</body>
</html>
