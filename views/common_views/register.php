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

$err = isset($_GET['err']) ? $_GET['err'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurfSlot - Register</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script>
</head>

<body>
    <div class="container">
        <div class="card">
            <h1 class="title">Register</h1>
            <p class="subtitle">Create your TurfSlot account</p>

            <?php if ($err): ?>
                <div class="alert error"><?php echo htmlspecialchars($err); ?></div>
            <?php endif; ?>

            <form id="registerForm" class="form" action="../../controllers/authControl.php" method="POST" novalidate>
                <input type="hidden" name="action" value="register">

                <label>Name</label>
                <input type="text" name="name" id="regName" placeholder="Your full name" required>

                <label>Email</label>
                <input type="email" name="email" id="regEmail" placeholder="example@mail.com" required>

                <label>Phone</label>
                <input type="text" name="phone" id="regPhone" placeholder="01XXXXXXXXX" required>

                <label>Password</label>
                <input type="password" name="password" id="regPassword" placeholder="Min 6 characters" required>

                <label>User Type</label>
                <select name="role" id="regRole" required>
                    <option value="">Select role</option>
                    <option value="customer">Customer (Player)</option>
                    <option value="manager">Turf Manager</option>
                </select>

                <button type="submit" class="btn">Create Account</button>
            </form>

            <div class="footer-text">
                Already have an account?
                <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</body>

</html>