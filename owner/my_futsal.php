<?php
session_start();
require_once '../config/auth.php';
require_login();
$currentPage = 'myFutsal';
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>My Futsals</title>

  <link rel="stylesheet" href="../assets/css/owner.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">

      <div class="header">

        <div>

          <h1>My Futsals</h1>

          <p>Manage all of your registered futsal courts.</p>

        </div>

        <a href="register_futsal.php" class="btn-add">
          + Register New Futsal
        </a>

      </div>

      <div class="futsal-grid">

        <div class="futsal-card">

          <img src="../assets/images/futsal.jpg" alt="">

          <div class="futsal-info">

            <h3>Goal Arena</h3>

            <p>📍 Kathmandu</p>

            <p>Rs.1500 / Hour</p>

            <span class="status approved">
              Approved
            </span>

            <div class="card-buttons">

              <a href="#" class="edit-btn">
                Edit
              </a>

              <a href="#" class="delete-btn">
                Delete
              </a>

            </div>

          </div>

        </div>

        <div class="futsal-card">

          <img src="../assets/images/futsal.jpg" alt="">

          <div class="futsal-info">

            <h3>Elite Arena</h3>

            <p>📍 Lalitpur</p>

            <p>Rs.1800 / Hour</p>

            <span class="status pending">
              Pending
            </span>

            <div class="card-buttons">

              <a href="#" class="edit-btn">
                Edit
              </a>

              <a href="#" class="delete-btn">
                Delete
              </a>

            </div>

          </div>

        </div>

        <div class="futsal-card">

          <img src="../assets/images/futsal.jpg" alt="">

          <div class="futsal-info">

            <h3>Futsal City</h3>

            <p>📍 Bhaktapur</p>

            <p>Rs.1700 / Hour</p>

            <span class="status rejected">
              Rejected
            </span>

            <div class="card-buttons">

              <a href="#" class="edit-btn">
                Edit
              </a>

              <a href="#" class="delete-btn">
                Delete
              </a>

            </div>

          </div>

        </div>

      </div>

    </main>

  </div>

</body>

</html>