<?php
// Database connection parameters
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Default XAMPP username
define('DB_PASSWORD', '');     // Default XAMPP password (empty)
define('DB_NAME', 'farm_inventory_db'); // The database name you created

// Attempt to connect to MySQL database
$db_connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($db_connection === false) {
    // Connection failed, handle error
    // Set $db_connection to null, pages can check for this.
    $db_connection = null;
    // Optional: log the actual error to the server's error log for debugging
    // if (function_exists('error_log')) {
    //    error_log("MySQL Connection Error: " . mysqli_connect_error());
    // }
} else {
    // Optional: Set character set to utf8mb4 for full Unicode support
    if (!mysqli_set_charset($db_connection, "utf8mb4")) {
        // Optional: log this error if it occurs
        // if (function_exists('error_log')) {
        //     error_log("Error loading character set utf8mb4: " . mysqli_error($db_connection));
        // }
    }
}

// Note: No explicit mysqli_close($db_connection) here,
// PHP will automatically close the connection at the end of the script execution.
// If you need to close it earlier for specific reasons, you would call it explicitly.
?>
