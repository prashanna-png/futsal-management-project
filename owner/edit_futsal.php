<?php
session_start();
global $conn;
include '../config/auth.php';
include '../config/db.php';

require_login();

if ($_SESSION['role'] !== 'owner') {
  header('Location: ../login.php');
  exit;
}
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$ownerid = $_SESSION['userid'];
$futsalid = $_GET['futsalid'];

$sql = "SELECT * FROM futsal WHERE ownerid='$ownerid' AND futsalid='$futsalid'";
$result = mysqli_query($conn, $sql);
$futsal = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM facility WHERE futsalid='$futsalid'";
$result = mysqli_query($conn, $sql);
$facilities = [];

while ($row = mysqli_fetch_assoc($result)) {
  $facilities[] = $row['facility_name'];
}

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
    $sql = "UPDATE futsal 
              SET 
                name='$name',
                location = '$location',
                address = '$address',
                description = '$description',
                price_per_hour = '$price_per_hour',
                contact_number = '$contact_number',
                opening_time ='$opening_time',
                closing_time = '$closing_time',
                
                ";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets//css//owner.css">
  <title>Edit futsal</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="dashboard">
    <?php
    include 'includes/sidebar.php';
    ?>

    <main class="main">

      <div class="header">

        <div>
          <a href="my_futsal.php" class="back-link">
            ← Back to My Futsals
          </a>

          <h1>Edit Futsal</h1>
          <p>Update your futsal details.</p>
        </div>

      </div>
      <div class="form-container">
        <form action="" method="POST" enctype="multipart/form-data">

          <div class="row">

            <div class="form-group">
              <label>Futsal Name</label>
              <input type="text" name="name" value="<?php echo $futsal['name'] ?>">
            </div>

            <div class="form-group">
              <label>Price Per Hour (Rs.)</label>
              <input type="number" name="price_per_hour" value="<?php echo $futsal['price_per_hour'] ?>">
            </div>

          </div>

          <div class="row">

            <div class="form-group">
              <label>Location</label>
              <input type="text" name="location" value="<?php echo $futsal['location'] ?>">
            </div>

            <div class="form-group">
              <label>Contact Number</label>
              <input type="text" name="contact_number" value="<?php echo $futsal['contact_number'] ?>">
            </div>

          </div>

          <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" value="<?php echo $futsal['address'] ?>">
          </div>

          <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5">
              <?php echo $futsal['description'] ?>
            </textarea>
          </div>

          <div class="row">

            <div class="form-group">
              <label>Opening Time</label>
              <input type="time" name="opening_time" value="<?php echo $futsal['opening_time'] ?>">
            </div>

            <div class="form-group">
              <label>Closing Time</label>
              <input type="time" name="closing_time" value="<?php echo $futsal['closing_time'] ?>">
            </div>

          </div>

          <div class="form-group">

            <label>Upload Image</label>

            <div class="image-upload">

              <img src="../assets/uploads/<?php echo $futsal['image'] ?>" alt="Current Image" class="preview-image" width="130"
                style="border-radius:8px;">

              <input type="file" name="image" accept="image/*">

            </div>

          </div>

          <div class="form-group">

            <label>Facilities</label>

            <div class="facility-grid">

              <label>
                <input type="checkbox" value="Parking" <?= in_array("Parking", $facilities) ? "checked" : ""; ?>>
                Parking
              </label>

              <label>
                <input type="checkbox" value="Locker Room" <?= in_array("Locker Room", $facilities) ? "checked" : "" ?>>
                Locker Room
              </label>

              <label>
                <input type="checkbox" value="WiFi" <?= in_array("WiFi", $facilities) ? "checked" : "" ?>>
                WiFi
              </label>
              <label>
                <input type="checkbox" value="Cafeteria" <?= in_array("Cafeteria", $facilities) ? "checked" : "" ?>>
                Cafeteria
              </label>
              <label>
                <input type="checkbox" value="Shower" <?= in_array("Shower", $facilities) ? "checked" : "" ?>>
                Shower
              </label>
            </div>
          </div>
          <div class="status-note <?php echo $futsal['status']  ?>">
            <strong>Current Status:</strong> <?php echo $futsal['status'] ?>
            <br>
            Editing this futsal may require administrator approval again.
          </div>
          <div class="form-actions">
            <a href="my_futsal.php" class="cancel-btn">
              Cancel
            </a>
            <button type="submit" class="btn">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</body>

</html>