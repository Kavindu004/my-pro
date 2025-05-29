<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin_loggedin']) || $_SESSION['is_admin_loggedin'] !== true) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit; // Important to stop further script execution
}
?>
<?php 
$pageTitle = "Dashboard - Farm Inventory";
include 'includes/header.php'; 
?>

<div class="dashboard-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Welcome, [User]!</h1> {/* Placeholder for username */}
        
        <div id="dashboard-stats">
            <h2>Quick Stats</h2>
            <p>Total Animals: <strong>120</strong> (Placeholder)</p>
            <p>Total Crop Fields: <strong>45</strong> (Placeholder)</p>
            <p>Low Stock Alerts: <strong>3</strong> (Placeholder)</p>
        </div>

        <div id="dashboard-quick-actions">
            <h2>Quick Actions</h2>
            <a href="add_item.php?type=animal" class="btn">Add New Animal</a>
            <a href="add_item.php?type=crop" class="btn">Add New Crop</a>
            <a href="reports.php" class="btn btn-secondary">Generate Report</a>
        </div>

        <div id="dashboard-recent-activity">
            <h2>Recent Activity Log</h2>
            <ul>
                <li>User [X] added [Y] (Placeholder) - <em>1 hour ago</em></li>
                <li>Item [Z] updated (Placeholder) - <em>3 hours ago</em></li>
                <li>Report [A] generated (Placeholder) - <em>Yesterday</em></li>
            </ul>
            <p><a href="#">View all activity...</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
