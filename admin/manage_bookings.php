<?php

global $conn;
/*
  =====================================================
  admin/manage_bookings.php
  
  PURPOSE:
  Admin can view ALL bookings across every futsal
  on the platform. Unlike owner/bookings.php which
  only shows bookings for that owner's courts, admin
  sees everything.
  =====================================================
*/

session_start();

require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

// Only admin can access this page
// If someone else tries to visit, send them back to login
if ($_SESSION['role'] != 'admin') {
  header("Location: ../login.php");
  exit();
}

$currentPage = 'manageBookings';

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

/*
  =====================================================
  FILTER LOGIC
  
  We get the filter value from the URL using $_GET.
  Example URLs:
    manage_bookings.php              → shows all
    manage_bookings.php?filter=pending   → pending only
    manage_bookings.php?filter=confirmed → confirmed only
  
  $_GET['filter'] ?? 'all' means:
    if ?filter= exists in URL, use it
    otherwise default to 'all'
  =====================================================
*/
$filter = $_GET['filter'] ?? 'all';

// Build the WHERE clause based on filter
// We start with empty string (no filter = show all)
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

/*
  =====================================================
  MAIN BOOKINGS QUERY
  
  This is a JOIN query — it connects 3 tables:
    booking  → has the booking details
    users    → has the customer's name and phone
    futsal   → has the futsal name and location
  
  JOIN means: "combine rows from multiple tables
  where the IDs match"
  
  b.playerid = u.userid means:
    match booking's playerid with users table's userid
  
  b.futsalid = f.futsalid means:
    match booking's futsalid with futsal table's futsalid
  
  We alias tables with short names:
    b = booking
    u = users
    f = futsal
  =====================================================
*/
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

/*
  =====================================================
  COUNT QUERY FOR STAT CARDS
  
  SUM(condition) is a MySQL trick:
    - When condition is TRUE, MySQL treats it as 1
    - When FALSE, treats it as 0
    - SUM adds them all up = total count for that status
  
  This is more efficient than running 4 separate
  COUNT queries.
  =====================================================
*/
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

      <!-- Alert Messages -->
      <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <!-- Header -->
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

      <!--
        =====================================================
        STAT CARDS

        We show 5 cards:
          1. Total bookings
          2. Pending
          3. Confirmed
          4. Completed
          5. Total Revenue

        $counts['total'] uses the COUNT(*) result
        from our query above.

        number_format() adds commas to large numbers:
          45000 → 45,000
        =====================================================
      -->
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

      <!--
        =====================================================
        FILTER TABS

        These are just links with ?filter=value in the URL.
        PHP reads that value above and builds the WHERE clause.

        The active class highlights the current filter.
        We compare $filter (from $_GET) with each tab's value.
        =====================================================
      -->
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

      <!-- Bookings Table -->
      <div class="panel" style="margin-top: 20px;">

        <!--
          Search box — filters table rows using JavaScript.
          No page reload needed, works instantly as you type.
          The JS code at the bottom handles this.
        -->
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

            <!--
              =====================================================
              DISPLAY BOOKINGS

              mysqli_num_rows() checks if query returned any rows.
              If 0 rows → show empty state message.
              If rows exist → loop through with while().

              Inside the loop:
                $row = one booking record as associative array
                $row['customer_name'] = from JOIN with users table
                $row['futsal_name']   = from JOIN with futsal table

              date('d M Y', strtotime($row['booking_date']))
                converts '2026-07-17' to '17 Jul 2026'

              strtolower($row['status']) gives us the CSS class
                'pending' → class="status pending" → orange badge
                'confirmed' → class="status confirmed" → green badge
              =====================================================
            -->
            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php $i = 1;
              while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>

                  <!-- Row number — $i starts at 1, increments each loop -->
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
                    <!--
                      strtolower() makes status lowercase
                      so it matches CSS class names:
                        'Pending' → 'pending' → .status.pending { orange }
                      ucfirst() capitalizes first letter for display:
                        'pending' → 'Pending'
                    -->
                    <span class="status <?= strtolower($row['status']) ?>">
                      <?= ucfirst($row['status']) ?>
                    </span>
                  </td>

                  <td>
                    <!-- Show when booking was created -->
                    <?= date('d M Y', strtotime($row['created_at'])) ?>
                  </td>

                </tr>
              <?php endwhile; ?>

            <?php else: ?>
              <!-- Empty state — shown when no bookings match the filter -->
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