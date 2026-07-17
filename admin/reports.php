<?php
global $conn;
/*
=====================================================
admin/reports.php

PURPOSE:
Shows platform-wide statistics and reports.
All data comes from SQL queries — no hardcoding.

This page is READ ONLY — no forms, no POST handling.
Just queries and display.
=====================================================
*/

session_start();

require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

if ($_SESSION['role'] != 'admin') {
  header("Location: ../login.php");
  exit();
}

$currentPage = 'reports';

/*
=====================================================
QUERY 1 — PLATFORM OVERVIEW STATS
=====================================================
*/
$overview = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*) AS total_bookings,
        SUM(status='completed') AS completed_bookings,
        SUM(status='pending') AS pending_bookings,
        SUM(status='cancelled') AS cancelled_bookings,
        COALESCE(SUM(amount),0) AS total_revenue,
        COALESCE(AVG(amount),0) AS avg_booking_amount,
        COALESCE(
            SUM(
                CASE
                    WHEN status='completed' THEN amount
                    ELSE 0
                END
            ),0
        ) AS completed_revenue
    FROM booking
"));

/*
=====================================================
QUERY 2 — USER STATS
=====================================================
*/
$userStats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*) AS total_users,
        SUM(role='customer') AS total_customers,
        SUM(role='owner') AS total_owners,
        SUM(role='staff') AS total_staff
    FROM users
"));

/*
=====================================================
QUERY 3 — FUTSAL STATS
=====================================================
*/
$futsalStats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*) AS total_futsals,
        SUM(status='approved') AS approved_futsals,
        SUM(status='pending') AS pending_futsals,
        SUM(status='rejected') AS rejected_futsals
    FROM futsal
"));

/*
=====================================================
QUERY 4 — THIS MONTH'S STATS
=====================================================
*/
$thisMonth = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(*) AS bookings_this_month,
        COALESCE(SUM(amount),0) AS revenue_this_month
    FROM booking
    WHERE DATE_FORMAT(booking_date,'%Y-%m')
        = DATE_FORMAT(NOW(),'%Y-%m')
"));

