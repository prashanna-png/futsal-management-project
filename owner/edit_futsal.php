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
                <input type="checkbox" name="facility[]" value="Parking" checked>
                Parking
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="Locker Room" checked>
                Locker Room
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="WiFi" checked>
                WiFi
              </label>
              <label>
                <input type="checkbox" name="facility[]" value="Cafeteria" checked>
                Cafeteria
              </label>
              <label>
                <input type="checkbox" name="facility[]" value="Shower">
                Shower
              </label>
            </div>
          </div>
          <div class="status-note approved">
            <strong>Current Status:</strong> Approved
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