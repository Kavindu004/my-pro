<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin_loggedin']) || $_SESSION['is_admin_loggedin'] !== true) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit; // Important to stop further script execution
}

require_once 'includes/db_connect.php'; // Include Database Connection

// Initialize dynamic data with fallbacks
$animal_count_display = "120 (Placeholder)"; // Original placeholder
$crop_fields_display = "45 (Placeholder)"; // Original placeholder
// $low_stock_alerts_display = "3 (Placeholder)"; // Low stock alerts are more complex, handle later

if ($db_connection) { // Check if database connection is established
    // Fetch Animal Count
    $sql_animals = "SELECT COUNT(*) as total_animals FROM inventory_items WHERE item_type = 'Animal'";
    $result_animals = mysqli_query($db_connection, $sql_animals);
    if ($result_animals && mysqli_num_rows($result_animals) > 0) {
        $row_animals = mysqli_fetch_assoc($result_animals);
        $animal_count_display = $row_animals['total_animals'];
        mysqli_free_result($result_animals);
    } else {
        // Query failed or no rows, keep placeholder or set to "N/A" or 0
        $animal_count_display = "0 (Error)"; 
    }

    // Fetch Crop Count (Number of distinct crop types or total crop items)
    // For simplicity, let's count total items of type 'Crop'
    $sql_crops = "SELECT COUNT(*) as total_crops FROM inventory_items WHERE item_type = 'Crop'";
    $result_crops = mysqli_query($db_connection, $sql_crops);
    if ($result_crops && mysqli_num_rows($result_crops) > 0) {
        $row_crops = mysqli_fetch_assoc($result_crops);
        // The wireframe says "45 fields", which is different from item count.
        // For now, we'll display the count of 'Crop' type items.
        // This could be refined later if "fields" means something else (e.g., distinct locations for crops).
        $crop_fields_display = $row_crops['total_crops'] . " crop items"; 
        mysqli_free_result($result_crops);
    } else {
        $crop_fields_display = "0 crop items (Error)";
    }
    
    // Note: $db_connection is usually closed automatically at script end.
} else {
    // $db_connection was null, use fallback messages
    $animal_count_display = "N/A (DB Error)";
    $crop_fields_display = "N/A (DB Error)";
}

$pageTitle = "Dashboard - Farm Inventory";
include 'includes/header.php'; 
?>

<div class="dashboard-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Welcome, [User]!</h1> {/* Placeholder for username */}
        
        <div id="dashboard-stats">
            <h2>Quick Stats</h2>
            <p>Total Animals: <strong><?php echo $animal_count_display; ?></strong></p>
            <p>Total Crop Fields/Items: <strong><?php echo $crop_fields_display; ?></strong></p> 
            <p>Low Stock Alerts: <strong>3</strong> (Placeholder - to be implemented later)</p>
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
