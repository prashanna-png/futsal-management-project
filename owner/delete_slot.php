<?php

session_start();
global $conn;

require_once '../config/auth.php';
require_once '../config/db.php';

require_login();

if ($_SESSION['role'] !== 'owner') {
  header('Location: ../login.php');
  exit;
}

$ownerid = $_SESSION['userid'];


$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$futsalid = $_GET['futsalid'];
$slotid = $_GET['slotid'];


$check = "SELECT slotid FROM timeslot JOIN futsal ON timeslot.futsalid=futsal.futsalid WHERE timeslot.slotid='$slotid' AND futsal.futsalid='$futsalid'";

$result = mysqli_query($conn, $check);
if (mysqli_num_rows($result) === 0) {
  $_SESSION['error'] = 'Unauthorized Access';
  header('Location: my_futsal.php');
  exit;
} else {

  $sql = "DELETE FROM timeslot WHERE futsalid='$futsalid' AND slotid='$slotid'";
  $result = mysqli_query($conn, $sql);

  if ($result) {
    $_SESSION['success'] = 'Time slot deleted successfully';
  } else {
    $_SESSION['error'] = 'Failed to delete Timeslot';
  }


  header("Location: manage_slot.php?futsalid=$futsalid");
  exit;
}
