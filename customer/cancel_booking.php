<?php
global $conn;
session_start();

require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);

if (!isset($_GET['bookingid'])) {
  $_SESSION['error'] = "Invalid booking.";
  header("Location: my_bookings.php");
  exit;
}

$currentPage = 'bookings';


$bookingid = $_GET['bookingid'];
$playerid = $_SESSION['userid'];

$sql = "SELECT * FROM booking WHERE bookingid='$bookingid' AND playerid = '$playerid'";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) == 0) {
  $_SESSION['error'] = "Booking not found.";
  header('location: my_bookings.php');
  exit;
}
$booking = mysqli_fetch_assoc($result);

if ($booking['status'] === 'completed' || $booking['status'] === 'cancelled') {
  $_SESSION['error'] = "This booking cannot be cancelled.";
  header("Location: my_bookings.php");
  exit;
}
if ($booking['status'] === 'pending') {
  $sql = "UPDATE booking SET status='cancelled' WHERE bookingid='$bookingid'";
  if (mysqli_query($conn, $sql)) {
    $_SESSION['success'] = 'Booking Cancelled Successfully';
    header("Location: my_bookings.php");
    exit;
  } else {
    $_SESSION['error'] = 'failed to cancel booking';
    header("Location: my_bookings.php");
    exit;
  }
}
$bookingDateTime = strtotime($booking['booking_date'] . ' ' . $booking['start_time']);

if ($bookingDateTime <= time()) {
  $_SESSION['error'] = "This booking can no longer be cancelled.";
  header("Location: my_bookings.php");
  exit;
}

$sql = "UPDATE booking SET status='cancelled' WHERE bookingid='$bookingid'";
if (mysqli_query($conn, $sql)) {
  $_SESSION['success'] = 'Booking Cancelled Successfully';
  header("Location: my_bookings.php");
  exit;
} else {
  $_SESSION['error'] = 'failed to cancel booking';
  header("Location: my_bookings.php");
  exit;
}
