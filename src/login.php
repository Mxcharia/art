<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="register.css" />
 <link rel="icon" type="image/x-icon" href="assets/images/moon.png" />

    <title>Login</title>
  </head>

  <body>
    <!-- Login form -->

    <div class="form-container">
      <form action="login.php" method="post" autocomplete="off" novalidate>
        <h1>Login</h1>

        <div class="form-group">
          <label for="email">Email:</label>
          <input
            type="email"
            id="email"
            class="form-control"
            name="email"
            placeholder="Enter your email"
          />
        </div>

        <div class="form-group">
          <label for="password">Password:</label>
          <input
            type="password"
            id="password"
            class="form-control"
            name="password"
          />
        </div>

        <button class="submit-btn" name="login">Login</button>

        <div class="login">
          <p>Don't have an account? <a href="registration.php">Signup</a></p>
        </div>
      </form>
    </div>
  </body>
</html>
