<?php

global $conn;

session_start();

require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

if ($_SESSION['role'] != 'admin') {
  header("Location: ../login.php");
  exit();
}

$currentPage = 'manageBookings';

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$filter = $_GET['filter'] ?? 'all';

$where = "";

if ($filter === 'pending') {
  $where = "WHERE b.status = 'pending'";
} elseif ($filter === 'confirmed') {
  $where = "WHERE b.status = 'confirmed'";
} elseif ($filter === 'completed') {
  $where = "WHERE b.status = 'completed'";
} elseif ($filter === 'cancelled') {
  $where = "WHERE b.status = 'cancelled'";
}

$result = mysqli_query($conn, "
  SELECT
    b.bookingid,
    b.booking_date,
    b.start_time,
    b.end_time,
    b.amount,
    b.status,
    b.created_at,
    u.name  AS customer_name,
    u.phone AS customer_phone,
    f.name  AS futsal_name,
    f.location
  FROM booking b
  JOIN users u  ON b.playerid  = u.userid
  JOIN futsal f ON b.futsalid  = f.futsalid
  $where
  ORDER BY b.created_at DESC
");

$counts = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT
    COUNT(*)                        AS total,
    SUM(status = 'pending')         AS pending,
    SUM(status = 'confirmed')       AS confirmed,
    SUM(status = 'completed')       AS completed,
    SUM(status = 'cancelled')       AS cancelled,
    SUM(amount)                     AS total_revenue,
    SUM(status = 'completed' AND amount > 0) AS paid_bookings
  FROM booking
"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Bookings</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
</head>

<body>

  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">

      <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <div class="header">
        <div>
          <h1>Manage Bookings</h1>
          <p>View all bookings across every futsal on the platform.</p>
        </div>
        <a href="profile.php" class="admin-user">
          <div class="avatar">
            <?= strtoupper(substr($_SESSION['name'], 0, 1)); ?>
          </div>
          <div>
            <strong><?= htmlspecialchars($_SESSION['name']); ?></strong>
            <br>
            Administrator
          </div>
        </a>
      </div>

      <div class="cards" style="grid-template-columns: repeat(5, 1fr);">

        <div class="card">
          <h4>Total Bookings</h4>
          <h2><?= $counts['total'] ?? 0 ?></h2>
          <p>All time</p>
        </div>

        <div class="card">
          <h4>Pending</h4>
          <h2><?= $counts['pending'] ?? 0 ?></h2>
          <p>Awaiting confirmation</p>
        </div>

        <div class="card">
          <h4>Confirmed</h4>
          <h2><?= $counts['confirmed'] ?? 0 ?></h2>
          <p>Upcoming matches</p>
        </div>

        <div class="card">
          <h4>Completed</h4>
          <h2><?= $counts['completed'] ?? 0 ?></h2>
          <p>Finished matches</p>
        </div>

        <div class="card">
          <h4>Total Revenue</h4>
          <h2>Rs. <?= number_format($counts['total_revenue'] ?? 0) ?></h2>
          <p>From completed bookings</p>
        </div>

      </div>

      <div class="filter-tabs">
        <a href="?filter=all" class="<?= $filter === 'all'       ? 'active' : '' ?>">
          All (<?= $counts['total'] ?>)
        </a>
        <a href="?filter=pending" class="<?= $filter === 'pending'   ? 'active' : '' ?>">
          Pending (<?= $counts['pending'] ?>)
        </a>
        <a href="?filter=confirmed" class="<?= $filter === 'confirmed' ? 'active' : '' ?>">
          Confirmed (<?= $counts['confirmed'] ?>)
        </a>
        <a href="?filter=completed" class="<?= $filter === 'completed' ? 'active' : '' ?>">
          Completed (<?= $counts['completed'] ?>)
        </a>
        <a href="?filter=cancelled" class="<?= $filter === 'cancelled' ? 'active' : '' ?>">
          Cancelled (<?= $counts['cancelled'] ?>)
        </a>
      </div>

      <div class="panel" style="margin-top: 20px;">

        <div class="search-box">
          <input
            type="text"
            id="searchInput"
            placeholder="Search by customer name or futsal...">
        </div>

        <table id="bookingsTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Customer</th>
              <th>Futsal</th>
              <th>Date</th>
              <th>Time</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Booked On</th>
            </tr>
          </thead>
          <tbody>

            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php $i = 1;
              while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>

                  <td><?= $i++ ?></td>

                  <td>
                    <strong><?= htmlspecialchars($row['customer_name']) ?></strong>
                    <br>
                    <small style="color:#666;"><?= htmlspecialchars($row['customer_phone']) ?></small>
                  </td>

                  <td>
                    <?= htmlspecialchars($row['futsal_name']) ?>
                    <br>
                    <small style="color:#666;">📍 <?= htmlspecialchars($row['location']) ?></small>
                  </td>

                  <td><?= date('d M Y', strtotime($row['booking_date'])) ?></td>

                  <td>
                    <?= date('g:i A', strtotime($row['start_time'])) ?>
                    -
                    <?= date('g:i A', strtotime($row['end_time'])) ?>
                  </td>

                  <td>Rs. <?= number_format($row['amount']) ?></td>

                  <td>
                    <span class="status <?= strtolower($row['status']) ?>">
                      <?= ucfirst($row['status']) ?>
                    </span>
                  </td>

                  <td>
                    <?= date('d M Y', strtotime($row['created_at'])) ?>
                  </td>

                </tr>
              <?php endwhile; ?>

            <?php else: ?>
              <tr>
                <td colspan="8" style="text-align:center; padding:40px; color:#666;">
                  <?php if ($filter !== 'all'): ?>
                    No <?= $filter ?> bookings found.
                  <?php else: ?>
                    No bookings yet.
                  <?php endif; ?>
                </td>
              </tr>
            <?php endif; ?>

          </tbody>
        </table>

      </div>

    </main>

  </div>

</body>

</html>