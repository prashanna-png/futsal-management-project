<?php
global $conn;
session_start();

require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);


$currentPage = 'bookings';

$bookingid = $_GET['bookingid'];
$playerid = $_SESSION['userid'];

if (!isset($_GET['bookingid'])) {
  $_SESSION['error'] = "Invalid booking.";
  header("Location: my_bookings.php");
  exit;
}


$sql = "DELETE FROM booking
        WHERE bookingid='$bookingid'
        AND playerid='$playerid'";

if (mysqli_query($conn, $sql)) {

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['success'] = "Booking removed successfully.";
    } else {
        $_SESSION['error'] = "Booking not found.";
    }

} else {
    $_SESSION['error'] = "Failed to remove booking.";
}

header("Location: my_bookings.php");
exit;
