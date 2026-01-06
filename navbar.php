<!-- Topbar -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <section id="topbar" class="topbar d-flex align-items-center bg-purple">
    <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
            <a href="mailto:support@grocerypos.com" class="d-flex align-items-center text-white me-4">
                <i class="bi bi-envelope-fill me-2"></i>erickmurigi@gmail.com
            </a>
            <span class="d-flex align-items-center text-white">
                <i class="bi bi-phone-fill me-2"></i>+254 101025698
            </span>
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
            <a href="https://twitter.com" class="text-white mx-2" target="_blank">
                <i class="bi bi-twitter-x"></i>
            </a>
            <a href="https://facebook.com" class="text-white mx-2" target="_blank">
                <i class="bi bi-facebook"></i>
            </a>
            <a href="https://instagram.com" class="text-white mx-2" target="_blank">
                <i class="bi bi-instagram"></i>
            </a>
            <a href="https://linkedin.com" class="text-white mx-2" target="_blank">
                <i class="bi bi-linkedin"></i>
            </a>
        </div>
    </div>
</section>

<style>
    /* Topbar */
.topbar.bg-purple {
    background-color:rgb(33, 102, 187)

;
    padding: 0.75rem 0;
    font-size: 0.9rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.contact-info a,
.contact-info span {
    color: #ffffff;
    text-decoration: none;
    transition: color 0.2s;
}

.contact-info a:hover {
    color: #9f7aea;
}

.contact-info i {
    font-size: 1.1rem;
    color: }
</style>
 <!-- Header / Navbar -->
  <nav style="background-color: maroon;" class="navbar navbar-expand-lg navbar-purple">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">MyApp</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon" style="background-color: white;"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#">Properties</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="register.php">Register</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>