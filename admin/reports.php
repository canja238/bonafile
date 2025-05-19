<?php
require_once '../config.php';
require_once 'header.php';

// Get sales data
$stmt = $pdo->query("
    SELECT 
        DATE(created_at) as day,
        COUNT(*) as order_count,
        SUM(total_amount) as total_sales
    FROM orders
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY day ASC
");
$sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get top products
$stmt = $pdo->query("
    SELECT 
        p.product_name,
        SUM(oi.quantity) as total_sold,
        SUM(oi.quantity * oi.price) as total_revenue
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY p.id
    ORDER BY total_sold DESC
    LIMIT 5
");
$top_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Sales Reports</h2>

<div class="report-grid">
    <div class="report-card">
        <h3>Sales Last 30 Days</h3>
        <canvas id="salesChart" height="300"></canvas>
    </div>
    
    <div class="report-card">
        <h3>Top Selling Products</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Units Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($top_products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo $product['total_sold']; ?></td>
                    <td>$<?php echo number_format($product['total_revenue'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                <?php foreach ($sales_data as $data): ?>
                '<?php echo date('M j', strtotime($data['day'])); ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                label: 'Daily Sales ($)',
                data: [
                    <?php foreach ($sales_data as $data): ?>
                    <?php echo $data['total_sales']; ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: 'rgba(107, 33, 168, 0.2)',
                borderColor: 'rgba(107, 33, 168, 1)',
                borderWidth: 2,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

<style>
.report-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

@media (min-width: 1024px) {
    .report-grid {
        grid-template-columns: 2fr 1fr;
    }
}

.report-card {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.report-card h3 {
    color: #6b21a8;
    margin-top: 0;
    margin-bottom: 1.5rem;
}
</style>

<?php include 'footer.php'; ?>