<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HomePage</title>
  <link rel="stylesheet" href="assets/css/index.css">
</head>

<body>
  <header id="navbar">
    <nav class="navbar-container container">
      <a href="/" class="home-link">
        <div class="navbar-logo"></div>
        ART GALLERY
      </a>
      <button type="button" id="navbar-toggle" aria-controls="navbar-menu" aria-label="Toggle menu" aria-expanded="false">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div id="navbar-menu" aria-labelledby="navbar-toggle">
        <ul class="navbar-links">
          <li class="navbar-item"><a class="navbar-link" href="/about">About</a></li>
          <li class="navbar-item"><a class="navbar-link" href="/blog">Blog</a></li>
          <li class="navbar-item"><a class="navbar-link" href="/careers">Careers</a></li>
          <li class="navbar-item"><a class="navbar-link" href="/contact">Contact</a></li>
        </ul>
      </div>
    </nav>
  </header>
  <?php
  include './lib/sql.php';
  // Instantiate the mysql class
  $mysql = new mysql();
  // Call the selectall function with the appropriate table name
  $result = $mysql->selectall('users');

  if ($result) {
    // Check if there are rows returned
    if (mysqli_num_rows($result) > 0) {
      // Fetch associative array
      while ($row = mysqli_fetch_assoc($result)) {
        // Print column name
        echo "<p>" . $row['column_name'] . "</p>";
      }
    } else {
      echo "<p>No rows returned.</p>";
    }
  } else {
    // Print error message if there's an issue with the query
    echo "<p>Error: " . mysqli_error($connection) . "</p>";
  }
  ?>
  <script src="assets/script/index.js"></script>
</body>

</html>
