<?php
global $conn;
session_start();

require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

if ($_SESSION['role'] != 'admin') {
  header("Location: ../login.php");
  exit();
}

$currentPage = 'manageUsers';

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

// Handle delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $userid = $_POST['userid'] ?? '';

  if (!$userid) {
    $_SESSION['error'] = 'Invalid user.';
    header("Location: manage_users.php");
    exit();
  }

  // Prevent admin from deleting themselves
  if ($userid == $_SESSION['userid']) {
    $_SESSION['error'] = 'You cannot delete your own account.';
    header("Location: manage_users.php");
    exit();
  }

  if ($action === 'delete') {
    $sql = "DELETE FROM users WHERE userid='$userid'";
    if (mysqli_query($conn, $sql)) {
      $_SESSION['success'] = 'User deleted successfully.';
    } else {
      $_SESSION['error'] = 'Failed to delete user.';
    }
  }

  header("Location: manage_users.php");
  exit();
}

// Get filter
$filter = $_GET['filter'] ?? 'all';

if ($filter === 'customer') {
  $where = "WHERE role = 'customer'";
} elseif ($filter === 'owner') {
  $where = "WHERE role = 'owner'";
} elseif ($filter === 'staff') {
  $where = "WHERE role = 'staff'";
} elseif ($filter === 'admin') {
  $where = "WHERE role = 'admin'";
} else {
  $where = "";
}

// Get users
$result = mysqli_query($conn, "
  SELECT * FROM users
  $where
  ORDER BY created_at DESC
");

// Count each role
$counts = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT
    COUNT(*) AS total,
    SUM(role = 'customer') AS customers,
    SUM(role = 'owner')    AS owners,
    SUM(role = 'staff')    AS staff
  FROM users
"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">

      <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <!-- Header -->
      <div class="header">
        <div>
          <h1>Manage Users</h1>
          <p>View and manage all registered users.</p>
        </div>
        <a href="profile.php" class="admin-user">
          <div class="avatar">
            <?= strtoupper(substr($_SESSION['name'], 0, 1)); ?>
          </div>
          <div>
            <strong><?= htmlspecialchars($_SESSION['name']); ?></strong>
            <br>
            Administrator
          </div>
        </a>
      </div>

      <!-- Stat Cards -->
      <div class="cards" style="grid-template-columns: repeat(4, 1fr);">

        <div class="card">
          <h4>Total Users</h4>
          <h2><?= $counts['total'] ?? 0 ?></h2>
          <p>All registered</p>
        </div>

        <div class="card">
          <h4>Customers</h4>
          <h2><?= $counts['customers'] ?? 0 ?></h2>
          <p>Active players</p>
        </div>

        <div class="card">
          <h4>Owners</h4>
          <h2><?= $counts['owners'] ?? 0 ?></h2>
          <p>Court owners</p>
        </div>

        <div class="card">
          <h4>Staff</h4>
          <h2><?= $counts['staff'] ?? 0 ?></h2>
          <p>Ground staff</p>
        </div>

      </div>

      <!-- Filter Tabs -->
      <div class="filter-tabs">
        <a href="?filter=all" class="<?= $filter === 'all'      ? 'active' : '' ?>">All</a>
        <a href="?filter=customer" class="<?= $filter === 'customer' ? 'active' : '' ?>">Customers</a>
        <a href="?filter=owner" class="<?= $filter === 'owner'    ? 'active' : '' ?>">Owners</a>
        <a href="?filter=staff" class="<?= $filter === 'staff'    ? 'active' : '' ?>">Staff</a>
        <a href="?filter=admin" class="<?= $filter === 'admin'    ? 'active' : '' ?>">Admins</a>
      </div>

      <!-- Users Table -->
      <div class="panel" style="margin-top: 20px;">

        <!-- Search Box -->
        <div class="search-box">
          <input type="text" id="searchInput" placeholder="Search by name or email...">
        </div>

        <table id="usersTable">
          <thead>
            <tr>
              <th>Avatar</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Role</th>
              <th>Joined</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td>
                    <div class="user-avatar">
                      <?= strtoupper(substr($row['name'], 0, 1)); ?>
                    </div>
                  </td>

                  <td><?= htmlspecialchars($row['name']); ?></td>

                  <td><?= htmlspecialchars($row['email']); ?></td>

                  <td><?= htmlspecialchars($row['phone']); ?></td>

                  <td>
                    <span class="role-badge <?= $row['role'] ?>">
                      <?= ucfirst($row['role']); ?>
                    </span>
                  </td>

                  <td><?= date('d M Y', strtotime($row['created_at'])); ?></td>

                  <td>
                    <?php if ($row['userid'] != $_SESSION['userid']): ?>
                      <form method="POST"
                        onsubmit="return confirm('Are you sure you want to delete <?= htmlspecialchars($row['name']) ?>?')">
                        <input type="hidden" name="userid" value="<?= $row['userid'] ?>">
                        <button
                          type="submit"
                          name="action"
                          value="delete"
                          class="btn reject-btn">
                          Delete
                        </button>
                      </form>
                    <?php else: ?>
                      <span style="color:#999; font-size:13px;">You</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" style="text-align:center; padding:40px; color:#666;">
                  No users found.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>

      </div>

    </main>

  </div>



</body>

</html>