<?php
session_start();
global $conn;
require_once '../config/db.php';
require_once '../config/auth.php';
require_login();

if ($_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

$currentPage = 'support';

$filter = $_GET['filter'] ?? 'all';
$where = "";

// ✅ Fixed filter logic
if ($filter === 'customer') {
  $where = "WHERE u.role = 'customer'";
} elseif ($filter === 'owner') {
  $where = "WHERE u.role = 'owner'";
} elseif ($filter === 'unread') {
  $where = "WHERE msg.is_read = 0";
} elseif ($filter === 'read') {
  $where = "WHERE msg.is_read = 1";
} elseif ($filter === 'resolved') {
  // If you have a separate 'resolved' status
  $where = "WHERE msg.is_solved = 1";
} elseif ($filter === 'all') {
  $where = ""; // No filter
}

$sql = "SELECT
            u.name,
            u.role,
            u.phone,
            u.email,
            msg.*
        FROM users u
        JOIN support_messages msg ON u.userid = msg.userid
        $where
        ORDER BY msg.sent_at DESC";

$result = mysqli_query($conn, $sql);

// ✅ Fixed count query to include solved
$countQuery = "
SELECT
    COUNT(*) AS total,
    SUM(is_read = 0) AS unread,
    SUM(is_read = 1) AS read_count,
    SUM(is_solved = 1) AS resolved
FROM support_messages
";

$counts = mysqli_fetch_assoc(mysqli_query($conn, $countQuery));

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Support Messages</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">

      <div class="header">
        <div>
          <h1>Support Message</h1>
          <p>Message Sent by Customers and Owners.</p>
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

      <div class="cards" style="grid-template-columns: repeat(3, 1fr);">
        <div class="card">
          <h4>Total Messages</h4>
          <h2><?= $counts['total'] ?? 0 ?></h2>
        </div>

        <div class="card">
          <h4>Unread</h4>
          <h2><?= $counts['unread'] ?? 0 ?></h2>
        </div>

        <div class="card">
          <h4>Solved</h4>
          <h2><?= $counts['resolved'] ?? 0 ?></h2>
        </div>
      </div>
      <div class="filter-tabs">
        <a href="?filter=all" class="<?= $filter === 'all' ? 'active' : '' ?>">All</a>
        <a href="?filter=customer" class="<?= $filter === 'customer' ? 'active' : '' ?>">Customer</a>
        <a href="?filter=owner" class="<?= $filter === 'owner' ? 'active' : '' ?>">Owners</a>
        <a href="?filter=unread" class="<?= $filter === 'unread' ? 'active' : '' ?>">Unread</a>
        <a href="?filter=read" class="<?= $filter === 'read' ? 'active' : '' ?>">Read</a>
        <a href="?filter=resolved" class="<?= $filter === 'resolved' ? 'active' : '' ?>">Solved</a>
      </div>

      <div class="search-box">
        <input type="text" placeholder="Search message ">
      </div>

      <div class="panel">

        <table style="width:100%">
          <thead>
            <tr>
              <th>From</th>
              <th>Role</th>
              <th>Subject</th>
              <th>Status</th>
              <th>Sent At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['role']) ?></td>
                  <td><?= htmlspecialchars($row['subject']) ?></td>
                  <td>
                    <?php
                    // Check if is_solved exists in your table
                    if (isset($row['is_solved']) && $row['is_solved'] == 1): ?>
                      <span class="status completed">Solved</span>
                    <?php elseif ($row['is_read'] == 1): ?>
                      <span class="status confirmed">Read</span>
                    <?php else: ?>
                      <span class="status pending">Unread</span>
                    <?php endif; ?>
                  </td>
                  <td><?= date('d M Y, h:i A', strtotime($row['sent_at'])) ?></td>
                  <td>
                    <button class="view-btn btn" onclick="location.href='view_message.php?messageid=<?= $row['messageid'] ?>'">
                      View
                    </button>
                  </td>
                </tr>
              <?php } ?>
            <?php else: ?>
              <tr>
                <td colspan="6" style="text-align:center; padding:30px; color:#666;">
                  No messages yet.
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