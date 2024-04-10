<?php
session_start();
if (!isset($_SESSION['user']))
  header("Location: /art/src/login.php");
include '../../lib/services.php';
$user_id = $_SESSION['user']['user_id'];
$services = new Services($user_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="icon" type="image/x-icon" href="../../assets/images/moon.png" />
  <title>Order Summary</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    h2 {
      color: #333;
      text-align: center;
    }

    table {
      width: 75%;
      border-collapse: collapse;
      background-color: #fff;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin: 0 auto;
      /* Center the table */
    }

    th,
    td {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #333;
      color: #fff;
    }

    tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    tr:hover {
      background-color: #ddd;
    }

    td:last-child {
      text-align: center;
    }
  </style>
</head>

<body>
  <header>
    <div class="logo">
      <a href="/art/index.html"><img src="../../assets/images/moon.png" alt="Logo"></a>
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
        $cart_details = $services->selectwhere('cart', 'user_id', '=', $user_id);
        $num_rows = mysqli_num_rows($cart_details);
        echo $num_rows;
        ?>
      </div>
    </div>

  </header>

  <h2>Orders Report</h2>
  <table>
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Art ID</th>
        <th>Exhibit ID</th>
        <th>Price</th>
        <th>Paid</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $query = "SELECT * FROM `order` WHERE user_id = $user_id AND paid = 1";

      $result = $services->freerun($query);

      // Check if there is data available
      while ($order_art = mysqli_fetch_assoc($result)) {
        // Output data in a table row
        echo "<tr>";
        echo "<td>" . $order_art['id'] . "</td>";
        echo "<td>" . $order_art['art_id'] . "</td>";
        echo "<td>" . $order_art['exhibit_id'] . "</td>";
        echo "<td>$" . $order_art['price'] . "</td>";
        echo "<td>" . ($order_art['paid'] == 1 ? 'Yes' : 'No') . "</td>";
        echo "<td>" . $order_art['date_created'] . "</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>

  <h2>Uncompleted Orders Report</h2>
  <table>
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Art ID</th>
        <th>Exhibit ID</th>
        <th>Price</th>
        <th>Paid</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $query = "SELECT * FROM `order` WHERE user_id = $user_id AND paid = 0";

      $result = $services->freerun($query);

      // Check if there is data available
      while ($order_art = mysqli_fetch_assoc($result)) {
        // Output data in a table row
        echo "<tr>";
        echo "<td>" . $order_art['id'] . "</td>";
        echo "<td>" . $order_art['art_id'] . "</td>";
        echo "<td>" . $order_art['exhibit_id'] . "</td>";
        echo "<td>$" . $order_art['price'] . "</td>";
        echo "<td>" . ($order_art['paid'] == 1 ? 'Yes' : 'No') . "</td>";
        echo "<td>" . $order_art['date_created'] . "</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>

</body>

</html>
