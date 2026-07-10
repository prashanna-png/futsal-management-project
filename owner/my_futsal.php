<?php
session_start();
global $conn;
require_once '../config/db.php';
require_once '../config/auth.php';

require_login();

$currentPage = 'myFutsal';

if ($_SESSION['role'] !== 'owner') {
  header("Location: ../login.php");
  exit;
}
$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$ownerid = $_SESSION['userid'];
$sql = "SELECT * FROM futsal WHERE ownerid='$ownerid'";
$result = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $futsalid = $_POST['futsalid'];
  $ownerid = $_SESSION['userid'];

  // Debug: Check what's being deleted
  error_log("Attempting to delete futsalid: $futsalid for ownerid: $ownerid");

  $sql = "DELETE FROM futsal
            WHERE futsalid='$futsalid'
            AND ownerid='$ownerid'";

  if (mysqli_query($conn, $sql)) {
    $affected_rows = mysqli_affected_rows($conn);
    error_log("Rows affected: $affected_rows");

    if ($affected_rows > 0) {
      $_SESSION['success'] = "Futsal deleted successfully.";
    } else {
      $_SESSION['error'] = "Futsal not found or you don't have permission to delete it.";
    }
  } else {
    $_SESSION['error'] = "Failed to delete futsal: " . mysqli_error($conn);
  }
  // Make sure futsalid is set and is numeric

  header("Location: my_futsal.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>My Futsals</title>

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

          <h1>My Futsals</h1>

          <p>Manage all of your registered futsal courts.</p>

        </div>

        <a href="register_futsal.php" class="btn-add">
          + Register New Futsal
        </a>

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
      <div class="futsal-grid">
        <?php
        if (mysqli_num_rows($result) > 0) {

          while ($row = mysqli_fetch_assoc($result)) {
        ?>

            <div class="futsal-card">

              <img class="futsal-img" src="../assets/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="">

              <div class="futsal-info">

                <h3><?php echo htmlspecialchars($row['name']); ?></h3>

                <p>📍 <?php echo htmlspecialchars($row['location']); ?></p>

                <p>Rs. <?php echo $row['price_per_hour']; ?> / Hour</p>

                <span class="status <?php echo $row['status']; ?>">
                  <?php echo ucfirst($row['status']); ?>
                </span>
              </div>

              <div class="buttons">

                <div class="top-actions">

                  <button
                    class="action-btn edit-btn"
                    onclick="location.href='edit_futsal.php?futsalid=<?php echo $row['futsalid']; ?>'">

                    <img src="../assets/icons/edit.png" alt="">
                    <span>Edit</span>

                  </button>

                  <button
                    class="action-btn delete-btn"
                    data-id="<?php echo $row['futsalid']; ?>">

                    <img src="../assets/icons/delete.png" alt="">
                    <span>Delete</span>

                  </button>

                </div>

                <button
                  class="action-btn slot-btn"
                  onclick="location.href='manage_slot.php?futsalid=<?php echo $row['futsalid']; ?>'">

                  <img src="../assets/icons/time-management.png" alt="">
                  <span>Manage Slots</span>

                </button>

              </div>
            </div>

        <?php
          }
        } else {
          echo "<p>No futsal found.</p>";
        }
        ?>
      </div>

      <div class="popup-overlay" id="popup-overlay">
        <div class="delete-container" id="delete-ctn">
          <p>Are you Sure You Want To Delete?</p>
          <p>This Action Cannot Be Undone</p>

          <div class="cancel-delete">
            <button class="cancel-btn" id="cancel-btn">Cancel</button>
            <form method="POST">
              <input type="hidden" name="futsalid" id="deleteFutsalId">
              <button type="submit" class="popup-delete-btn">Delete</button>
            </form>
          </div>
        </div>
      </div>

    </main>

  </div>
  <script src="../assets/js/owner.js"></script>
</body>

</html>