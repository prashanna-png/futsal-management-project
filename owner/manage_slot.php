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
    $checkSql = "SELECT slotid FROM timeslot WHERE futsalid= '$futsalid' AND  start_time='$startingTime' AND end_time='$endTime'";

    $result = mysqli_query($conn, $checkSql);
    if (mysqli_num_rows($result) > 0) {
      $_SESSION['error'] = "This time slot already exists";
    } else {
      $sql = "INSERT INTO timeslot (futsalid, start_time , end_time) VALUES ('$futsalid','$startingTime', '$endTime')";
      if (mysqli_query($conn, $sql)) {
        $slotid = mysqli_insert_id($conn);

        $_SESSION['success'] = 'time slot added successfully';
      } else {
        $_SESSION['error'] = 'failed to add time slot';
      }
    }
  }
  header("Location: " . $_SERVER['PHP_SELF'] . "?futsalid=" . $futsalid);
  exit;
}

$sql = "SELECT * FROM timeslot WHERE futsalid='$futsalid'";

$result = mysqli_query($conn, $sql);



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

        <h3>Add New Slot
        </h3>
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

        <form class="slot-form" method="POST">

          <div class="input-group">
            <label>Start Time</label>
            <input type="time" name="start_time">
          </div>

          <div class="input-group">
            <label>End Time</label>
            <input type="time" name="end_time">
          </div>

          <button type="submit" class="add-btn">
            + Add Slot
          </button>

        </form>

      </div>

      <div class="slot-list">

        <h3>Available Slots</h3>
        <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="slot-item">

              <span>
                <?php echo substr($row['start_time'], 0, 5); ?>
                -
                <?php echo substr($row['end_time'], 0, 5); ?>
              </span>

              <div class="slot-actions">

                <button class="slot-delete-btn" onclick="location.href='delete_slot.php?futsalid=<?= $futsalid ?>&slotid=<?= $row['slotid'] ?>'">
                  🗑 Delete
                </button>

              </div>

            </div>
        <?php }
        }

        ?>

      </div>


    </main>
  </div>

  <script src="../assets/js/main.js"></script>
</body>

</html>