<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch categories
$categories = [];
$result = $conn->query("SELECT * FROM categories");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if ($name !== '') {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
        header("Location: category.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dash.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-dark text-light">
    <div class="d-flex">
        <?php include 'sidebar.html'; ?>
        <div class="main-content flex-grow-1 p-4">
            <header class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manage Categories</h2>
                <button class="btn btn-outline-light btn-sm no-print">Profile</button>
            </header>

            <!-- Add Category Form -->
            <div class="card bg-dark-purple text-light mb-4 no-print">
                <div class="card-body">
                    <h5 class="card-title">Add Category</h5>
                    <form action="category.php" method="POST" class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control bg-dark text-light" id="name" name="name" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Add</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Category List Table -->
          <!-- Category List Table -->
<div class="card bg-dark-purple text-light">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title">Category List</h5>
            <button class="btn btn-light btn-sm no-print" onclick="printTable()">🖨️ Print categories</button>
        </div>

        <!-- START OF TABLE CONTAINER -->
        <div id="printableTable">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?php echo $category['id']; ?></td>
                                <td><?php echo htmlspecialchars($category['name']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (count($categories) === 0): ?>
                            <tr><td colspan="2">No categories available.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END OF TABLE CONTAINER -->

    </div>
</div>

        </div>
    </div>
<script>
function printTable() {
    var content = document.getElementById('printableTable').innerHTML;
    var win = window.open('', '', 'height=600,width=800');
    win.document.write('<html><head><title>Category List</title>');
    win.document.write('<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">');
    win.document.write('</head><body class="bg-white text-dark">');
    win.document.write(content);
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}
</script>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
