<?php 
$pageTitle = "Farm Settings - Farm Inventory";
include 'includes/header.php'; 

// Placeholder for current settings (would be fetched from a database)
$currentSettings = [
    'farm_name' => 'Sunny Acres Farm',
    'measurement_units' => 'imperial', // 'imperial' or 'metric'
];

// Placeholder for user list (would be fetched from a database)
$users = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'owner@farm.com', 'role' => 'Admin'],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'worker1@farm.com', 'role' => 'Worker'],
    ['id' => 3, 'name' => 'Mike Brown', 'email' => 'worker2@farm.com', 'role' => 'Worker'],
];

?>

<div class="dashboard-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Farm Settings</h1>

        <form action="settings.php" method="POST"> {/* Action to handle settings update */}
            <div class="settings-section" style="margin-bottom: 30px; padding:20px; background-color:#fff; border:1px solid #eee; border-radius: 5px;">
                <h2>Farm Profile</h2>
                <div>
                    <label for="farm_name">Farm Name:</label>
                    <input type="text" id="farm_name" name="farm_name" value="<?php echo htmlspecialchars($currentSettings['farm_name']); ?>" required>
                </div>
                <div>
                    <label for="measurement_units">Measurement Units:</label>
                    <select id="measurement_units" name="measurement_units">
                        <option value="imperial" <?php echo ($currentSettings['measurement_units'] == 'imperial') ? 'selected' : ''; ?>>Imperial (lbs, acres)</option>
                        <option value="metric" <?php echo ($currentSettings['measurement_units'] == 'metric') ? 'selected' : ''; ?>>Metric (kg, hectares)</option>
                    </select>
                </div>
                <button type="submit" name="save_profile" class="btn" style="margin-top:15px;">Save Profile Settings</button>
            </div>
        </form>

        <div class="settings-section" style="margin-bottom: 30px; padding:20px; background-color:#fff; border:1px solid #eee; border-radius: 5px;">
            <h2>User Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="#" class="btn btn-secondary btn-sm" style="padding: 5px 10px;">Edit</a> 
                            {/* Add more actions like 'Delete' or 'Change Role' if needed, with appropriate confirmations */}
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button class="btn" style="margin-top:15px;">Add New User</button> {/* This would typically lead to a form or modal */}
        </div>

        <div class="settings-section" style="padding:20px; background-color:#fff; border:1px solid #eee; border-radius: 5px;">
            <h2>System Tools</h2>
            <p>Manage integrations and data backups.</p>
            <button class="btn btn-secondary" style="margin-right: 10px;">Integration Settings</button> {/* Placeholder */}
            <button class="btn btn-secondary">Backup Data</button> {/* Placeholder */}
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
