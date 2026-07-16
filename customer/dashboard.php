<?php
session_start();
global $conn;
require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

$currentPage = 'dashboard';

$playerid = $_SESSION['userid'];

// Total Bookings
$sql = "SELECT COUNT(*) AS total
        FROM booking
        WHERE playerid='$playerid'";
$result = mysqli_query($conn, $sql);
$total = mysqli_fetch_assoc($result);


// Pending
$sql = "SELECT COUNT(*) AS pending
        FROM booking
        WHERE playerid='$playerid'
        AND status='pending'";
$result = mysqli_query($conn, $sql);
$pending = mysqli_fetch_assoc($result);


// Confirmed
$sql = "SELECT COUNT(*) AS confirmed
        FROM booking
        WHERE playerid='$playerid'
        AND status='confirmed'";
$result = mysqli_query($conn, $sql);
$confirmed = mysqli_fetch_assoc($result);


// Completed
$sql = "SELECT COUNT(*) AS completed
        FROM booking
        WHERE playerid='$playerid'
        AND status='completed'";
$result = mysqli_query($conn, $sql);
$completed = mysqli_fetch_assoc($result);


$sql = "
SELECT
    b.bookingid,
    b.booking_date,
    b.start_time,
    b.end_time,
    b.status,
    f.name

FROM booking b

JOIN futsal f
ON b.futsalid = f.futsalid

WHERE b.playerid='$playerid'

ORDER BY b.created_at DESC

LIMIT 5
";

$recentResult = mysqli_query($conn, $sql);

$sql = "
SELECT
    b.bookingid,
    b.booking_date,
    b.start_time,
    b.end_time,
    b.status,

    f.name,
    f.location,
    f.image

FROM booking b

JOIN futsal f
ON b.futsalid=f.futsalid

WHERE
    b.playerid='$playerid'
    AND b.status='confirmed'
    AND CONCAT(b.booking_date,' ',b.start_time) >= NOW()

ORDER BY
    b.booking_date ASC,
    b.start_time ASC

LIMIT 1
";

$result = mysqli_query($conn, $sql);

$nextBooking = mysqli_fetch_assoc($result);

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
          <h1>
            Welcome Back,
            <?= htmlspecialchars($_SESSION['name']); ?> 👋
          </h1>

          <p>
            Manage your bookings and discover new futsals.
          </p>
        </div>

        <div class="user" onclick="location.href='profile.php'">
          <div class="avatar">
            <?= strtoupper(substr($_SESSION['name'], 0, 1)); ?>
          </div>

          <div>
            <strong><?= htmlspecialchars($_SESSION['name']); ?></strong>
            <br>
            Customer
          </div>
        </div>

      </div>


      <!-- Statistics -->

      <section class="cards">

        <div class="card">
          <h4>Total Bookings</h4>
          <h2><?= $total['total']; ?></h2>
        </div>

        <div class="card">
          <h4>Confirmed</h4>
          <h2><?= $confirmed['confirmed']; ?></h2>
        </div>

        <div class="card">
          <h4>Pending</h4>
          <h2><?= $pending['pending']; ?></h2>
        </div>

        <div class="card">
          <h4>Completed</h4>
          <h2><?= $completed['completed']; ?></h2>
        </div>

      </section>


      <!-- Main Content -->

      <section class="middle">

        <!-- Recent Bookings -->

        <div class="table">

          <div class="section-header">
            <h3>Recent Bookings</h3>

            <a href="my_bookings.php" class="view-all">
              View All
            </a>
          </div>

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

              <?php while ($row = mysqli_fetch_assoc($recentResult)) { ?>

                <tr>

                  <td>
                    <?= htmlspecialchars($row['name']); ?>
                  </td>

                  <td>
                    <?= date("d M Y", strtotime($row['booking_date'])); ?>
                  </td>

                  <td>
                    <?= date("g:i A", strtotime($row['start_time'])); ?>
                    -
                    <?= date("g:i A", strtotime($row['end_time'])); ?>
                  </td>

                  <td>

                    <span class="status <?= strtolower($row['status']); ?>">
                      <?= ucfirst($row['status']); ?>
                    </span>

                  </td>

                </tr>

              <?php } ?>

            </tbody>

          </table>

        </div>



        <!-- Quick Actions -->

        <div class="actions">

          <h3>Quick Actions</h3>

          <div class="action-grid">

            <button class="action" onclick="location.href='browse.php'">
              Browse Futsals
            </button>

            <button class="action" onclick="location.href='my_bookings.php'">
              My Bookings
            </button>

            <button class="action" onclick="location.href='profile.php'">
              Edit Profile
            </button>

            <button class="action" onclick="location.href='support.php'">
              Support
            </button>

          </div>

        </div>

      </section>



      <!-- Upcoming Booking -->

      <section class="bottom">

        <div class="upcoming-booking">

          <div class="section-header">

            <h3>
              Next Upcoming Booking
            </h3>

          </div>

          <?php if (isset($nextBooking)) { ?>

            <div class="booking-box">

              <h2>
                <?= htmlspecialchars($nextBooking['name']); ?>
              </h2>

              <p>
                📍 <?= htmlspecialchars($nextBooking['location']); ?>
              </p>

              <p>
                📅
                <?= date("d M Y", strtotime($nextBooking['booking_date'])); ?>
              </p>

              <p>
                🕒
                <?= date("g:i A", strtotime($nextBooking['start_time'])); ?>
                -
                <?= date("g:i A", strtotime($nextBooking['end_time'])); ?>
              </p>

              <button onclick="location.href='browse.php?bookingid=<?= $nextBooking['bookingid']; ?>'">
                View Booking
              </button>

            </div>

          <?php } else { ?>

            <div class="booking-box empty">

              <h2>No Upcoming Booking</h2>

              <p>
                You don't have any upcoming bookings.
              </p>

              <button onclick="location.href='browse.php'">
                Book Now
              </button>

            </div>

          <?php } ?>

        </div>

      </section>

    </main>
  </div>

</body>

</html>