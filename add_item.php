<?php
session_start(); // For Flash Messages
require_once 'includes/db_connect.php';

$form_errors = [];
$form_success_message = null; // Not used directly on this page due to redirect
$submitted_data = []; // To repopulate form

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store submitted data for repopulation
    $submitted_data = $_POST;

    // --- 1. Retrieve and Sanitize Form Data ---
    $item_type = isset($_POST['item_type']) ? trim($_POST['item_type']) : '';
    $item_name = isset($_POST['item_name']) ? trim($_POST['item_name']) : '';
    $quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';
    $unit_of_measure = isset($_POST['unit_of_measure']) ? trim($_POST['unit_of_measure']) : '';
    $location_id = isset($_POST['location_id']) ? trim($_POST['location_id']) : '';
    $health_status = ($item_type == 'Animal' && isset($_POST['health_status'])) ? trim($_POST['health_status']) : null;
    $growth_stage = ($item_type == 'Crop' && isset($_POST['growth_stage'])) ? trim($_POST['growth_stage']) : null;
    $date_acquired = isset($_POST['date_acquired']) ? trim($_POST['date_acquired']) : null;
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

    // --- 2. Basic Server-Side Validation ---
    if (empty($item_type)) { $form_errors['item_type'] = "Item Type is required."; }
    if (empty($item_name)) { $form_errors['item_name'] = "Item Name is required."; }
    if ($quantity === '' || !is_numeric($quantity) || $quantity < 0) { $form_errors['quantity'] = "Quantity must be a non-negative number."; }
    if (empty($location_id)) { $form_errors['location_id'] = "Location is required."; }
    if (!empty($date_acquired) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_acquired)) {
        $form_errors['date_acquired'] = "Invalid date format. Use YYYY-MM-DD.";
    } elseif (empty($date_acquired)) { // Handle empty date_acquired explicitly for DB
        $date_acquired = null;
    }

    if (empty($health_status) && $item_type == 'Animal' && $_POST['health_status'] === '') {
        // If type is Animal and health_status was submitted as empty string, treat as null or apply specific logic
        // For now, we allow null based on current DB schema for non-animals.
        // If it's required for animals, this validation should be stricter.
    }
     if (empty($growth_stage) && $item_type == 'Crop' && $_POST['growth_stage'] === '') {
        // Similar for growth_stage
    }


    // --- 3. If No Validation Errors, Proceed to Database Insertion ---
    if (empty($form_errors) && $db_connection) {
        $sql = "INSERT INTO inventory_items
                    (item_type, item_name, quantity, unit_of_measure, location_id, health_status, growth_stage, date_acquired, notes, created_at, last_updated)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = mysqli_prepare($db_connection, $sql);

        if ($stmt) {
            // Note: location_id is 'i' for integer, quantity can be 'd' for double/decimal
            // Assuming location_id from form is integer. If it can be empty string, cast to null or handle.
            $loc_id_for_db = !empty($location_id) ? (int)$location_id : null;

            mysqli_stmt_bind_param($stmt, "ssdssssss", // s for string, d for double, i for integer
                $item_type,
                $item_name,
                $quantity,
                $unit_of_measure,
                $loc_id_for_db,
                $health_status,
                $growth_stage,
                $date_acquired,
                $notes
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Item "' . htmlspecialchars($item_name) . '" added successfully!'];
                header("Location: inventory.php");
                exit;
            } else {
                $form_errors['db_error'] = "Database error: Failed to add item. " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $form_errors['db_error'] = "Database error: Failed to prepare statement. " . mysqli_error($db_connection);
        }
    } elseif (!$db_connection) {
        $form_errors['db_error'] = "Database connection not available. Cannot save item.";
    }
}


$pageTitle = "Add New Item - Farm Inventory";
$itemType = isset($submitted_data['item_type']) ? $submitted_data['item_type'] : (isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '');

