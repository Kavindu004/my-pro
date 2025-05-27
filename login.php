<?php
$pageTitle = "Login - Farm Inventory"; 
include 'includes/header.php'; 
?>

<div class="login-card">
    <img src="images/logo_placeholder.png" alt="Farm Logo"> 
    <h2>Login</h2>
    <form action="dashboard.php" method="POST"> {/* Action will eventually point to a login handler */}
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
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
