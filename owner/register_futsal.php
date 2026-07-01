<?php
session_start();

require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

$currentPage = 'addFutsal';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $name = trim($_POST['name']);
  $location = trim($_POST['location']);
  $address = trim($_POST['address']);
  $description = trim($_POST['description']);

  $price_per_hour = trim($_POST['price_per_hour']);
  $contact_number = trim($_POST['contact_number']);

  $opening_time = $_POST['opening_time'];
  $closing_time = $_POST['closing_time'];

  $image = $_FILES['image'];

  $facilities = $_POST['facility'] ?? [];

  if ($name === '' || $location === '' || $address === "" || $description === "" || $price_per_hour === "" || $contact_number === '' || $opening_time === '' || $closing_time === '') {
    $_SESSION['error'] = 'All fields are required';
  } elseif (!preg_match("/^[A-Za-z0-9\s]+$/", $name)) {
    $_SESSION['error'] = "Futsal name contains invalid characters.";
  } elseif (!is_numeric($price_per_hour) || $price_per_hour <= 0) {
    $_SESSION['error'] = "Enter a valid price per hour.";
  } elseif ($opening_time >= $closing_time) {
    $_SESSION['error'] = "Closing time must be after opening time.";
  } elseif ($image['error'] != 0) {
    $error = "Please upload a futsal image.";
  } elseif (!is_numeric($phone) || strlen($phone) != 10) {
    $_SESSION['error'] = "Please enter a valid 10-digit phone number";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Futsal</title>

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
          <h1>Register New Futsal</h1>
          <p>Fill in the details below to register your futsal.</p>
        </div>

      </div>
      <?php if (!empty($error)): ?>
        <div class="error-message">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <div class="form-container">

        <form action="" method="POST" enctype="multipart/form-data">

          <div class="form-group">

            <label>Futsal Name</label>
            <input type="text" name="name" placeholder="Enter futsal name" required>

          </div>

          <div class="form-group">

            <label>Location</label>
            <input type="text" name="location" placeholder="Enter city/location" required>

          </div>

          <div class="form-group">

            <label>Address</label>
            <textarea name="address" placeholder="Enter complete address"></textarea>

          </div>

          <div class="form-group">

            <label>Description</label>

            <textarea name="description" placeholder="Describe your futsal"></textarea>

          </div>

          <div class="row">

            <div class="form-group">

              <label>Price Per Hour (Rs.)</label>

              <input type="number" name="price_per_hour" placeholder="1500" required>

            </div>

            <div class="form-group">

              <label>Contact Number</label>

              <input type="text" name="contact_number" placeholder="98XXXXXXXX" required>

            </div>

          </div>

          <div class="row">

            <div class="form-group">

              <label>Opening Time</label>

              <input type="time" name="opening_time" required>

            </div>

            <div class="form-group">

              <label>Closing Time</label>

              <input type="time" name="closing_time" required>

            </div>

          </div>

          <div class="form-group">

            <label>Upload Image</label>

            <input type="file" name="image" accept="image/*">

          </div>

          <div class="form-group">

            <label>Facilities</label>

            <div class="facility-grid">

              <label>
                <input type="checkbox" name="facility[]" value="Parking">
                Parking
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="WiFi">
                WiFi
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="Shower">
                Shower
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="Locker Room">
                Locker Room
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="Cafeteria">
                Cafeteria
              </label>

            </div>

          </div>

          <button type="submit" class="btn">

            Register Futsal

          </button>

        </form>

      </div>

    </main>

  </div>

</body>

</html>