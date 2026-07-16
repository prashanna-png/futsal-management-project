<?php
global $conn;
require_once '../config/db.php';
require_once '../config/auth.php';
require_login();

if ($_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

$currentPage = 'support';

// Mark as read when admin views
mysqli_query($conn, "UPDATE support_messages SET is_read = TRUE");

// Get all messages
$result = mysqli_query($conn, "
    SELECT sm.*, u.name, u.email,u.role
    FROM support_messages sm
    JOIN users u ON sm.userid = u.userid
    ORDER BY sm.sent_at DESC
");
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
          <h1>Support Messages</h1>
          <p>Messages sent by customers.</p>
        </div>
      </div>

      <div class="panel">

        <table>
          <thead>
            <tr>
              <th>From</th>
              <th>Role</th>
              <th>Email</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Sent At</th>
            </tr>
          </thead>
          <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['role']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['subject']) ?></td>
                  <td><?= htmlspecialchars($row['message']) ?></td>
                  <td><?= date('d M Y, h:i A', strtotime($row['sent_at'])) ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" style="text-align:center; padding:30px; color:#666;">
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