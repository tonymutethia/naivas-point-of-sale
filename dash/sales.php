<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Filter handling
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$query = "SELECT * FROM sales";
if (!empty($start_date) && !empty($end_date)) {
    $query .= " WHERE sale_date BETWEEN '$start_date' AND '$end_date'";
}
$query .= " ORDER BY sale_date DESC";

$sales_result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dash.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-dark text-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-secondary text-white p-3" style="width: 250px; min-height: 100vh;">
            <?php include 'sidebar.html'; ?>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Sales Report</h2>
                <button onclick="printSalesTable()" class="btn btn-light no-print">🖨️ Print Sales</button>
            </div>

            <!-- Filter Form -->
            <form class="row g-3 mb-4 no-print" method="GET">
                <div class="col-md-4">
                    <label class="form-label">From</label>
                    <input type="date" name="start_date" class="form-control bg-dark text-light" value="<?php echo htmlspecialchars($start_date); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">To</label>
                    <input type="date" name="end_date" class="form-control bg-dark text-light" value="<?php echo htmlspecialchars($end_date); ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <!-- Sales Table -->
            <div id="printableSales">
                <table class="table table-dark table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Sale Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $sales_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td>KES <?php echo number_format($row['total_price'], 2); ?></td>
                                <td><?php echo $row['sale_date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div> <!-- End of Main Content -->
    </div> <!-- End of Flex Container -->

    <!-- Print Script -->
    <script>
        function printSalesTable() {
            var content = document.getElementById('printableSales').innerHTML;
            var win = window.open('', '', 'height=700,width=1000');
            win.document.write('<html><head><title>Sales Report</title>');
            win.document.write('<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">');
            win.document.write('<style>body{padding:20px;font-family:sans-serif;} table{width:100%;} th,td{padding:8px;border:1px solid #ccc;text-align:left;} </style>');
            win.document.write('</head><body>');
            win.document.write('<h3>Sales Report</h3>');
            win.document.write(content);
            win.document.write('</body></html>');
            win.document.close();
            win.print();
        }
    </script>
</body>
</html>
