<?php
require_once '../config/db.php';
require_once '../config/auth.php';
require_login();
global $conn;

if ($_SESSION['role'] !== 'owner') {
  header("Location: ../login.php");
  exit();
}

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$currentPage = 'addFutsal';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name           = trim($_POST['name']);
  $location       = trim($_POST['location']);
  $address        = trim($_POST['address']);
  $description    = trim($_POST['description']);
  $price_per_hour = trim($_POST['price_per_hour']);
  $contact_number = trim($_POST['contact_number']);
  $opening_time   = $_POST['opening_time'];
  $closing_time   = $_POST['closing_time'];
  $facilities     = $_POST['facility'] ?? [];
  $ownerid        = $_SESSION['userid'];

  // Validation
  if (empty($name) || empty($location) || empty($address) || empty($description) || empty($price_per_hour) || empty($contact_number) || empty($opening_time) || empty($closing_time)) {
    $_SESSION['error'] = 'All fields are required.';
  } elseif (!preg_match("/^[A-Za-z0-9\s]+$/", $name)) {
    $_SESSION['error'] = 'Futsal name contains invalid characters.';
  } elseif (!is_numeric($price_per_hour) || $price_per_hour <= 0) {
    $_SESSION['error'] = 'Enter a valid price per hour.';
  } elseif ($opening_time >= $closing_time) {
    $_SESSION['error'] = 'Closing time must be after opening time.';
  } elseif (!preg_match("/^[0-9]{10}$/", $contact_number)) {
    $_SESSION['error'] = 'Please enter a valid 10-digit phone number.';
  } elseif ($_FILES['image']['error'] !== 0) {
    $_SESSION['error'] = 'Please upload a futsal image.';
  } else {
    // Check duplicate
    $checkSql = "SELECT futsalid FROM futsal WHERE name='$name' AND location='$location'";
    $result   = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($result) > 0) {
      $_SESSION['error'] = 'A futsal with this name already exists in this location.';
    } else {
      // Handle image upload
      $allowed   = ['jpg', 'jpeg', 'png', 'webp'];
      $ext       = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

      if (!in_array($ext, $allowed)) {
        $_SESSION['error'] = 'Only JPG, PNG and WEBP images are allowed.';
      } else {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $target    = "../assets/uploads/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        // Insert fselect * from futsal;utsal into database
        $sql = "INSERT INTO futsal 
                        (ownerid, name, location, address, description, price_per_hour, opening_time, closing_time, contact_number, image)
                        VALUES
                        ('$ownerid', '$name', '$location', '$address', '$description', '$price_per_hour', '$opening_time', '$closing_time', '$contact_number', '$imageName')";

        if (mysqli_query($conn, $sql)) {
          // Get the new futsal id
          $futsalid = mysqli_insert_id($conn);

          // Save facilities if any selected
          if (!empty($facilities)) {
            foreach ($facilities as $facility) {
              $facSql = "INSERT INTO facility (futsalid, facility_name) 
                                       VALUES ('$futsalid', '$facility')";
              mysqli_query($conn, $facSql);
            }
          }

          $_SESSION['success'] = 'Futsal registered successfully! Waiting for admin approval.';
        } else {
          $_SESSION['error'] = 'Failed to register futsal. Try again.';
        }
      }
    }
  }

  header("Location: register_futsal.php");
  exit();
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
        <div class="error-message" id="error-success-msg">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="success-message" id="error-success-msg">
          <?php echo $success; ?>
        </div>
      <?php endif; ?>

      <div class="form-container">

        <form action="" method="POST" enctype="multipart/form-data" class="register-form" novalidate>

          <div class="left-column">
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
          </div>
          <div class="right-column">
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
            <div class="info-card">

              <h3>Registration Information</h3>

              <ul class="info-list">

                <li>✔ Your futsal will be reviewed by the administrator.</li>

                <li>✔ Review usually takes 24–48 hours.</li>

                <li>✔ You cannot receive bookings until approved.</li>

                <li>✔ You can edit your futsal before approval.</li>

              </ul>

            </div>
          </div>

          <button type="submit" class="register-btn">

            Register Futsal

          </button>

        </form>

      </div>

    </main>

  </div>

</body>
<script src="../assets/js/main.js"></script>

</html>