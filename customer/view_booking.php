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
    b.amount,
    b.status,
    b.created_at,

    f.futsalid,
    f.name,
    f.location,
    f.address,
    f.image,
    f.description,
    f.price_per_hour

FROM booking AS b
JOIN futsal AS f
ON b.futsalid = f.futsalid

WHERE b.bookingid = '$bookingid'
AND b.playerid = '{$_SESSION['userid']}'
";
$result = mysqli_query($conn, $sql);
$booking = mysqli_fetch_assoc($result);

$sql = "
SELECT facility_name
FROM facility
WHERE futsalid = '{$booking['futsalid']}'
";

$facilityResult = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Detail</title>

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

    <main class="main view-booking-page">

      <div class="header">

        <div>
          <h1>Booking Details</h1>
          <p>View complete information about your booking.</p>
        </div>

        <a href="my_bookings.php" class="back-btn">
          ← Back to My Bookings
        </a>

      </div>


      <div class="view-booking-container">

        <!-- Left Side -->

        <div class="view-booking-left">

          <img
            src="../assets/uploads/<?php echo $booking['image']; ?>"
            class="view-booking-image"
            alt="Futsal Image">

          <div class="view-booking-card">

            <h2><?php echo htmlspecialchars($booking['name']); ?></h2>

            <p class="view-booking-location">
              📍
              <?php echo htmlspecialchars($booking['address']); ?>,
              <?php echo htmlspecialchars($booking['location']); ?>
            </p>

            <span class="view-booking-status <?php echo strtolower($booking['status']); ?>">
              <?php echo ucfirst($booking['status']); ?>
            </span>

            <p class="view-booking-description">
              <?php echo htmlspecialchars($booking['description']); ?>
            </p>

          </div>

        </div>


        <!-- Right Side -->

        <div class="view-booking-right">

          <!-- Booking Information -->

          <div class="view-booking-info-card">

            <h3>Booking Information</h3>

            <div class="view-booking-row">
              <span>Booking ID</span>
              <strong>#<?php echo $booking['bookingid']; ?></strong>
            </div>

            <div class="view-booking-row">
              <span>Booking Date</span>
              <strong>
                <?php echo date("d M Y", strtotime($booking['booking_date'])); ?>
              </strong>
            </div>

            <div class="view-booking-row">
              <span>Time Slot</span>
              <strong>
                <?php echo date("g:i A", strtotime($booking['start_time'])); ?>
                -
                <?php echo date("g:i A", strtotime($booking['end_time'])); ?>
              </strong>
            </div>

            <div class="view-booking-row">
              <span>Duration</span>
              <strong>1 Hour</strong>
            </div>

            <div class="view-booking-row">
              <span>Booked On</span>
              <strong>
                <?php echo date("d M Y", strtotime($booking['created_at'])); ?>
              </strong>
            </div>

            <div class="view-booking-row">
              <span>Price</span>
              <strong>
                Rs. <?php echo number_format($booking['amount']); ?>
              </strong>
            </div>

            <div class="view-booking-row">
              <span>Payment Status</span>
              <strong class="payment-pending">
                Pending
              </strong>
            </div>

          </div>


          <!-- Facilities -->

          <div class="view-booking-info-card">

            <h3>Facilities</h3>

            <div class="view-facility-list">

              <?php while ($facility = mysqli_fetch_assoc($facilityResult)) { ?>

                <span class="view-facility">
                  <?php echo htmlspecialchars($facility['facility_name']); ?>
                </span>

              <?php } ?>

            </div>

          </div>


          <!-- Action -->

          <div class="view-booking-action">

            <?php
            if (
              $booking['status'] == 'pending' ||
              $booking['status'] == 'confirmed'
            ) {
            ?>

              <button class="view-booking-cancel-btn">
                🗑 Cancel Booking
              </button>

            <?php } ?>

          </div>

        </div>

      </div>

    </main>

  </div>

</body>

</html>