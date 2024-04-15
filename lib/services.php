<?php
include 'sql.php';

class Services extends Mysql
{
  public $user_id;
  function __construct($user_id)
  {
    // initializes Mysql
    parent::__construct();

    $this->user_id = $user_id;
  }
  // $quantity argument can be null
  function add_art_to_cart($art_id, ?int $quantity)
  {
    $quantity = $quantity ?? 1;
    $query = "SELECT * FROM cart WHERE user_id = $this->user_id AND art_id = $art_id";
    $cart_item = $this->selectfreerun($query);
    $cart_item = mysqli_fetch_assoc($cart_item);
    if ($cart_item) {
      // If the item already exists, update the quantity
      $quantity = $cart_item['quantity'] + $quantity;
      $this->freerun("UPDATE cart SET quantity = $quantity WHERE user_id = $this->user_id AND art_id = $art_id");
    } else {
      // If the item doesn't exist, insert a new record
      $this->freerun("INSERT INTO cart (user_id, art_id, quantity) VALUES ($this->user_id, $art_id, $quantity)");
    }
  }

  function get_total_no_orders()
  {
    $gallery_result = $this->selectwhere('gallery', 'user_id', '=', $this->user_id);
    $gallery = mysqli_fetch_assoc($gallery_result);
    $count = 0;
    $exhibit_result = $this->selectwhere('exhibit', 'gallery_id', '=', $gallery['id']);
    while ($exhibit = mysqli_fetch_assoc($exhibit_result)) {
      $order_result = $this->selectwhere('`order`', 'exhibit_id', '=', $exhibit['id']);
      $count += mysqli_num_rows($order_result);
    }
    $art_result = $this->selectwhere('art', 'gallery_id', '=', $gallery['id']);
    while ($art = mysqli_fetch_assoc($art_result)) {
      $order_result = $this->selectwhere('`order`', 'art_id', '=', $art['id']);
      $count += mysqli_num_rows($order_result);
    }

    return $count;
  }
  function get_total_no_orders_admin()
  {
    $count = 0;
    $order_result = $this->selectall('`order`');
    $count += mysqli_num_rows($order_result);
    return $count;
  }
  function get_total_no_earnings()
  {
    $result =  $this->selectwhere('gallery', 'user_id', '=', $this->user_id);
    $gallery = mysqli_fetch_assoc($result);
    $art_result =  $this->selectwhere('art', 'gallery_id', '=', $gallery['id']);
    $event_result =  $this->selectwhere('exhibit', 'gallery_id', '=', $gallery['id']);
    $count  = (int)0;
    while ($events = mysqli_fetch_assoc($event_result)) {
      $result =  $this->selectwhere('`order`', 'exhibit_id', '=', $events['id']);
      $order_event = mysqli_fetch_assoc($result);
      $order_result = $this->selectwhere('transaction', 'order_id', '=', $order_event['id']);
      $order = mysqli_fetch_assoc($order_result);
      $count += $order['amount'];
    }
    while ($arts = mysqli_fetch_assoc($art_result)) {
      $result =  $this->selectwhere('`order`', 'art_id', '=', $arts['id']);
      $order_art = mysqli_fetch_assoc($result);
      $order_result = $this->selectwhere('transaction', 'order_id', '=', $order_art['id']);
      $order = mysqli_fetch_assoc($order_result);
      $count += $order['amount'];
    }
    return $count;
  }
  function get_total_no_earnings_admin()
  {
    $count = 0;
    $order_result = $this->selectall('transaction');
    while ($order = mysqli_fetch_assoc($order_result)) {
      $count += $order['amount'];
    }
    return $count;
  }
  function get_order_art_by_gallery($artid)
  {
    $result =  $this->selectwhere('`order`', 'art_id', '=', $artid);
    $order_art = mysqli_fetch_assoc($result);
    return $order_art;
  }

