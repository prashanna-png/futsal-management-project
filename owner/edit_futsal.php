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

// Fetch futsal details
$sql = "SELECT * FROM futsal WHERE ownerid='$ownerid' AND futsalid='$futsalid'";
$result = mysqli_query($conn, $sql);
$futsal = mysqli_fetch_assoc($result);

if (!$futsal) {
  $_SESSION['error'] = 'Futsal not found or you don\'t have permission to edit it.';
  header('Location: my_futsal.php');
  exit;
}

// Fetch facilities
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
  $facility_data  = $_POST['facility'] ?? [];
  $ownerid        = $_SESSION['userid'];

  // Validation
  if (empty($name) || empty($location) || empty($address) || empty($description) || empty($price_per_hour) || empty($contact_number) || empty($opening_time) || empty($closing_time)) {
    $_SESSION['error'] = 'All fields are required.';
    header('Location: edit_futsal.php?futsalid=' . $futsalid);
    exit;
  } elseif (!preg_match("/^[A-Za-z0-9\s]+$/", $name)) {
    $_SESSION['error'] = 'Futsal name contains invalid characters.';
    header('Location: edit_futsal.php?futsalid=' . $futsalid);
    exit;
  } elseif (!is_numeric($price_per_hour) || $price_per_hour <= 0) {
    $_SESSION['error'] = 'Enter a valid price per hour.';
    header('Location: edit_futsal.php?futsalid=' . $futsalid);
    exit;
  } elseif ($opening_time >= $closing_time) {
    $_SESSION['error'] = 'Closing time must be after opening time.';
    header('Location: edit_futsal.php?futsalid=' . $futsalid);
    exit;
  } elseif (!preg_match("/^[0-9]{10}$/", $contact_number)) {
    $_SESSION['error'] = 'Please enter a valid 10-digit phone number.';
    header('Location: edit_futsal.php?futsalid=' . $futsalid);
    exit;
  } else {
    // Start with existing image
    $image_name = $futsal['image'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $upload_dir = '../assets/uploads/';

      // Check if uploads directory exists, if not create it
      if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];

      // Get file extension
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

      // Allowed extensions
      $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

      // Allowed MIME types for security
      $allowed_mime_types = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp'
      ];

      // Validate file using MIME type
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $mime_type = finfo_file($finfo, $file_tmp);
      finfo_close($finfo);

      if (in_array($file_ext, $allowed_extensions) && in_array($mime_type, $allowed_mime_types)) {
        // Check file size (5MB max)
        if ($file_size <= 5000000) {
          // Generate unique filename to prevent conflicts
          $new_file_name = uniqid('futsal_') . '.' . $file_ext;
          $destination = $upload_dir . $new_file_name;

          // Upload file
          if (move_uploaded_file($file_tmp, $destination)) {
            // Delete old image if exists and is not default
            if ($image_name && $image_name != 'default.jpg' && file_exists($upload_dir . $image_name)) {
              unlink($upload_dir . $image_name);
            }

            $image_name = $new_file_name;
          } else {
            $_SESSION['error'] = 'Error uploading file. Please check folder permissions.';
            header('Location: edit_futsal.php?futsalid=' . $futsalid);
            exit;
          }
        } else {
          $_SESSION['error'] = 'File size exceeds 5MB limit.';
          header('Location: edit_futsal.php?futsalid=' . $futsalid);
          exit;
        }
      } else {
        $_SESSION['error'] = 'Invalid file type. Only JPG, PNG, GIF, and WEBP are allowed.';
        header('Location: edit_futsal.php?futsalid=' . $futsalid);
        exit;
      }
    }
    // If no new image uploaded, keep the existing image

    // Update futsal details
    $sql = "UPDATE futsal 
            SET 
                name = '$name',
                location = '$location',
                address = '$address',
                description = '$description',
                price_per_hour = '$price_per_hour',
                contact_number = '$contact_number',
                opening_time = '$opening_time',
                closing_time = '$closing_time',
                image = '$image_name',
                status = 'pending'
            WHERE futsalid = '$futsalid' AND ownerid = '$ownerid'";

    if (mysqli_query($conn, $sql)) {
      // Update facilities
      // First, delete existing facilities
      $delete_sql = "DELETE FROM facility WHERE futsalid = '$futsalid'";
      mysqli_query($conn, $delete_sql);

      // Then insert new facilities
      if (!empty($facility_data)) {
        foreach ($facility_data as $facility_name) {
          $facility_name = mysqli_real_escape_string($conn, $facility_name);
          $insert_sql = "INSERT INTO facility (futsalid, facility_name) VALUES ('$futsalid', '$facility_name')";
          mysqli_query($conn, $insert_sql);
        }
      }

      $_SESSION['success'] = 'Futsal details updated successfully! Status set to pending for review.';
      header('Location: my_futsal.php');
      exit;
    } else {
      $_SESSION['error'] = 'Error updating record: ' . mysqli_error($conn);
      header('Location: edit_futsal.php?futsalid=' . $futsalid);
      exit;
    }
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
                <input type="checkbox" name="facility[]" value="Parking" <?= in_array("Parking", $facilities) ? "checked" : ""; ?>>
                Parking
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="Locker Room" <?= in_array("Locker Room", $facilities) ? "checked" : "" ?>>
                Locker Room
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="WiFi" <?= in_array("WiFi", $facilities) ? "checked" : "" ?>>
                WiFi
              </label>
              <label>
                <input type="checkbox" name="facility[]" value="Cafeteria" <?= in_array("Cafeteria", $facilities) ? "checked" : "" ?>>
                Cafeteria
              </label>
              <label>
                <input type="checkbox" name="facility[]" value="Shower" <?= in_array("Shower", $facilities) ? "checked" : "" ?>>
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