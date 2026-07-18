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

$currentPage = 'support';

// Check if messageid exists in URL
if (!isset($_GET['messageid'])) {
  header("Location: support_message.php");
  exit();
}

$messageid = $_GET['messageid'];

$sql = "SELECT
          msg.*,
          u.name,
          u.userid,
          u.role,
          u.email,
          u.phone
        FROM support_messages msg
        JOIN users u ON u.userid = msg.userid
        WHERE msg.messageid = '$messageid'";

$result  = mysqli_query($conn, $sql);
$details = mysqli_fetch_assoc($result);

// If no message found, redirect back
if (!$details) {
  header("Location: support.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Message</title>
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
          <h1>Support Message</h1>
          <p>View complete message details.</p>
        </div>
        <a href="support_message.php" class="back-btn">← Back to Messages</a>
      </div>

      <div class="view-message-container">

        <!-- Card 1: Sender Info LEFT -->
        <div class="view-message-card">

          <h2>Sender Information</h2>

          <div class="message-avatar">
            <?= strtoupper(substr($details['name'], 0, 1)); ?>
          </div>

          <div class="message-info">

            <div class="info-row">
              <span>Name</span>
              <strong><?= htmlspecialchars($details['name']) ?></strong>
            </div>

            <div class="info-row">
              <span>Role</span>
              <strong><?= ucfirst(htmlspecialchars($details['role'])) ?></strong>
            </div>

            <div class="info-row">
              <span>Email</span>
              <strong><?= htmlspecialchars($details['email']) ?></strong>
            </div>

            <div class="info-row">
              <span>Phone</span>
              <wstrong><?= htmlspecialchars($details['phone']) ?></wstrong>
            </div>

            <div class="info-row">
              <span>Sent On</span>
              <strong>
                <?= date('d M Y, h:i A', strtotime($details['sent_at'])) ?>
              </strong>
            </div>

            <div class="info-row">
              <span>Status</span>
              <span class="status <?= $details['is_read'] ? 'approved' : 'pending' ?>">
                <?= $details['is_read'] ? 'Read' : 'Unread' ?>
              </span>
            </div>

          </div>

        </div>

        <!-- Card 2: Message Details RIGHT -->
        <div class="view-message-card">

          <h2>Message Details</h2>

          <div class="message-subject">
            <label>Subject</label>
            <h3><?= htmlspecialchars($details['subject']) ?></h3>
          </div>

          <div class="message-body">
            <label>Message</label>
            <p><?= nl2br(htmlspecialchars($details['message'])) ?></p>
          </div>

        </div>

        <!-- Action Buttons FULL WIDTH -->
        <div class="view-message-actions">

          <!-- Mark as Read — show only if unread -->
          <?php if (!$details['is_read'] && !$details['is_solved']): ?>
            <form method="POST" action="mark_resolved.php">
              <input type="hidden" name="messageid" value="<?= $details['messageid'] ?>">
              <button type="submit" class="btn">✓ Mark as Read</button>
            </form>
          <?php endif; ?>

          <!-- Mark as Solved — show only if read but not yet solved -->
          <?php if ($details['is_read'] && !$details['is_solved']): ?>
            <form method="POST" action="mark_solved.php">
              <input type="hidden" name="messageid" value="<?= $details['messageid'] ?>">
              <button type="submit" class="btn" style="background:#2ecc71;">
                ✅ Mark as Solved
              </button>
            </form>
          <?php endif; ?>

          <!-- Already solved — just show label, no button -->
          <?php if ($details['is_solved']): ?>
            <span style="
      background: #eafaf1;
      color: #27ae60;
      padding: 13px 20px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
    ">
              ✅ This issue has been resolved
            </span>
          <?php endif; ?>

          <!-- Delete — always show -->
          <form method="POST" action="delete_message.php"
            onsubmit="return confirm('Are you sure you want to delete this message?')">
            <input type="hidden" name="message_id" value="<?= $details['messageid'] ?>">
            <button type="submit" class="delete-btn">🗑 Delete Message</button>
          </form>

        </div>

      </div>

    </main>

  </div>

</body>

</html>