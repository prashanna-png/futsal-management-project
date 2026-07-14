<?php
global $conn;

require_once '../config/auth.php';
require_once '../config/db.php';
require_login();
$currentPage = 'browse';

$futsalid = $_GET['futsalid'];

$sql = "SELECT * FROM futsal WHERE futsalid='$futsalid'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM timeslot WHERE futsalid='$futsalid'";
$slotresult = mysqli_query($conn, $sql);

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

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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


      <!-- Futsal Information -->

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


      <!-- Booking Form -->

      <form method="POST">

        <div class="booking-grid">


          <!-- Left Side -->

          <div class="booking-left">

            <div class="card">

              <h3>Select Date</h3>

              <input
                type="date"
                name="booking_date"
                required>

            </div>


            <div class="card">

              <h3>Available Time Slots</h3>

              <div class="slot-grid">
                <?php while ($slot = mysqli_fetch_array($slotresult)) {
                ?>
                  <label class="slot">

                    <input
                      type="radio"
                      name="slotid">

                    <?= substr($slot['start_time'], 0, 5) ?> AM - <?= substr($slot['end_time'], 0, 5) ?> AM

                  </label>
                <?php } ?>

              </div>

            </div>

          </div>


          <!-- Right Side -->

          <div class="booking-right">

            <div class="summary-card">

              <h3>Booking Summary</h3>

              <div class="summary-row">

                <span>Futsal</span>

                <strong>Yala Futsal</strong>

              </div>

              <div class="summary-row">

                <span>Date</span>

                <strong>Not Selected</strong>

              </div>

              <div class="summary-row">

                <span>Time Slot</span>

                <strong>Not Selected</strong>

              </div>

              <div class="summary-row">

                <span>Duration</span>

                <strong>1 Hour</strong>

              </div>

              <hr>

              <div class="summary-row total">

                <span>Total</span>

                <strong>Rs. 1000</strong>

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

</body>

</html>