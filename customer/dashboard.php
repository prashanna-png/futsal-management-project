<?php
session_start();
global $conn;
require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

$currentPage = 'dashboard';

$playerid = $_SESSION['userid'];

$sql = "SELECT COUNT(*) AS total
        FROM booking
        WHERE playerid='$playerid'";
$result = mysqli_query($conn, $sql);
$total = mysqli_fetch_assoc($result);


$sql = "SELECT COUNT(*) AS pending
        FROM booking
        WHERE playerid='$playerid'
        AND status='pending'";
$result = mysqli_query($conn, $sql);
$pending = mysqli_fetch_assoc($result);


$sql = "SELECT COUNT(*) AS confirmed
        FROM booking
        WHERE playerid='$playerid'
        AND status='confirmed'";
$result = mysqli_query($conn, $sql);
$confirmed = mysqli_fetch_assoc($result);

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
  <link rel="shortcut icon" href="../assets/logo/main-logo.png" type="image/x-icon">
  <title>FutZo</title>
  <link rel="stylesheet" href="../assets/css/customer.css">
</head>

<body>
  <nav class="nav-bar">

    <div class="left-section">
      <img src="../assets/logo/futzo-logo.png" alt="FutZo Logo">
      <span>FutZo</span>
    </div>

    <div class="center-section">
      <a href="#dashboard" class="nav-link active">Dashboard</a>
      <a href="#browse" class="nav-link">Browse</a>
      <a href="#bookings" class="nav-link">My Bookings</a>
      <a href="#support" class="nav-link">Support</a>
      <a href="#profile" class="nav-link">Profile</a>
    </div>

    <div class="right-section">

      <div class="avatar">
        <?= strtoupper(substr($_SESSION['name'], 0, 1)); ?>
      </div>

      <div>
        <strong><?= htmlspecialchars($_SESSION['name']); ?></strong>
        <small>Customer</small>
      </div>

    </div>

  </nav>
  <main class="dashboard">

    <section class="hero">

      <div class="hero-text">

        <h1>
          Welcome back,
          <?= htmlspecialchars($_SESSION['name']); ?> 👋
        </h1>

        <p>
          Ready for your next futsal match? Browse available courts and manage your bookings with ease.
        </p>

        <button onclick="location.href='browse.php'">
          Browse Futsals
        </button>

      </div>

    </section>

    <section class="stats">

      <div class="stat-card">
        <h3><?= $total['total']; ?></h3>
        <p>Total Bookings</p>
      </div>

      <div class="stat-card">
        <h3><?= $confirmed['confirmed']; ?></h3>
        <p>Confirmed</p>
      </div>

      <div class="stat-card">
        <h3><?= $pending['pending']; ?></h3>
        <p>Pending</p>
      </div>

      <div class="stat-card">
        <h3><?= $completed['completed']; ?></h3>
        <p>Completed</p>
      </div>

    </section>

    <section class="upcoming">

      <div class="section-title">
        <h2>Next Upcoming Booking</h2>
      </div>

      <?php if ($nextBooking) { ?>

        <div class="booking-card">

          <div class="booking-image">
            <img src="../uploads/<?= htmlspecialchars($nextBooking['image']); ?>" alt="">
          </div>

          <div class="booking-info">

            <h2><?= htmlspecialchars($nextBooking['name']); ?></h2>

            <p>📍 <?= htmlspecialchars($nextBooking['location']); ?></p>

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

            <button>
              View Booking
            </button>

          </div>

        </div>

      <?php } else { ?>

        <div class="empty-booking">

          <h2>No Upcoming Booking</h2>

          <p>
            Looks like you don't have any upcoming matches.
          </p>

          <button onclick="location.href='browse.php'">
            Book Now
          </button>

        </div>

      <?php } ?>

    </section>

    <section class="recent">

      <div class="section-title">

        <h2>Recent Bookings</h2>

        <a href="my_bookings.php">
          View All
        </a>

      </div>

      <div class="recent-grid">

        <?php while ($row = mysqli_fetch_assoc($recentResult)) { ?>

          <div class="recent-card">

            <h3><?= htmlspecialchars($row['name']); ?></h3>

            <p>
              📅
              <?= date("d M Y", strtotime($row['booking_date'])); ?>
            </p>

            <p>
              🕒
              <?= date("g:i A", strtotime($row['start_time'])); ?>
              -
              <?= date("g:i A", strtotime($row['end_time'])); ?>
            </p>

            <span class="status <?= strtolower($row['status']); ?>">
              <?= ucfirst($row['status']); ?>
            </span>

          </div>

        <?php } ?>

      </div>

    </section>

    <section class="recommended">

      <div class="section-title">

        <h2>Recommended Futsals</h2>

        <a href="browse.php">
          View All
        </a>

      </div>

      <div class="recommended-grid">
      </div>

    </section>

  </main>

</body>

</html>