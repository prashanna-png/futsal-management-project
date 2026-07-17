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

$userid = $_SESSION['userid'];
$messageid = $_GET['bookingid'];

$sql = "SELECT 
          msg.*,
          
          u.name,
          u.userid,
          u.role,
          u.email,
          u.phone
          
          FROM support_messages msg
          JOIN users u 
          ON u.userid = msg.userid
          WHERE u.userid = '$userid'
          ORDER BY msg.sent_at DESC";

$result = mysqli_query($conn, $sql);
$details = mysqli_fetch_assoc($result);



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>view messages</title>
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

      <div class="header">

        <div>
          <h1>Support Message</h1>
          <p>View complete message details.</p>
        </div>

        <a href="support.php" class="back-btn">
          ← Back to Messages
        </a>

      </div>


      <div class="view-message-container">

        <!-- Sender Information -->

        <div class="view-message-card">

          <h2>Sender Information</h2>

          <div class="message-avatar">
            <?= strtoupper(substr($_SESSION['name'], 0, 1)); ?>
          </div>

          <div class="message-info">

            <div class="info-row">
              <span>Name</span>
              <strong>
                <?= htmlspecialchars($details['name']) ?>
              </strong>
            </div>

            <div class="info-row">
              <span>Role</span>
              <strong>
                <?= htmlspecialchars($details['role']) ?>
              </strong>
            </div>

            <div class="info-row">
              <span>Email</span>
              <strong>
                <?= htmlspecialchars($details['email']) ?>
              </strong>
            </div>

            <div class="info-row">
              <span>Phone</span>
              <strong><?= htmlspecialchars($details['phone']) ?></strong>
            </div>

            <div class="info-row">
              <span>Sent On</span>
              <strong>17 Jul 2026, 8:15 PM</strong>
            </div>

            <div class="info-row">
              <span>Status</span>

              <span class="status pending">
                Unread
              </span>

            </div>

          </div>

        </div>



        <!-- Message -->

        <div class="view-message-card">

          <h2>Message Details</h2>

          <div class="message-subject">

            <label>Subject</label>

            <h3>
              Unable to cancel my booking
            </h3>

          </div>

          <div class="message-body">

            <label>Message</label>

            <p>
              Hello Admin,

              I booked a futsal yesterday but I am unable to cancel it from
              my account. Every time I click the cancel button, nothing
              happens.

              Please check the issue.

              Thank you.
            </p>

          </div>

        </div>



        <!-- Actions -->

        <div class="view-message-actions">

          <button class="btn">
            ✓ Mark as Resolved
          </button>

          <button class="delete-btn">
            Delete Message
          </button>

        </div>

      </div>

    </main>

  </div>

</body>

</html>