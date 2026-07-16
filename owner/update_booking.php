<?php

session_start();
global $conn;
require_once '../config/db.php';
require_once '../config/auth.php';
require_login();
$currentPage = 'bookings';

$ownerid = $_SESSION['userid'];

$bookingid = $_GET['bookingid'] ?? null;
$action    = $_GET['action'] ?? null;

$allowedAction = ['confirm', 'reject', 'complete'];

if (!$bookingid || !$action || !in_array($action, $allowedAction)) {
  $_SESSION['error'] = 'Unauthorized Action.';
  header('Location: bookings.php');
  exit;
}

$bookingid_safe = mysqli_real_escape_string($conn, $bookingid);
$ownerid_safe   = mysqli_real_escape_string($conn, $ownerid);

$sql = "SELECT b.* FROM booking b 
        JOIN futsal f ON b.futsalid = f.futsalid 
        WHERE b.bookingid = '$bookingid_safe' AND f.ownerid = '$ownerid_safe'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
  $_SESSION['error'] = 'There\'s no such booking.';
  header('Location: bookings.php');
  exit;
}

$booking = mysqli_fetch_assoc($result);

// Debug line - uncomment to check what status is actually stored
// echo "Current status: " . $booking['status']; exit;

if ($booking['status'] === 'pending') {

  if ($action !== 'confirm' && $action !== 'reject') {
    $_SESSION['error'] = "Invalid action.";
    header("Location: bookings.php");
    exit;
  }
} elseif ($booking['status'] === 'confirmed') {

  if ($action !== 'complete') {
    $_SESSION['error'] = "Invalid action.";
    header("Location: bookings.php");
    exit;
  }
} else {
  $_SESSION['error'] = "This booking cannot be modified. (current status: " . htmlspecialchars($booking['status']) . ")";
  header("Location: bookings.php");
  exit;
}

$new_status = '';

if ($action === 'confirm') {
  $new_status = 'confirmed';
} elseif ($action === 'reject') {
  $new_status = 'cancelled';
} elseif ($action === 'complete') {
  $new_status = 'completed';
}

$sql = "UPDATE booking
        SET status = '$new_status'
        WHERE bookingid = '$bookingid_safe'";

if (mysqli_query($conn, $sql)) {
  $_SESSION['success'] = "Booking $new_status successfully.";
  header('Location: bookings.php');
  exit;
} else {
  $_SESSION['error'] = 'Failed to update status: ' . mysqli_error($conn);
  header('Location: bookings.php');
  exit;
}
