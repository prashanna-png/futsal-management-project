<?php
session_start();
require_once 'config/db.php';
require_once 'config/auth.php';

$email = "";
$password = "";

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if ($email === '' || $password === '') {
    $_SESSION['error'] = "All fields are required";
    header("Location: login.php");
    exit;
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format";
    header("Location: login.php");
    exit;
  } else {
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
      $user = mysqli_fetch_assoc($result);

      if (password_verify($password, $user['password'])) {

        $_SESSION['userid'] = $user['userid'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        switch ($user['role']) {
          case 'admin':
            header("Location: admin/dashboard.php");
            break;
          case 'owner':
            header("Location: owner/dashboard.php");
            break;
          case 'staff':
            header("Location: staff/dashboard.php");
            break;
          default:
            header("Location: customer/dashboard.php");
            break;
        }
        exit;
      } else {
        $error = "Incorrect Password";
      }
    } else {
      $error = "User does not exist";
    }
  }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>login</title>
  <link rel="stylesheet" href="./assets/css/user.css">
</head>

<body>
  <div class="container">
    <div class="left-pannel">
      <div class="logo">
        ⚽ FutsalHub
      </div>
      <div class="side-text">
        <h2>Welcome Back</h2>
        <p>
          Login to your account to manage booking, teams, and matches.
        </p>
      </div>
    </div>
    <div class="right-pannel">
      <h1>Log In</h1>
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

      <form action="" method="POST">
        <input type="email" id="email" name="email" placeholder="Email">

        <input type="password" id="password" name="password" placeholder="Password">

        <div class="mid-box">
          <div class="remember-me">
            <input type="checkbox" id="rember" name="rember">
            <label for="remember">Remember me</label>
          </div>
          <a href="#" class="forgot-password">Forgot Password?</a>
        </div>

        <input type="submit" value="login">
      </form>
      <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
  </div>
</body>
<script src="./assets/js/main.js"></script>

</html>