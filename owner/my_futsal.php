<?php
session_start();
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

      <div class="futsal-grid">
        <?php
        if (mysqli_num_rows($result) > 0) {

          while ($row = mysqli_fetch_assoc($result)) {
        ?>

            <div class="futsal-card">

              <img src="../assets/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="">

              <div class="futsal-info">

                <h3><?php echo htmlspecialchars($row['name']); ?></h3>

                <p>📍 <?php echo htmlspecialchars($row['location']); ?></p>

                <p>Rs. <?php echo $row['price_per_hour']; ?> / Hour</p>

                <span class="status <?php echo $row['status']; ?>">
                  <?php echo ucfirst($row['status']); ?>
                </span>

                <div class="card-buttons">
                  <a href="#" class="edit-btn">Edit</a>
                  <a href="#" class="delete-btn">Delete</a>
                </div>

              </div>

            </div> <!-- Close futsal-card here -->

        <?php
          }
        } else {
          echo "<p>No futsal found.</p>";
        }
        ?>
      </div>

    </main>

  </div>

</body>

</html>