<?php
session_start();
global $conn;

require_once '../config/db.php';
require_once '../config/auth.php';

require_login();

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);

$userid = $_SESSION['userid'];

$sql = "SELECT * FROM users WHERE userid='$userid'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if($_SERVER['REQUEST_METHOD']==='POST'){
  
}

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
      <?php if (!empty($error)): ?>
        <div class="error-message" id="error-success-msg">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="success-message" id="error-success-msg">
          <?php echo $success; ?>
        </div>
      <?php endif; ?>
      <div class="header">
        <div>
          <h1>My Profile</h1>
          <p>Manage Your Personal Information and Account Settings.</p>
        </div>
      </div>
      <div class="profile-container">
        <div class="profile-card">
          <div class="profile-avatar">
            <?= strtoupper(substr($user['name'], 0, 1)); ?>
          </div>
          <h2>
            <?= $user['name']; ?>
          </h2>
        </div>
        <div class="profile-form">
          <h3>Personal Information</h3>

          <form action="" method="POST" novalidate>
            <input type="hidden" name="update_type" value="profile">

            <div class="row">

              <div class="form-group">
                <label for="">
                  Full Name
                </label>

                <input type="text" name="name" value="<?= $user['name']; ?>">
              </div>

              <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" value="<?= $user['email']; ?>">
              </div>
            </div>

            <div class="row">

              <div class="form-group">
                <label for="">
                  Phone Number
                </label>
                <input type="text" name="phone" value="<?= $user['phone']; ?>">
              </div>

              <div class="form-group">
                <label for="">Role</label>
                <input type="text" value="Player" readonly>
              </div>
            </div>

            <div class="form-group">
              <label for="">
                Current Password
                <span style="color: red;">
                  *
                </span>
              </label>
              <input type="password" name="current_password" placeholder="Enter Your Current Password To Update Your Profile">
              <small style="color: #666;">
                Password is Rwquired to update profile
              </small>
            </div>

            <button type="submit" class="btn">
              Update Profile
            </button>
          </form>

          <hr style="margin: 30px 0;">

          <h3>Change Password?</h3>

          <form action="" method="POST" novalidate>
            <input type="hidden" name="update_type" value="password">

            <div class="form-group">
              <label for="">
                Current Password:
              </label>
              <input type="password" name="current_password" required>
            </div>

            <div class="row">
              <div class="form-group">
                <label for="">
                  New Password
                </label>
                <input type="password" name="new_password" required>
              </div>

              <div class="form-group">
                <label for="">
                  Confirm Password
                </label>
                <input type="password" name="current_password" required>
              </div>
            </div>
            <button type="submit" class="btn">
              Change Password
            </button>
          </form>
        </div>
      </div>
    </main>
  </div>
</body>

</html>