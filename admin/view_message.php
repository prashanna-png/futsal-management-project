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

if (!$details) {
  header("Location: support_message.php");
  exit();
}

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
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
              <strong><?= htmlspecialchars($details['phone']) ?></strong>
            </div>

            <div class="info-row">
              <span>Sent On</span>
              <strong><?= date('d M Y, h:i A', strtotime($details['sent_at'])) ?></strong>
            </div>

            <div class="info-row">
              <span>Status</span>
              <?php if ($details['is_solved']): ?>
                <span class="status completed" style="color: white;">Solved</span>
              <?php elseif ($details['is_read']): ?>
                <span class="status confirmed" style="color: white;">Read</span>
              <?php else: ?>
                <span class="status pending" style="color: white;">Unread</span>
              <?php endif; ?>
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

          <?php if (!$details['is_read'] && !$details['is_solved']): ?>
            <form method="POST" action="mark_resolved.php">
              <input type="hidden" name="messageid" value="<?= $details['messageid'] ?>">
              <button type="submit" class="btn">✓ Mark as Read</button>
            </form>
          <?php endif; ?>

          <?php if ($details['is_read'] && !$details['is_solved']): ?>
            <form method="POST" action="mark_solved.php">
              <input type="hidden" name="messageid" value="<?= $details['messageid'] ?>">
              <button type="submit" class="btn solved-btn">✅ Mark as Solved</button>
            </form>
          <?php endif; ?>

          <?php if ($details['is_solved']): ?>
            <span class="resolved-label">✅ This issue has been resolved</span>
          <?php endif; ?>


        </div>

      </div>

    </main>

  </div>

</body>

</html>