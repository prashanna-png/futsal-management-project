<?php
require_once '../config/auth.php';
require_login();
$currentPage = 'favorites';
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
          <h1>Favorite Futsals</h1>
          <p>Your saved futsals for quick booking.</p>
        </div>
      </div>

      <div class="futsal-grid">

        <div class="futsal-card">

          <img src="../assets/images/futsal.jpg">

          <h3>KickOff Arena</h3>

          <p>📍 Kathmandu</p>

          <p>⭐⭐⭐⭐⭐</p>

          <button>Book Now</button>

          <button>Remove</button>

        </div>

        <div class="futsal-card">

          <img src="../assets/images/futsal.jpg">

          <h3>Goal Arena</h3>

          <p>📍 Bhaktapur</p>

          <p>⭐⭐⭐⭐☆</p>

          <button>Book Now</button>

          <button>Remove</button>

        </div>

      </div>
  </div>
  </main>

  </div>

</body>

</html>