<?php
session_start();
if (!isset($_SESSION['user']))
  header("Location: /art/src/login.php");
include '../../lib/services.php';
$user_id = $_SESSION['user']['user_id'];
$service = new Services($user_id);
$result =  $service->selectwhere('gallery', 'user_id', '=', $user_id);
$gallery = mysqli_fetch_assoc($result);

?>

<head>
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="icon" type="image/x-icon" href="../../assets/images/moon.png" />
  <title>Admin dashboard</title>
</head>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

  body {
    font-family: Poppins;
    line-height: 1.25;
  }

  .container {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px 30px;
  }


  .heading {
    width: 800px;
    height: 40px;
    /* border-radius: 15px; */
    background-color: #003060;
    color: white;
    padding: 10px;
    /* text-align: center; */
  }

  .info {
    width: 300px;
    height: 300px;
    border-radius: 15px;
    background-color: black;
    color: white;
    text-align: center;
  }

  .product-container {
    position: relative;
    display: inline-block;
  }

  .product-container:hover .sm {
    display: flex;
  }

  .sm {
    display: none;
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 10px;
    background-color: rgba(0, 0, 0, 0.5);
    transition: opacity 0.3s;
  }

  .sm a {
    text-decoration: none;
  }

  .sm button {
    padding: 20px 40px;
    border: none;
    border-radius: 5px;
    margin: 20px;
    font-size: 12px;
    cursor: pointer;
    height: auto;
    transition: background-color 0.3s, color 0.3s;
  }

  .sm button.edit {
    background-color: black;
    color: white;
  }

  .sm button.view-orders {
    background-color: #f1683a;
    color: white;
  }

  .sm button:hover {
    opacity: 0.8;
  }
</style>
<header>
  <div class="logo">
    <a href="/art/index.html"><img src="../../assets/images/moon.png" alt="Logo"></a>
  </div>
  <div class="title">
    <h1>Fusion</h1>
  </div>
  <nav class="nav-links">
    <a href="/art/src/logout.php">Logout</a>
    <a href="events.php">Event Reports</a>
    <a href="users.php">Users Reports</a>
    <a href="arts.php">Art Reports</a>
    <a href="orders.php">Orders Reports</a>
    <a href="/art/src/admin/dashboard.php">Home</a>
  </nav>
</header>
<br />
<br />
<div class="container">
  <div class="info">
    <p>Total No. of hosted events</p>
    <br />
    <br />
    <br />
    <br />
    <br />
    <h3>
      <?php
      echo $service->get_total_no_events_admin();
      ?>
    </h3>
  </div>
  <div class="info">
    <p>Total No. of Orders</p>
    <br />
    <br />
    <br />
    <br />
    <br />
    <h3>
      <?php
      echo $service->get_total_no_orders_admin();
      ?>
    </h3>
  </div>
  <div class="info">
    <p>Total No. of Art pieces</p>
    <br />
    <br />
    <br />
    <br />
    <br />
    <h3>
      <?php
      echo $service->get_total_no_art_pieces_admin();
      ?>
    </h3>
  </div>
  <div class="info">
    <p>Total No. of Revenue generated</p>
    <br />
    <br />
    <br />
    <br />
    <br />
    <h3>
      $
      <?php
      echo $service->get_total_no_earnings_admin();
      ?>
    </h3>
  </div>
</div>
<br />

<center>
  <h2 style="font-size: 25px;">Recent Art pieces</h2>
</center>
<section class="product">
  <?php
  // Retrieve data from the 'art' table
  $artworks = $service->selectall_sorted('art', 'date_created', 'DESC');
  $art_counter = 0; // Counter for art pieces

  // Check if any artworks were retrieved
  if ($artworks) {
    // Loop through each artwork and generate HTML for product container
    while ($artwork = mysqli_fetch_assoc($artworks)) {
      if ($art_counter >= 8) break; // Stop loop after six iterations
  ?>
      <div class="product-container">
        <a href="order.php?artid=<?php echo $artwork['id']; ?>">
          <div class="product-image">
            <img src="<?php echo $artwork['art_url']; ?>" alt="<?php echo $artwork['name']; ?>">
          </div>
          <div class="product-details">
            <h2><?php echo $artwork['name']; ?></h2>
            <div class="additional-details">
              <p><i><b>Artist name:</b></i> <?php echo $artwork['artist']; ?></p>
              <p><i><b>Price:</b></i> $<?php echo $artwork['price']; ?></p>
            </div>
          </div>
        </a>
        <div class="sm">
          <a href="art.php?id=<?php echo $artwork['id']; ?>"><button class="edit">Edit</button></a>
          <a href="order.php?artid=<?php echo $artwork['id']; ?>"><button class="view-orders">View Orders</button></a>
        </div>
      </div>
  <?php
      $art_counter++; // Increment art counter
    }
  } else {
    echo "<p>No artworks found.</p>";
  }
  ?>
</section>

<center>
  <h2 style="font-size: 25px;">Recent Art Exhibits</h2>
</center>
<section class="product">
  <?php
  // Retrieve data from the 'exhibit' table, sorted by event date
  $events = $service->selectall_sorted('exhibit', 'date_created', 'DESC');
  $event_counter = 0; // Counter for events

  // Check if any gallery events were retrieved
  if ($events) {
    // Loop through each art gallery event and generate HTML for product container
    while ($event = mysqli_fetch_assoc($events)) {
      if ($event_counter >= 8) break; // Stop loop after eight iterations
  ?>
      <div class="product-container">
        <a href="order.php?exhibitid=<?php echo $event['id']; ?>">
          <div class="product-image">
            <img src="<?php echo $event['event_image_url']; ?>" alt="<?php echo $event['name']; ?>">
          </div>
          <div class="product-details">
            <h2><?php echo $event['name']; ?></h2>
            <div class="additional-details">
              <p><i><b>Description:</b></i> <?php echo $event['description']; ?></p>
              <p><i><b>Date:</b></i> <?php echo $event['event_date']; ?></p>
              <p><i><b>Price:</b></i> $<?php echo $event['price']; ?></p>
            </div>
          </div>
        </a>
        <div class="sm">
          <a href="event.php?id=<?php echo $event['id']; ?>"><button class="edit">Edit</button></a>
          <a href="order.php?exhibitid=<?php echo $event['id']; ?>"><button class="view-orders">View Orders</button></a>
        </div>


      </div>
  <?php
      $event_counter++; // Increment event counter
    }
  } else {
    echo "<p>No events found.</p>";
  }
  ?>
</section>
