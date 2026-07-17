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

$currentPage = 'pendingFutsals';

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

// Handle approve/reject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action   = $_POST['action'] ?? '';
  $futsalid = $_POST['futsalid'] ?? '';

  if (!$futsalid || !in_array($action, ['approve', 'reject'])) {
    $_SESSION['error'] = 'Invalid action.';
    header("Location: pending_futsals.php");
    exit();
  }

  $status = ($action === 'approve') ? 'approved' : 'rejected';

  $sql = "UPDATE futsal SET status='$status' WHERE futsalid='$futsalid'";

  if (mysqli_query($conn, $sql)) {
    $_SESSION['success'] = "Futsal " . ucfirst($status) . " successfully.";
  } else {
    $_SESSION['error'] = "Failed to update futsal status.";
  }

  header("Location: pending_futsals.php");
  exit();
}

// Get filter from URL
$filter = $_GET['filter'] ?? 'all';

// Build query based on filter
if ($filter === 'pending') {
  $where = "WHERE f.status = 'pending'";
} elseif ($filter === 'approved') {
  $where = "WHERE f.status = 'approved'";
} elseif ($filter === 'rejected') {
  $where = "WHERE f.status = 'rejected'";
} else {
  $where = "";
}

$result = mysqli_query($conn, "
  SELECT
    f.futsalid,
    f.name,
    f.location,
    f.price_per_hour,
    f.image,
    f.status,
    f.created_at,
    u.name AS owner,
    u.email AS owner_email,
    u.phone AS owner_phone
  FROM futsal f
  JOIN users u ON f.ownerid = u.userid
  $where
  ORDER BY f.created_at DESC
");

// Count each status
$counts = mysqli_fetch_assoc(mysqli_query($conn, "
  SELECT
    SUM(status = 'pending')  AS pending,
    SUM(status = 'approved') AS approved,
    SUM(status = 'rejected') AS rejected
  FROM futsal
"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Futsals</title>
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
          <h1>Manage Futsals</h1>
          <p>Review and approve futsal courts submitted by owners.</p>
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
      <div class="cards" style="grid-template-columns: repeat(3, 1fr);">

        <div class="card">
          <h4>Pending</h4>
          <h2><?= $counts['pending'] ?? 0 ?></h2>
          <p>Waiting for review</p>
        </div>

        <div class="card">
          <h4>Approved</h4>
          <h2><?= $counts['approved'] ?? 0 ?></h2>
          <p>Live on platform</p>
        </div>

        <div class="card">
          <h4>Rejected</h4>
          <h2><?= $counts['rejected'] ?? 0 ?></h2>
          <p>Declined registrations</p>
        </div>

      </div>

      <!-- Filter Tabs -->
      <div class="filter-tabs">
        <a href="?filter=all" class="<?= $filter === 'all'      ? 'active' : '' ?>">All</a>
        <a href="?filter=pending" class="<?= $filter === 'pending'  ? 'active' : '' ?>">Pending</a>
        <a href="?filter=approved" class="<?= $filter === 'approved' ? 'active' : '' ?>">Approved</a>
        <a href="?filter=rejected" class="<?= $filter === 'rejected' ? 'active' : '' ?>">Rejected</a>
      </div>

      <!-- Futsals Table -->
      <div class="panel" style="margin-top: 20px;">

        <table>
          <thead>
            <tr>
              <th>Image</th>
              <th>Futsal</th>
              <th>Owner</th>
              <th>Location</th>
              <th>Price/Hr</th>
              <th>Submitted</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td>
                    <img
                      src="../assets/uploads/<?= htmlspecialchars($row['image']) ?>"
                      width="70"
                      height="50"
                      style="border-radius:8px; object-fit:cover;">
                  </td>

                  <td>
                    <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                  </td>

                  <td>
                    <?= htmlspecialchars($row['owner']) ?><br>
                    <small style="color:#666;"><?= htmlspecialchars($row['owner_phone']) ?></small>
                  </td>

                  <td><?= htmlspecialchars($row['location']) ?></td>

                  <td>Rs. <?= number_format($row['price_per_hour']) ?></td>

                  <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>

                  <td>
                    <span class="status <?= strtolower($row['status']) ?>">
                      <?= ucfirst($row['status']) ?>
                    </span>
                  </td>

                  <td>
                    <?php if ($row['status'] === 'pending'): ?>
                      <form method="POST" style="display:flex; gap:8px;">
                        <input type="hidden" name="futsalid" value="<?= $row['futsalid'] ?>">
                        <button type="submit" name="action" value="approve" class="btn approve-btn">
                          Approve
                        </button>
                        <button type="submit" name="action" value="reject" class="btn reject-btn">
                          Reject
                        </button>
                      </form>

                    <?php elseif ($row['status'] === 'approved'): ?>
                      <form method="POST">
                        <input type="hidden" name="futsalid" value="<?= $row['futsalid'] ?>">
                        <button type="submit" name="action" value="reject" class="btn reject-btn">
                          Revoke
                        </button>
                      </form>

                    <?php elseif ($row['status'] === 'rejected'): ?>
                      <form method="POST">
                        <input type="hidden" name="futsalid" value="<?= $row['futsalid'] ?>">
                        <button type="submit" name="action" value="approve" class="btn approve-btn">
                          Approve
                        </button>
                      </form>

                    <?php endif; ?>
                  </td>

                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" style="text-align:center; padding:40px; color:#666;">
                  <?php if ($filter === 'pending'): ?>
                    🎉 No pending futsals — all caught up!
                  <?php else: ?>
                    No futsals found.
                  <?php endif; ?>
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