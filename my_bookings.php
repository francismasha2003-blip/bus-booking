<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/config.php';

$userId = $_SESSION['user_id'];

// Get bookings
$sql = "SELECT bookings.id, bookings.seat_number, bookings.created_at,
        trips.origin, trips.destination, trips.travel_date, trips.price,
        buses.bus_name, buses.plate_number
        FROM bookings
        JOIN trips ON bookings.trip_id = trips.id
        JOIN buses ON trips.bus_id = buses.id
        WHERE bookings.user_id = $userId
        ORDER BY bookings.created_at DESC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f2f5;
        }

        .page-container {
            max-width: 900px;
            margin: auto;
            margin-top: 50px;
        }

        .card-style {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .topbar {
            background: #343a40;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .header-text {
            font-size: 30px;
            font-weight: 700;
        }

        .booking-box {
            background: #ffffff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .booking-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .booking-sub {
            color: #555;
        }

        .btn-back {
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
        }
    </style>
</head>

<body>

<div class="topbar">
    <h3>📘 My Bookings</h3>
</div>

<div class="page-container">
    <div class="card-style">

        <h1 class="header-text text-center mb-4">
            Hello <?php echo $_SESSION['user_name']; ?>, here are your bookings 📄
        </h1>

        <?php if (mysqli_num_rows($result) > 0) { ?>
            
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="booking-box">

                    <p class="booking-title">
                        🚌 <?php echo $row['bus_name']; ?> — <?php echo $row['plate_number']; ?>
                    </p>

                    <p class="booking-sub">
                        <strong>From:</strong> <?php echo $row['origin']; ?><br>
                        <strong>To:</strong> <?php echo $row['destination']; ?><br>
                        <strong>Date:</strong> <?php echo $row['travel_date']; ?><br>
                        <strong>Seat:</strong> <?php echo $row['seat_number']; ?><br>
                        <strong>Price:</strong> KES <?php echo number_format($row['price']); ?><br>
                        <strong>Booked At:</strong> <?php echo $row['created_at']; ?>
                    </p>

                </div>
            <?php } ?>

        <?php } else { ?>
            <p class="text-center mt-4" style="font-size: 18px; color: #777;">
                😪 You have no bookings yet.  
                <br><br>
                <a href="trips.php" class="btn btn-primary btn-back">Book a Trip</a>
            </p>
        <?php } ?>

        <div class="text-center mt-3">
            <a href="dashboard.php" class="btn btn-secondary btn-back">⬅ Back to Dashboard</a>
        </div>

    </div>
</div>

</body>
</html>
