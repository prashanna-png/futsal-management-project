<?php
session_start();
require_once 'config/db.php';
$name='';
$email = "";
$phone = "";
$role = "";
$password = "";
$conform = "";

$error = $_SESSION['error'] ?? '';

if($_SERVER["REQUEST_METHOD"] == "POST"){
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
  } 
  elseif (!preg_match("/^[A-Za-z\s]+$/", $name)) {
    $_SESSION['error'] = "Only letters are allowed in name";
    header("Location: register.php");
    exit;
  } 
  elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format";
      header("Location: register.php");
      exit;
  } 
  elseif (!is_numeric($phone) || strlen($phone) != 10) {
    $_SESSION['error'] = "Please enter a valid 10-digit phone number";
    header("Location: register.php");
    exit;
  } 
  elseif ($role != "customer" && $role != "owner" && $role != "staff") {
    $_SESSION['error'] = "Please select a valid role";
    header("Location: register.php");
    exit;
  } 
  elseif ($password != $confirm) {
    $_SESSION['error'] = "Passwords do not match";
    header("Location: register.php");
    exit;
  }

  else{
    $check_email = "SELECT * FROM users where email='$email'";
    $result = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($result) > 0) {
      $_SESSION["error"] = "Email already exist";
    }
    else{
      $password = password_hash($password, PASSWORD_DEFAULT);
      $sql = "INSERT INTO users
      (name, email, phone, password, role)
      VALUES
      ('$name', '$email', '$phone', '$password', '$role')";

      if (mysqli_query($conn, $sql)) {
        header("Location: login.php?registered=1");
        exit;
      } 
      else {
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
  <title>User Authentication</title>
  <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
  <div class="container">
    <div class="left-pannel">
      <div class="logo">
        ⚽ FutsalHub
      </div>
      <div class="side text">
        <h2>Join the Community</h2>
        <p>
          Book futsal grounds, manage teams,
          and organize matches with one account.
        </p>
      </div>
    </div>
    <div class="right-pannel">
      <h1>Register</h1>
      <?php if(!empty($error)): ?>
          <div class="error-message">
              <?php echo $error; ?>
          </div>
      <?php endif; ?>
      <form action="" method="POST">
        <div class="name">
            <input type="text" id="name" name="name" placeholder="Name" required>
        </div>

        <input type="text" name="phone" placeholder="Phone Number" required>
        <select name="role"  placeholder="Select Role" required>
          <option value="" disabled selected>Select Role</option>
          <option value="customer">Player</option>
          <option value="owner">Futsal Owner</option>
          <option value="staff">Staff</option>
        </select>

        <input type="email" id="email" name="email" placeholder="Email">
        
        <input type="password" id="password" name="password" placeholder="Password">

        <input type="password" id="confirm" name="confirm_password" placeholder="Conform Password">

        <input type="submit" value="Register">
      </form>
      <p>Already have an account? <a href="login.php">Log in</a></p>
    </div>
  </div>
</body>

</html>