<?php
session_start();
global $conn;
require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

$currentPage = 'manageBooking';

$ownerid = $_SESSION['userid'];

$sql = "
  SELECT 
    b.bookingid,
    b.booking_date,
    b.start_time,
    b.end_time,
    b.amount,
     
    u.name AS customer_name,
    u.phone,

    f.name AS futsal_name

    FROM booking b 
    JOIN users u 
    ON b.playerid = u.userid

    JOIN futsal f
    ON b.futsalid = f.futsalid

    WHERE f.ownerid = '$ownerid'

    ORDER BY b.booking_date ASC,
             b.start_time ASC;
";

$result = mysqli_query($conn, $sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Bookings</title>

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
          <h1>Manage Bookings</h1>
          <p>View and manage customer booking requests.</p>
        </div>

      </div>

      <div class="table-container">

        <div class="table-header">

          <h3>Booking Requests</h3>

          <select>
            <option>All Bookings</option>
            <option>Pending</option>
            <option>Confirmed</option>
            <option>Completed</option>
            <option>Cancelled</option>
          </select>

        </div>

        <table>

          <thead>

            <tr>
              <th>Customer</th>
              <th>Phone no.</th>
              <th>Futsal</th>
              <th>Date</th>
              <th>Time</th>
              <th>Status</th>
              <th>Action</th>
            </tr>

          </thead>

          <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                  <td>
                    <?= $row['customer_name'] ?>
                  </td>

                  <td>
                    <?= $row['phone'] ?>
                  </td>

                  <td>
                    <?= $row['futsal_name'] ?>
                  </td>

                  <td>
                    <?= date("d M Y", strtotime($row['booking_date'])) ?>
                  </td>

                  <td>
                    <?= date("g:i A", strtotime($row['start_time'])); ?>
                    -
                    <?= date("g:i A", strtotime($row['end_time'])); ?>
                  </td>

                  <td><span class="status pending">Pending</span></td>
                  <td>
                    <button class="btn-small confirm">Confirm</button>
                    <button class="btn-small cancel">Reject</button>
                  </td>
                </tr>
            <?php
              }
            }
            ?>

          </tbody>

        </table>

      </div>

    </main>

  </div>

</body>

</html>