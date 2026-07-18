<?php
global $conn;
session_start();
require_once '../config/auth.php';
require_once '../config/db.php';
require_login();

if ($_SESSION['role'] != 'admin') {
  header("Location: ../login.php");
  exit();
}

$messageid = $_POST['messageid'] ?? null;

if (!$messageid) {
  header("Location: support_message.php");
  exit();
}

// Mark both is_read and is_solved as true
$sql = "UPDATE support_messages
        SET is_read = 1, is_solved = 1
        WHERE messageid = '$messageid'";

if (mysqli_query($conn, $sql)) {
  $_SESSION['success'] = 'Message marked as solved!';
} else {
  $_SESSION['error'] = 'Failed to update message.';
}

header("Location: view_message.php?messageid=$messageid");
exit();
