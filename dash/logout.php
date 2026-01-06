<?php
session_start();
include '../db.php'; // Include database connection


// Destroy session
session_unset();
session_destroy();

// Redirect to login page
header("Location: ../login.php");
exit();
?>