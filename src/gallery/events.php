<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="icon" type="image/x-icon" href="../../assets/images/moon.png" />
  <title>Events Summary</title>
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

    .delete-btn {
      background-color: #ff6347;
      color: #fff;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .delete-btn:hover {
      background-color: #ff3b20;
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
      <a href="event_upload.php">New Event</a>
      <a href="art_upload.php">Upload Art</a>
      <a href="/art/src/gallery/dashboard.php">Home</a>
    </nav>

  </header>

  <h2>Exhibit Report</h2>
  <table>
    <thead>
      <tr>
        <th>Event ID</th>
        <th>Gallery ID</th>
        <th>Name</th>
        <th>Event Image</th>
        <th>Description</th>
        <th>Price</th>
        <th>Date Created</th>
        <th>Action</th>
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
      $gallery_result = $services->selectwhere('gallery', 'user_id', '=', $user_id);
      $gallery = mysqli_fetch_assoc($gallery_result);
      $result = $services->selectwhere('exhibit', 'gallery_id', '=', $gallery['id']);

      // Check if there is data available
      while ($event = mysqli_fetch_assoc($result)) {
        // Output data in a table row
        echo "<tr>";
        echo "<td>" . $event['id'] . "</td>";
        echo "<td>" . $event['gallery_id'] . "</td>";
        echo "<td>" . $event['name'] . "</td>";
        echo "<td><img src='" . $event['event_image_url'] . "' alt='Event Image' style='max-width: 100px; max-height: 100px;'></td>";
        echo "<td>" . $event['description'] . "</td>";
        echo "<td>$" . $event['price'] . "</td>";
        echo "<td>" . $event['date_created'] . "</td>";
        echo "<td><button class='delete-btn' onclick='confirmDelete(" . $event['id'] . ")'>Delete</button></td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
  <script>
    function confirmDelete(exhibitId) {
      if (confirm("Are you sure you want to delete this event?")) {
        window.location.href = "delete_event.php?id=" + exhibitId; // Redirect to delete script with art ID
      }
    }
  </script>

</body>

</html>
