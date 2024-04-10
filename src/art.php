<?php
session_start();
if (!isset($_SESSION['user']))
  header("Location: /art/src/login.php");
include '../lib/services.php';
$user_id = $_SESSION['user']['user_id'];
$service = new Services($user_id);
if (isset($_POST['add_to_cart'])) {
  $art_id = $_POST['art_id'];
  $service->add_art_to_cart($art_id, null);
}
if (isset($_POST['add_to_cart_ticket'])) {
  $exhibit_id = $_POST['exhibit_id'];
  $service->add_event_to_cart($exhibit_id, null);
}
?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="../assets/css/dashboard.css" />
  <link rel="icon" type="image/x-icon" href="../assets/images/moon.png" />

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
      <a href="/art/src/logout.php">Logout</a>
      <a href="/art/src/art.php">Art</a>
      <a href="/art/src/views/orders.php">Orders</a>
      <a href="/art/src/exhibits.php">Exhibits</a>
      <a href="/art/src/dashboard.php">Home</a>
    </nav>
    <div class="cart">
      <a href="/art/src/views/cart.php"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRuaQCz8N8GNnjjeA7ofPcPQY5k42c0UrfRnbRyUFilgA7MiEGGIZ_-1wUwVd_VzJh_ZqQ&usqp=CAU" alt="Cart"></a>
      <div class="ccalc">
        <?php
        $user_id = $_SESSION['user']['user_id'];
        $cart_details = $service->selectwhere('cart', 'user_id', '=', $user_id);
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
    $artworks = $service->selectall('art');

    // Check if any artworks were retrieved
    if ($artworks) {
      // Loop through each artwork and generate HTML for product container
      while ($artwork = mysqli_fetch_assoc($artworks)) {
    ?>
        <div class="product-container">
          <a href="http://localhost/art/src/views/product.php?id=<?php echo $artwork['id']; ?>">
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
          </a>
        </div>
    <?php
      }
    } else {
      echo "<p>No artworks found.</p>";
    }
    ?>
  </section>
  <script src="../assets/script/app.js"></script>
</body>

</html>
