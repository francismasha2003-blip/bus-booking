<?php
session_start();
include '../includes/config.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // MD5 match

    // Fetch admin
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin' LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $admin = $res->fetch_assoc();

        if ($password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['fullname'];

            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "❌ Invalid password";
        }
    } else {
        $error = "❌ Admin not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #e9ecef;
        }

        .login-container {
            max-width: 450px;
            margin: 100px auto;
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.15);
        }

        .title {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
        }

        .topbar {
            background: #212529;
            padding: 15px;
            color: #fff;
            text-align: center;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            border-radius: 10px;
            font-weight: bold;
        }

        .error-box {
            background: #f8d7da;
            color: #842029;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="topbar">
    <h3 class="m-0">🛠 Admin Panel</h3>
</div>

<div class="login-container">

    <h1 class="title mb-4">Admin Login</h1>

    <?php if (!empty($error)): ?>
        <div class="error-box"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label"><strong>Email</strong></label>
            <input type="email" name="email" class="form-control" required placeholder="admin@bus.com">
        </div>

        <div class="mb-4">
            <label class="form-label"><strong>Password</strong></label>
            <input type="password" name="password" class="form-control" required placeholder="Enter admin password">
        </div>

        <button type="submit" name="login" class="btn btn-primary btn-login">
            Login 🔐
        </button>

    </form>

</div>

</body>
</html>
