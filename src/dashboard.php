<?php
session_start();
if (!isset($_SESSION['user']))
  header("Location: /art/src/login.php");
include '../lib/sql.php';

$db = new Mysql();


?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="../assets/css/dashboard.css" />
</head>

<body>
  <header>
    <div class="logo">
      <a href="#"><img src="../assets/images/moon.png" alt="Logo"></a>
    </div>
    <div class="title">
      <h1>Fusion</h1>
    </div>
    <nav class="nav-links">
      <a href="#">Galleries</a>
      <a href="#">Art</a>
      <a href="#">Exhibits</a>
      <a href="#">Home</a>
    </nav>
    <div class="cart">
      <a href="#"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRuaQCz8N8GNnjjeA7ofPcPQY5k42c0UrfRnbRyUFilgA7MiEGGIZ_-1wUwVd_VzJh_ZqQ&usqp=CAU" alt="Cart"></a>
      <div class="ccalc">
        1
      </div>
    </div>
    <div class="hamburger-menu">&#9776;</div>
  </header>

  <center>
    <h2 style="font-size: 25px;">Recent Art pieces</h2>
  </center>
  <section class="product">
    <?php
    // Retrieve data from the 'art' table
    $artworks = $db->selectall('art');

    // Check if any artworks were retrieved
    if ($artworks) {
      // Loop through each artwork and generate HTML for product container
      while ($artwork = mysqli_fetch_assoc($artworks)) {
    ?>
        <div class="product-container">
          <div class="product-image">
            <img src="<?php echo $artwork['art_url']; ?>" alt="<?php echo $artwork['name']; ?>">
          </div>
          <div class="product-details">
            <h2><?php echo $artwork['name']; ?></h2>
            <div class="additional-details">
              <p><i><b>Artist name:</b></i> <?php echo $artwork['artist']; ?></p>
              <p><i><b>Price:</b></i> $<?php echo $artwork['price']; ?></p>
            </div>
            <button class="add-to-cart">&#43; Add to Cart</button>
          </div>
        </div>
    <?php
      }
    } else {
      echo "<p>No artworks found.</p>";
    }
    ?>
  </section>
  <center>
    <h2 style="font-size: 25px;">Upcoming Art Exhibits from Galleries</h2>
  </center>
  <section class="product">
    <?php
    // Retrieve data from the 'exhibit' table
    $events = $db->selectall('exhibit');
    // Check if any gallery event were retrieved
    if ($events) {
      // Loop through each art gallery event and generate HTML for product container
      while ($event = mysqli_fetch_assoc($events)) {
    ?>
        <div class="product-container">
          <div class="product-image">
            <img src="<?php echo $event['event_image_url']; ?>" alt="<?php echo $event['name']; ?>">
          </div>
          <div class="product-details">
            <h2><?php echo $event['name']; ?></h2>
            <div class="additional-details">
              <p><i><b>Description:</b></i> <?php echo $event['description']; ?></p>
              <p> <i><b>Price:</b></i>$<?php echo $event['price']; ?></p>
            </div>
            <button class="add-to-cart">&#43; Buy Ticket</button>
          </div>
        </div>
    <?php
      }
    } else {
      echo "<p>No event found.</p>";
    }
    ?>
  </section>

  <script src="../assets/script/app.js"></script>
</body>

</html>
