<?php
$pageTitle = "Equipment Maintenance Log - Farm Inventory";
include 'includes/header.php';

// Placeholder for selected equipment (In a real app, you'd get an equipment ID and fetch its data)
$equipmentName = "Tractor #3 - John Deere 5075E";
$equipmentDetails = [
    'last_service_date' => 'March 15, 2023',
    'last_service_hours' => '50 hrs',
    'next_service_due_date' => 'June 10, 2023',
    'next_service_due_hours' => '200 hrs',
];

// Placeholder for maintenance history
$maintenanceHistory = [
    ['date' => '03/15/2023', 'type' => 'Oil Change & Filter', 'cost' => '$150.00', 'notes' => 'Routine 50hr service.'],
    ['date' => '01/20/2023', 'type' => 'Annual Inspection', 'cost' => '$75.00', 'notes' => 'Passed all checks.'],
    ['date' => '11/05/2022', 'type' => 'Hydraulic Hose Repair', 'cost' => '$220.00', 'notes' => 'Replaced burst hose on front loader.'],
];
?>

<div class="dashboard-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Equipment Maintenance Log</h1>

        <div class="equipment-select-section" style="margin-bottom: 20px;">
            <label for="select_equipment" style="margin-right:10px;">Select Equipment:</label>
            <select id="select_equipment" name="select_equipment" style="padding:8px; min-width: 300px;">

                <option value="tractor3"><?php echo htmlspecialchars($equipmentName); ?> (Currently Selected)</option>
                <option value="tractor1">Tractor #1 - Massey Ferguson</option>
                <option value="harvester1">Combine Harvester CX2</option>
                <option value="baler1">Round Baler RB500</option>
            </select>
            <button class="btn btn-secondary" style="padding: 8px 15px; margin-left:10px;">Load Log</button>
        </div>

        <div class="equipment-details-card" style="margin-bottom: 30px; padding:20px; background-color:#fff; border:1px solid #eee; border-radius: 5px;">
            <h2><?php echo htmlspecialchars($equipmentName); ?></h2>
            <p><strong>Last Service:</strong> <?php echo htmlspecialchars($equipmentDetails['last_service_date']); ?> (<?php echo htmlspecialchars($equipmentDetails['last_service_hours']); ?>)</p>
            <p><strong>Next Service Due:</strong> <?php echo htmlspecialchars($equipmentDetails['next_service_due_date']); ?> or <?php echo htmlspecialchars($equipmentDetails['next_service_due_hours']); ?></p>

        </div>

        <div class="maintenance-history-section" style="padding:20px; background-color:#fff; border:1px solid #eee; border-radius: 5px;">
            <h3>Maintenance History</h3>
            <div style="margin-bottom:15px;">
                <a href="#add-service-form" class="btn">Add Service Record</a>
                <button class="btn btn-secondary">Upload Documents</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Service Type</th>
                        <th>Cost</th>
                        <th>Notes / Performed By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($maintenanceHistory)): ?>
                        <?php foreach ($maintenanceHistory as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['date']); ?></td>
                            <td><?php echo htmlspecialchars($record['type']); ?></td>
                            <td><?php echo htmlspecialchars($record['cost']); ?></td>
                            <td><?php echo htmlspecialchars($record['notes']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No maintenance records found for this equipment.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


        <div id="add-service-form" style="margin-top: 30px; padding:20px; background-color:#f9f9f9; border:1px solid #ddd; border-radius: 5px; display:none;">
             <h4>Add New Service Record for <?php echo htmlspecialchars($equipmentName); ?></h4>
             <form action="equipment_log.php" method="POST">
                 <input type="hidden" name="equipment_id" value="tractor3">
                 <div>
                     <label for="service_date">Service Date:</label>
                     <input type="date" id="service_date" name="service_date" required>
                 </div>
                 <div>
                     <label for="service_type">Service Type:</label>
                     <input type="text" id="service_type" name="service_type" required placeholder="e.g., Oil Change, Filter Replacement">
                 </div>
                 <div>
                     <label for="service_hours">Operating Hours at Service (if applicable):</label>
                     <input type="number" id="service_hours" name="service_hours" min="0" placeholder="e.g., 150">
                 </div>
                 <div>
                     <label for="service_cost">Cost:</label>
                     <input type="text" id="service_cost" name="service_cost" placeholder="$0.00">
                 </div>
                 <div>
                     <label for="service_notes">Notes / Performed By:</label>
                     <textarea id="service_notes" name="service_notes" rows="3"></textarea>
                 </div>
                 <button type="submit" name="add_service" class="btn" style="margin-top:15px;">Save Service Record</button>
                 <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-service-form').style.display='none';">Cancel</button>
             </form>
        </div>
        <script>
            // Simple script to toggle the add service form (for demo purposes)
            document.querySelector('a[href="#add-service-form"]').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('add-service-form').style.display = 'block';
            });
        </script>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
