<?php
session_start();
global $conn;
require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

$currentPage = 'dashboard';

$playerid = $_SESSION['userid'];

$sql = "
SELECT COUNT(*) AS total
FROM booking
WHERE playerid='$playerid'
";
$result = mysqli_query($conn, $sql);
$total = mysqli_fetch_assoc($result);


$sql = "
SELECT COUNT(*) AS confirmed
FROM booking
WHERE playerid='$playerid'
AND status='confirmed'
";
$result = mysqli_query($conn, $sql);
$confirmed = mysqli_fetch_assoc($result);


$sql = "
SELECT COUNT(*) AS pending
FROM booking
WHERE playerid='$playerid'
AND status='pending'
";
$result = mysqli_query($conn, $sql);
$pending = mysqli_fetch_assoc($result);


$sql = "
SELECT COUNT(*) AS completed
FROM booking
WHERE playerid='$playerid'
AND status='completed'
";
$result = mysqli_query($conn, $sql);
$completed = mysqli_fetch_assoc($result);



$sql = "
SELECT
    b.bookingid,
    b.booking_date,
    b.start_time,
    b.end_time,
    b.status,

    f.futsalid,
    f.name,
    f.location,
    f.image,
    f.price_per_hour

FROM booking b

JOIN futsal f
ON b.futsalid = f.futsalid

WHERE
    b.playerid='$playerid'
    AND b.status='confirmed'
    AND CONCAT(b.booking_date,' ',b.start_time) >= NOW()

ORDER BY
    b.booking_date ASC,
    b.start_time ASC

LIMIT 1
";

$result = mysqli_query($conn, $sql);
$nextBooking = mysqli_fetch_assoc($result);

$sql = "
SELECT

    b.booking_date,
    b.start_time,
    b.status,

    f.name

FROM booking b

JOIN futsal f
ON b.futsalid=f.futsalid

WHERE b.playerid='$playerid'

ORDER BY b.created_at DESC

LIMIT 5
";

$recentResult = mysqli_query($conn, $sql);

$sql = "
SELECT

    f.futsalid,
    f.name,
    f.location,
    f.image,
    f.price_per_hour

FROM futsal f

WHERE
    f.status='approved'

    AND f.futsalid NOT IN
    (
        SELECT futsalid
        FROM booking
        WHERE playerid='$playerid'
    )

ORDER BY RAND()

LIMIT 3
";

$recommendedResult = mysqli_query($conn, $sql);


if (mysqli_num_rows($recommendedResult) == 0) {

  $sql = "

    SELECT

        futsalid,
        name,
        location,
        image,
        price_per_hour

    FROM futsal

    WHERE status='approved'

    ORDER BY RAND()

    LIMIT 3

    ";

  $recommendedResult = mysqli_query($conn, $sql);
}

$sql = "SELECT * FROM futsal WHERE status='approved'";
$browse = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../assets/logo/main-logo.png" type="image/x-icon">
  <title>FutZo</title>
  <link rel="stylesheet" href="../assets/css/customer.css">
  <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
    rel="stylesheet" />
</head>

