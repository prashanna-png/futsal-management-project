<?php
session_start();
global $conn;

require_once '../config/auth.php';
require_once '../config/db.php';
require_login();
$currentPage = 'browse';

$futsalid = $_GET['futsalid'];

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$sql = "SELECT * FROM futsal WHERE futsalid='$futsalid'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM timeslot WHERE futsalid='$futsalid'";
$slotresult = mysqli_query($conn, $sql);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $booking_date = $_POST['booking_date'];
  $slotid = $_POST['slotid'];
  $playerid = $_SESSION['userid'];

  if (empty($_POST['booking_date'])) {
    $_SESSION['error'] = "Please select a booking date.";
    header("Location: " . $_SERVER['PHP_SELF'] . "?futsalid=" . $futsalid);
    exit;
  }

  if (empty($_POST['slotid'])) {
    $_SESSION['error'] = "Please select a time slot.";
    header("Location: " . $_SERVER['PHP_SELF'] . "?futsalid=" . $futsalid);
    exit;
  }

  if ($booking_date < date('Y-m-d')) {
    $_SESSION['error'] = 'You cannot book a past date.';
  }

  $sql = "SELECT * FROM timeslot WHERE slotid='$slotid'";
  $result = mysqli_query($conn, $sql);
  $slot = mysqli_fetch_assoc($result);

  $start_time = $slot['start_time'];
  $end_time = $slot['end_time'];

  $sql = "SELECT bookingid
          FROM booking
          WHERE futsalid='$futsalid'
          AND booking_date='$booking_date'
          AND start_time='$start_time'
          AND end_time='$end_time'
          AND status!='cancelled'";

  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {

    $_SESSION['error'] = "This slot is already booked.";
  } else {

    $sql = "INSERT INTO booking (
              playerid,
              futsalid,
              booking_date,
              start_time,
              end_time,
              amount
            )
            VALUES (
              '$playerid',
              '$futsalid',
              '$booking_date',
              '$start_time',
              '$end_time',
              '{$row['price_per_hour']}'
            )";

    if (mysqli_query($conn, $sql)) {
      $_SESSION['success'] = "Booking placed successfully.";
    } else {
      $_SESSION['error'] = "Failed to place booking.";
    }
  }

  header("Location: " . $_SERVER['PHP_SELF'] . "?futsalid=" . $futsalid);
  exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Futsal</title>

  <link rel="stylesheet" href="../assets/css/customer.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">

      <div class="header">

        <div>
          <h1>Book Futsal</h1>
          <p>Select your preferred date and available time slot.</p>
        </div>

        <a href="view_futsal.php" class="back-btn">
          ← Back
        </a>

      </div>



      <div class="booking-top">

        <img src="../assets//uploads/<?php echo $row['image']; ?>" alt="" class="booking-image">

        <div class="booking-info">

          <h2><?= $row['name'] ?></h2>

          <p class="location">
            📍 <?= $row['address'] ?>, <?= $row['location'] ?>
          </p>

          <div class="price">
            Rs. <?= $row['price_per_hour'] ?> / Hour
          </div>



        </div>

      </div>

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

      <form method="POST">

        <div class="booking-grid">

          <div class="booking-left">

            <div class="card">

              <h3>Select Date</h3>

              <input
                type="date"
                name="booking_date"
                id="booking_date"
                min="<?php echo date('Y-m-d'); ?>"
                required>

            </div>


            <div class=" card">

              <h3>Available Time Slots</h3>

              <div class="slot-grid">
                <?php while ($slot = mysqli_fetch_array($slotresult)) {
                ?>
                  <label class="slot">

                    <input
                      type="radio"
                      name="slotid"
                      value="<?= $slot['slotid']; ?>"

                      data-time="
                        <?= date('g:i A', strtotime($slot['start_time'])) ?> 
                        - 
                        <?= date('g:i A', strtotime($slot['end_time'])) ?>
                        ">

                    <?= date("g:i A", strtotime($slot['start_time'])); ?>

                    -

                    <?= date("g:i A", strtotime($slot['end_time'])); ?>

                  </label>
                <?php } ?>

              </div>

            </div>

          </div>

          <div class="booking-right">

            <div class="summary-card">

              <h3>Booking Summary</h3>

              <div class="summary-row">

                <span>Futsal</span>


                <strong><?= $row['name'] ?></strong>

              </div>

              <div class="summary-row">

                <span>Date</span>

                <strong id="summary-date">Not Selected</strong>

              </div>

              <div class="summary-row">

                <span>Time Slot</span>

                <strong id="summary-slot">Not Selected</strong>

              </div>

              <div class="summary-row">

                <span>Duration</span>

                <strong>1 Hour</strong>

              </div>

              <hr>

              <div class="summary-row total">

                <span>Total</span>

                <strong>Rs. <?= $row['price_per_hour'] ?></strong>

              </div>

              <button
                class="confirm-btn"
                type="submit">

                Confirm Booking

              </button>

            </div>

          </div>

        </div>

      </form>

    </main>

  </div>
  <script src="../assets/js/customer.js"></script>
  <script src="../assets/js/main.js"></script>
</body>

</html>