<?php
session_start();
include 'db.php';


$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (empty($email) || empty($password)) {
    $errors[] = "All fields are required.";
  } else {
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
      $stmt->bind_result($id, $hashed_password);
      $stmt->fetch();
      if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        header("Location: dash/dashboard.php"); // or wherever you redirect
        exit;
      } else {
        $errors[] = "Incorrect password.";
      }
    } else {
      $errors[] = "No user found with that email.";
    }
    $stmt->close();
  }
}
?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" ent="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">
 </head>
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
  <?php include 'navbar.php'; ?>

  <section class="login-section d-flex align-items-center justify-content-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="card shadow-lg bg-white bg-opacity-75 rounded-4">
            <div class="card-body p-4">
              <h4 class="card-title mb-4 text-center fw-bold">Login</h4>

              <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                  <?php foreach ($errors as $error) echo "<div>$error</div>"; ?>
                </div>
              <?php endif; ?>

              <form method="post" action="login.php">
                <div class="mb-3">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                </div>
                <div class="mb-3">
                  <label>Password</label>
                  <input type="password" name="password" class="form-control" required>
                </div>
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary">Login</button>
                </div>
              </form>

              <div class="mt-3 text-center">
                <a href="register.php">Don't have an account?</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>

 <script src="bootstrap/js/bootstrap.min.js"></script>
 </html>