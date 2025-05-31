<?php
$pageTitle = "Item Detail - Farm Inventory";
include 'includes/header.php';

// Placeholder for item ID (In a real app, you'd get an item ID from GET/POST and fetch its data)
$itemId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : 'SAMPLE001';

// Placeholder for item data (would be fetched from a database based on $itemId)
$itemDetails = [
    'name' => 'Chicken Feed - 50lb bags',
    'type' => 'Supplies',
    'current_stock' => 12,
    'unit' => 'bags',
    'location' => 'Barn Storage A',
    'last_updated' => 'May 15, 2023',
    'reorder_level' => 5, // Alert if stock falls below this
    'supplier' => 'AgriFeed Co.',
    'purchase_price_per_unit' => 22.50,
    'notes' => 'High-protein feed for laying hens.'
];

// Placeholder for transaction history for this item
$transactionHistory = [
    ['date' => '2023-05-15', 'type' => 'Usage', 'quantity_change' => -2, 'related_to' => 'Chicken Coop Consumption', 'notes' => 'Fed to layer flock 1.'],
    ['date' => '2023-05-10', 'type' => 'Delivery / Purchase', 'quantity_change' => 20, 'related_to' => 'Order #PO4567', 'notes' => 'Received new stock.'],
    ['date' => '2023-04-25', 'type' => 'Adjustment', 'quantity_change' => -1, 'related_to' => 'Inventory Count', 'notes' => 'Damaged bag, disposed.'],
];

?>

<div class="dashboard-layout">
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>Item Detail: <?php echo htmlspecialchars($itemDetails['name']); ?> <span style="font-size:0.7em; color: #777;">(ID: <?php echo $itemId; ?>)</span></h1>

        <div class="item-actions" style="margin-bottom: 20px;">
            <a href="edit_item.php?id=<?php echo $itemId; ?>" class="btn">Edit Item</a>
            <a href="inventory.php?action=delete&id=<?php echo $itemId; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete Item</a>
            <a href="#add-transaction-form" class="btn btn-secondary">Add Transaction</a>
        </div>

        <div class="item-summary-card" style="margin-bottom: 30px; padding:20px; background-color:#fff; border:1px solid #eee; border-radius: 5px; display:flex; flex-wrap:wrap;">
            <div style="flex: 1; min-width: 250px; padding-right:20px;">
                <h3>Overview</h3>
                <p><strong>Item Type:</strong> <?php echo htmlspecialchars($itemDetails['type']); ?></p>
                <p><strong>Current Stock:</strong> <?php echo htmlspecialchars($itemDetails['current_stock']); ?> <?php echo htmlspecialchars($itemDetails['unit']); ?></p>
                <p><strong>Reorder Level:</strong> <?php echo htmlspecialchars($itemDetails['reorder_level']); ?> <?php echo htmlspecialchars($itemDetails['unit']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($itemDetails['location']); ?></p>
            </div>
            <div style="flex: 1; min-width: 250px;">
                <h3>Additional Details</h3>
                <p><strong>Supplier:</strong> <?php echo htmlspecialchars($itemDetails['supplier']); ?></p>
                <p><strong>Purchase Price:</strong> $<?php echo number_format($itemDetails['purchase_price_per_unit'], 2); ?> per <?php echo htmlspecialchars($itemDetails['unit']); ?></p>
                <p><strong>Last Updated:</strong> <?php echo htmlspecialchars($itemDetails['last_updated']); ?></p>
                <p><strong>Notes:</strong> <?php echo nl2br(htmlspecialchars($itemDetails['notes'])); ?></p>
            </div>
        </div>

        <div class="transaction-history-section" style="padding:20px; background-color:#fff; border:1px solid #eee; border-radius: 5px;">
            <h3>Transaction History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Quantity Change</th>
                        <th>Related To / Reference</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transactionHistory)): ?>
                        <?php foreach ($transactionHistory as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['date']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['type']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['quantity_change']); ?> <?php echo htmlspecialchars($itemDetails['unit']);?></td>
                            <td><?php echo htmlspecialchars($transaction['related_to']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['notes']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No transaction history found for this item.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


        <div id="add-transaction-form" style="margin-top: 30px; padding:20px; background-color:#f9f9f9; border:1px solid #ddd; border-radius: 5px; display:none;">
             <h4>Add New Transaction for <?php echo htmlspecialchars($itemDetails['name']); ?></h4>
             <form action="item_detail.php?id=<?php echo $itemId; ?>" method="POST">
                 <input type="hidden" name="item_id" value="<?php echo $itemId; ?>">
                 <div>
                     <label for="transaction_date">Transaction Date:</label>
                     <input type="date" id="transaction_date" name="transaction_date" value="<?php echo date('Y-m-d'); ?>" required>
                 </div>
                 <div>
                     <label for="transaction_type">Transaction Type:</label>
                     <select id="transaction_type" name="transaction_type" required>
                         <option value="">-- Select Type --</option>
                         <option value="purchase">Purchase / Delivery</option>
                         <option value="usage">Usage / Consumption</option>
                         <option value="adjustment_in">Stock Adjustment (Increase)</option>
                         <option value="adjustment_out">Stock Adjustment (Decrease)</option>
                         <option value="spoilage">Spoilage / Loss</option>
                         <option value="sale">Sale / Output</option>
                     </select>
                 </div>
                 <div>
                     <label for="quantity_change">Quantity Change:</label>
                     <input type="number" id="quantity_change" name="quantity_change" required placeholder="e.g., 10 or -5">
                     <small><em>(Use positive for additions, negative for subtractions)</em></small>
                 </div>
                 <div>
                     <label for="transaction_related_to">Related To / Reference:</label>
                     <input type="text" id="transaction_related_to" name="transaction_related_to" placeholder="e.g., Order #, Batch ID, Event">
                 </div>
                 <div>
                     <label for="transaction_notes">Notes:</label>
                     <textarea id="transaction_notes" name="transaction_notes" rows="3"></textarea>
                 </div>
                 <button type="submit" name="add_transaction" class="btn" style="margin-top:15px;">Save Transaction</button>
                 <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-transaction-form').style.display='none';">Cancel</button>
             </form>
        </div>
        <script>
            // Simple script to toggle the add transaction form
            document.querySelector('a[href="#add-transaction-form"]').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('add-transaction-form').style.display = 'block';
            });
        </script>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
