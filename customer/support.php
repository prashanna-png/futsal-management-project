<?php
require_once '../config/auth.php';
require_login();
$currentPage = 'support';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Dashboard</title>

  <link rel="stylesheet" href="../assets/css/customer.css">

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
          <h1>Support Center</h1>
          <p>Need help? Contact us anytime.</p>
        </div>
      </div>

      <div class="support-container">

        <div class="contact-info">

          <h3>Contact Information</h3>

          <p>📞 +977-9761849372</p>

          <p>📧 admin@futsal.com</p>

          <p>📍 lalitpur, Nepal</p>

        </div>

        <div class="support-form">

          <h3>Send Message</h3>

          <input type="text" placeholder="Subject">

          <textarea rows="6" placeholder="Write your message..."></textarea>

          <button>Send Message</button>

        </div>

      </div>
  </div>
  </main>

  </div>

</body>

</html>