// Fetch Locations for the dropdown
$locations_array = [];
$location_select_error = null;

if ($db_connection) {
    $sql_locations = "SELECT id, name FROM locations ORDER BY name ASC";
    $result_locations = mysqli_query($db_connection, $sql_locations);
    if ($result_locations) {
        while ($row = mysqli_fetch_assoc($result_locations)) {
            $locations_array[] = $row;
        }
        mysqli_free_result($result_locations);
    } else {
        $location_select_error = "Error fetching locations: " . mysqli_error($db_connection);
    }
} else {
    $location_select_error = "Database connection not available.";
}

include 'includes/header.php';
?>

<div class="dashboard-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Add New Inventory Item</h1>

        <?php if (!empty($form_errors)): ?>
            <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
                <strong>Please correct the following errors:</strong><br>
                <ul>
                    <?php foreach ($form_errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="add_item.php" method="POST" style="padding: 20px; background-color: #fff; border: 1px solid #eee; border-radius: 5px;">

            <div>
                <label for="item_type">Item Type:</label>
                <select id="item_type" name="item_type" required>
                    <option value="">-- Select Type --</option>
                    <option value="Animal" <?php echo ( (isset($submitted_data['item_type']) && $submitted_data['item_type'] == 'Animal') || (!isset($submitted_data['item_type']) && $itemType == 'animal') ) ? 'selected' : ''; ?>>Animal</option>
                    <option value="Crop" <?php echo ( (isset($submitted_data['item_type']) && $submitted_data['item_type'] == 'Crop') || (!isset($submitted_data['item_type']) && $itemType == 'crop') ) ? 'selected' : ''; ?>>Crop</option>
                    <option value="Equipment" <?php echo (isset($submitted_data['item_type']) && $submitted_data['item_type'] == 'Equipment') ? 'selected' : ''; ?>>Equipment</option>
                    <option value="Supplies" <?php echo (isset($submitted_data['item_type']) && $submitted_data['item_type'] == 'Supplies') ? 'selected' : ''; ?>>Supplies</option>
                </select>
                <?php if(isset($form_errors['item_type'])): ?><p style="color:red;font-size:0.9em;"><?php echo $form_errors['item_type']; ?></p><?php endif; ?>
            </div>

            <div>
                <label for="item_name">Name:</label>
                <input type="text" id="item_name" name="item_name" required value="<?php echo htmlspecialchars(isset($submitted_data['item_name']) ? $submitted_data['item_name'] : ''); ?>">
                <?php if(isset($form_errors['item_name'])): ?><p style="color:red;font-size:0.9em;"><?php echo $form_errors['item_name']; ?></p><?php endif; ?>
            </div>

            <div>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required min="0" step="any" value="<?php echo htmlspecialchars(isset($submitted_data['quantity']) ? $submitted_data['quantity'] : ''); ?>">
                <?php if(isset($form_errors['quantity'])): ?><p style="color:red;font-size:0.9em;"><?php echo $form_errors['quantity']; ?></p><?php endif; ?>
            </div>

            <div>
                <label for="unit_of_measure">Unit of Measure (e.g., head, kg, items, bags)</label>
                <input type="text" id="unit_of_measure" name="unit_of_measure" placeholder="Unit (e.g., head, kg, bags)" value="<?php echo htmlspecialchars(isset($submitted_data['unit_of_measure']) ? $submitted_data['unit_of_measure'] : ''); ?>">
            </div>

            <div>
                <label for="location_id">Location:</label>
                <select id="location_id" name="location_id" <?php echo (empty($locations_array) && !$location_select_error) ? 'disabled' : ''; ?>>
                    <option value="">-- Select Location --</option>
                    <?php if (!empty($locations_array)): ?>
                        <?php foreach ($locations_array as $loc): ?>
                            <option value="<?php echo htmlspecialchars($loc['id']); ?>" <?php echo (isset($submitted_data['location_id']) && $submitted_data['location_id'] == $loc['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($loc['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <?php if(isset($form_errors['location_id'])): ?><p style="color:red;font-size:0.9em;"><?php echo $form_errors['location_id']; ?></p>
                <?php elseif ($location_select_error): ?><p style="color: red; font-size: 0.9em;"><?php echo htmlspecialchars($location_select_error); ?></p>
                <?php elseif (empty($locations_array) && !$location_select_error && $db_connection): ?><p style="font-size: 0.9em;">No locations found. Please <a href="#">add locations</a> first (link not functional yet).</p>
                <?php endif; ?>
                <?php if (!$db_connection && !$location_select_error && !isset($form_errors['location_id'])) : ?>
                     <p style="color: red; font-size: 0.9em;">Database connection not available for locations.</p>
                <?php endif; ?>
            </div>

            <div id="animal_fields" style="display: <?php echo ( (isset($submitted_data['item_type']) && $submitted_data['item_type'] == 'Animal') || (!isset($submitted_data['item_type']) && $itemType == 'animal') ) ? 'block' : 'none'; ?>;">
                 <label for="health_status">Health Status (For Animals):</label>
                 <select id="health_status" name="health_status">
                     <option value="">-- Select Status --</option>
                     <option value="Healthy" <?php echo (isset($submitted_data['health_status']) && $submitted_data['health_status'] == 'Healthy') ? 'selected' : ''; ?>>Healthy</option>
                     <option value="Sick" <?php echo (isset($submitted_data['health_status']) && $submitted_data['health_status'] == 'Sick') ? 'selected' : ''; ?>>Sick</option>
                     <option value="Treatment" <?php echo (isset($submitted_data['health_status']) && $submitted_data['health_status'] == 'Treatment') ? 'selected' : ''; ?>>Under Treatment</option>
                 </select>
            </div>

            <div id="crop_fields" style="display: <?php echo ( (isset($submitted_data['item_type']) && $submitted_data['item_type'] == 'Crop') || (!isset($submitted_data['item_type']) && $itemType == 'crop') ) ? 'block' : 'none'; ?>;">
                <label for="growth_stage">Growth Stage (For Crops):</label>
                <input type="text" id="growth_stage" name="growth_stage" placeholder="e.g., Seedling, Vegetative, Flowering, Harvest" value="<?php echo htmlspecialchars(isset($submitted_data['growth_stage']) ? $submitted_data['growth_stage'] : ''); ?>">
            </div>

            <div>
                <label for="date_acquired">Date Acquired/Planted:</label>
                <input type="date" id="date_acquired" name="date_acquired" value="<?php echo htmlspecialchars(isset($submitted_data['date_acquired']) ? $submitted_data['date_acquired'] : ''); ?>">
                <?php if(isset($form_errors['date_acquired'])): ?><p style="color:red;font-size:0.9em;"><?php echo $form_errors['date_acquired']; ?></p><?php endif; ?>
            </div>

            <div>
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes" rows="4"><?php echo htmlspecialchars(isset($submitted_data['notes']) ? $submitted_data['notes'] : ''); ?></textarea>
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
                var currentAnimalDisplay = document.getElementById('animal_fields').style.display;
                var currentCropDisplay = document.getElementById('crop_fields').style.display;
                var newAnimalDisplay = (type === 'Animal') ? 'block' : 'none';
                var newCropDisplay = (type === 'Crop') ? 'block' : 'none';

                if (currentAnimalDisplay !== newAnimalDisplay) {
                    document.getElementById('animal_fields').style.display = newAnimalDisplay;
                }
                if (currentCropDisplay !== newCropDisplay) {
                    document.getElementById('crop_fields').style.display = newCropDisplay;
                }
            });
            // Trigger change on page load to set initial visibility based on $itemType (from GET or POST)
            document.getElementById('item_type').dispatchEvent(new Event('change'));
        </script>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