/*
=====================================================
QUERY 5 — TOP 5 MOST BOOKED FUTSALS
=====================================================
*/
$topFutsals = mysqli_query($conn, "
    SELECT
        f.name,
        f.location,
        COUNT(b.bookingid) AS total_bookings,
        COALESCE(SUM(b.amount),0) AS total_revenue
    FROM futsal f
    LEFT JOIN booking b
        ON f.futsalid = b.futsalid
    WHERE f.status='approved'
    GROUP BY
        f.futsalid,
        f.name,
        f.location
    ORDER BY total_bookings DESC
    LIMIT 5
");

/*
=====================================================
QUERY 6 — TOP 5 CUSTOMERS
=====================================================
*/
$topCustomers = mysqli_query($conn, "
    SELECT
        u.name,
        u.email,
        COUNT(b.bookingid) AS total_bookings,
        COALESCE(SUM(b.amount),0) AS total_spent
    FROM users u
    LEFT JOIN booking b
        ON u.userid = b.playerid
    WHERE u.role='customer'
    GROUP BY
        u.userid,
        u.name,
        u.email
    ORDER BY total_bookings DESC
    LIMIT 5
");

/*
=====================================================
QUERY 7 — BOOKINGS BY DAY OF WEEK
=====================================================
*/
$bookingsByDay = mysqli_query($conn, "
    SELECT
        DAYNAME(booking_date) AS day_name,
        COUNT(*) AS total
    FROM booking
    WHERE status!='cancelled'
    GROUP BY
        DAYNAME(booking_date),
        DAYOFWEEK(booking_date)
    ORDER BY DAYOFWEEK(booking_date)
");

/*
=====================================================
QUERY 8 — LAST 6 MONTHS REVENUE
=====================================================
*/
$sql = "SELECT DATE_FORMAT(booking_date,'%b %Y') AS month,
        COUNT(*) AS bookings,
        COALESCE(SUM(amount),0) AS revenue
    FROM booking
    WHERE
        status='completed'
        AND booking_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(booking_date,'%Y-%m')
    ORDER BY booking_date ASC
";
$monthlyRevenue = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

  <title>Reports</title>

  <link
    rel="stylesheet"
    href="../assets/css/admin.css">

  <link
    rel="preconnect"
    href="https://fonts.googleapis.com">

  <link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin>

  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

</head>

<body>
  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">

      <!-- Header -->
      <div class="header">

        <div>
          <h1>Platform Reports 📊</h1>
          <p>Overview of all activity on FutsalHub.</p>
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
        ================================================
        THIS MONTH PERFORMANCE
        ================================================
        -->

      <div style="
            background:#111;
            border-radius:18px;
            padding:25px;
            margin-bottom:25px;
            color:#fff;
        ">

        <h3 style="
                margin-bottom:15px;
                color:#fff;
            ">
          📅 This Month's Performance
        </h3>

        <div style="
                display:grid;
                grid-template-columns:1fr 1fr;
                gap:20px;
            ">

          <div>

            <p style="
                        color:#aaa;
                        margin-bottom:5px;
                    ">
              Bookings This Month
            </p>

            <h2 style="font-size:36px;">
              <?= $thisMonth['bookings_this_month']; ?>
            </h2>

          </div>

          <div>

            <p style="
                        color:#aaa;
                        margin-bottom:5px;
                    ">
              Revenue This Month
            </p>

            <h2 style="font-size:36px;">
              Rs. <?= number_format($thisMonth['revenue_this_month']); ?>
            </h2>

          </div>

        </div>

      </div>

      <!--
        ================================================
        OVERVIEW STAT CARDS
        ================================================
        -->

      <div class="cards" style="
            grid-template-columns:repeat(3,1fr);
            margin-bottom:25px;
        ">

        <div class="card">

          <h4>Total Revenue</h4>

          <h2>
            Rs. <?= number_format($overview['completed_revenue']); ?>
          </h2>

          <p>From completed bookings</p>

        </div>

        <div class="card">

          <h4>Total Bookings</h4>

          <h2>
            <?= $overview['total_bookings']; ?>
          </h2>

          <p>
            <?= $overview['completed_bookings']; ?> completed,
            <?= $overview['cancelled_bookings']; ?> cancelled
          </p>

        </div>

        <div class="card">

          <h4>Average Booking Value</h4>

          <h2>
            Rs. <?= number_format($overview['avg_booking_amount']); ?>
          </h2>

          <p>Per booking</p>

        </div>

        <div class="card">

          <h4>Total Users</h4>

          <h2>
            <?= $userStats['total_users']; ?>
          </h2>

          <p>
            <?= $userStats['total_customers']; ?> customers,
            <?= $userStats['total_owners']; ?> owners
          </p>

        </div>

        <div class="card">

          <h4>Total Futsals</h4>

          <h2>
            <?= $futsalStats['total_futsals']; ?>
          </h2>

          <p>
            <?= $futsalStats['approved_futsals']; ?> approved,
            <?= $futsalStats['pending_futsals']; ?> pending
          </p>

        </div>

        <div class="card">

          <h4>Pending Bookings</h4>

          <h2>
            <?= $overview['pending_bookings']; ?>
          </h2>

          <p>
            Waiting for confirmation
          </p>

        </div>

      </div>
      <!--
        ================================================
        TOP FUTSALS & TOP CUSTOMERS
        ================================================
        -->
      <div class="content" style="margin-bottom:25px;">

        <!-- Top 5 Most Booked Futsals -->
        <div class="panel">

          <h2>🏆 Top 5 Most Booked Futsals</h2>

          <?php if (mysqli_num_rows($topFutsals) > 0): ?>

            <table>

              <thead>
                <tr>
                  <th>#</th>
                  <th>Futsal</th>
                  <th>Location</th>
                  <th>Bookings</th>
                  <th>Revenue</th>
                </tr>
              </thead>

              <tbody>

                <?php
                $rank = 1;

                while ($row = mysqli_fetch_assoc($topFutsals)):
                ?>

                  <tr>

                    <td>
                      <?= $rank === 1
                        ? '🥇'
                        : ($rank === 2
                          ? '🥈'
                          : ($rank === 3
                            ? '🥉'
                            : $rank)) ?>
                    </td>

                    <td>
                      <strong>
                        <?= htmlspecialchars($row['name']) ?>
                      </strong>
                    </td>

                    <td>
                      <?= htmlspecialchars($row['location']) ?>
                    </td>

                    <td>
                      <?= $row['total_bookings'] ?>
                    </td>

                    <td>
                      Rs. <?= number_format($row['total_revenue']) ?>
                    </td>

                  </tr>

                  <?php $rank++; ?>

                <?php endwhile; ?>

              </tbody>

            </table>

          <?php else: ?>

            <p style="color:#666;padding:20px 0;">
              No booking data yet.
            </p>

          <?php endif; ?>

        </div>

        <!-- Top 5 Customers -->
        <div class="panel">

          <h2>⭐ Top 5 Customers</h2>

          <?php if (mysqli_num_rows($topCustomers) > 0): ?>

            <div class="user-list">

              <?php
              $rank = 1;

              while ($row = mysqli_fetch_assoc($topCustomers)):
              ?>

                <div class="user-item">

                  <div class="user-info">

                    <div class="user-avatar">
                      <?= strtoupper(substr($row['name'], 0, 1)); ?>
                    </div>

                    <div>

                      <strong>
                        <?= htmlspecialchars($row['name']) ?>
                      </strong>

                      <br>

                      <small style="color:#666;">

                        <?= $row['total_bookings']; ?> bookings •

                        Rs. <?= number_format($row['total_spent']); ?> spent

                      </small>

                    </div>

                  </div>

                  <span style="
                                    background:<?= $rank <= 3 ? '#111' : '#f5f5f5' ?>;
                                    color:<?= $rank <= 3 ? '#fff' : '#666' ?>;
                                    padding:5px 12px;
                                    border-radius:20px;
                                    font-size:13px;
                                    font-weight:600;
                                ">
                    #<?= $rank ?>
                  </span>

                </div>

                <?php $rank++; ?>

              <?php endwhile; ?>

            </div>

          <?php else: ?>

            <p style="color:#666;padding:20px 0;">
              No customer data yet.
            </p>

          <?php endif; ?>

        </div>

      </div>
      <!--
        ================================================
        MONTHLY REVENUE & POPULAR BOOKING DAYS
        ================================================
        -->
      <div class="content">

        <!-- Last 6 Months Revenue -->
        <div class="panel">

          <h2>📈 Last 6 Months Revenue</h2>

          <?php
          $months = [];

          while ($row = mysqli_fetch_assoc($monthlyRevenue)) {
            $months[] = $row;
          }

          $maxRevenue = !empty($months)
            ? max(array_column($months, 'revenue'))
            : 1;
          ?>

          <?php if (!empty($months)): ?>

            <table>

              <thead>
                <tr>
                  <th>Month</th>
                  <th>Bookings</th>
                  <th>Revenue</th>
                  <th>Progress</th>
                </tr>
              </thead>

              <tbody>

                <?php foreach ($months as $row): ?>

                  <tr>

                    <td>
                      <?= $row['month'] ?>
                    </td>

                    <td>
                      <?= $row['bookings'] ?>
                    </td>

                    <td>
                      Rs. <?= number_format($row['revenue']) ?>
                    </td>

                    <td>

                      <div style="
                                            background:#f0f0f0;
                                            border-radius:10px;
                                            height:8px;
                                            width:120px;
                                        ">

                        <div style="
                                                background:#111;
                                                height:8px;
                                                border-radius:10px;
                                                width:<?= $maxRevenue > 0
                                                        ? round(($row['revenue'] / $maxRevenue) * 100)
                                                        : 0 ?>%;
                                            "></div>

                      </div>

                    </td>

                  </tr>

                <?php endforeach; ?>

              </tbody>

            </table>

          <?php else: ?>

            <p style="color:#666;padding:20px 0;">
              No revenue data yet.
            </p>

          <?php endif; ?>

        </div>

        <!-- Popular Booking Days -->
        <div class="panel">

          <h2>📅 Popular Booking Days</h2>

          <?php
          $days = [];

          while ($row = mysqli_fetch_assoc($bookingsByDay)) {
            $days[] = $row;
          }

          $maxDay = !empty($days)
            ? max(array_column($days, 'total'))
            : 1;
          ?>

          <?php if (!empty($days)): ?>

            <div style="
                        display:flex;
                        flex-direction:column;
                        gap:15px;
                        margin-top:10px;
                    ">

              <?php foreach ($days as $row): ?>

                <div>

                  <div style="
                                    display:flex;
                                    justify-content:space-between;
                                    margin-bottom:6px;
                                ">

                    <span style="font-size:14px;">
                      <?= $row['day_name'] ?>
                    </span>

                    <span style="
                                        font-size:14px;
                                        font-weight:600;
                                    ">
                      <?= $row['total'] ?> bookings
                    </span>

                  </div>

                  <div style="
                                    background:#f0f0f0;
                                    border-radius:10px;
                                    height:10px;
                                ">

                    <div style="
                                        background:#111;
                                        height:10px;
                                        border-radius:10px;
                                        width:<?= $maxDay > 0
                                                ? round(($row['total'] / $maxDay) * 100)
                                                : 0 ?>%;
                                        transition:width .3s;
                                    "></div>

                  </div>

                </div>

              <?php endforeach; ?>

            </div>

          <?php else: ?>

            <p style="color:#666;padding:20px 0;">
              No booking data yet.
            </p>

          <?php endif; ?>

        </div>

      </div>

    </main>

  </div>

</body>

</html>