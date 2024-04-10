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
  </header>

  <center>
    <h2 style="font-size: 25px;">Upcoming Art Exhibits from Galleries</h2>
  </center>
  <section class="product">
    <?php
    // Retrieve data from the 'exhibit' table
    $events = $service->selectall('exhibit');
    // Check if any gallery event were retrieved
    if ($events) {
      // Loop through each art gallery event and generate HTML for product container
      while ($event = mysqli_fetch_assoc($events)) {
    ?>
        <div class="product-container">
          <a href="http://localhost/art/src/views/event.php?id=<?php echo $event['id']; ?>">
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
                <button type="submit" class="add-to-cart" name="add_to_cart_ticket">&#43; Add Ticket to Cart</button>
              </form>
            </div>
          </a>
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
