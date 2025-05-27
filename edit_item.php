<?php 
$pageTitle = "Edit Item - Farm Inventory";
$itemId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'N/A'; // Get item ID from URL

// --- Placeholder for fetching item data from database ---
// In a real application, you would use $itemId to fetch data from the database here.
// For now, we'll use placeholder values.
$item = null;
if ($itemId === '001') { // Example: if ID is 001 (Holstein Cow from inventory.php)
    $item = ['id' => '001', 'item_type' => 'animal', 'item_name' => 'Holstein Cow', 'quantity' => 15, 'unit_of_measure' => 'head', 'location' => 'Barn A', 'health_status' => 'healthy', 'growth_stage' => '', 'date_acquired' => '2023-01-15', 'notes' => 'Good condition.'];
} elseif ($itemId === '002') { // Example: if ID is 002 (Wheat Grain)
    $item = ['id' => '002', 'item_type' => 'crop', 'item_name' => 'Wheat Grain', 'quantity' => 200, 'unit_of_measure' => 'kg', 'location' => 'Silo 3', 'health_status' => '', 'growth_stage' => 'Harvested', 'date_acquired' => '2023-07-20', 'notes' => 'Ready for sale.'];
} else {
    // Default placeholder if ID doesn't match known examples or for a new item scenario (though this is edit page)
    $item = ['id' => $itemId, 'item_type' => '', 'item_name' => 'Sample Item', 'quantity' => 1, 'unit_of_measure' => 'item', 'location' => 'Default Location', 'health_status' => '', 'growth_stage' => '', 'date_acquired' => date('Y-m-d'), 'notes' => 'This is a sample item.'];
}
// --- End of placeholder data fetching ---

$itemType = $item['item_type']; // Set itemType for conditional fields logic

include 'includes/header.php'; 
?>

<div class="dashboard-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Edit Inventory Item (ID: <?php echo $item['id']; ?>)</h1>
        <form action="inventory.php" method="POST" style="padding: 20px; background-color: #fff; border: 1px solid #eee; border-radius: 5px;">
            {/* The action will eventually point to a script that updates the item data */}
            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
            
            <div>
                <label for="item_type">Item Type:</label>
                <select id="item_type" name="item_type" required>
                    <option value="">-- Select Type --</option>
                    <option value="animal" <?php echo ($item['item_type'] == 'animal') ? 'selected' : ''; ?>>Animal</option>
                    <option value="crop" <?php echo ($item['item_type'] == 'crop') ? 'selected' : ''; ?>>Crop</option>
                    <option value="equipment" <?php echo ($item['item_type'] == 'equipment') ? 'selected' : ''; ?>>Equipment</option>
                    <option value="supplies" <?php echo ($item['item_type'] == 'supplies') ? 'selected' : ''; ?>>Supplies</option>
                </select>
            </div>

            <div>
                <label for="item_name">Name:</label>
                <input type="text" id="item_name" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
            </div>

            <div>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" required min="0">
            </div>

            <div>
                <label for="unit_of_measure">Unit of Measure (e.g., head, kg, items, bags):</label>
                <input type="text" id="unit_of_measure" name="unit_of_measure" value="<?php echo htmlspecialchars($item['unit_of_measure']); ?>" placeholder="Unit (e.g., head, kg, bags)">
            </div>

            <div>
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($item['location']); ?>" placeholder="e.g., Barn A, Field 3, Shed 1">
            </div>

            <div id="animal_fields" style="display: <?php echo ($itemType == 'animal') ? 'block' : 'none'; ?>;">
                 <label for="health_status">Health Status (For Animals):</label>
                 <select id="health_status" name="health_status">
                     <option value="">-- Select Status --</option>
                     <option value="healthy" <?php echo ($item['health_status'] == 'healthy') ? 'selected' : ''; ?>>Healthy</option>
                     <option value="sick" <?php echo ($item['health_status'] == 'sick') ? 'selected' : ''; ?>>Sick</option>
                     <option value="treatment" <?php echo ($item['health_status'] == 'treatment') ? 'selected' : ''; ?>>Under Treatment</option>
                 </select>
            </div>

            <div id="crop_fields" style="display: <?php echo ($itemType == 'crop') ? 'block' : 'none'; ?>;">
                <label for="growth_stage">Growth Stage (For Crops):</label>
                <input type="text" id="growth_stage" name="growth_stage" value="<?php echo htmlspecialchars($item['growth_stage']); ?>" placeholder="e.g., Seedling, Vegetative, Flowering, Harvest">
            </div>
            
            <div>
                <label for="date_acquired">Date Acquired/Planted:</label>
                <input type="date" id="date_acquired" name="date_acquired" value="<?php echo htmlspecialchars($item['date_acquired']); ?>">
            </div>

            <div>
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes" rows="4"><?php echo htmlspecialchars($item['notes']); ?></textarea>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn">Update Item</button>
                <a href="inventory.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <script>
            // Basic JavaScript to show/hide conditional fields based on item type
            document.getElementById('item_type').addEventListener('change', function() {
                var type = this.value;
                document.getElementById('animal_fields').style.display = (type === 'animal') ? 'block' : 'none';
                document.getElementById('crop_fields').style.display = (type === 'crop') ? 'block' : 'none';
            });
            // Trigger change on page load to set initial visibility
            document.getElementById('item_type').dispatchEvent(new Event('change'));
        </script>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
