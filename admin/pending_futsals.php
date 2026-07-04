<?php
session_start();

require_once '../config/auth.php';
require_once '../config/db.php';
global $conn;

require_login();

if ($_SESSION['role'] != 'admin') {
  header("Location: ../login.php");
  exit();
}

$currentPage = 'pendingFutsals';

$pendingResult = mysqli_query($conn, "
SELECT
f.futsalid,
f.name,
f.location,
f.image,
u.name AS owner
FROM futsal f
JOIN users u
ON f.ownerid=u.userid
WHERE f.status='pending'
ORDER BY f.created_at DESC
LIMIT 5
");

// Recent Users
$userResult = mysqli_query($conn, "
SELECT *
FROM users
ORDER BY created_at DESC
LIMIT 5
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $action = $_POST['action'];
  $futsalid = $_POST['futsalid'];

  if ($action == 'approve') {

    $status = 'approved';
  } elseif ($action == 'reject') {

    $status = 'rejected';
  }

  $sql = "UPDATE futsal
            SET status = '$status'
            WHERE futsalid = '$futsalid'";

  mysqli_query($conn, $sql);

  header("Location: pending_futsals.php");
  exit();
}




?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <title>pending futsals</title>

  <link rel="stylesheet" href="../assets/css/admin.css">

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

          <h1>Manage Pending Futsals</h1>

          <p>Review and approve futsals submitted by owners.</p>

        </div>

        <div class="admin-user">

          <div class="avatar">

            <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>

          </div>

          <div>

            <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong>

            <br>

            Administrator

          </div>

        </div>

      </div>

      <!-- Pending Futsals -->

      <div class="panel">

        <h2>Recent Pending Futsals</h2>

        <table>

          <tr>

            <th>Image</th>

            <th>Futsal</th>

            <th>Owner</th>

            <th>Location</th>

            <th>Status</th>

            <th>Action</th>

          </tr>

          <?php while ($row = mysqli_fetch_assoc($pendingResult)) { ?>

            <tr>

              <td>

                <img
                  src="../assets/uploads/<?php echo htmlspecialchars($row['image']); ?>"
                  width="80"
                  style="border-radius:8px;">

              </td>

              <td>

                <?php echo htmlspecialchars($row['name']); ?>

              </td>

              <td>

                <?php echo htmlspecialchars($row['owner']); ?>

              </td>

              <td>

                <?php echo htmlspecialchars($row['location']); ?>

              </td>

              <td>

                <span class="status pending">

                  Pending

                </span>

              </td>

              <td>

                <form action="" method="POST">

                  <input type="hidden" name="futsalid"
                    value="<?php echo $row['futsalid']; ?>">

                  <button
                    type="submit"
                    name="action"
                    value="approve"
                    class="btn approve-btn">
                    Approve
                  </button>

                  <button
                    type="submit"
                    name="action"
                    value="reject"
                    class="btn reject-btn">
                    Reject
                  </button>

                </form>

              </td>
            </tr>
          <?php } ?>
        </table>
      </div>
    </main>

  </div>

</body>

</html>