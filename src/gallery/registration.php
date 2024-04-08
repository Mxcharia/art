<?php
include '../../lib/sql.php'; // Include the Mysql class file
include '../../lib/constants.php'; // Include the Constants class file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $username = $_POST['uname'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Create an instance of the Mysql class
  $mysql = new Mysql();

  // Hash the password
  $passwordHash = password_hash($password, PASSWORD_DEFAULT);

  // Set user type - Assuming this is a regular user registration
  $userType = Constants::GALLERY;

  // Prepare the values array for insertion
  $values = array(
    'username' => array('val' => $username, 'type' => 's'), //  username is a string
    'email' => array('val' => $email, 'type' => 's'), //  email is a string
    'user_type' => array('val' => $userType, 'type' => 'i'), // user_type is an integer
    'hash_password' => array('val' => $passwordHash, 'type' => 's') //  hash_password is a string
  );

  // Call the insertInto method of the Mysql class
  $result = $mysql->insertInto('users', $values);

  // Check the result
  if ($result === true) {
    echo "<script>alert('Registration Successful!');</script>";
    echo "<script>window.location.href = 'login.php';</script>";
  } else {
    if (strpos($result, 'Email') !== false) {
      echo "<script>alert('Error occurred during registration: Email already exists.');</script>";
    } elseif (strpos($result, 'Username') !== false) {
      echo "<script>alert('Error occurred during registration: Username already exists.');</script>";
    } else {
      echo "<script>alert('Error occurred during registration: $result, register again.');</script>";
    }
  }

  // Close the database connection
  $mysql->dbdisconnect();
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="../../assets/images/moon.png" />
  <link rel="stylesheet" href="../../assets/css/register.css">
</head>

<body>


  <!-- Signup form -->

  <div class="form-container">


    <form method="post" autocomplete="off" novalidate>
      <h1>Signup</h1>
      <div class="form-group">
        <label for="uname">Username:</label>
        <input type="text" id="uname" class="form-control" name="uname" placeholder="Enter your username">
        <span class="error" aria-live="polite"></span>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" class="form-control" name="email" placeholder="Enter your email">
        <span class="error" aria-live="polite" id="email-error"></span>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password">
        <span class="error" aria-live="polite"></span>
      </div>

      <div class="form-group">
        <label for="cpassword">Confirm password:</label>
        <input type="password" id="cpassword" class="form-control" name="cpassword" placeholder="Confirm password">
        <span class="error" aria-live="polite"></span>
      </div>

      <button class="submit-btn" name="submit">Sign up</button>


      <div class="login">
        <p>Have an account?<a href="/art/src/login.php">Login</a></p>
      </div>


    </form>

  </div>
  <script src="../../assets/script/form_validation.js"></script>
</body>

</html>
