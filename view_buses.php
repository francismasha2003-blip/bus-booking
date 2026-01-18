<?php
session_start();
include '../includes/config.php';
if(!isset($_SESSION['admin_id'])){ header("Location: admin_login.php"); exit(); }

$sql = "SELECT * FROM buses";
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
<title>All Buses</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f0f2f5; }
.table-card { max-width: 1000px; margin: 50px auto; background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
.topbar { background: #343a40; padding: 15px; color: white; text-align: center; }
</style>
</head>
<body>
<div class="topbar">
    <h3>🚌 All Buses</h3>
</div>
<div class="table-card">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Buses List</h2>
    <a href="add_bus.php" class="btn btn-primary">➕ Add New Bus</a>
</div>
<table class="table table-striped table-bordered">
<thead class="table-dark">
<tr>
<th>#</th>
<th>Bus Name</th>
<th>Plate Number</th>
<th>Seats</th>
<th>Created At</th>
</tr>
</thead>
<tbody>
<?php if(mysqli_num_rows($result) > 0): 
    $i = 1;
    while($bus = mysqli_fetch_assoc($result)): 
        $created_at = date('M d, Y', strtotime($bus['created_at']));
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo htmlspecialchars($bus['bus_name'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo htmlspecialchars($bus['plate_number'], ENT_QUOTES, 'UTF-8'); ?></td>
<td><?php echo $bus['seats']; ?></td>
<td><?php echo $created_at; ?></td>
</tr>
<?php endwhile; 
else: ?>
<tr><td colspan="5" class="text-center">No buses found.</td></tr>
<?php endif; ?>
</tbody>
</table>
<a href="admin_dashboard.php" class="btn btn-secondary w-100 mt-3">⬅ Back to Dashboard</a>
</div>
</body>
</html>