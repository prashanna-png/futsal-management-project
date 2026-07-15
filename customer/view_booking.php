<?php
global $conn;

require_once '../config/auth.php';
require_once '../config/db.php';
require_login();
$currentPage = 'bookings';

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$bookingid = $_GET['bookingid'];
$playerid = $_SESSION['userid'];

$sql = "
  SELECT 
    b.bookingid,
    b.booking_date,
    b.start_time,
    b.end_time,
    
";



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
  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">

      <div class="header">
        <div>
          <h1>Booking Details</h1>
          <p>View complete information about your booking.</p>
        </div>

        <a href="my_bookings.php" class="back-btn">
          ← Back to My Bookings
        </a>
      </div>

      <div class="booking-detail-container">

        <!-- Left Side -->

        <div class="booking-left">

          <img src="../assets/uploads/<?php echo $booking['image']; ?>"
            class="booking-image"
            alt="Futsal">

          <div class="booking-card">

            <h2><?php echo $booking['name']; ?></h2>

            <p class="location">
              📍 <?php echo $booking['address']; ?>,
              <?php echo $booking['location']; ?>
            </p>

            <span class="status <?php echo strtolower($details['status']); ?>">
              <?php echo ucfirst($details['status']); ?>
            </span>

          </div>

        </div>


        <!-- Right Side -->

        <div class="booking-right">

          <div class="detail-card">

            <h3>Booking Information</h3>

            <div class="detail-row">
              <span>Booking ID</span>
              <strong>#<?php echo $details['bookingid']; ?></strong>
            </div>

            <div class="detail-row">
              <span>Booking Date</span>
              <strong>
                <?php echo date("d M Y", strtotime($details['booking_date'])); ?>
              </strong>
            </div>

            <div class="detail-row">
              <span>Time Slot</span>
              <strong>
                <?php echo date("g:i A", strtotime($details['start_time'])); ?>
                -
                <?php echo date("g:i A", strtotime($details['end_time'])); ?>
              </strong>
            </div>

            <div class="detail-row">
              <span>Duration</span>
              <strong>1 Hour</strong>
            </div>

            <div class="detail-row">
              <span>Booked On</span>
              <strong>
                <?php echo date("d M Y", strtotime($details['created_at'])); ?>
              </strong>
            </div>

            <div class="detail-row">
              <span>Amount</span>
              <strong>Rs. <?php echo number_format($details['amount']); ?></strong>
            </div>

            <div class="detail-row">
              <span>Payment</span>
              <strong>Pending</strong>
            </div>

          </div>


          <div class="detail-card">

            <h3>Facilities</h3>

            <div class="facility-list">

              <?php while ($facility = mysqli_fetch_assoc($facilityResult)) { ?>

                <span class="facility">
                  <?php echo $facility['facility_name']; ?>
                </span>

              <?php } ?>

            </div>

          </div>


          <div class="action-area">

            <?php
            if (
              $booking['status'] == 'pending' ||
              $booking['status'] == 'confirmed'
            ) {
            ?>

              <button class="cancel-btn">
                Cancel Booking
              </button>

            <?php } ?>

          </div>

        </div>

      </div>

    </main>

  </div>

</body>

</html>