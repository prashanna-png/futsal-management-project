<?php
global $conn;
session_start();
require_once '../config/auth.php';
require_once '../config/db.php';
require_login();
$currentPage = 'bookings';

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);

$playerid = $_SESSION['userid'];

$sql = "
SELECT
    b.bookingid,
    b.booking_date,
    b.start_time,
    b.end_time,
    b.amount,
    b.status,
    f.name,
    f.location,
    f.image

FROM booking AS b
JOIN futsal AS f
ON b.futsalid = f.futsalid

WHERE b.playerid = '$playerid'

ORDER BY
    CASE
        WHEN b.status = 'pending' THEN 1
        WHEN b.status = 'confirmed' THEN 2
        WHEN b.status = 'completed' THEN 3
        WHEN b.status = 'cancelled' THEN 4
    END,
    b.booking_date ASC
";
$result = mysqli_query($conn, $sql);

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
      <?php if (!empty($error)): ?>
        <div class="error-message" id="error-success-msg">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="success-message" id="error-success-msg">
          <?php echo $success; ?>
        </div>
      <?php endif; ?>
      <div class="header">
        <div>
          <h1>My Bookings</h1>
          <p>View all your booked matches.</p>
        </div>
      </div>

      <div class="table">

        <table>

          <tr>
            <th>Image</th>
            <th>Futsal Name</th>
            <th>Booking Date</th>
            <th>Time Slot</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
          <?php
          while ($row = mysqli_fetch_assoc($result)) {
          ?>
            <tr>
              <td>
                <img src="../assets/uploads/<?= $row['image'] ?>" alt="" class="booking-image">
              </td>

              <td><?= $row['name'] ?></td>

              <td><?= date("d M Y", strtotime($row['booking_date'])) ?></td>
              <td>
                <?= date("g:i A", strtotime($row['start_time'])) ?>
                -
                <?= date("g:i A", strtotime($row['end_time'])) ?>
              </td>

              <td>
                <span class="status <?= strtolower($row['status']) ?>">
                  <?= ucfirst($row['status']) ?>
                </span>
              </td>

              <td>
                <div class="action-buttons">

                  <button class="view-btn" onclick="location.href='view_booking.php?bookingid=<?= $row['bookingid'] ?>'">
                    <img src="../assets/icons/view.png" class="btn-icon" alt="">
                    View
                  </button>

                  <?php if ($row['status'] === 'pending' || $row['status'] === 'confirmed'): ?>
                    <button class="cancel-btn" onclick="location.href='cancel_booking.php?bookingid=<?= $row['bookingid']; ?>'">
                      <img src="../assets/icons/delete.png" class="btn-icon" alt="">
                      Cancel
                    </button>
                  <?php endif; ?>

                </div>
              </td>
            </tr>
          <?php
          }
          ?>

        </table>

      </div>
  </div>
  </main>

  </div>

</body>

</html>