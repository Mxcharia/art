<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="icon" type="image/x-icon" href="../../assets/images/moon.png" />
  <title>Users Summary</title>
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
      <a href="orders.php">Orders Reports</a>
      <a href="users.php">Users Reports</a>
      <a href="/art/src/admin/dashboard.php">Home</a>
    </nav>
  </header>

  <h2>Users Report</h2>
  <table>
    <thead>
      <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>User Type</th>
        <th>Created Date</th>
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
      class Constants
      {
        // represent admins
        const ADMIN = 1;
        // represent users 
        const USER = 2;
        // represent users that are gallery owners
        const GALLERY = 3;

        public static function getUserTypeName($userType)
        {
          switch ($userType) {
            case self::ADMIN:
              return 'Admin';
            case self::USER:
              return 'User';
            case self::GALLERY:
              return 'Gallery Owner';
            default:
              return 'Unknown';
          }
        }
      }
      // Create an instance of your service class
      $services = new Services($user_id);
      $result = $services->selectall('users');

      // Check if there is data available
      while ($user = mysqli_fetch_assoc($result)) {
        // Output data in a table row
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . Constants::getUserTypeName($user['user_type']) . "</td>";
        echo "<td>" . $user['created_date'] . "</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</body>

</html>
