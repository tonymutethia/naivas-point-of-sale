<?php
include 'db.php';
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $firstname = trim($_POST['firstname'] ?? '');
  $lastname = trim($_POST['lastname'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';

  // Validate
  if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirm_password)) {
    $errors[] = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  } elseif ($password !== $confirm_password) {
    $errors[] = "Passwords do not match.";
  } else {
    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $errors[] = "Email already registered.";
    } else {
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashed);
      if ($stmt->execute()) {
        $success = "Registration successful. <a href='login.php'>Login here</a>";
      } else {
        $errors[] = "Registration failed. Try again.";
      }
    }
    $stmt->close();
  }
}
?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">
 </head>
 <body>
    <?php include 'navbar.php'; ?>
 <style>

    .login-body {
  margin: 0;
  padding: 0;
  background-image: url('images/img5.jpg'); /* Replace with your image path */
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  min-height: 100vh;
}

.login-section {
  min-height: 100vh;
  backdrop-filter: blur(2px);
}

.card {
  background-color: rgba(255, 255, 255, 0.85);
  border-radius: 15px;
}

 </style>
<body class="login-body">

<section class="login-section d-flex align-items-center justify-content-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg bg-white bg-opacity-75 rounded-4">
          <div class="card-body p-4">
            <h4 class="text-center mb-4 fw-bold">Register</h4>

            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger">
                <?php foreach ($errors as $error) echo "<div>$error</div>"; ?>
              </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
              <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php">
              <div class="mb-3">
                <label>First Name</label>
                <input type="text" name="firstname" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Last Name</label>
                <input type="text" name="lastname" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Register</button>
              </div>
            </form>

            <div class="mt-3 text-center">
              Already have an account? <a href="login.php">Login</a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

</body>

 </body>
 <script src="bootstrap/js/bootstrap.min.js"></script>
 </html>