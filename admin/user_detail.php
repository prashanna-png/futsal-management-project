<?php

global $conn;
session_start();
require_once '../config/auth.php';
require_once '../config/db.php';
require_login();

if ($_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

$currentPage = 'manageUsers';

// Check if userid exists in URL
if (!isset($_GET['userid'])) {
  header("Location: manage_users.php");
  exit();
}

$userid = $_GET['userid'];

// Initialize variables to avoid undefined variable warnings.
$futsalResult = null;
$futsalCounts = [
  'total' => 0,
  'approved' => 0,
  'pending' => 0,
  'rejected' => 0,
];
$bookingResult = null;
$bookingStats = [
  'total_bookings' => 0,
  'completed' => 0,
  'cancelled' => 0,
  'total_spent' => 0,
  'total_revenue' => 0,
];

// Get user details
$userResult = mysqli_query($conn, "
  SELECT * FROM users WHERE userid = '$userid'
");
$user = mysqli_fetch_assoc($userResult);

// If user not found redirect back
if (!$user) {
  header("Location: manage_users.php");
  exit();
}

// Get different data based on role
if ($user['role'] === 'owner') {

  // Get owner's futsals
  $futsalResult = mysqli_query($conn, "
    SELECT * FROM futsal
    WHERE ownerid = '$userid'
    ORDER BY created_at DESC
  ");

  // Get futsal counts
  $futsalCounts = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
      COUNT(*)                    AS total,
      SUM(status = 'approved')    AS approved,
      SUM(status = 'pending')     AS pending,
      SUM(status = 'rejected')    AS rejected
    FROM futsal
    WHERE ownerid = '$userid'
  "));

  // Get total bookings across owner's futsals
  $bookingStats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
      COUNT(b.bookingid)          AS total_bookings,
      COALESCE(SUM(b.amount), 0)  AS total_revenue
    FROM booking b
    JOIN futsal f ON b.futsalid = f.futsalid
    WHERE f.ownerid = '$userid'
    AND b.status = 'completed'
  "));
} elseif ($user['role'] === 'customer') {

  // Get customer's bookings
  $bookingResult = mysqli_query($conn, "
    SELECT
      b.*,
      f.name AS futsal_name,
      f.location
    FROM booking b
    JOIN futsal f ON b.futsalid = f.futsalid
    WHERE b.playerid = '$userid'
    ORDER BY b.created_at DESC
    LIMIT 10
  ");

  // Get booking stats
  $bookingStats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
      COUNT(*)                    AS total_bookings,
      SUM(status = 'completed')   AS completed,
      SUM(status = 'cancelled')   AS cancelled,
      COALESCE(SUM(amount), 0)    AS total_spent
    FROM booking
    WHERE playerid = '$userid'
  "));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Details</title>
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

      <!-- Header -->
      <div class="header">
        <div>
          <h1>User Details</h1>
          <p>View complete information about this user.</p>
        </div>
        <a href="manage_users.php" class="back-btn">← Back to Users</a>
      </div>

      <!-- Top Section: Profile + Info -->
      <div class="user-detail-container">

        <!-- Left: Avatar Card -->
        <div class="panel" style="text-align:center; padding:35px;">

          <div class="profile-avatar" style="margin: 0 auto 20px;">
            <?= strtoupper(substr($user['name'], 0, 1)) ?>
          </div>

          <h2 style="margin-bottom:8px;"><?= htmlspecialchars($user['name']) ?></h2>

          <span class="role-badge <?= $user['role'] ?>" style="margin-bottom:20px; display:inline-block;">
            <?= ucfirst($user['role']) ?>
          </span>

          <p style="color:#666; font-size:14px; margin-top:10px;">
            Member since <?= date('d M Y', strtotime($user['created_at'])) ?>
          </p>

        </div>

        <!-- Right: Personal Info -->
        <div class="panel">

          <h2 style="margin-bottom:20px;">Personal Information</h2>

          <div class="info-grid">

            <div class="info-box">
              <label>Full Name</label>
              <p><?= htmlspecialchars($user['name']) ?></p>
            </div>

            <div class="info-box">
              <label>Email</label>
              <p><?= htmlspecialchars($user['email']) ?></p>
            </div>

            <div class="info-box">
              <label>Phone</label>
              <p><?= htmlspecialchars($user['phone']) ?></p>
            </div>

            <div class="info-box">
              <label>Role</label>
              <p><?= ucfirst($user['role']) ?></p>
            </div>

            <div class="info-box">
              <label>Joined On</label>
              <p><?= date('d M Y', strtotime($user['created_at'])) ?></p>
            </div>

            <div class="info-box">
              <label>User ID</label>
              <p>#<?= $user['userid'] ?></p>
            </div>

          </div>

        </div>

      </div>

      <?php if ($user['role'] === 'owner'): ?>
        <!-- ===================== OWNER VIEW ===================== -->

        <!-- Owner Stats -->
        <div class="cards" style="grid-template-columns: repeat(4,1fr); margin-top:25px;">
          <div class="card">
            <h4>Total Futsals</h4>
            <h2><?= $futsalCounts['total'] ?? 0 ?></h2>
          </div>
          <div class="card">
            <h4>Approved</h4>
            <h2><?= $futsalCounts['approved'] ?? 0 ?></h2>
          </div>
          <div class="card">
            <h4>Pending</h4>
            <h2><?= $futsalCounts['pending'] ?? 0 ?></h2>
          </div>
          <div class="card">
            <h4>Total Revenue</h4>
            <h2>Rs. <?= number_format($bookingStats['total_revenue'] ?? 0) ?></h2>
          </div>
        </div>

        <!-- Owner's Futsals Table -->
        <div class="panel" style="margin-top:25px;">

          <h2 style="margin-bottom:20px;">Registered Futsals</h2>

          <table>
            <thead>
              <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Location</th>
                <th>Price/Hr</th>
                <th>Status</th>
                <th>Registered</th>
              </tr>
            </thead>
            <tbody>
              <?php if (mysqli_num_rows($futsalResult) > 0): ?>
                <?php while ($futsal = mysqli_fetch_assoc($futsalResult)): ?>
                  <tr>
                    <td>
                      <img
                        src="../assets/uploads/<?= htmlspecialchars($futsal['image']) ?>"
                        width="70" height="50"
                        style="border-radius:8px; object-fit:cover;">
                    </td>
                    <td><strong><?= htmlspecialchars($futsal['name']) ?></strong></td>
                    <td><?= htmlspecialchars($futsal['location']) ?></td>
                    <td>Rs. <?= number_format($futsal['price_per_hour']) ?></td>
                    <td>
                      <span class="status <?= strtolower($futsal['status']) ?>">
                        <?= ucfirst($futsal['status']) ?>
                      </span>
                    </td>
                    <td><?= date('d M Y', strtotime($futsal['created_at'])) ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" style="text-align:center; padding:30px; color:#666;">
                    No futsals registered yet.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>

        </div>

      <?php elseif ($user['role'] === 'customer'): ?>
        <!-- ===================== CUSTOMER VIEW ===================== -->

        <!-- Customer Stats -->
        <div class="cards" style="grid-template-columns: repeat(4,1fr); margin-top:25px;">
          <div class="card">
            <h4>Total Bookings</h4>
            <h2><?= $bookingStats['total_bookings'] ?? 0 ?></h2>
          </div>
          <div class="card">
            <h4>Completed</h4>
            <h2><?= $bookingStats['completed'] ?? 0 ?></h2>
          </div>
          <div class="card">
            <h4>Cancelled</h4>
            <h2><?= $bookingStats['cancelled'] ?? 0 ?></h2>
          </div>
          <div class="card">
            <h4>Total Spent</h4>
            <h2>Rs. <?= number_format($bookingStats['total_spent'] ?? 0) ?></h2>
          </div>
        </div>

        <!-- Customer's Bookings Table -->
        <div class="panel" style="margin-top:25px;">

          <h2 style="margin-bottom:20px;">Booking History</h2>

          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Futsal</th>
                <th>Location</th>
                <th>Date</th>
                <th>Time</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (mysqli_num_rows($bookingResult) > 0): ?>
                <?php $i = 1;
                while ($booking = mysqli_fetch_assoc($bookingResult)): ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td><strong><?= htmlspecialchars($booking['futsal_name']) ?></strong></td>
                    <td><?= htmlspecialchars($booking['location']) ?></td>
                    <td><?= date('d M Y', strtotime($booking['booking_date'])) ?></td>
                    <td>
                      <?= date('g:i A', strtotime($booking['start_time'])) ?> -
                      <?= date('g:i A', strtotime($booking['end_time'])) ?>
                    </td>
                    <td>Rs. <?= number_format($booking['amount']) ?></td>
                    <td>
                      <span class="status <?= strtolower($booking['status']) ?>">
                        <?= ucfirst($booking['status']) ?>
                      </span>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" style="text-align:center; padding:30px; color:#666;">
                    No bookings yet.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>

        </div>

      <?php else: ?>
        <!-- ===================== STAFF / ADMIN VIEW ===================== -->
        <div class="panel" style="margin-top:25px; text-align:center; padding:40px;">
          <p style="color:#666; font-size:15px;">
            No additional data to show for <?= ucfirst($user['role']) ?> accounts.
          </p>
        </div>

      <?php endif; ?>

      <!-- Delete Action -->
      <?php if ($user['userid'] != $_SESSION['userid']): ?>
        <div style="margin-top:25px;">
          <form method="POST" action="manage_users.php"
            onsubmit="return confirm('Are you sure you want to delete <?= htmlspecialchars($user['name']) ?>?')">
            <input type="hidden" name="userid" value="<?= $user['userid'] ?>">
            <button type="submit" name="action" value="delete" class="btn reject-btn">
              🗑 Delete This User
            </button>
          </form>
        </div>
      <?php endif; ?>

    </main>

  </div>

</body>

</html>