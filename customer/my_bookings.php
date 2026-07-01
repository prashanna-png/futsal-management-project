<?php
require_once '../config/auth.php';
require_login();
$currentPage = 'bookings';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Booking</title>

  <link rel="stylesheet" href="../assets/css/customer.css">

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
          <h1>My Bookings</h1>
          <p>View all your booked matches.</p>
        </div>
      </div>

      <div class="table">

        <table>

          <tr>
            <th>Futsal</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
          </tr>

          <tr>
            <td>Goal Arena</td>
            <td>25 June 2026</td>
            <td>6 PM - 7 PM</td>
            <td><span class="status">Confirmed</span></td>
            <td>
              <button>View</button>
              <button>Cancel</button>
            </td>
          </tr>

          <tr>
            <td>Elite Arena</td>
            <td>28 June 2026</td>
            <td>8 PM - 9 PM</td>
            <td><span class="status">Pending</span></td>
            <td>
              <button>View</button>
            </td>
          </tr>

        </table>

      </div>
  </div>
  </main>

  </div>

</body>

</html>