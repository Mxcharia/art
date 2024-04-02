<?php
session_start();
if (!isset($_SESSION['user']))
  header("Location: /art/src/login.php");
include '../../lib/services.php';
$user_id = $_SESSION['user']['user_id'];
$service = new Services($user_id);

// Check if the product ID is provided in the URL
if (isset($_GET['id'])) {
  // Sanitize the input to prevent SQL injection or other attacks
  $event_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

  $events = $service->selectwhere('exhibit', 'id', '=', $event_id);
  $event = mysqli_fetch_assoc($events);

  $galleries = $service->selectwhere('gallery', 'id', '=', $event['gallery_id']);
  $gallery = mysqli_fetch_assoc($galleries);
} else {
  echo "Event ID not provided";
}
if (isset($_POST['add_to_cart'])) {
  $event_id = $_POST['exhibit_id'];
  $qty = $_POST['qty'];
  $service->add_event_to_cart($event_id, $qty);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product View</title>
  <link rel="icon" type="image/x-icon" href="../../assets/images/moon.png" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />

  <style>
    .view-product-container {
      display: flex;
      max-width: 800px;
      /* Adjust as needed */
      width: 100%;
      background-color: #f2f2f2;
      border-radius: 10px;
      overflow: hidden;
      margin-top: 60px;
    }

    .view-product-image,
    .view-product-details {
      flex: 1;
    }

    .view-product-image img {
      display: block;
      width: 100%;
      object-fit: fill;
    }

    .view-product-details {
      padding: 20px;
    }

    .view-additional-details {
      margin-top: 10px;
    }

    .view-add-to-cart {
      background-color: black;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    @media (max-width: 768px) {
      .view-product-container {
        flex-direction: column;
      }

      .view-product-details {
        padding: 20px;
        text-align: center;
      }

      .view-product-image img {
        width: 100%;
        height: auto;
      }
    }
  </style>
</head>

<body>
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
  <center>
    <div class="view-product-container">
      <div class="view-product-image">
        <img src="<?php echo $event['event_image_url']; ?>" alt="<?php echo $event['name']; ?>">
      </div>
      <div class="view-product-details">
        <h2><?php echo $event['name']; ?></h2>
        <div class="view-additional-details">
          <p><i><b>Event description:</b></i> <?php echo $event['description']; ?></p>
          <p><i><b>Hosted by:</b></i> <?php echo $gallery['name']; ?></p>
          <p><i><b>Price:</b></i> $<?php echo $event['price']; ?></p>
        </div>
        <form method="POST">
          <input type="hidden" name="exhibit_id" value="<?php echo $event['id']; ?>">
          <label for="quantity">Quantity:</label>
          <input type="number" style="border-radius: 20px;width: 60px" id="quantity" name="qty" value="1" min="1">
          <button type="submit" class="view-add-to-cart" name="add_to_cart">&#43; Add ticket to cart</button>
        </form>
      </div>
    </div>
  </center>
</body>

</html>