  function get_order_events_by_gallery($exhibitid)
  {
    $result =  $this->selectwhere('`order`', 'exhibit_id', '=', $exhibitid);
    $order_event = mysqli_fetch_assoc($result);
    return $order_event;
  }
  function get_total_no_art_pieces()
  {
    $result =  $this->selectwhere('gallery', 'user_id', '=', $this->user_id);
    $gallery = mysqli_fetch_assoc($result);
    $art_result =  $this->selectwhere('art', 'gallery_id', '=', $gallery['id']);
    $art_count = mysqli_num_rows($art_result);
    return $art_count;
  }
  function get_total_no_art_pieces_admin()
  {
    $art_result =  $this->selectall('art');
    $art_count = mysqli_num_rows($art_result);
    return $art_count;
  }
  function get_total_no_events()
  {
    $result =  $this->selectwhere('gallery', 'user_id', '=', $this->user_id);
    $gallery = mysqli_fetch_assoc($result);
    $event_result =  $this->selectwhere('exhibit', 'gallery_id', '=', $gallery['id']);
    $event_count = mysqli_num_rows($event_result);
    return $event_count;
  }
  function get_total_no_events_admin()
  {
    $event_result =  $this->selectall('exhibit');
    $event_count = mysqli_num_rows($event_result);
    return $event_count;
  }
  function reduce_art_to_cart($cart_id, ?int $quantity)
  {
    $quantity = $quantity ?? 1;
    $query = "SELECT * FROM cart WHERE user_id = $this->user_id AND id = $cart_id";
    $cart_item_result = $this->selectfreerun($query);
    // Check if the query was successful
    if ($cart_item_result) {
      $cart_item = mysqli_fetch_assoc($cart_item_result);
      if ($cart_item) {
        // If the item already exists, update the quantity
        if ((int)$cart_item['quantity'] <= 1) {
          $id = $cart_item["id"];
          $this->freerun("DELETE FROM cart WHERE id=$id");
        } else {
          $quantity = $cart_item['quantity'] - $quantity;
          $this->freerun("UPDATE cart SET quantity = $quantity WHERE user_id = $this->user_id AND id = $cart_id");
        }
      } else {
        // If cart item not found, handle it accordingly
        error_log("Cart item not found for user_id=$this->user_id and cart id=$cart_id");
      }
    } else {
      // Handle the case when the query fails
      error_log("Query failed: $query");
    }
  }

  function delete_art_from_cart($cart_id)
  {
    // Check if the query was successful
    if ((int)$cart_id) {
      // If the item already exists, update the quantity
      $this->freerun("DELETE FROM cart WHERE id=$cart_id");
    } else {
      // Handle the case when the query fails
      error_log("Query failed");
    }
  }
  function add_event_to_cart($exhibit_id, ?int $quantity)
  {
    if ($quantity === null) {
      $quantity = 1;
    }
    $query = "SELECT * FROM cart WHERE user_id = $this->user_id AND exhibit_id = $exhibit_id";
    $cart_item = $this->selectfreerun($query);
    $cart_item = mysqli_fetch_assoc($cart_item);
    if ($cart_item) {
      // If the item already exists, update the quantity
      $quantity = $cart_item['quantity'] + $quantity;
      $this->freerun("UPDATE cart SET quantity = $quantity WHERE user_id = $this->user_id AND exhibit_id = $exhibit_id");
    } else {
      // If the item doesn't exist, insert a new record
      $this->freerun("INSERT INTO cart (user_id, exhibit_id, quantity) VALUES ($this->user_id, $exhibit_id, $quantity)");
    }
  }
  /**
   * file upload.
   * @param $target_file target file to be uploaded.
   * @param $file_size size of the file.
   * @return int return 0 on success and message string on failure
   */
  function upload($target_file, $file_size)
  {
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["file-input"]["tmp_name"]);
    $uploadOk = 1;
    $error_message = "";

    if ($check !== false) {
      $uploadOk = 1;
    } else {
      $error_message = "File is not an image.";
      $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
      $error_message = "file already exists.";
      $uploadOk = 0;
    }

