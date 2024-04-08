<?php
include '../lib/sql.php'; // Include the Mysql class file
include '../lib/constants.php'; // Include the Constants class file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $mysql = new Mysql();
  // Check if the user exists by email
  $result = $mysql->selectwhere('users', 'email', '=', $email, 'char');
  if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $hashedPassword = $user['hash_password'];

    // Verify the password
    if (password_verify($password, $hashedPassword)) {
      // Password is correct, create a session for the user
      session_start();
      $_SESSION['user'] = array(
        'user_id' => $user['id'],
        'email' => $user['email'],
        'user_type' => $user['user_type']
      );      // Add any other relevant user information to the session

      // Redirect the user to the dashboard or any other page
      if ($user['user_type'] == Constants::USER) {
        header("Location: /art/src/dashboard.php");
      } elseif ($user['user_type'] == Constants::ADMIN) {
        header("Location: /art/src/admin/dashboard.php");
      } else {
        header("Location: /art/src/gallery/dashboard.php");
      }
      exit();
    } else {
      // Incorrect password
      $error = "Incorrect credentials";
    }
  } else {
    // User not found
    $error = "User not found";
  }
  // Close the database connection
  $mysql->dbdisconnect();
} ?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="../assets/images/moon.png" />
  <link rel="stylesheet" href="../assets/css/register.css">

  <title>Login</title>
</head>

<body>
  <!-- Login form -->
  <div class="form-container">

    <?php if (isset($error)) : ?>
      <center>
        <div class="error-container">
          <span class="error" aria-live="polite"><?php echo $error; ?></span>
        </div>
      </center>
    <?php endif; ?>


    <form method="post" autocomplete="off" novalidate>
      <h1>Login</h1>

      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" class="form-control" name="email" placeholder="Enter your email" />
        <span class="error" aria-live="polite" id="email-error"></span>

      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" />
        <span class="error" aria-live="polite"></span>

      </div>

      <button class="submit-btn" name="login">Login</button>

      <div class="login">
        <p>Don't have an account? <a href="registration.php">Signup</a></p>
      </div>
    </form>
  </div>
  <script src="../assets/script/form_validation.js"></script>
</body>

</html>
