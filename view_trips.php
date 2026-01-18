<?php
session_start();
include '../includes/config.php';
if(!isset($_SESSION['admin_id'])){ header("Location: admin_login.php"); exit(); }

$sql = "SELECT trips.id AS trip_id, buses.bus_name, trips.origin, trips.destination, trips.travel_date, trips.price
        FROM trips
        JOIN buses ON trips.bus_id = buses.id
        ORDER BY trips.travel_date DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Trips</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f0f2f5; }
.table-card { max-width: 1000px; margin: 50px auto; background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
.topbar { background: #343a40; padding: 15px; color: white; text-align: center; }
</style>
</head>
<body>
<div class="topbar">
    <h3>🛣️ All Trips</h3>
</div>
<div class="table-card">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Trips List</h2>
    <a href="add_trip.php" class="btn btn-success">➕ Add New Trip</a>
</div>
<table class="table table-striped table-bordered">
<thead class="table-dark">
<tr>
<th>#</th>
<th>Bus</th>
<th>From</th>
<th>To</th>
<th>Date</th>
<th>Price</th>
</tr>
</thead>
<tbody>
<?php if(mysqli_num_rows($result) > 0): 
    $i = 1;
    while($trip = mysqli_fetch_assoc($result)): 
        $travel_date = date('M d, Y', strtotime($trip['travel_date']));
        $price = number_format($trip['price']);
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo htmlspecialchars($trip['bus_name'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($trip['origin'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($trip['destination'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo $travel_date; ?></td>
<td><?php echo $price; ?></td>
</tr>
<?php endwhile; 
else: ?>
<tr><td colspan="6" class="text-center">No trips found.</td></tr>
<?php endif; ?>
</tbody>
</table>
<a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">⬅ Back to Dashboard</a>
</div>
</body>
</html>