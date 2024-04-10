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
      <a href="events.php">Event Reports</a>
      <a href="arts.php">Art Reports</a>
      <a href="users.php">Users Reports</a>
      <a href="orders.php">Orders Reports</a>
      <a href="/art/src/admin/dashboard.php">Home</a>
    </nav>
  </header>

  <h2>Order Summary</h2>
  <table>
    <thead>
      <tr>
        <th>Order ID</th>
        <?php
        // Check if either 'exhibitid' or 'artid' parameter is set in the URL
        if (isset($_GET['exhibitid'])) {
          // Sanitize the input to prevent SQL injection or other attacks
          $exhibit_id = filter_input(INPUT_GET, 'exhibitid', FILTER_SANITIZE_NUMBER_INT);
          // If 'exhibitid' is set, display Exhibit ID column
          echo "<th>Exhibit ID</th>";
        } elseif (isset($_GET['artid'])) {
          // Sanitize the input to prevent SQL injection or other attacks
          $art_id = filter_input(INPUT_GET, 'artid', FILTER_SANITIZE_NUMBER_INT);
          // If 'artid' is set, display Art ID column
          echo "<th>Art ID</th>";
        } else {
          // If neither 'exhibitid' nor 'artid' parameter is set, redirect to the dashboard
          header("Location: /art/src/dashboard.php");
          exit(); // Stop further execution
        }
        ?>
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
      if ($exhibit_id) {
        $order_art = $services->get_order_events_by_gallery($exhibit_id);
      } elseif ($art_id) {
        $order_art = $services->get_order_art_by_gallery($art_id);
      }

      // Check if there is data available
      if ($order_art) {
        // Output data in a table row
        echo "<tr>";
        echo "<td>" . $order_art['id'] . "</td>";
        if (isset($exhibit_id)) {
          echo "<td>" . $order_art['exhibit_id'] . "</td>";
        } elseif (isset($art_id)) {
          echo "<td>" . $order_art['art_id'] . "</td>";
        }
        echo "<td>$" . $order_art['price'] . "</td>";
        echo "<td>" . ($order_art['paid'] == 1 ? 'Yes' : 'No') . "</td>";
        echo "<td>" . $order_art['date_created'] . "</td>";
        echo "</tr>";
      } else {
        echo "<tr><td colspan='5'>No data available</td></tr>";
      }
      ?>
    </tbody>
  </table>
</body>

</html>
