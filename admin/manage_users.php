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

$currentPage = 'manageUsers';

$result = mysqli_query($conn, "SELECT * FROM users");


?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <title>manage futsals</title>

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

          <h1>Manage Users</h1>

          <p>View and manage all registered users.</p>

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

        <h2>Users</h2>

        <table>

          <tr>

            <th>Avatar</th>

            <th>Name</th>

            <th>Email</th>

            <th>Phone</th>

            <th>Role</th>

            <th>Joined</th>

            <th>Actions</th>

          </tr>
          <?php
          while ($row = mysqli_fetch_assoc($result)) {
          ?>
            <tr>
              <td class="avatar" style="margin-top: 10px;">
                <?php
                echo strtoupper(substr($row['name'], 0, 1));
                ?>
              </td>

              <td>
                <?php echo htmlspecialchars($row['name']); ?>
              </td>

              <td>
                <?php echo htmlspecialchars($row['email']); ?>
              </td>

              <td>
                <?php echo htmlspecialchars($row['phone']); ?>
              </td>

              <td>
                <?php echo htmlspecialchars($row['role']); ?>
              </td>

              <td>
                <?php echo htmlspecialchars($row['created_at']); ?>
              </td>

              <td>

                <form action="./user_detail.php" method="POST">

                  <input type="hidden" name="futsalid"
                    value="">

                  <button
                    type="submit"
                    name="action"
                    value="view"
                    class="btn approve-btn">
                    View
                  </button>

                </form>

              </td>
            <?php } ?>
            </tr>

        </table>
      </div>
    </main>

  </div>

</body>

</html>