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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $startingTime = $_POST['start_time'];
  $endTime = $_POST['end_time'];

  if (empty($startingTime) || empty($endTime)) {
    $_SESSION['error'] = "must select both star time and end time";
  } elseif ($startingTime >= $endTime) {
    $_SESSION['error'] = 'end time must be after start time';
  } else {
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Manage Slots</title>

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
          <h1>Manage Slots</h1>
          <p>Manage available playing hours for your futsal.</p>
        </div>

        <a href="my_futsal.php" class="back-btn">
          ← Back
        </a>

      </div>

      <div class="futsal-info">

        <h2><?php echo $futsal['name']; ?></h2>

        <p>📍 <?php echo $futsal['location']; ?></p>

        <span class="status <?php echo $futsal['status']; ?>">
          <?php echo $futsal['status']; ?>
        </span>

      </div>

      <div class="add-slot">

        <h3>Add New Slot</h3>

        <form class="slot-form">

          <div class="input-group">
            <label>Start Time</label>
            <input type="time" name="start_time">
          </div>

          <div class="input-group">
            <label>End Time</label>
            <input type="time" name="end_time">
          </div>

          <button class="add-btn">
            + Add Slot
          </button>

        </form>

      </div>

      <div class="slot-list">

        <h3>Available Slots</h3>

        <div class="slot-item">

          <span>06:00 AM - 07:00 AM</span>

          <div class="slot-actions">

            <button class="edit-btn">
              ✏ Edit
            </button>

            <button class="delete-btn">
              🗑 Delete
            </button>

          </div>

        </div>

      </div>

    </main>
  </div>
</body>

</html>