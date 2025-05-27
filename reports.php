<?php 
$pageTitle = "Reports & Analytics - Farm Inventory";
include 'includes/header.php'; 
?>

<div class="dashboard-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Reports & Analytics</h1>

        <div class="report-controls" style="margin-bottom: 20px; padding: 15px; background-color:#fff; border:1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <label for="date_range_selector" style="margin-right: 10px;">Select Report Period:</label>
                <select id="date_range_selector" name="date_range" style="padding: 8px;">
                    <option value="current_month">Current Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="last_3_months">Last 3 Months</option>
                    <option value="current_year">Current Year</option>
                    <option value="custom">Custom Range</option>
                </select>
                {/* Add input fields for custom date range, initially hidden */}
                <input type="date" id="custom_date_start" name="custom_date_start" style="display:none; padding: 8px; margin-left:5px;">
                <input type="date" id="custom_date_end" name="custom_date_end" style="display:none; padding: 8px;">
                <button class="btn btn-secondary" style="padding: 8px 15px; margin-left:10px;">Generate</button>
            </div>
            <div>
                <button class="btn btn-secondary">Export PDF</button>
                <button class="btn btn-secondary">Export Excel</button>
            </div>
        </div>

        <div class="report-section" style="margin-bottom: 30px; padding:15px; background-color:#fff; border:1px solid #eee;">
            <h2>Chart Placeholder: Stock Levels</h2>
            <div style="width:100%; height:200px; background-color:#e0e0e0; display:flex; align-items:center; justify-content:center; text-align:center;">
                [Stock Levels Chart Area - e.g., Bar Chart showing quantity by item or category]
            </div>
        </div>
        
        <div class="report-section" style="margin-bottom: 30px; padding:15px; background-color:#fff; border:1px solid #eee;">
            <h2>Chart Placeholder: Productivity Trends</h2>
            <div style="width:100%; height:200px; background-color:#e0e0e0; display:flex; align-items:center; justify-content:center; text-align:center;">
                [Productivity Trends Chart Area - e.g., Line chart showing milk production, crop yield over time]
            </div>
        </div>

        <div class="report-section" style="padding:15px; background-color:#fff; border:1px solid #eee;">
            <h2>Inventory Value Report</h2>
            <p><em>As of: <?php echo date("F j, Y"); ?> (Placeholder)</em></p>
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Item Count / Area</th>
                        <th>Total Value (Approx.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Placeholder data for the report
                    $reportData = [
                        ['category' => 'Animals', 'count_area' => '120 head', 'value' => '$124,500'],
                        ['category' => 'Crops', 'count_area' => '45 fields (approx 560 acres)', 'value' => '$89,600'],
                        ['category' => 'Equipment', 'count_area' => '84 units', 'value' => '$210,000'],
                        ['category' => 'Supplies', 'count_area' => '250 items', 'value' => '$15,200'],
                    ];

                    foreach ($reportData as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['count_area']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['value']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total Estimated Farm Value</th>
                        <th>$439,300 (Placeholder)</th>
                    </tr>
                </tfoot>
            </table>
            <p>
                <small><em>Note: Values are estimates for demonstration purposes. Actual valuation would require detailed accounting.</em></small>
            </p>
        </div>
        
        <script>
            // Show/hide custom date inputs
            document.getElementById('date_range_selector').addEventListener('change', function() {
                var showCustom = this.value === 'custom';
                document.getElementById('custom_date_start').style.display = showCustom ? 'inline-block' : 'none';
                document.getElementById('custom_date_end').style.display = showCustom ? 'inline-block' : 'none';
            });
        </script>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
