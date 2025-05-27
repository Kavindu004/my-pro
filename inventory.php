<?php 
$pageTitle = "Inventory - Farm Inventory";
include 'includes/header.php'; 
?>

<div class="dashboard-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Inventory List</h1>

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
                <?php 
                // Placeholder data - In a real application, this would come from a database
                $inventoryItems = [
                    ['id' => '001', 'name' => 'Holstein Cow', 'type' => 'Animal', 'quantity' => 15, 'location' => 'Barn A', 'status' => 'Healthy'],
                    ['id' => '002', 'name' => 'Wheat Grain', 'type' => 'Crop', 'quantity' => '200 kg', 'location' => 'Silo 3', 'status' => 'Good'],
                    ['id' => '003', 'name' => 'Tractor John D.', 'type' => 'Equipment', 'quantity' => 1, 'location' => 'Shed 1', 'status' => 'Operational'],
                    ['id' => '004', 'name' => 'Chicken Feed', 'type' => 'Supplies', 'quantity' => '12 bags', 'location' => 'Storage B', 'status' => 'Low Stock'],
                ];

                if (!empty($inventoryItems)) {
                    foreach ($inventoryItems as $item) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($item['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['type']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['location']) . "</td>";
                        echo "<td>" . htmlspecialchars($item['status']) . "</td>";
                        echo "<td>";
                        echo "<a href='edit_item.php?id=" . htmlspecialchars($item['id']) . "' class='btn btn-secondary' style='margin-right: 5px; padding: 5px 10px;'>Edit</a>";
                        echo "<a href='#' class='btn btn-danger' style='padding: 5px 10px;' onclick='return confirm(\"Are you sure you want to delete this item?\");'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No inventory items found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
    </div>
</div>

<?php include 'includes/footer.php'; ?>
