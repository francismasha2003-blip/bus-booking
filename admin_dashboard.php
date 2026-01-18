<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f0f2f5; }
.dashboard-card {
    max-width: 700px; margin: 50px auto; padding: 40px;
    background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.btn-main { padding: 15px; font-size: 18px; border-radius: 12px; font-weight: bold; }
.topbar { background: #343a40; padding: 15px; color: white; text-align: center; }
</style>
</head>
<body>
<div class="topbar">
    <h3>🛠 Admin Panel</h3>
</div>
<div class="dashboard-card text-center">
    <h2>Welcome, <?php echo $_SESSION['admin_name']; ?> 👋</h2>
    <div class="d-grid gap-4 mt-4">
        <a href="add_bus.php" class="btn btn-primary btn-main">➕ Add Bus</a>
        <a href="add_trip.php" class="btn btn-success btn-main">➕ Add Trip</a>
        <a href="view_buses.php" class="btn btn-info btn-main">🚌 View Buses</a>
        <a href="view_trips.php" class="btn btn-info btn-main">🛣️ View Trips</a>
        <a href="view_bookings.php" class="btn btn-warning btn-main">📄 View Bookings</a>
        <a href="admin_login.php" class="btn btn-danger btn-main">🚪 Logout</a>
    </div>
</div>
</body>
</html>
