<?php
session_start();
include '../includes/config.php';
if(!isset($_SESSION['admin_id'])){ header("Location: admin_login.php"); exit(); }

if(isset($_POST['submit'])){
    $bus_name = $_POST['bus_name'];
    $plate_number = $_POST['plate_number'];
    $seats = $_POST['seats'];

    $query = "INSERT INTO buses (bus_name, plate_number, seats) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $bus_name, $plate_number, $seats);
    mysqli_stmt_execute($stmt);

    $success = "Bus added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Bus</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f0f2f5; }
.form-card { max-width: 500px; margin: 50px auto; padding: 30px; background:white; border-radius:20px; box-shadow:0 4px 20px rgba(0,0,0,0.1);}
</style>
</head>
<body>
<div class="form-card">
<h2 class="text-center mb-4">Add Bus</h2>
<?php if(isset($success)){ echo "<div class='alert alert-success'>$success</div>"; } ?>
<form method="POST">
<input type="text" name="bus_name" class="form-control mb-3" placeholder="Bus Name" required>
<input type="text" name="plate_number" class="form-control mb-3" placeholder="Plate Number" required>
<input type="number" name="seats" class="form-control mb-3" placeholder="Number of Seats" required>
<button type="submit" name="submit" class="btn btn-primary w-100">Add Bus</button>
</form>
<a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">⬅ Back</a>
</div>
</body>
</html>
