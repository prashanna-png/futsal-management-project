<?php
session_start();
require_once 'config/db.php';
require_once 'config/auth.php';
$name = '';
$email = "";
$phone = "";
$role = "";
$password = "";
$conform = "";

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = ($_POST['phone']);
  $role = $_POST['role'] ?? '';
  $password = $_POST['password'];
  $confirm = $_POST['confirm_password'];

  if ($name === '' || $email === '' || $phone === '' || $role === '' || $password === '' || $confirm === '') {
    $_SESSION['error'] = "All fields are required";
    header("Location: register.php");
    exit;
  } elseif (!preg_match("/^[A-Za-z\s]+$/", $name)) {
    $_SESSION['error'] = "Only letters are allowed in name";
    header("Location: register.php");
    exit;
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format";
    header("Location: register.php");
    exit;
  } elseif (!is_numeric($phone) || strlen($phone) != 10) {
    $_SESSION['error'] = "Please enter a valid 10-digit phone number";
    header("Location: register.php");
    exit;
  } elseif ($role != "customer" && $role != "owner" && $role != "staff") {
    $_SESSION['error'] = "Please select a valid role";
    header("Location: register.php");
    exit;
  } elseif ($password != $confirm) {
    $_SESSION['error'] = "Passwords do not match";
    header("Location: register.php");
    exit;
  } else {
    $check_email = "SELECT * FROM users where email='$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
      $_SESSION["error"] = "Email already exist";
    } else {
      $password = password_hash($password, PASSWORD_DEFAULT);
      $sql = "INSERT INTO users
      (name, email, phone, password, role)
      VALUES
      ('$name', '$email', '$phone', '$password', '$role')";

      if (mysqli_query($conn, $sql)) {
        header("Location: login.php?registered=1");
        exit;
      } else {
        $error = "Error: " . mysqli_error($conn);
      }
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
  <title>Register</title>
  <link rel="stylesheet" href="./assets/css/user.css">
</head>

<body>

  <a href="index.html" class="back-home">
    <span>&larr;</span>
    <span>Back to Home</span>
  </a>

  <div class="container">
    <div class="left-pannel">

      <div class="logo">
        <img src="./assets/logo/futzo-logo.png" alt="" width="50">

        <div>
          <h3>FutZo</h3>
          <span>Futsal Management System</span>
        </div>
      </div>

      <div class="side-text">

        <h2>Join FutZo ⚽</h2>

        <p>
          Create your account and start booking courts, managing arenas,
          and organizing matches in minutes.
        </p>

      </div>

      <div class="left-features">

        <div class="feature-item">
          <span>✓</span>
          <p>Book Courts Anytime</p>
        </div>

        <div class="feature-item">
          <span>✓</span>
          <p>Easy Team Management</p>
        </div>

        <div class="feature-item">
          <span>✓</span>
          <p>Fast & Secure Registration</p>
        </div>

      </div>

      <div class="left-stats">

        <div>
          <h3>2000+</h3>
          <span>Players</span>
        </div>

        <div>
          <h3>120+</h3>
          <span>Arenas</span>
        </div>

        <div>
          <h3>24/7</h3>
          <span>Support</span>
        </div>

      </div>

    </div>
    <div class="right-pannel">
      <h1>Register</h1>
      <?php if (!empty($error)): ?>
        <div class="error-message">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <form action="" method="POST">
        <div class="form-row">
          <input type="text" id="name" name="name" placeholder="Name" required>
          <input type="text" name="phone" placeholder="Phone Number" required>
        </div>

        <select name="role" required>
          <option value="" disabled selected>Select Role</option>
          <option value="customer">Player</option>
          <option value="owner">Futsal Owner</option>
          <option value="staff">Staff</option>
        </select>

        <input type="email" id="email" name="email" placeholder="Email" required>

        <div class="form-row">
          <input type="password" id="password" name="password" placeholder="Password" required>
          <input type="password" id="confirm" name="confirm_password" placeholder="Confirm Password" required>
        </div>

        <input type="submit" value="Register">
      </form>
      <p>Already have an account? <a href="login.php">Log in</a></p>
    </div>
  </div>


  <footer class="auth-footer">
    <p>&copy; 2026 FutZo. Built for players, owners & staff.</p>
  </footer>

</body>

</html>