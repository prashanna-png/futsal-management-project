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


$slotSql = "SELECT * FROM timeslot WHERE futsalid='$futsalid' ORDER BY start_time";
$slotResult = mysqli_query($conn, $slotSql);


$facilitySql = "SELECT * FROM facility WHERE futsalid='$futsalid'";
$facilityResult = mysqli_query($conn, $facilitySql);

$userid = $_SESSION['userid'];
$contactSql = "SELECT * FROM users WHERE userid='$userid'";
$contact = mysqli_query($conn, $contactSql);
$conctatRow = mysqli_fetch_assoc($contact);

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Dashboard</title>

  <link rel="stylesheet" href="../assets/css/customer.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">
      <div class="futsal-details">

        <div class="top-section">

          <div class="image-box">
            <img src="../assets//uploads/<?php echo $row['image']; ?>" alt="">
          </div>

          <div class="booking-card">

            <h2>Rs. <?php echo number_format($row['price_per_hour']); ?></h2>
            <p>Per Hour</p>

            <div class="booking-info">
              <span>🕒
                <?php echo date("g:i A", strtotime($row['opening_time'])); ?>
                -
                <?php echo date("g:i A", strtotime($row['closing_time'])); ?>
              </span>

              <span class="status <?php echo $row['status']; ?>">
                <?php echo ucfirst($row['status']); ?>
              </span>
            </div>

            <button class="book-btn">
              Book Now
            </button>

          </div>

        </div>


        <div class="content-card">

          <h1><?php echo $row['name']; ?></h1>

          <p class="location">
            📍 <?php echo $row['location']; ?>
          </p>

          <h3>Description</h3>

          <p class="description">
            <?php echo nl2br(htmlspecialchars($row['description'])); ?>
          </p>

        </div>


        <div class="content-card">

          <h3>Facilities</h3>

          <div class="facility-list">

            <?php
            while ($facility = mysqli_fetch_assoc($facilityResult)) {
            ?>

              <span class="facility">
                ✓ <?php echo $facility['facility_name']; ?>
              </span>

            <?php } ?>

          </div>

        </div>


        <div class="content-card">

          <h3>Available Time Slots</h3>

          <div class="slot-grid">

            <?php

            while ($slot = mysqli_fetch_assoc($slotResult)) {
            ?>

              <div class="slot">

                <?php echo date("g:i A", strtotime($slot['start_time'])); ?>

                -

                <?php echo date("g:i A", strtotime($slot['end_time'])); ?>

              </div>

            <?php } ?>

          </div>

        </div>

        <div class="content-card">
          <h3>Contacts:</h3>
          <p><?= $row['contact_number'] ?></p>
          <p><?= $row['address'] ?> ,<?= $row['location']  ?></p>
          <p><?= $conctatRow['email'] ?></p>

        </div>

      </div>

    </main>

  </div>

</body>

</html>