<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <title>Admin Dashboard</title>
</head>

<body>
  <div class="header">
    <div>
      <h1>Welcome Back, <?php echo $_SESSION['name']; ?> 👋</h1>
      <p>Manage your futsal business efficiently.</p>
    </div>
  </div>

  <div class="cards">

    <div class="card">
      <h4>Total Futsals</h4>
      <h2><?php echo $totalCourt; ?></h2>
    </div>

    <div class="card">
      <h4>Approved</h4>
      <h2><?php echo $totalApproved; ?></h2>
    </div>

    <div class="card">
      <h4>Pending</h4>
      <h2><?php echo $totalPending; ?></h2>
    </div>

    <div class="card">
      <h4>Today's Bookings</h4>
      <h2><?php echo $todayBookings; ?></h2>
    </div>

  </div>

  <div class="dashboard-content">

    <div class="recent-futsals">

      <div class="section-title">
        <h2>Recent Futsals</h2>
        <a href="my_futsal.php" class="view-btn">View All</a>
      </div>

      <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <div class="recent-card">

          <img src="../uploads/<?php echo $row['image']; ?>">

          <div class="recent-info">

            <h3><?php echo $row['name']; ?></h3>

            <p><?php echo $row['location']; ?></p>

            <p>Rs. <?php echo $row['price_per_hour']; ?>/hour</p>

            <span class="status <?php echo $row['status']; ?>">
              <?php echo ucfirst($row['status']); ?>
            </span>

          </div>

          <div class="recent-buttons">

            <a href="#">Edit</a>

            <a href="#">Delete</a>

          </div>

        </div>

      <?php } ?>

    </div>

    <div class="quick-actions">

      <h2>Quick Actions</h2>

      <a href="register_futsal.php">+ Register New Futsal</a>

      <a href="my_futsal.php">My Futsals</a>

      <a href="manage_bookings.php">Manage Bookings</a>

      <a href="profile.php">Profile</a>

    </div>

  </div>
</body>

</html>