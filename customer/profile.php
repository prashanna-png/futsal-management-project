<?php
require_once '../config/auth.php';
require_login();
$currentPage = 'profile';


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
          <h1>My Profile</h1>
          <p>Manage your personal information.</p>
        </div>
      </div>

      <div class="profile-card">

        <div class="profile-avatar">
          P
        </div>

        <form>

          <label>Full Name</label>
          <input type="text" value="">

          <label>Email</label>
          <input type="email" value="">

          <label>Phone</label>
          <input type="text" value="">

          <label>Role</label>
          <select name="role" placeholder="Select Role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="customer">Player</option>
            <option value="owner">Futsal Owner</option>
            <option value="staff">Staff</option>
          </select>

          <button>Update Profile</button>

        </form>

      </div>
  </div>
  </main>

  </div>

</body>

</html>