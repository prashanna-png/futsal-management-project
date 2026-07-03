<?php
session_start();

require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

if ($_SESSION['role'] != 'admin') {
  header("Location: ../login.php");
  exit();
}

$currentPage = 'dashboard';

$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='customer'");
$totalUsers = mysqli_fetch_assoc($result)['total'];


$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM futsal");
$totalFutsals = mysqli_fetch_assoc($result)['total'];


$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM futsal WHERE status='pending'");
$totalPending = mysqli_fetch_assoc($result)['total'];


$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM futsal WHERE status='approved'");
$totalApproved = mysqli_fetch_assoc($result)['total'];

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


$userResult = mysqli_query($conn, "
SELECT *
FROM users
ORDER BY created_at DESC
LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <title>Admin Dashboard</title>

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

          <h1>Welcome Back, <?php echo htmlspecialchars($_SESSION['name']); ?> 👋</h1>

          <p>Manage your futsal management system.</p>

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

      <!-- Statistics -->

      <div class="cards">

        <div class="card">

          <h4>Total Customer</h4>

          <h2><?php echo $totalUsers; ?></h2>

        </div>

        <div class="card">

          <h4>Total Futsals</h4>

          <h2><?php echo $totalFutsals; ?></h2>

        </div>

        <div class="card">

          <h4>Pending Approval</h4>

          <h2><?php echo $totalPending; ?></h2>

        </div>

        <div class="card">

          <h4>Approved</h4>

          <h2><?php echo $totalApproved; ?></h2>

        </div>

      </div>

      <div class="content">

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

              </tr>

            <?php } ?>

          </table>

        </div>

        <!-- Right Side -->

        <div>

          <div class="panel">

            <h2>Recent Users</h2>

            <div class="user-list">

              <?php while ($user = mysqli_fetch_assoc($userResult)) { ?>

                <div class="user-item">

                  <div class="user-info">

                    <div class="user-avatar">

                      <?php echo strtoupper(substr($user['name'], 0, 1)); ?>

                    </div>

                    <div>

                      <strong>
                        <?php echo htmlspecialchars($user['name']); ?>
                      </strong>
                      <br>
                      <?php echo ucfirst($user['role']); ?>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>

          <br>

          <div class="panel">

            <h2>Quick Actions</h2>

            <div class="quick-links">

              <a href="manage_futsals.php">

                Manage Futsals

              </a>

              <a href="manage_users.php">

                Manage Users

              </a>

              <a href="manage_bookings.php">

                Manage Bookings

              </a>

              <a href="reports.php">

                View Reports

              </a>

            </div>

          </div>

        </div>

      </div>

    </main>

  </div>

</body>

</html>