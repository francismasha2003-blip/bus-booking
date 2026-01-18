<?php
session_start();
include '../includes/config.php';
if(!isset($_SESSION['admin_id'])){ header("Location: admin_login.php"); exit(); }

// Fetch buses for dropdown
$buses = mysqli_query($conn, "SELECT * FROM buses");

if(isset($_POST['submit'])){
    $bus_id = $_POST['bus_id'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $travel_date = $_POST['travel_date'];
    $price = $_POST['price'];

    $query = "INSERT INTO trips (bus_id, origin, destination, travel_date, price) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isssd", $bus_id, $origin, $destination, $travel_date, $price);
    mysqli_stmt_execute($stmt);

    $success = "Trip added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Trip</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f0f2f5; }
.form-card { max-width: 500px; margin: 50px auto; padding: 30px; background:white; border-radius:20px; box-shadow:0 4px 20px rgba(0,0,0,0.1);}
</style>
</head>
<body>
<div class="form-card">
<h2 class="text-center mb-4">Add Trip</h2>
<?php if(isset($success)){ echo "<div class='alert alert-success'>$success</div>"; } ?>
<form method="POST">
<select name="bus_id" class="form-control mb-3" required>
<option value="">Select Bus</option>
<?php while($bus = mysqli_fetch_assoc($buses)) { ?>
<option value="<?php echo $bus['id']; ?>"><?php echo $bus['bus_name']." - ".$bus['plate_number']; ?></option>
<?php } ?>
</select>
<input type="text" name="origin" class="form-control mb-3" placeholder="Origin" required>
<input type="text" name="destination" class="form-control mb-3" placeholder="Destination" required>
<input type="date" name="travel_date" class="form-control mb-3" required>
<input type="number" step="0.01" name="price" class="form-control mb-3" placeholder="Price" required>
<button type="submit" name="submit" class="btn btn-success w-100">Add Trip</button>
</form>
<a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">⬅ Back</a>
</div>
</body>
</html>
