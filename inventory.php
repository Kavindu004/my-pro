<?php
session_start(); // For Flash Messages
require_once 'includes/db_connect.php';

$flash_message = null;
if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']); // Clear flash message after displaying
}

$pageTitle = "Inventory - Farm Inventory";
include 'includes/header.php';

$inventory_items_list = []; // Initialize for safety
$inventory_error = null;

if ($db_connection) {
    $sql = "SELECT ii.id, ii.item_name, ii.item_type, ii.quantity, ii.unit_of_measure,
                   loc.name as location_name, ii.health_status, ii.growth_stage
            FROM inventory_items ii
            LEFT JOIN locations loc ON ii.location_id = loc.id
            ORDER BY ii.item_name ASC";

    $result = mysqli_query($db_connection, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $inventory_items_list[] = $row;
        }
        mysqli_free_result($result);
    } else {
        $inventory_error = "Error fetching inventory: " . mysqli_error($db_connection);
    }
} else {
    $inventory_error = "Database connection not available.";
}
?>

<div class="dashboard-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Inventory List</h1>

        <?php if ($flash_message): ?>
            <div style="padding: 10px; margin-bottom: 15px; border: 1px solid <?php echo ($flash_message['type'] == 'success' ? 'green' : 'red'); ?>; color: <?php echo ($flash_message['type'] == 'success' ? 'green' : 'red'); ?>; background-color: <?php echo ($flash_message['type'] == 'success' ? '#e6ffe6' : '#ffe6e6'); ?>;">
                <?php echo htmlspecialchars($flash_message['text']); ?>
            </div>
        <?php endif; ?>

        <div class="inventory-controls" style="margin-bottom: 20px; padding: 15px; background-color:#fff; border:1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <input type="text" placeholder="Search Inventory..." name="search" style="width: 250px; padding: 8px;">
                <select name="filter_type" style="padding: 8px;">
                    <option value="">Filter by Type...</option>
                    <option value="animal">Animal</option>
                    <option value="crop">Crop</option>
                    <option value="equipment">Equipment</option>
                    <option value="supplies">Supplies</option>
                </select>
                <select name="filter_status" style="padding: 8px;">
                    <option value="">Filter by Status...</option>
                    <option value="healthy">Healthy</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="maintenance_required">Maintenance Required</option>
                </select>
                <button class="btn btn-secondary" style="padding: 8px 15px;">Apply Filters</button>
            </div>
            <div>
                <a href="add_item.php" class="btn">Add New Item</a>
                <button class="btn btn-secondary">Export to CSV</button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($inventory_error): ?>
                    <tr><td colspan="7" style="color: red; text-align: center;"><?php echo htmlspecialchars($inventory_error); ?></td></tr>
                <?php elseif (!empty($inventory_items_list)): ?>
                    <?php foreach ($inventory_items_list as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['id']); ?></td>
                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['item_type']); ?></td>
                        <td><?php echo htmlspecialchars(is_numeric($item['quantity']) ? rtrim(rtrim(number_format($item['quantity'], 2), '0'), '.') : $item['quantity']) . ($item['unit_of_measure'] ? ' ' . htmlspecialchars($item['unit_of_measure']) : ''); ?></td>
                        <td><?php echo htmlspecialchars($item['location_name'] ? $item['location_name'] : 'N/A'); ?></td>
                        <td>
                            <?php
                            if ($item['item_type'] == 'Animal' && !empty($item['health_status'])) {
                                echo htmlspecialchars($item['health_status']);
                            } elseif ($item['item_type'] == 'Crop' && !empty($item['growth_stage'])) {
                                echo htmlspecialchars($item['growth_stage']);
                            } else {
                                if (empty($item['health_status']) && empty($item['growth_stage'])) {
                                    echo 'Available';
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <a href="edit_item.php?id=<?php echo htmlspecialchars($item['id']); ?>" class="btn btn-secondary" style="margin-right: 5px; padding: 5px 10px;">Edit</a>
                            <a href="#" class="btn btn-danger" style="padding: 5px 10px;" onclick="return confirm('Are you sure you want to delete this item ID: <?php echo htmlspecialchars($item['id']); ?>? This action is not yet functional.');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7">No inventory items found. <?php if ($db_connection) echo '<a href="add_item.php">Add some items?</a>'; ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
