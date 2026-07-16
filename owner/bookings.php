<?php
session_start();
global $conn;
require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

$currentPage = 'manageBooking';



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
              <th>Futsal</th>
              <th>Date</th>
              <th>Time</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Action</th>
            </tr>

          </thead>

          <tbody>

            <tr>
              <td>Ram Sharma</td>
              <td>Goal Arena</td>
              <td>12 July 2026</td>
              <td>6 PM - 7 PM</td>
              <td>Rs.1500</td>
              <td><span class="status pending">Pending</span></td>
              <td>
                <button class="btn-small confirm">Confirm</button>
                <button class="btn-small cancel">Reject</button>
              </td>
            </tr>

            <tr>
              <td>Sita Rai</td>
              <td>Elite Arena</td>
              <td>13 July 2026</td>
              <td>8 PM - 9 PM</td>
              <td>Rs.1800</td>
              <td><span class="status confirmed">Confirmed</span></td>
              <td>
                <button class="btn-small complete">Complete</button>
              </td>
            </tr>

            <tr>
              <td>Hari KC</td>
              <td>Goal Arena</td>
              <td>14 July 2026</td>
              <td>5 PM - 6 PM</td>
              <td>Rs.1500</td>
              <td><span class="status completed">Completed</span></td>
              <td>-</td>
            </tr>

            <tr>
              <td>Prabin Thapa</td>
              <td>Futsal City</td>
              <td>15 July 2026</td>
              <td>7 PM - 8 PM</td>
              <td>Rs.1700</td>
              <td><span class="status cancelled">Cancelled</span></td>
              <td>-</td>
            </tr>

          </tbody>

        </table>

      </div>

    </main>

  </div>

</body>

</html>