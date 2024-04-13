<?php
session_start();
if (!isset($_SESSION['user']))
  header("Location: /art/src/login.php");
include '../../lib/services.php';
$user_id = $_SESSION['user']['user_id'];
$service = new Services($user_id);
$total_amount  =  $service->get_cart_total();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $cart_id = $_POST['cart_id'];
  $action = $_POST['action'];
  if ($action === 'reduce') {
    // Handle reducing quantity action
    $service->reduce_art_to_cart($cart_id, null);

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
  .modal {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.4);
    /* Black w/ opacity */
  }

  /* Modal Content/Box */
  .modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    /* Could be more or less, depending on screen size */
  }

  /* Close Button */
  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }

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
    margin-top: 70px;
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

  #checkoutBtn {
    float: right;
    margin-top: 20px;
    transform: translate(-50%);
    width: 120px;
    height: 40px;
    outline: none;
    border: none;
    background: black;
    cursor: pointer;
    font-size: 16px;
    color: white;
    border-radius: 5px;
    transition: .3s;
  }

  #checkBTN {
    width: 120px;
    height: 40px;
    outline: none;
    border: none;
    background: black;
    cursor: pointer;
    font-size: 16px;
    color: white;
    border-radius: 5px;
    transition: .3s;
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
    <a href="/art/src/logout.php">Logout</a>
    <a href="/art/src/art.php">Art</a>
    <a href="/art/src/views/orders.php">Orders</a>
    <a href="/art/src/exhibits.php">Exhibits</a>
    <a href="/art/src/dashboard.php">Home</a>
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
<button id="checkoutBtn" onclick="checkout()">Checkout</button>

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
  <div id="checkoutModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2>Checkout</h2>
      <form id="contactForm">
        <label for="contact">Contact:</label>
        <input type="number" id="contact" name="contact" required><br><br>
        <button id="checkBTN" type="submit">Submit</button>
      </form>
      <b>
        <p>Total Amount you will pay: $<span id="totalAmount"></span></p>
      </b>
    </div>
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
      if (confirm("Are you sure you want to delete this item from your cart?")) {
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
    }

    function checkout() {
      openModal()
    }

    function openModal() {
      var modal = document.getElementById('checkoutModal');
      modal.style.display = 'block';

      // Calculate and display total amount
      var totalAmount = getTotalAmount()
      document.getElementById('totalAmount').textContent = totalAmount;
    }

    // Function to close the modal
    function closeModal() {
      var modal = document.getElementById('checkoutModal');
      modal.style.display = 'none';
    }

    // Function to calculate total amount
    function getTotalAmount() {
      return <?php echo $total_amount; ?>;
    }


    // Function to handle form submission (you can adjust this according to your needs)
    document.getElementById('contactForm').addEventListener('submit', function(event) {
      event.preventDefault();
      var contact = document.getElementById('contact').value;
      // Send the contact information to the server or process it as needed
      var formData = new FormData();
      formData.append('contact', contact);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '../checkout.php', true);
      xhr.onload = function() {
        // Handle the response from the server
      };
      xhr.send(formData);
      closeModal();
    });
  </script>
