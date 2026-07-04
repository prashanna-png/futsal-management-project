<?php
$currentPage = 'profile';
global $conn;
require_once '../config/db.php';
require_once '../config/auth.php';

session_start();
require_login();

$userid = $_SESSION['userid'];

$sql = "SELECT * FROM users WHERE userid = '$userid'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>My Profile</title>

  <link rel="stylesheet" href="../assets/css/owner.css">

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

          <p>Manage your personal information and account settings.</p>

        </div>

      </div>

      <div class="profile-container">

        <div class="profile-card">

          <div class="profile-avatar">

            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>

          </div>

          <h2><?php echo htmlspecialchars($user['name']); ?></h2>

          <p>Owner</p>

        </div>

        <div class="profile-form">

          <h3>Personal Information</h3>

          <form action="" method="POST">

            <div class="row">

              <div class="form-group">

                <label>Full Name</label>

                <input
                  type="text"
                  name="name"
                  value="<?php echo htmlspecialchars($user['name']); ?>">

              </div>

              <div class="form-group">

                <label>Email</label>

                <input
                  type="email"
                  name="email"
                  value="<?php echo htmlspecialchars($user['email']); ?>">

              </div>

            </div>

            <div class="row">

              <div class="form-group">

                <label>Phone Number</label>

                <input
                  type="text"
                  name="phone"
                  value="<?php echo htmlspecialchars($user['phone']); ?>">

              </div>

              <div class="form-group">

                <label>Role</label>

                <input
                  type="text"
                  value="Owner"
                  readonly>

              </div>

            </div>

            <hr>

            <h3>Change Password</h3>

            <div class="form-group">

              <label>Current Password</label>

              <input
                type="password"
                name="current_password">

            </div>

            <div class="row">

              <div class="form-group">

                <label>New Password</label>

                <input
                  type="password"
                  name="new_password">

              </div>

              <div class="form-group">

                <label>Confirm Password</label>

                <input
                  type="password"
                  name="confirm_password">

              </div>

            </div>

            <button class="btn">

              Update Profile

            </button>

          </form>

        </div>

      </div>

    </main>

  </div>

</body>

</html>