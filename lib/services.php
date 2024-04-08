<?php
include 'sql.php';

class Services extends Mysql
{
  public $user_id;
  function __construct($user_id)
  {
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
  function get_total_no_events()
  {
    $result =  $this->selectwhere('gallery', 'user_id', '=', $this->user_id);
    $gallery = mysqli_fetch_assoc($result);
    $event_result =  $this->selectwhere('exhibit', 'gallery_id', '=', $gallery['id']);
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
}
