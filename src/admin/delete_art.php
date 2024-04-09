<?php
session_start();

if (!isset($_SESSION['user'])) {
  header("Location: /art/src/login.php");
  exit; // Stop script execution
}

include '../../lib/services.php';

$user_id = $_SESSION['user']['user_id'];

// Create an instance of your service class
$services = new Services($user_id);

// Check if the art ID is provided and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $artId = $_GET['id'];

  // Attempt to delete the art with the provided ID
  $deleted = $services->delete('art', "id = $artId");

  if ($deleted) {
    header("Location: arts.php");
    exit; // Stop script execution
  } else {
    echo "Error deleting art.";
  }
} else {
  echo "Invalid art ID.";
}
