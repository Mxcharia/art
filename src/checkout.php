<?php
include '../lib/services.php';
include '../lib/constants.php';

session_start();
if (!isset($_SESSION['user']))
  header("Location: /art/src/login.php");
$user_id = $_SESSION['user']['user_id'];

// Initialize the Services class
$services = new Services($user_id); // Replace $user_id with the actual user ID

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the contact information from the form
  $contact = $_POST['contact'];

  // Process the payment
  $payment_result = $services->createOrderFromCart($contact, Constants::$config);
  error_log("result = " . $payment_result);
  // Handle the payment result
  // Payment successful
  // Additional processing if needed (e.g., creating orders)
  if ($payment_result === true) {
    // Orders created successfully
    echo "Payment successful. Orders created successfully.";
  } else {
    // Error creating orders
    echo "Payment successful, but there was an error creating orders: $payment_result";
  }
} else {
  // Handle the case when the form data is not submitted
  echo "Form data not submitted.";
}