<body>
  <nav class="nav-bar">

    <div class="left-section">
      <img src="../assets/logo/futzo-logo.png" alt="FutZo Logo">
      <span>FutZo</span>
    </div>

    <div class="center-section">
      <a href="#dashboard" class="nav-link active">Dashboard</a>
      <a href="#browse" class="nav-link">Browse</a>
      <a href="#bookings" class="nav-link">My Bookings</a>
      <a href="#support" class="nav-link">Support</a>
      <a href="#profile" class="nav-link">Profile</a>
    </div>

    <div class="right-section">

      <div class="avatar">
        <?= strtoupper(substr($_SESSION['name'], 0, 1)); ?>
      </div>

      <div>
        <strong>
          <?= strtoupper($_SESSION['name']); ?>
        </strong>
        <small>Customer</small>
      </div>

    </div>

  </nav>

  <main class="main">

    <section class="dashboard-header">

      <div class="header-left">

        <h1>
          Welcome back,
          <?= strtoupper(htmlspecialchars($_SESSION['name'])); ?>!
        </h1>

        <p>
          <?= date("l, d F Y"); ?>
        </p>

      </div>

      <div class="header-right">

        <a href="browse.php" class="booking-btn">
          + New Booking
        </a>

      </div>

    </section>

    <section class="dashboard-overview">

      <!-- Left -->
      <div class="upcoming-card">

        <div class="section-header">
          <h2>Upcoming Booking</h2>

          <a href="my_bookings.php">
            View All
          </a>
        </div>

        <?php if ($nextBooking) { ?>

          <div class="booking">

            <img src="../assets/uploads/<?= htmlspecialchars($nextBooking['image']); ?>" alt="">

            <div class="booking-info">

              <h3><?= htmlspecialchars($nextBooking['name']); ?></h3>

              <p>
                <i class="ri-map-pin-line"></i>
                <?= htmlspecialchars($nextBooking['location']); ?>
              </p>

              <div class="booking-meta">

                <div>
                  <small>Date</small>
                  <span><?= date("d M Y", strtotime($nextBooking['booking_date'])); ?></span>
                </div>

                <div>
                  <small>Time</small>
                  <span>
                    <?= date("g:i A", strtotime($nextBooking['start_time'])); ?>
                    -
                    <?= date("g:i A", strtotime($nextBooking['end_time'])); ?>
                  </span>
                </div>

              </div>

              <a href="my_bookings.php" class="primary-btn">
                View Booking
              </a>

            </div>

          </div>

        <?php } else { ?>

          <div class="empty-booking">

            <i class="ri-calendar-close-line"></i>

            <h3>No Upcoming Booking</h3>

            <p>You don't have any confirmed bookings.</p>

            <a href="browse.php" class="primary-btn">
              Book Now
            </a>

          </div>

        <?php } ?>

      </div>



      <!-- Right -->

      <div class="overview-card">

        <h2>Booking Overview</h2>

        <div class="overview-item">

          <div>
            <i class="ri-calendar-line"></i>
            Total Bookings
          </div>

          <span><?= $total['total']; ?></span>

        </div>

        <div class="overview-item">

          <div>
            <i class="ri-checkbox-circle-line"></i>
            Confirmed
          </div>

          <span><?= $confirmed['confirmed']; ?></span>

        </div>

        <div class="overview-item">

          <div>
            <i class="ri-time-line"></i>
            Pending
          </div>

          <span><?= $pending['pending']; ?></span>

        </div>

        <div class="overview-item">

          <div>
            <i class="ri-trophy-line"></i>
            Completed
          </div>

          <span><?= $completed['completed']; ?></span>

        </div>

      </div>

    </section>

    <section class="recommended">

      <div class="section-title">

        <h2>Recommended Futsals</h2>

        <a href="browse.php">
          View All
        </a>

      </div>

      <div class="recommend-grid">

        <?php if (mysqli_num_rows($recommendedResult) > 0) { ?>

          <?php while ($futsal = mysqli_fetch_assoc($recommendedResult)) { ?>

            <div class="recommend-card">

              <img
                src="../assets/uploads/<?= htmlspecialchars($futsal['image']); ?>"
                alt="<?= htmlspecialchars($futsal['name']); ?>">

              <div class="recommend-info">

                <h3>
                  <?= htmlspecialchars($futsal['name']); ?>
                </h3>

                <p>
                  <i class="ri-map-pin-line"></i>
                  <?= htmlspecialchars($futsal['location']); ?>
                </p>

                <div class="recommend-footer">

                  <span>
                    Rs.
                    <?= number_format($futsal['price_per_hour']); ?>
                    / hour
                  </span>

                  <a href="booking.php?futsalid=<?= $futsal['futsalid']; ?>">
                    Book
                  </a>

                </div>

              </div>

            </div>

          <?php } ?>

        <?php } else { ?>

          <div class="empty-recommend">

            <i class="ri-football-line"></i>

            <h3>No futsals available</h3>

            <p>
              There are currently no approved futsals.
            </p>

          </div>

        <?php } ?>

      </div>

    </section>

    <section class="browse-section">

      <div class="section-header">

        <div>
          <h2>Browse Futsals</h2>
          <p>Find your next match venue.</p>
        </div>

        <a href="browse.php" class="view-all">
          View All
          <i class="ri-arrow-right-line"></i>
        </a>

      </div>


      <div class="browse-grid">

        <?php
        if (mysqli_num_rows($browse) > 0) {
          while ($futsal = mysqli_fetch_assoc($browse)) {
        ?>
            <div class="futsal-card">

              <div class="card-image">

                <img src="../assets/uploads/<?php echo htmlspecialchars($futsal['image']); ?>" alt="">

                <span class="price">
                  Rs. <?= $futsal['price_per_hour'] ?> /hr
                </span>

              </div>

              <div class="card-content">

                <h3><?= $futsal['name'] ?></h3>

                <p class="location">
                  <i class="ri-map-pin-2-fill"></i>
                  <?= $futsal['address'] ?>, <?= $futsal['location'] ?>
                </p>

                <div class="card-info">

                  <span>
                    <i class="ri-time-line"></i>

                    <?= date("g:i A", strtotime($futsal['opening_time'])); ?>
                    -
                    <?= date("g:i A", strtotime($futsal['closing_time'])); ?>

                  </span>

                </div>

                <div class="card-buttons">

                  <a href="#" class="details-btn">
                    View Details
                  </a>

                  <a href="#" class="book-btn">
                    Book Now
                  </a>

                </div>

              </div>

            </div>
        <?php
          }
        }
        ?>

      </div>

    </section>



  </main>

</body>

</html>