    // Check file size
    if ($file_size > 20971520) { // 20MB in bytes
      $error_message = "your file is too large.";
      $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, array("jpg", "png", "jpeg", "gif"))) {
      $error_message = "only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      $error_message = "Sorry, your file was not uploaded, $error_message";
    } else {
      // Ensure the target directory exists, if not, create it
      if (!file_exists(dirname($target_file))) {
        mkdir(dirname($target_file), 0755, true);
      }

      if (move_uploaded_file($_FILES["file-input"]["tmp_name"], $target_file)) {
        // "The file " . htmlspecialchars(basename($_FILES["file-input"]["name"])) . " has been uploaded.";
        return 0; // Success code
      } else {
        $error_message = "Sorry, there was an error uploading your file.";
        return $error_message; // Error message
      }
    }
    return $error_message; // Return the error message
  }


  function updateArt($art_id, $values)
  {
    if (empty($values) || !is_array($values)) {
      return false; // Return false if values are empty or not an array
    }

    // Constructing the prepared statement
    $set = '';
    $params = array();
    $types = '';

    foreach ($values as $column => $data) {
      $set .= $column . '=?, ';
      $params[] = &$data['val']; // Note the use of reference here
      $types .= isset($data['type']) ? $data['type'] : 's'; // Use the specified type if available, otherwise default to 's' for string
    }

    // Remove the trailing comma and space from $set
    $set = rtrim($set, ', ');

    $sql = "UPDATE art SET $set WHERE id = ?"; // Assuming your art table has 'id' as the primary key

    // Add art_id to $params array
    $params[] = $art_id;
    $types .= 'i'; // Assuming art_id is an integer

    // Prepare the statement
    $stmt = mysqli_prepare($this->connectionstring, $sql);
    if (!$stmt) {
      return false; // Error in preparing the statement
    }

    // Bind parameters
    // Add types as the first parameter
    array_unshift($params, $types);

    // Bind parameters using the ... operator to unpack the array
    if (!mysqli_stmt_bind_param($stmt, ...$params)) {
      return false; // Error in binding parameters
    }

    // Execute the statement
    $result = mysqli_stmt_execute($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);

    return $result; // Return true if update was successful, false otherwise
  }
  function updateEvent($event_id, $values)
  {
    if (empty($values) || !is_array($values)) {
      return false; // Return false if values are empty or not an array
    }

    // Constructing the prepared statement
    $set = '';
    $params = array();
    $types = '';

    foreach ($values as $column => $data) {
      $set .= $column . '=?, ';
      $params[] = &$data['val']; // Note the use of reference here
      $types .= isset($data['type']) ? $data['type'] : 's'; // Use the specified type if available, otherwise default to 's' for string
    }

    // Remove the trailing comma and space from $set
    $set = rtrim($set, ', ');

    $sql = "UPDATE exhibit SET $set WHERE id = ?"; // Assuming your art table has 'id' as the primary key

    // Add art_id to $params array
    $params[] = $event_id;
    $types .= 'i'; // Assuming art_id is an integer

    // Prepare the statement
    $stmt = mysqli_prepare($this->connectionstring, $sql);
    if (!$stmt) {
      return false; // Error in preparing the statement
    }

    // Bind parameters
    // Add types as the first parameter
    array_unshift($params, $types);

    // Bind parameters using the ... operator to unpack the array
    if (!mysqli_stmt_bind_param($stmt, ...$params)) {
      return false; // Error in binding parameters
    }

    // Execute the statement
    $result = mysqli_stmt_execute($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);

    return $result; // Return true if update was successful, false otherwise
  }
  protected function curlRequest($url, $headers = [], $data = null)
  {
    $ch = curl_init($url);
    $headers = (array) $headers;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if ($data !== null) {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    // Execute the curl request
    $response = curl_exec($ch);

    // Log request URL
    error_log("cURL Request URL: $url  = = = " . print_r(curl_getinfo($ch), true));

    if (!empty($headers)) {
      $headerString = implode(', ', $headers);
      error_log("cURL Request Headers: $headerString");
    }
    if ($data !== null) {
      error_log("cURL Request Data: " . print_r($data, true));
    }

    // Check for errors
    if (curl_errno($ch)) {
      $error = curl_error($ch);
      error_log("cURL Error: $error");
    }
    // Log response
    error_log("cURL Response: " . print_r($response, true));

    curl_close($ch);
    return $response;
  }


  function processPayment($contact, $amount, $orderNo, $config)
  {
    // Example code for payment processing
    $phone = "254{$contact}"; // Format phone number
    $amount = 1; // Example amount, replace with actual amount
    $access_token_url = ($config['env'] == "live") ? "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" : "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
    $credentials = base64_encode($config['key'] . ':' . $config['secret']);
    $access_token_response = $this->curlRequest($access_token_url, ['Authorization: Basic ' . $credentials]);
    error_log("access_token_response = " . $access_token_response);

    $access_token_data = json_decode($access_token_response, true);
    error_log("access_token = " . $access_token_data);
    $token = isset($access_token_data['access_token']) ? $access_token_data['access_token'] : null;
    error_log("token = " . $token);
    if ($token) {
      // Generate password
      $timestamp = date("YmdHis");
      $password = base64_encode($config['BusinessShortCode'] . $config['passkey'] . $timestamp);

      // Prepare request data
      $data = array(
        "BusinessShortCode" => $config['BusinessShortCode'],
        "Password" => $password,
        "Timestamp" => $timestamp,
        "TransactionType" => $config['TransactionType'],
        "Amount" => $amount,
        "PartyA" => $phone,
        "PartyB" => $config['BusinessShortCode'],
        "PhoneNumber" => $phone,
        "CallBackURL" => $config['CallBackURL'],
        "AccountReference" => $config['AccountReference'],
        "TransactionDesc" => $config['TransactionDesc'],
      );

      $data_string = json_encode($data);

      // Make payment request
      $endpoint = ($config['env'] == "live") ? "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest" : "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";
      $headers = array(
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
      );
      $payment_response = $this->curlRequest($endpoint, $headers, $data_string);

      // Handle payment response
      $payment_result = json_decode($payment_response, true);
      error_log("payment_result = " . $payment_result);
      if ($payment_result && $payment_result['ResponseCode'] === "0") {
        // Payment successful
        $MerchantRequestID = $payment_result['MerchantRequestID'];
        $CheckoutRequestID = $payment_result['CheckoutRequestID'];

        // Insert transaction details into the database
        $sql = "INSERT INTO `stk_transactions`(`order_no`, `amount`, `phone`, `CheckoutRequestID`, `MerchantRequestID`) VALUES ('$orderNo', '$amount', '$phone', '$CheckoutRequestID', '$MerchantRequestID')";
        $this->freerun($sql);

        return true;
      } else {
        // Payment failed
        return false;
      }
    } else {
      // Access token not obtained
      return false;
    }
  }

  function get_cart_total()
  {
    $total = 0;

    // Calculate the total value of items in the cart
    $query = "SELECT 
                SUM(cart.quantity * art.price) AS art_total,
                SUM(cart.quantity * exhibit.price) AS exhibit_total
              FROM cart 
              LEFT JOIN art ON cart.art_id = art.id 
              LEFT JOIN exhibit ON cart.exhibit_id = exhibit.id 
              WHERE cart.user_id = ?";
    $stmt = $this->connectionstring->prepare($query);
    $stmt->bind_param("i", $this->user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $art_total = $row['art_total'] ?? 0;
    $exhibit_total = $row['exhibit_total'] ?? 0;
    $total = $art_total + $exhibit_total;

    return $total;
  }


  public function createOrderFromCart($contact, $config)
  {
    // Select cart items for the user
    $cart_items = $this->selectwhere('cart', 'user_id', '=', $this->user_id);

    // Debug statement
    error_log("Selected cart items: " . $contact);

    // Check if cart items exist
    if ($cart_items) {
      // Begin a transaction
      $this->connectionstring->begin_transaction();

      // Flag to track overall success of all orders
      $success = true;
      $total_amount = 0; // Initialize total amount

      // Array to store payment information for each item
      $payment_info = array();

      while ($cart_item = $cart_items->fetch_assoc()) {
        // Extract cart item details
        $exhibit_id = $cart_item['exhibit_id'];
        $art_id = $cart_item['art_id'];
        $quantity = $cart_item['quantity'];

        // Calculate the price based on whether it's an exhibit or an art
        if ($exhibit_id !== null) {
          // If it's an exhibit, fetch its price
          $exhibit_price_result = $this->selectwhere('exhibit', 'id', '=', $exhibit_id);
          $exhibit_price_row = $exhibit_price_result->fetch_assoc();
          $price = $exhibit_price_row['price'];
        } elseif ($art_id !== null) {
          // If it's an art, fetch its price
          $art_price_result = $this->selectwhere('art', 'id', '=', $art_id);
          $art_price_row = $art_price_result->fetch_assoc();
          $price = $art_price_row['price'];
        }

        // Calculate item total and accumulate to total amount
        $item_total = $price * $quantity;
        $total_amount += $item_total;

        // Store payment information for this item
        $payment_info[] = array(
          'order_id' => null, // Placeholder for order ID
          'amount' => $item_total,
        );
      }
      $uuid = uniqid();
      // Process payment for the total amount
      $payment_result = $this->processPayment($contact, $total_amount, $uuid, $config);

      // Debug statement
      error_log("Payment result: $payment_result");

      if ($payment_result) {
        // Payment successful, now create orders
        foreach ($payment_info as &$payment_item) {
          // Insert order into database
          $stmt = $this->connectionstring->prepare("INSERT INTO `order` (user_id, exhibit_id, art_id, quantity, price, uuid,paid) 
                    VALUES (?, ?, ?, ?, ?,? , 0)"); // Set paid flag to 1
          $stmt->bind_param("iiidis", $this->user_id, $exhibit_id, $art_id, $quantity, $price, $uuid);
          $stmt->execute();

          // Check if order insertion was successful
          if ($stmt->affected_rows === 1) {
            $payment_item['order_id'] = $this->connectionstring->insert_id;
          } else {
            // Order insertion failed
            $success = false;
            break;
          }
        }

        // If all orders were created successfully, remove items from the cart
        if ($success) {
          // Remove items from the cart
          $delete_cart_items_query = "DELETE FROM `cart` WHERE user_id = ?";
          $delete_cart_items_stmt = $this->connectionstring->prepare($delete_cart_items_query);
          $delete_cart_items_stmt->bind_param("i", $this->user_id);
          $delete_cart_items_stmt->execute();

          $this->connectionstring->commit();
          return "All orders created successfully. Cart items removed.";
        } else {
          $this->connectionstring->rollback();
          return "Error creating orders";
        }
      } else {
        // Payment failed
        $this->connectionstring->rollback();
        return "Payment processing failed";
      }
    } else {
      return "No items in the cart";
    }
  }
}
