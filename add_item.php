<?php
$pageTitle = "Add New Item - Farm Inventory";
// You might want to pre-fill item type based on a GET parameter, e.g., from dashboard quick links
$itemType = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';

include 'includes/header.php';
?>

<div class="dashboard-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Add New Inventory Item</h1>
        <form action="inventory.php" method="POST" style="padding: 20px; background-color: #fff; border: 1px solid #eee; border-radius: 5px;">


            <div>
                <label for="item_type">Item Type:</label>
                <select id="item_type" name="item_type" required>
                    <option value="">-- Select Type --</option>
                    <option value="animal" <?php echo ($itemType == 'animal') ? 'selected' : ''; ?>>Animal</option>
                    <option value="crop" <?php echo ($itemType == 'crop') ? 'selected' : ''; ?>>Crop</option>
                    <option value="equipment">Equipment</option>
                    <option value="supplies">Supplies</option>
                </select>
            </div>

            <div>
                <label for="item_name">Name:</label>
                <input type="text" id="item_name" name="item_name" required>
            </div>

            <div>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required min="0">
            </div>

            <div>
                <label for="unit_of_measure">(e.g., head, kg, items, bags)</label>
                <input type="text" id="unit_of_measure" name="unit_of_measure" placeholder="Unit (e.g., head, kg, bags)">
            </div>

            <div>
                <label for="location">Location:</label>

                <input type="text" id="location" name="location" placeholder="e.g., Barn A, Field 3, Shed 1">
            </div>

            <div id="animal_fields" style="display: <?php echo ($itemType == 'animal') ? 'block' : 'none'; ?>;">
                 <label for="health_status">Health Status (For Animals):</label>
                 <select id="health_status" name="health_status">
                     <option value="">-- Select Status --</option>
                     <option value="healthy">Healthy</option>
                     <option value="sick">Sick</option>
                     <option value="treatment">Under Treatment</option>
                 </select>
            </div>

            <div id="crop_fields" style="display: <?php echo ($itemType == 'crop') ? 'block' : 'none'; ?>;">
                <label for="growth_stage">Growth Stage (For Crops):</label>
                <input type="text" id="growth_stage" name="growth_stage" placeholder="e.g., Seedling, Vegetative, Flowering, Harvest">
            </div>

            <div>
                <label for="date_acquired">Date Acquired/Planted:</label>
                <input type="date" id="date_acquired" name="date_acquired">
            </div>

            <div>
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes" rows="4"></textarea>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn">Save Item</button>
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
            // Trigger change on page load in case of pre-filled type
            if ('<?php echo $itemType; ?>') {
                document.getElementById('item_type').dispatchEvent(new Event('change'));
            }
        </script>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
