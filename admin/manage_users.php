<?php
session_start();

require_once '../config/auth.php';
require_once '../config/db.php';
global $conn;

require_login();

if ($_SESSION['role'] != 'admin') {
  header("Location: ../login.php");
  exit();
}

$currentPage = 'manageUsers';

$pendingResult = mysqli_query($conn, "
SELECT
f.futsalid,
f.name,
f.location,
f.image,
u.name AS owner
FROM futsal f
JOIN users u
ON f.ownerid=u.userid
WHERE f.status='pending'
ORDER BY f.created_at DESC
LIMIT 5
");

// Recent Users
$userResult = mysqli_query($conn, "
SELECT *
FROM users
ORDER BY created_at DESC
LIMIT 5
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $action = $_POST['action'];
  $futsalid = $_POST['futsalid'];

  if ($action == 'approve') {

    $status = 'approved';
  } elseif ($action == 'reject') {

    $status = 'rejected';
  }

  $sql = "UPDATE futsal
            SET status = '$status'
            WHERE futsalid = '$futsalid'";

  mysqli_query($conn, $sql);

  header("Location: manage_futsals.php");
  exit();
}




?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <title>manage futsals</title>

  <link rel="stylesheet" href="../assets/css/admin.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

  </div>

</body>

</html>