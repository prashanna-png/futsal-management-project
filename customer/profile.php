<?php
require_once '../config/auth.php';
require_once '../config/auth.php';

require_login();
$currentPage = 'profile';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Check if it's a password change request
  $update_type = $_POST['update_type'] ?? 'profile';

  if ($update_type === 'password') {
    // Handle password change only
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    if (!password_verify($current_password, $user['password'])) {
      $_SESSION['error'] = "Invalid current password.";
      header("Location: profile.php");
      exit;
    }

    // Validate new password
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

    // Update only password
    $sql = "UPDATE users SET password='$password' WHERE userid='$current_user'";

    if (mysqli_query($conn, $sql)) {
      $_SESSION['success'] = "Password updated successfully.";
    } else {
      $_SESSION['error'] = "Failed to update password.";
    }

    header("Location: profile.php");
    exit;
  } else {
    // Handle profile update (name, email, phone) - requires password verification
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $current_password = $_POST['current_password'];

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

    // Verify current password (required for security when updating sensitive info)
    if (empty($current_password)) {
      $_SESSION['error'] = "Please enter your current password to update profile information.";
      header("Location: profile.php");
      exit;
    }

    if (!password_verify($current_password, $user['password'])) {
      $_SESSION['error'] = "Invalid current password.";
      header("Location: profile.php");
      exit;
    }

    // Update profile information (without changing password)
    $sql = "UPDATE users 
            SET name='$name', email='$email', phone='$phone' 
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
}

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

          <!-- Profile Update Form -->
          <h3>Personal Information</h3>

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

          <form action="" method="POST" novalidate>
            <input type="hidden" name="update_type" value="profile">

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

            <!-- Password required for profile update -->
            <div class="form-group">
              <label>Current Password <span style="color: red;">*</span></label>
              <input
                type="password"
                name="current_password"
                placeholder="Enter current password to update profile">
              <small style="color: #666;">Password is required to update profile information</small>
            </div>

            <button type="submit" class="btn">Update Profile</button>
          </form>

          <hr style="margin: 30px 0;">

          <!-- Password Change Form -->
          <h3>Change Password</h3>

          <form action="" method="POST" novalidate>
            <input type="hidden" name="update_type" value="password">

            <div class="form-group">

              <label>Current Password</label>

              <input
                type="password"
                name="current_password"
                required>

            </div>

            <div class="row">

              <div class="form-group">

                <label>New Password</label>

                <input
                  type="password"
                  name="new_password"
                  required>

              </div>

              <div class="form-group">

                <label>Confirm Password</label>

                <input
                  type="password"
                  name="confirm_password"
                  required>

              </div>

            </div>
            <button type="submit" class="btn">Change Password</button>
          </form>

        </div>

      </div>

    </main>


  </div>

</body>

</html>