<?php
require_once '../config/auth.php';
require_login();
$currentPage = 'browse';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Dashboard</title>

  <link rel="stylesheet" href="../assets/css/customer.css">

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
          <h1>Browse Futsals</h1>
          <p>Find and book the perfect futsal for your next match.</p>
        </div>
      </div>

      <div class="search-box">
        <input type="text" placeholder="Search futsal...">
      </div>

      <div class="futsal-grid">

        <div class="futsal-card">
          <img src="../assets/images/futsal.jpg" alt="Futsal">
          <h3>Goal Arena</h3>
          <p>📍 Kathmandu</p>
          <p>⭐⭐⭐⭐☆ (4.8)</p>
          <p>Rs. 1500 / hour</p>

          <div class="facilities">
            <span>Parking</span>
            <span>Shower</span>
            <span>Cafe</span>
          </div>

          <button>View Details</button>
        </div>

        <div class="futsal-card">
          <img src="../assets/images/futsal.jpg" alt="Futsal">
          <h3>Elite Arena</h3>
          <p>📍 Lalitpur</p>
          <p>⭐⭐⭐⭐⭐ (5.0)</p>
          <p>Rs. 1800 / hour</p>

          <div class="facilities">
            <span>Parking</span>
            <span>Wifi</span>
            <span>Cafe</span>
          </div>

          <button>View Details</button>
        </div>

      </div>
    </main>

  </div>

</body>

</html>