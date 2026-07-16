<?php
global $conn;

require_once '../config/db.php';
require_once '../config/auth.php';
require_login();

$currentPage = 'support';

$error   = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $subject = trim($_POST['subject']);
  $message = trim($_POST['message']);
  $userid  = $_SESSION['userid'];

  if (empty($subject) || empty($message)) {
    $_SESSION['error'] = 'Please fill in all fields.';
  } elseif (strlen($subject) < 5) {
    $_SESSION['error'] = 'Subject is too short.';
  } elseif (strlen($message) < 10) {
    $_SESSION['error'] = 'Message is too short.';
  } else {
    $sql = "INSERT INTO support_messages (userid, subject, message)
                VALUES ('$userid', '$subject', '$message')";

    if (mysqli_query($conn, $sql)) {
      $_SESSION['success'] = 'Message sent! We will get back to you soon.';
    } else {
      $_SESSION['error'] = 'Failed to send message. Try again.';
    }
  }

  header("Location: support.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support Center</title>
  <link rel="stylesheet" href="../assets/css/customer.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">

      <div class="header">
        <div>
          <h1>Support Center</h1>
          <p>Need help? Contact us anytime.</p>
        </div>
      </div>

      <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
      <?php endif; ?>

      <div class="support-container">

        <!-- Left: Contact Info -->
        <div class="contact-info">

          <h3>Contact Information</h3>

          <p>📞 +977-9761849372</p>
          <p>✉️ admin@futsal.com</p>
          <p>📍 Lalitpur, Nepal</p>

          <hr style="margin: 20px 0; border-color: #eee;">

          <h3>Office Hours</h3>
          <p>Sunday - Friday</p>
          <p>9:00 AM - 6:00 PM</p>

          <hr style="margin: 20px 0; border-color: #eee;">

          <h3>Response Time</h3>
          <p>We typically respond within 24 hours.</p>

        </div>

        <!-- Right: Message Form -->
        <div class="support-form">

          <h3>Send Message</h3>

          <form method="POST" action="support.php">

            <input
              type="text"
              name="subject"
              placeholder="Subject"
              required
              minlength="5">

            <textarea
              name="message"
              placeholder="Write your message..."
              required
              minlength="10"
              rows="6"></textarea>

            <button type="submit">Send Message</button>

          </form>

        </div>

      </div>

    </main>

  </div>

</body>

</html>