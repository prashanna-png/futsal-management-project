<?php
session_start();
global $conn;
require_once '../config/db.php';
require_once '../config/auth.php';
require_login();
$currentPage = 'dashboard';

$ownerid = $_SESSION['userid'];

$sql = "SELECT * FROM futsal WHERE ownerid='$ownerid'";
$result = mysqli_query($conn, $sql);
$totalCourt = mysqli_num_rows($result);

$sql = "SELECT * FROM futsal
        WHERE ownerid='$ownerid'
        AND status='pending'";

$result = mysqli_query($conn, $sql);
$totalPending = mysqli_num_rows($result);

$sql = "SELECT * FROM futsal WHERE ownerid='$ownerid' AND status='approved'";
$result = mysqli_query($conn, $sql);
$totalApproved = mysqli_num_rows($result);

$sql = "SELECT * FROM futsal WHERE ownerid='$ownerid' AND status='rejected'";
$result = mysqli_query($conn, $sql);
$totalReject = mysqli_num_rows($result);

$sql = "SELECT * FROM futsal WHERE ownerid='$ownerid'";
$result = mysqli_query($conn, $sql);


$sql = "
SELECT 
    b.booking_date,
    b.start_time,
    b.end_time,
    b.status,
    u.name AS customer_name,
    f.name AS futsal_name

FROM booking b
JOIN users u
    ON b.playerid = u.userid
JOIN futsal f
    ON b.futsalid = f.futsalid

WHERE f.ownerid = '$ownerid'

ORDER BY b.booking_date DESC,
         b.start_time DESC

LIMIT 3;
";

$bookingResult = mysqli_query($conn, $sql);


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

      <div class="cards">

        <div class="card">
          <h4>Total Futsals</h4>
          <h2><?php echo htmlspecialchars($totalCourt); ?></h2>
          <p>Registered Courts</p>
        </div>

        <div class="card">
          <h4>Pending Approval</h4>
          <h2>
            <?php echo htmlspecialchars($totalPending); ?>
          </h2>
          <p>Waiting for Admin</p>
        </div>

        <div class="card">
          <h4>Approved Courts</h4>
          <h2>
            <?php echo htmlspecialchars($totalApproved); ?>
          </h2>
        </div>

        <div class="card">
          <h4>Reject Courts</h4>
          <h2>
            <?php echo htmlspecialchars($totalReject); ?>
          </h2>
        </div>

      </div>

      <div class="middle">

        <div class="booking">

          <h3>Recent Registered Futsals</h3>

          <div class="booking-info">
            <?php
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="item">
                  <strong>
                    <?php
                    echo htmlspecialchars($row['name']);
                    ?>
                  </strong>
                  <br>
                  <?php
                  echo htmlspecialchars($row['location']);
                  ?>
                  <br>
                  <?php
                  echo htmlspecialchars($row['price_per_hour']);
                  ?> / Hour<br>
                  Status : <?php
                            echo htmlspecialchars($row['status']);
                            ?>
                </div>
            <?php
              }
            }
            ?>

          </div>

        </div>

        <div class="actions">

          <h3>Quick Actions</h3>

          <div class="action-grid">

            <a href="register_futsal.php" class="action">
              Register Futsal
            </a>

            <a href="my_futsal.php" class="action">
              My Futsals
            </a>

            <a href="profile.php" class="action">
              Profile
            </a>

            <a href="manage_bookings.php" class="action">
              Bookings
            </a>

          </div>

        </div>

      </div>

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
              <?php
              if (mysqli_num_rows($bookingResult) > 0) {
                while ($booking = mysqli_fetch_assoc($bookingResult)) {

              ?>
                  <tr>
                    <td>
                      <?= $booking['customer_name'] ?>
                    </td>
                    <td>
                      <?= $booking['futsal_name'] ?>
                    </td>
                    <td>
                      <?= date("d M Y", strtotime($booking['booking_date'])) ?>
                    </td>
                    <td>
                      <?= date("g:i A", strtotime($booking['start_time'])); ?>
                      -
                      <?= date("g:i A", strtotime($booking['end_time'])); ?>
                    </td>
                    <td>
                      <span class="status <?= strtolower($booking['status']) ?>">
                        <?= ucfirst($booking['status']) ?>
                      </span>
                    </td>
                  </tr>
              <?php
                }
              }
              ?>

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