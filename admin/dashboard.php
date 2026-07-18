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

$currentPage = 'dashboard';

$result     = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='customer'");
$totalUsers = mysqli_fetch_assoc($result)['total'];


$result      = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='owner'");
$totalOwners = mysqli_fetch_assoc($result)['total'];


$result       = mysqli_query($conn, "SELECT COUNT(*) AS total FROM futsal");
$totalFutsals = mysqli_fetch_assoc($result)['total'];


$result       = mysqli_query($conn, "SELECT COUNT(*) AS total FROM futsal WHERE status='pending'");
$totalPending = mysqli_fetch_assoc($result)['total'];


$result        = mysqli_query($conn, "SELECT COUNT(*) AS total FROM booking");
$totalBookings = mysqli_fetch_assoc($result)['total'];


$pendingResult = mysqli_query($conn, "
  SELECT
    f.futsalid,
    f.name,
    f.location,
    f.image,
    u.name AS owner
  FROM futsal f
  JOIN users u ON f.ownerid = u.userid
  WHERE f.status = 'pending'
  ORDER BY f.created_at DESC
  LIMIT 5
");


$userResult = mysqli_query($conn, "
  SELECT * FROM users
  ORDER BY created_at DESC
  LIMIT 5
");

$bookingResult = mysqli_query($conn, "
  SELECT
    b.bookingid,
    b.booking_date,
    b.start_time,
    b.end_time,
    b.status,
    b.amount,
    u.name AS customer_name,
    f.name AS futsal_name
  FROM booking b
  JOIN users u ON b.playerid = u.userid
  JOIN futsal f ON b.futsalid = f.futsalid
  ORDER BY b.created_at DESC
  LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
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
          <h1>Welcome Back, <?= htmlspecialchars($_SESSION['name']); ?> 👋</h1>
          <p>Here's what's happening on the platform today.</p>
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

      <div class="cards">

        <div class="card">
          <h4>Total Customers</h4>
          <h2><?= $totalUsers; ?></h2>
          <p>Registered players</p>
        </div>

        <div class="card">
          <h4>Total Owners</h4>
          <h2><?= $totalOwners; ?></h2>
          <p>Registered owners</p>
        </div>

        <div class="card">
          <h4>Total Futsals</h4>
          <h2><?= $totalFutsals; ?></h2>
          <p>Listed courts</p>
        </div>

        <div class="card">
          <h4>Pending Approval</h4>
          <h2><?= $totalPending; ?></h2>
          <p>Waiting for review</p>
        </div>

        <div class="card">
          <h4>Total Bookings</h4>
          <h2><?= $totalBookings; ?></h2>
          <p>All time bookings</p>
        </div>

      </div>

      <div class="content">

        <div class="panel">

          <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2>Recent Pending Futsals</h2>
            <a href="pending_futsals.php" style="color:#111; font-size:14px;">View All →</a>
          </div>

          <table>
            <thead>
              <tr>
                <th>Image</th>
                <th>Futsal</th>
                <th>Owner</th>
                <th>Location</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (mysqli_num_rows($pendingResult) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($pendingResult)): ?>
                  <tr>
                    <td>
                      <img
                        src="../assets/uploads/<?= htmlspecialchars($row['image']); ?>"
                        width="70"
                        style="border-radius:8px; object-fit:cover; height:50px;">
                    </td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['owner']); ?></td>
                    <td><?= htmlspecialchars($row['location']); ?></td>
                    <td><span class="status pending">Pending</span></td>
                    <td>
                      <a href="pending_futsals.php" class="btn view-btn">Review</a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" style="text-align:center; padding:30px; color:#666;">
                    No pending futsals 🎉
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>

          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; margin-top: 80px;">
            <h2>Recent Bookings</h2>
            <a href="manage_bookings.php" style="color:#111; font-size:14px;">View All →</a>
          </div>

          <table>
            <thead>
              <tr>
                <th>Customer</th>
                <th>Futsal</th>
                <th>Date</th>
                <th>Time</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (mysqli_num_rows($bookingResult) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($bookingResult)): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['customer_name']); ?></td>
                    <td><?= htmlspecialchars($row['futsal_name']); ?></td>
                    <td><?= date('d M Y', strtotime($row['booking_date'])); ?></td>
                    <td>
                      <?= date('g:i A', strtotime($row['start_time'])); ?>
                      -
                      <?= date('g:i A', strtotime($row['end_time'])); ?>
                    </td>
                    <td>Rs. <?= number_format($row['amount']); ?></td>
                    <td>
                      <span class="status <?= strtolower($row['status']); ?>">
                        <?= ucfirst($row['status']); ?>
                      </span>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" style="text-align:center; padding:30px; color:#666;">
                    No bookings yet.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>


        </div>

        <div style="display:flex; flex-direction:column; gap:20px;">

          <div class="panel">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
              <h2>Recent Users</h2>
              <a href="manage_users.php" style="color:#111; font-size:14px;">View All →</a>
            </div>

            <div class="user-list">
              <?php if (mysqli_num_rows($userResult) > 0): ?>
                <?php while ($user = mysqli_fetch_assoc($userResult)): ?>
                  <div class="user-item">
                    <div class="user-info">
                      <div class="user-avatar">
                        <?= strtoupper(substr($user['name'], 0, 1)); ?>
                      </div>
                      <div>
                        <strong><?= htmlspecialchars($user['name']); ?></strong>
                        <br>
                        <small style="color:#666;"><?= ucfirst($user['role']); ?></small>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              <?php else: ?>
                <p style="color:#666; text-align:center; padding:20px;">No users yet.</p>
              <?php endif; ?>
            </div>
          </div>

          <div class="panel">
            <h2 style="margin-bottom:20px;">Quick Actions</h2>
            <div class="quick-links">
              <a href="pending_futsals.php">⚽ Manage Futsals</a>
              <a href="manage_users.php">👥 Manage Users</a>
              <a href="manage_bookings.php">📅 Manage Bookings</a>
              <a href="support_message.php">💬 Support Messages</a>
              <a href="reports.php">📊 View Reports</a>
            </div>
          </div>

        </div>

      </div>


    </main>

  </div>

</body>

</html>