<?php
session_start();
include 'includes/config.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch available trips
$sql = "SELECT trips.id AS trip_id, buses.bus_name, trips.origin, trips.destination, trips.travel_date, trips.price
        FROM trips
        JOIN buses ON trips.bus_id = buses.id
        ORDER BY trips.travel_date ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Trips</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Available Trips</h2>
        <div>
            <a href="my_bookings.php" class="btn btn-primary me-2">My Bookings</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <?php if(mysqli_num_rows($result) > 0): ?>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Bus</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Date</th>
                    <th>Price (KES)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($trip = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $trip['trip_id']; ?></td>
                    <td><?php echo htmlspecialchars($trip['bus_name']); ?></td>
                    <td><?php echo htmlspecialchars($trip['origin']); ?></td>
                    <td><?php echo htmlspecialchars($trip['destination']); ?></td>
                    <td><?php echo $trip['travel_date']; ?></td>
                    <td><?php echo $trip['price']; ?></td>
                    <td>
                        <a href="book.php?trip_id=<?php echo $trip['trip_id']; ?>" class="btn btn-success btn-sm">Book Now</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No trips available right now.</div>
    <?php endif; ?>

</div>
</body>
</html>
