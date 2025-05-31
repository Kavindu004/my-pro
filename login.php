<?php
session_start(); // Start session at the very top

// If already logged in, redirect to dashboard
if (isset($_SESSION['is_admin_loggedin']) && $_SESSION['is_admin_loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
}

$login_error = null; // Initialize error variable

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['is_admin_loggedin'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $login_error = "Invalid username or password.";
    }
}

$pageTitle = "Login - Farm Inventory";
$bodyClass = "login-page-body";
include 'includes/header.php';
?>

<div class="login-card">
    <img src="images/logo_placeholder.png" alt="Farm Logo">
    <h2>Login</h2>

    <?php if ($login_error): ?>
        <p style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($login_error); ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <button type="submit" class="btn">Login</button>
        </div>
        <div style="margin-top: 15px;">
            <a href="#">Forgot Password?</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
