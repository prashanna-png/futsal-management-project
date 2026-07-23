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
        <strong>
          <?= strtoupper($_SESSION['name']); ?>
        </strong>
        <small>Customer</small>
      </div>

    </div>

  </nav>

  <main class="main">

    <section class="dashboard-header">

      <div class="header-left">

        <h1>
          Welcome back,
          <?= strtoupper(htmlspecialchars($_SESSION['name'])); ?>!
        </h1>

        <p>
          <?= date("l, d F Y"); ?>
        </p>

      </div>

      <div class="header-right">

        <a href="browse.php" class="booking-btn">
          + New Booking
        </a>

      </div>

    </section>

    <section class="stats">

      <div class="stat-card">
        <div class="stat-info">
          <span>Total Bookings</span>
          <h2><?= $total['total']; ?></h2>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <span>Confirmed</span>
          <h2><?= $confirmed['confirmed']; ?></h2>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <span>Pending</span>
          <h2><?= $pending['pending']; ?></h2>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <span>Completed</span>
          <h2><?= $completed['completed']; ?></h2>
        </div>
      </div>

    </section>

  </main>

</body>

</html>