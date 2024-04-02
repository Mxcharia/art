<?php
session_start();
if (!isset($_SESSION['user']))
  header("Location: /art/src/login.php");
include '../lib/sql.php';

$db = new Mysql();
$user_id = $_SESSION['user']['user_id'];
if (isset($_POST['add_to_cart'])) {
  $art_id = $_POST['art_id'];
  // Check if the combination of user_id and art_id already exists in the cart
  $query = "SELECT * FROM cart WHERE user_id = $user_id AND art_id = $art_id";
  $cart_item = $db->selectfreerun($query);
  $cart_item = mysqli_fetch_assoc($cart_item);
  if ($cart_item) {
    // If the item already exists, update the quantity
    $quantity = $cart_item['quantity'] + 1;
    $db->freerun("UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND art_id = $art_id");
  } else {
    // If the item doesn't exist, insert a new record
    $db->freerun("INSERT INTO cart (user_id, art_id, quantity) VALUES ($user_id, $art_id, 1)");
  }
}

if (isset($_POST['add_to_cart_ticket'])) {
  $exhibit_id = $_POST['exhibit_id'];
  // Check if the combination of user_id and art_id already exists in the cart
  $query = "SELECT * FROM cart WHERE user_id = $user_id AND exhibit_id = $exhibit_id";
  $cart_item = $db->selectfreerun($query);
  $cart_item = mysqli_fetch_assoc($cart_item);
  if ($cart_item) {
    // If the item already exists, update the quantity
    $quantity = $cart_item['quantity'] + 1;
    $db->freerun("UPDATE cart SET quantity = $quantity WHERE user_id = $user_id AND exhibit_id = $exhibit_id");
  } else {
    // If the item doesn't exist, insert a new record
    $db->freerun("INSERT INTO cart (user_id, exhibit_id, quantity) VALUES ($user_id, $exhibit_id, 1)");
  }
}
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
        <?php
        $user_id = $_SESSION['user']['user_id'];
        $cart_details = $db->selectwhere('cart', 'user_id', '=', $user_id);
        $num_rows = mysqli_num_rows($cart_details);
        echo $num_rows;
        ?>
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
            <form method="POST">
              <input type="hidden" name="art_id" value="<?php echo $artwork['id']; ?>">
              <button type="submit" class="add-to-cart" name="add_to_cart">&#43; Add to Cart</button>
            </form>
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
              <p> <i><b>Price:</b></i> $<?php echo $event['price']; ?></p>
            </div>
            <form method="POST">
              <input type="hidden" name="exhibit_id" value="<?php echo $event['id']; ?>">
              <button type="submit" class="add-to-cart" name="add_to_cart_ticket">&#43; Add to Cart</button>
            </form>
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
