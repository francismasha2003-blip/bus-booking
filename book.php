<?php
session_start();
include 'includes/config.php'; // use the correct path to your DB config

// ensure user is logged in (we stored the user row in $_SESSION['user'])
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Get trip_id from URL
$trip_id = isset($_GET['trip_id']) ? intval($_GET['trip_id']) : 0;
if ($trip_id <= 0) {
    echo "Invalid trip selected.";
    exit;
}

// Fetch trip details and bus info
$sql = "SELECT t.id AS trip_id, t.origin, t.destination, t.travel_date, t.price, b.bus_name, b.seats
        FROM trips t
        JOIN buses b ON t.bus_id = b.id
        WHERE t.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $trip_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$trip = mysqli_fetch_assoc($res);

if (!$trip) {
    echo "Trip not found.";
    exit;
}

// fetch already booked seats for this trip
$booked_stmt = mysqli_prepare($conn, "SELECT seat_number FROM bookings WHERE trip_id = ?");
mysqli_stmt_bind_param($booked_stmt, "i", $trip_id);
mysqli_stmt_execute($booked_stmt);
$booked_res = mysqli_stmt_get_result($booked_stmt);
$booked_seats = [];
while ($r = mysqli_fetch_assoc($booked_res)) {
    $booked_seats[] = intval($r['seat_number']);
}

// handle booking submission
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seat = isset($_POST['seat']) ? intval($_POST['seat']) : 0;

    if ($seat < 1 || $seat > intval($trip['seats'])) {
        $error = "Invalid seat number.";
    } elseif (in_array($seat, $booked_seats)) {
        $error = "Seat already booked. Choose another.";
    } else {
        $insert = mysqli_prepare($conn, "INSERT INTO bookings (trip_id, user_id, seat_number) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($insert, "iii", $trip_id, $user_id, $seat);
        if (mysqli_stmt_execute($insert)) {
            header("Location: my_bookings.php");
            exit;
        } else {
            $error = "Booking failed: " . mysqli_error($conn);
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Book Seat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f4f6f8; }
    .seat-box { width:56px; height:56px; border-radius:8px; margin:6px; display:flex; align-items:center; justify-content:center; font-weight:600; cursor:pointer; user-select:none; }
    .available { background:#198754; color:#fff; }
    .booked { background:#dc3545; color:#fff; cursor:not-allowed; opacity:0.9; }
    .selected { outline:3px solid #0d6efd; }
    .seat-label { display:inline-block; }
    .seat-input { display:none; } /* we control selection via JS */
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Book Seat — <?= htmlspecialchars($trip['bus_name']) ?></h3>
    <div>
      <a href="trips.php" class="btn btn-secondary me-2">Back to Trips</a>
      <a href="my_bookings.php" class="btn btn-primary">My Bookings</a>
    </div>
  </div>

  <div class="card p-3 mb-4">
    <div><strong>From:</strong> <?= htmlspecialchars($trip['origin']) ?></div>
    <div><strong>To:</strong> <?= htmlspecialchars($trip['destination']) ?></div>
    <div><strong>Date:</strong> <?= htmlspecialchars($trip['travel_date']) ?></div>
    <div><strong>Price:</strong> KES <?= htmlspecialchars($trip['price']) ?></div>
    <div><strong>Total seats:</strong> <?= intval($trip['seats']) ?></div>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" id="bookingForm">
    <div class="mb-3">
      <div class="d-flex flex-wrap">
        <?php
        $totalSeats = intval($trip['seats']);
        for ($i = 1; $i <= $totalSeats; $i++):
            $isBooked = in_array($i, $booked_seats);
            $class = $isBooked ? 'booked' : 'available';
        ?>
          <label class="seat-label">
            <input class="seat-input" type="radio" name="seat" value="<?= $i ?>" <?= $isBooked ? 'disabled' : '' ?>>
            <div class="seat-box <?= $class ?>" data-seat="<?= $i ?>"><?= $i ?></div>
          </label>
        <?php endfor; ?>
      </div>
    </div>

    <button type="submit" class="btn btn-success btn-lg">Confirm Booking</button>
  </form>
</div>

<script>
// make seat boxes clickable and toggle selection
document.querySelectorAll('.seat-box').forEach(function(box) {
  box.addEventListener('click', function(){
    if (box.classList.contains('booked')) return; // can't select booked seats

    // unselect previously selected
    document.querySelectorAll('.seat-box.selected').forEach(function(s){ s.classList.remove('selected'); });
    // select this box
    box.classList.add('selected');

    // check the associated radio input
    var seatNumber = box.getAttribute('data-seat');
    var radio = document.querySelector('input.seat-input[value="'+seatNumber+'"]');
    if (radio) radio.checked = true;
  });
});

// optional: prevent form submit without selecting seat
document.getElementById('bookingForm').addEventListener('submit', function(e){
  var checked = document.querySelector('input[name="seat"]:checked');
  if (!checked) {
    e.preventDefault();
    alert('Please select a seat before confirming.');
  }
});
</script>
</body>
</html>
