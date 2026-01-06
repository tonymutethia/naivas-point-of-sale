<?php
session_start();
include '../db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch customers
$result = $conn->query("SELECT id, firstname, lastname, email FROM users");
$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

// Handle form submission (add customer)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error = "Email already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);
        $stmt->execute();
        $stmt->close();
        header("Location: customers.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dash.css">
</head>
<body class="bg-dark text-light">
    <div class="d-flex">
        <!-- Sidebar -->
    <?php include 'sidebar.html'; ?>

        <!-- Main Content -->
        <div class="main-content flex-grow-1 p-4">
            <!-- Header -->
            <header class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manage Customers</h2>
                <button class="btn btn-outline-light btn-sm">Profile</button>
            </header>

            <!-- Error Message -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <!-- Horizontal Form -->
            <div class="card bg-dark-purple text-light mb-4">
                <div class="card-body">
                    <h5 class="card-title">Add Customer</h5>
                    <form action="customers.php" method="POST" class="row g-3">
                        <div class="col-md-3">
                            <label for="firstname" class="form-label">Firstname</label>
                            <input type="text" class="form-control bg-dark text-light" id="firstname" name="firstname" required>
                        </div>
                        <div class="col-md-3">
                            <label for="lastname" class="form-label">Lastname</label>
                            <input type="text" class="form-control bg-dark text-light" id="lastname" name="lastname" required>
                        </div>
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control bg-dark text-light" id="email" name="email" required>
                        </div>
                        <div class="col-md-2">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control bg-dark text-light" id="password" name="password" required>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Add</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Customers Table -->
           
<div class="card bg-dark-purple text-light">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title">Customer List</h5>
            <button class="btn btn-light btn-sm no-print" onclick="printCustomersTable()">🖨️ Print Customer</button>
        </div>

        <!-- START printable area -->
        <div id="printableCustomers">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?php echo $customer['id']; ?></td>
                                <td><?php echo htmlspecialchars($customer['firstname']); ?></td>
                                <td><?php echo htmlspecialchars($customer['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END printable area -->

    </div>
</div>

        </div>
    </div>
<script>
function printCustomersTable() {
    var content = document.getElementById('printableCustomers').innerHTML;
    var win = window.open('', '', 'height=700,width=1000');
    win.document.write('<html><head><title>Customer List</title>');
    win.document.write('<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">');
    win.document.write('<style>body{padding:20px;font-family:sans-serif;} table{width:100%;} th,td{padding:8px;border:1px solid #ccc;text-align:left;} </style>');
    win.document.write('</head><body>');
    win.document.write('<h3>Customer List</h3>');
    win.document.write(content);
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}
</script>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>