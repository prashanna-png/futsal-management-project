<?php
require_once '../config/auth.php';
require_login();
$currentPage = 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Dashboard</title>

  <link rel="stylesheet" href="../assets/css/dashboard.css">

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

          <h1>
            Welcome Back,
            <?php echo htmlspecialchars($_SESSION['name']); ?>
            👋
          </h1>

          <p>
            Here's what's happening with your account today.
          </p>

        </div>

        <div class="user">

          <div class="avatar">
            <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>
          </div>

          <div>

            <strong>
              <?php echo htmlspecialchars($_SESSION['name']); ?>
            </strong>

            <br>

            Customer

          </div>

        </div>

      </div>

      <section class="cards">

        <div class="card">
          <h4>Total Bookings</h4>
          <h2>08</h2>
        </div>

        <div class="card">
          <h4>Upcoming Matches</h4>
          <h2>02</h2>
        </div>

        <div class="card">
          <h4>Favorite Futsals</h4>
          <h2>05</h2>
        </div>

        <div class="card">
          <h4>Total Spent</h4>
          <h2>Rs. 12,450</h2>
        </div>

      </section>


      <section class="middle">

        <div class="booking">

          <h3>Upcoming Booking</h3>

          <div class="booking-info">

            <strong>Goal Arena Futsal</strong><br><br>

            📅 Date : 25 May 2026 <br>
            ⏰ Time : 6:00 PM - 7:00 PM <br>
            📍 Location : Kathmandu <br>
            ✔ Status : Confirmed

          </div>

        </div>


        <div class="actions">

          <h3>Quick Actions</h3>

          <div class="action-grid">

            <div class="action">
              Browse Futsals
            </div>

            <div class="action">
              New Booking
            </div>

            <div class="action">
              My Bookings
            </div>

            <div class="action">
              Edit Profile
            </div>

          </div>

        </div>

      </section>


      <section class="bottom">

        <div class="table">

          <h3>Recent Bookings</h3>

          <br>

          <table>

            <thead>

              <tr>
                <th>Futsal</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
              </tr>

            </thead>

            <tbody>

              <tr>
                <td>KickOff Arena</td>
                <td>20 May</td>
                <td>7 PM</td>
                <td><span class="status">Completed</span></td>
              </tr>

              <tr>
                <td>Goal Arena</td>
                <td>23 May</td>
                <td>6 PM</td>
                <td><span class="status">Confirmed</span></td>
              </tr>

              <tr>
                <td>Elite Futsal</td>
                <td>28 May</td>
                <td>8 PM</td>
                <td><span class="status">Pending</span></td>
              </tr>

            </tbody>

          </table>

        </div>


        <div class="notice">

          <h3>Announcements</h3>

          <div class="notice-item">

            <strong>🔥 Weekend Discount</strong>

            <p>
              Book any futsal this weekend and receive a 20% discount.
            </p>

          </div>

          <div class="notice-item">

            <strong>⚽ New Futsal Added</strong>

            <p>
              Elite Arena is now available for online booking.
            </p>

          </div>

          <div class="notice-item">

            <strong>🚧 Maintenance</strong>

            <p>
              Court 2 will remain closed tomorrow from 10 AM to 3 PM.
            </p>

          </div>

        </div>

      </section>

      <!-- Dashboard Content Ends -->

    </main>

  </div>

</body>

</html>