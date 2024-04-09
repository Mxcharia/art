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
      <a href="events.php">Event Reports</a>
      <a href="arts.php">Art Reports</a>
      <a href="orders.php">Orders Reports</a>
      <a href="/art/src/admin/dashboard.php">Home</a>
    </nav>
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
      // Include your PHP script
      session_start();
      if (!isset($_SESSION['user']))
        header("Location: /art/src/login.php");
      include '../../lib/services.php';
      $user_id = $_SESSION['user']['user_id'];

      // Create an instance of your service class
      $services = new Services($user_id);
      $result = $services->selectall('`order`');

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
