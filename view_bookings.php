<?php
session_start();
include '../includes/config.php';
if(!isset($_SESSION['admin_id'])){ header("Location: admin_login.php"); exit(); }

$sql = "SELECT bookings.id AS booking_id, users.fullname AS user_name, trips.origin, trips.destination,
        trips.travel_date, bookings.seat_number, trips.price, buses.bus_name
        FROM bookings
        JOIN users ON bookings.user_id = users.id
        JOIN trips ON bookings.trip_id = trips.id
        JOIN buses ON trips.bus_id = buses.id
        ORDER BY bookings.created_at DESC";
$result = mysqli_query($conn, $sql);

// Error handling
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Bookings</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f0f2f5; }
.table-card { max-width: 1000px; margin: 50px auto; background:white; border-radius:20px; padding:20px; box-shadow:0 4px 20px rgba(0,0,0,0.1);}
</style>
</head>
<body>
<div class="table-card">
<h2 class="text-center mb-4">All Bookings</h2>
<table class="table table-striped table-bordered">
<thead class="table-dark">
<tr>
<th>#</th>
<th>User</th>
<th>Bus</th>
<th>Origin</th>
<th>Destination</th>
<th>Date</th>
<th>Seat</th>
<th>Price</th>
</tr>
</thead>
<tbody>
<?php if(mysqli_num_rows($result) > 0){ 
    $i=1; 
    while($row = mysqli_fetch_assoc($result)){ 
        $travel_date = !empty($row['travel_date']) ? date('M d, Y', strtotime($row['travel_date'])) : 'N/A';
        $price = !empty($row['price']) && is_numeric($row['price']) ? number_format($row['price']) : '0';
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo htmlspecialchars($row['user_name'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($row['bus_name'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($row['origin'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($row['destination'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo $travel_date; ?></td>
<td><?php echo htmlspecialchars($row['seat_number'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo $price; ?></td>
</tr>
<?php } 
} else { ?>
<tr><td colspan="8" class="text-center">No bookings yet.</td></tr>
<?php } ?>
</tbody>
</table>
<a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">⬅ Back</a>
</div>
</body>
</html>