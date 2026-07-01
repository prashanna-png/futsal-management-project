<?php
session_start();
require_once '../config/auth.php';
require_login();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Owner Dashboard</title>

  <link rel="stylesheet" href="../assets/css/owner.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">

      <!-- Header -->

      <div class="header">

        <div>
          <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION['name']); ?> 👋</h1>
          <p>Manage your futsal courts and bookings efficiently.</p>
        </div>

        <div class="user">

          <div class="avatar">
            <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>
          </div>

          <div>
            <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong><br>
            Owner
          </div>

        </div>

      </div>

      <!-- Statistics -->

      <div class="cards">

        <div class="card">
          <h4>Total Futsals</h4>
          <h2>3</h2>
          <p>Registered Courts</p>
        </div>

        <div class="card">
          <h4>Pending Approval</h4>
          <h2>1</h2>
          <p>Waiting for Admin</p>
        </div>

        <div class="card">
          <h4>Today's Bookings</h4>
          <h2>8</h2>
          <p>Confirmed Matches</p>
        </div>

        <div class="card">
          <h4>Total Earnings</h4>
          <h2>Rs. 18,500</h2>
          <p>This Month</p>
        </div>

      </div>

      <!-- Middle Section -->

      <div class="middle">

        <div class="booking">

          <h3>Recent Registered Futsals</h3>

          <div class="booking-info">

            <div class="item">
              <strong>Goal Arena</strong><br>
              Kathmandu<br>
              Rs.1500 / Hour<br>
              Status : Approved
            </div>

            <hr>

            <div class="item">
              <strong>Elite Arena</strong><br>
              Lalitpur<br>
              Rs.1800 / Hour<br>
              Status : Pending
            </div>

            <hr>

            <div class="item">
              <strong>Futsal City</strong><br>
              Bhaktapur<br>
              Rs.1700 / Hour<br>
              Status : Approved
            </div>

          </div>

        </div>

        <div class="actions">

          <h3>Quick Actions</h3>

          <div class="action-grid">

            <a href="register_futsal.php" class="action">
              Register Futsal
            </a>

            <a href="my_futsals.php" class="action">
              My Futsals
            </a>

            <a href="manage_slots.php" class="action">
              Manage Slots
            </a>

            <a href="manage_bookings.php" class="action">
              Bookings
            </a>

          </div>

        </div>

      </div>

      <!-- Bottom Section -->

      <div class="bottom">

        <div class="table">

          <h3>Recent Bookings</h3>

          <table>

            <thead>

              <tr>
                <th>Customer</th>
                <th>Court</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
              </tr>

            </thead>

            <tbody>

              <tr>
                <td>Ram Sharma</td>
                <td>Goal Arena</td>
                <td>10 July</td>
                <td>6 PM - 7 PM</td>
                <td><span class="status">Confirmed</span></td>
              </tr>

              <tr>
                <td>Sita Rai</td>
                <td>Elite Arena</td>
                <td>11 July</td>
                <td>7 PM - 8 PM</td>
                <td><span class="status">Pending</span></td>
              </tr>

              <tr>
                <td>Hari KC</td>
                <td>Goal Arena</td>
                <td>12 July</td>
                <td>8 PM - 9 PM</td>
                <td><span class="status">Completed</span></td>
              </tr>

            </tbody>

          </table>

        </div>

        <div class="notice">

          <h3>Business Summary</h3>

          <div class="notice-item">
            <strong>Pending Approval</strong>
            <p>You have one futsal waiting for admin approval.</p>
          </div>

          <div class="notice-item">
            <strong>Revenue</strong>
            <p>Your estimated revenue this month is Rs. 95,000.</p>
          </div>

          <div class="notice-item">
            <strong>Reminder</strong>
            <p>Add more available slots to receive more bookings.</p>
          </div>

        </div>

      </div>

    </main>

  </div>

</body>

</html>