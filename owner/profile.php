<?php
$currentPage = 'profile';
global $conn;

require_once '../config/db.php';
require_once '../config/auth.php';

session_start();
require_login();

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);

$userid = $_SESSION['userid'];

$sql = "SELECT * FROM users WHERE userid='$userid'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$current_user = $user['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  // Name validation
  if (!preg_match("/^[A-Za-z\s]+$/", $name)) {
    $_SESSION['error'] = "Only letters are allowed in name.";
    header("Location: profile.php");
    exit;
  }

  // Email validation
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email address.";
    header("Location: profile.php");
    exit;
  }

  // Phone validation
  if (!preg_match("/^[0-9]{10}$/", $phone)) {
    $_SESSION['error'] = "Invalid phone number.";
    header("Location: profile.php");
    exit;
  }

  // Check duplicate email
  $sql = "SELECT userid FROM users WHERE email='$email' AND userid!='$current_user'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $_SESSION['error'] = "User with this email already exists.";
    header("Location: profile.php");
    exit;
  }

  // Verify current password
  if (!password_verify($current_password, $user['password'])) {
    $_SESSION['error'] = "Invalid current password.";
    header("Location: profile.php");
    exit;
  }

  // Check new password fields
  if (empty($new_password) || empty($confirm_password)) {
    $_SESSION['error'] = "Please enter the new password.";
    header("Location: profile.php");
    exit;
  }

  if ($new_password !== $confirm_password) {
    $_SESSION['error'] = "New passwords do not match.";
    header("Location: profile.php");
    exit;
  }

  // Hash new password
  $password = password_hash($new_password, PASSWORD_DEFAULT);

  // Update profile
  $sql = "UPDATE users
            SET
                name='$name',
                email='$email',
                phone='$phone',
                password='$password'
            WHERE userid='$current_user'";

  if (mysqli_query($conn, $sql)) {

    $_SESSION['success'] = "Profile updated successfully.";
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
  } else {

    $_SESSION['error'] = "Failed to update profile.";
  }

  header("Location: profile.php");
  exit;
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
          <?php if (!empty($error)): ?>
            <div class="error-message">
              <?php echo $error; ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($success)): ?>
            <div class="success-message">
              <?php echo $success; ?>
            </div>
          <?php endif; ?>
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

            <input type="submit" value="Update Profile" class="btn">

          </form>

        </div>

      </div>

    </main>

  </div>

</body>

</html>