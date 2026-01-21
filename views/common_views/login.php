<?php
session_start();

if (isset($_SESSION['user_id'])) {

    if ($_SESSION['role'] === 'manager') {
        header("Location: ../manager_views/home.php");
        exit();
    } else {
        header("Location: ../customer_views/home.php");
        exit();
    }
}

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$err = isset($_GET['err']) ? $_GET['err'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurfSlot - Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1 class="title">Login</h1>
            <p class="subtitle">Login to your TurfSlot account</p>

            <?php if ($msg === 'registered'): ?>
                <div class="alert success">Registration successful. Please login.</div>
            <?php endif; ?>

            <?php if ($msg === 'logout'): ?>
                <div class="alert success">You have been logged out.</div>
            <?php endif; ?>

            <?php if ($err): ?>
                <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
            <?php endif; ?>

            <form id="loginForm" class="form" action="../../controllers/authControl.php" method="POST" novalidate>
                <input type="hidden" name="action" value="login">

                <label>Email</label>
                <input type="email" name="email" id="loginEmail" placeholder="example@mail.com" required>

                <label>Password</label>
                <input type="password" name="password" id="loginPassword" placeholder="Your password" required>

                <button type="submit" class="btn">Login</button>
            </form>

            <div class="footer-text">
                Don’t have an account?
                <a href="register.php">Register</a>
            </div>
        </div>
    </div>
</body>

</html>