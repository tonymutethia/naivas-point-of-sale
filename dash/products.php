<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch categories for the dropdown
$result = $conn->query("SELECT name FROM categories");
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['name'];
}

// Fetch products
$result = $conn->query("SELECT * FROM products");
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Handle form submission (add product)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = 'Uploads/' . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
            $image = ''; // Fallback if upload fails
        }
    }
    $stmt = $conn->prepare("INSERT INTO products (name, price, category, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $category, $image);
    $stmt->execute();
    $stmt->close();
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dash.css">
</head>
<body class="bg-dark text-light">
    <div class="d-flex">
        <!-- Sidebar -->
       <?php include 'sidebar.html';?>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <!-- Header -->
            <header class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manage Products</h2>
                <button class="btn btn-outline-light btn-sm">Profile</button>
            </header>

            <!-- Horizontal Form -->
            <div class="card bg-dark-purple text-light mb-4">
                <div class="card-body">
                    <h5 class="card-title">Add Product</h5>
                    <form action="products.php" method="POST" enctype="multipart/form-data" class="row g-3">
                        <div class="col-md-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control bg-dark text-light" id="name" name="name" required>
                        </div>
                        <div class="col-md-2">
                            <label for="price" class="form-label">Price (ksh)</label>
                            <input type="number"  class="form-control bg-dark text-light" id="price" name="price" required>
                        </div>
                        <div class="col-md-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select bg-dark text-light" id="category" name="category" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control bg-dark text-light" id="image" name="image" accept="image/*">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Add</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card bg-dark-purple text-light">
                <div class="card-body">
                    <h5 class="card-title">Product List</h5>
                    <div   class="table-responsive ">
                        <button onclick="printTable()" class="btn btn-outline-light mb-3">🖨️ Print Report</button>
                        <div id="printableTable">

                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['id']; ?></td>
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td>ksh<?php echo number_format($product['price'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                                        <td>
                                            <?php if ($product['image']): ?>
                                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                No Image
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="delete-product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
function printTable() {
    var printContents = document.getElementById("printableTable").innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = `
        <html>
        <head>
            <title>Product Report</title>
            <style>
                body { font-family: Arial; padding: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #444; padding: 8px; text-align: left; }
                th { background: #222; color: #fff; }
            </style>
        </head>
        <body>
            <h2>Product Report</h2>
            ${printContents}
        </body>
        </html>
    `;

    window.print();
    window.location.reload(); // Reload page to restore content
}
</script>

</body>
</html>