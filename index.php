<?php
session_start();
include 'db.php';

// Fetch categories
$result = $conn->query("SELECT name FROM categories");
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['name'];
}

// Fetch products (filter by category if selected)
$selected_category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : null;
$query = "SELECT id, name, price, image, category FROM products";
if ($selected_category) {
    $query .= " WHERE category = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selected_category);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($query);
}
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
if (isset($stmt)) {
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocery POS</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
</head>
<body class="bg-dark">
    <?php include 'navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div style="background-color:maroon;" class="col-md-3 col-lg-2 bg-dark-purple sidebar p-3">
                <h5 class="text-light">Categories</h5>
                <ul class="list-group">
                    <?php foreach ($categories as $category): ?>
                        <li class="list-group-item bg-primary text-light border-0">
                            <a href="?category=<?php echo urlencode($category); ?>" class="text-light"><?php echo htmlspecialchars($category); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <!-- Search Bar -->
                <div class="my-3">
                    <form class="d-flex" action="search.php" method="GET">
                        <input class="form-control me-2 bg-dark text-light border-dark-purple" type="search" placeholder="Search products..." name="query" aria-label="Search">
                        <button class="btn btn-outline-primary" type="submit">Search</button>
                    </form>
                </div>

                <!-- Smaller Carousel -->
                <div id="carouselExampleControls" class="carousel slide mb-4" data-bs-ride="carousel" style="max-height: 300px;">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="images/img5.jpg" class="d-block w-100" alt="Fruits Promo">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                <h5>Get all you need ,order your best drink </h5>
                                <p>Shop our best product today!</p>
                                <a href="#products" class="btn btn-sm btn-primary">Shop Now</a>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="images/img6.jpg" class="d-block w-100" alt="Dairy Promo">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                <h5>buy Grocery in one place</h5>
                                <p>Get the best Grocery products.</p>
                                <a href="#products" class="btn btn-sm btn-primary">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                <!-- Product Grid -->
                <section id="products" class="my-5">
                    <h2 class="text-center text-light mb-4">All Products</h2>
                    <div class="row">
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="card h-100 bg-dark text-light border-dark-purple">
                                    <img src="dash/<?php echo htmlspecialchars($product['image'] ?: 'images/placeholder.jpg'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                        <p class="card-text">Price: ksh<?php echo number_format($product['price'], 2); ?></p>
                                       
                                           <a href="print_receipt.php?id=<?php echo $product['id']; ?>" target="_blank" class="btn btn-primary">Order</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>
    </div>
   <script>
function printcard() {
    var content = document.getElementById("printableTable").innerHTML;
    var win = window.open('', '', 'height=700,width=900');
    win.document.write('<html><head><title>Print Schedule</title>');
    win.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    win.document.write('</head><body>');
    win.document.write('<h2 class="text-center my-4">Class Schedule</h2>');
    win.document.write(content);
    win.document.write('</body></html>');
    win.document.close();
    win.print(); 
}
</script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>