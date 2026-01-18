<?php
include "includes/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conn, "INSERT INTO users(fullname, email, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $fullname, $email, $password);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Email already exists!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Bus Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="width: 480px; border-radius: 15px;">
        <h2 class="text-center mb-3">Create Account</h2>
        <p class="text-center text-muted">Join the Bus Booking System</p>

        <?php if (isset($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label fw-bold">Full Name</label>
                <input type="text" name="fullname" class="form-control p-2" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control p-2" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control p-2" required>
            </div>

            <button class="btn btn-success w-100 py-2">Register</button>
        </form>

        <p class="mt-3 text-center">
            Already have an account?  
            <a href="login.php" class="text-decoration-none fw-bold">Login</a>
        </p>
    </div>
</div>

</body>
</html>
