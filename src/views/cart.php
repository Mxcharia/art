<?php
session_start();
if (!isset($_SESSION['user']))
  header("Location: /art/src/login.php");
include '../../lib/services.php';
$user_id = $_SESSION['user']['user_id'];
$service = new Services($user_id);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $cart_id = $_POST['cart_id'];
  $action = $_POST['action'];

  if ($action === 'reduce') {
    // Handle reducing quantity action
    $service->reduce_art_to_cart($cart_id, null);

    header("Refresh:0");
    exit(); // Exit after handling the request
  } elseif ($action === 'delete') {
    // Handle delete from cart action

    $service->delete_art_from_cart($cart_id);
    header("Refresh:0");
    exit(); // Exit after handling the request
  }
}
$cart_details = $service->selectwhere('cart', 'user_id', '=', $user_id);
?>

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title>Cart</title>
  <link rel="icon" type="image/x-icon" href="../../assets/images/moon.png" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />

</head>

<style>
  #cartcontainer {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
  }

  .cartitem {
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 30px;
  }

  .cartitem img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 1px solid #ddd;
  }

  .cartitemdetails {
    padding: 20px;
  }

  .cartitemdetails h3 {
    margin-top: 0;
  }

  .cartitemdetails p {
    margin: 10px 0;
  }

  .action-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 10px;
  }

  .action-buttons button {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .reducebtn {
    background-color: #4caf50;
    color: white;
  }

  .deletebtn {
    background-color: #f44336;
    color: white;
  }

  .reducebtn:hover,
  .deletebtn:hover {
    background-color: #333;
  }
</style>
<header>
  <div class="logo">
    <a href="#"><img src="../../assets/images/moon.png" alt="Logo"></a>
  </div>
  <div class="title">
    <a href="/art/src/dashboard.php" style="text-decoration: none;color: black;">
      <h1>Fusion</h1>
    </a>
  </div>
  <nav class="nav-links">
    <a href="#">Galleries</a>
    <a href="/art/src/art.php">Art</a>
    <a href="/art/src/exhibits.php">Exhibits</a>
    <a href="/art/src/dashboard.php" style="text-decoration: none;color: black;">Home</a>
  </nav>
  <div class="cart">
    <a href="#"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRuaQCz8N8GNnjjeA7ofPcPQY5k42c0UrfRnbRyUFilgA7MiEGGIZ_-1wUwVd_VzJh_ZqQ&usqp=CAU" alt="Cart"></a>
    <div class="ccalc">
      <?php
      $cart_details = $service->selectwhere('cart', 'user_id', '=', $user_id);
      $num_rows = mysqli_num_rows($cart_details);
      echo $num_rows;
      ?>
    </div>
  </div>
  <div class="hamburger-menu">&#9776;</div>
</header>

<div id="cartcontainer">
  <?php
  $art_items = array();
  $exhibit_items = array();

  while ($cart = mysqli_fetch_assoc($cart_details)) {
    $events = $service->selectwhere('exhibit', 'id', '=', $cart['exhibit_id']);
    $event = mysqli_fetch_assoc($events);
    $artworks = $service->selectwhere('art', 'id', '=', $cart['art_id']);
    $artwork = mysqli_fetch_assoc($artworks);
    $galleries = $service->selectwhere('gallery', 'id', '=', $event['gallery_id']);
    $gallery = mysqli_fetch_assoc($galleries);

    if (!empty($event)) {
      $exhibit_items[] = array(
        'event' => $event,
        'gallery' => $gallery,
        'cart' => $cart
      );
    } else {
      $art_items[] = array(
        'artwork' => $artwork,
        'cart' => $cart
      );
    }
  }

  foreach ($art_items as $item) {
    $artwork = $item['artwork'];
    $cart = $item['cart'];
  ?>
    <div class="cartitem">
      <img src="<?php echo $artwork['art_url']; ?>" alt="<?php echo $artwork['name']; ?>">
      <div class="cartitemdetails">
        <h3><?php echo $artwork['name']; ?></h3>
        <p>Artist: <?php echo $artwork['artist']; ?></p>
        <p>Price: $<?php echo $artwork['price']; ?></p>
        <p>Quantity: <?php echo $cart['quantity']; ?></p>
        <div class="action-buttons">
          <button class="reducebtn" onclick="reduceQuantity(<?php echo $cart['id']; ?>)"><i class="fas fa-minus"></i></button>
          <button class="deletebtn" onclick="deleteFromCart(<?php echo $cart['id']; ?>)"><i class="fas fa-trash-alt"></i></button>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php foreach ($exhibit_items as $item) {
    $event = $item['event'];
    $gallery = $item['gallery'];
    $cart = $item['cart'];
  ?>
    <div class="cartitem">
      <img src="<?php echo $event['event_image_url']; ?>" alt="<?php echo $event['name']; ?>">
      <div class="cartitemdetails">
        <h3><?php echo $event['name']; ?></h3>
        <p>Hosted by: <?php echo $gallery['name']; ?></p>
        <p>Price: $<?php echo $event['price']; ?></p>
        <p>Quantity: <?php echo $cart['quantity']; ?></p>
        <div class="action-buttons">
          <button class="reducebtn" onclick="reduceQuantity(<?php echo $cart['id']; ?>)"><i class="fas fa-minus"></i></button>
          <button class="deletebtn" onclick="deleteFromCart(<?php echo $cart['id']; ?>)"><i class="fas fa-trash-alt"></i></button>
        </div>
      </div>
    </div>
  <?php } ?>
</div>


<script>
  function reduceQuantity(cartId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          // Update UI if request is successful
          location.reload();
        } else {
          console.error('Error: ' + xhr.status);
        }
      }
    };
    xhr.open('POST', 'cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('cart_id=' + cartId + '&action=reduce');
  }

  function deleteFromCart(cartId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          // Update UI if request is successful
          location.reload();
        } else {
          console.error('Error: ' + xhr.status);
        }
      }
    };
    xhr.open('POST', 'cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('cart_id=' + cartId + '&action=delete');
  }
</script>
