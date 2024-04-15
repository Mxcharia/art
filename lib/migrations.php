<?php

include 'sql.php';

class Migration extends Mysql
{
  // Method to create users table
  public function createUsersTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `users` (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(80) NOT NULL UNIQUE,
            email VARCHAR(80) NOT NULL UNIQUE,
            user_type INT(11) NOT NULL,
            hash_password VARCHAR(80) NOT NULL,
            created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
    if ($this->freerun($sql)) {
      return "Table users created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create gallery table
  public function createGalleryTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `gallery` (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6) UNSIGNED NOT NULL,
            name VARCHAR(255),
            location VARCHAR(255),
            description VARCHAR(255),
            date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table gallery created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create art table
  public function createArtTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `art` (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            gallery_id INT(6) UNSIGNED NOT NULL,
            name VARCHAR(255),
            art_url VARCHAR(255),
            artist VARCHAR(255),
            description VARCHAR(255),
            price FLOAT,
            date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (gallery_id) REFERENCES gallery(id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table art created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create exhibit table
  public function createExhibitTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `exhibit` (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            gallery_id INT(6) UNSIGNED NOT NULL,
            name VARCHAR(255),
            event_image_url VARCHAR(255),
            description VARCHAR(255),
            price FLOAT,
            date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (gallery_id) REFERENCES gallery(id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table exhibit created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create cart table
  public function createCartTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `cart` (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6) UNSIGNED NOT NULL,
            exhibit_id INT(6) UNSIGNED,
            art_id INT(6) UNSIGNED,
            quantity INT,
            date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (exhibit_id) REFERENCES exhibit(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (art_id) REFERENCES art(id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table cart created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create order table
  public function createOrderTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `order` (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6) UNSIGNED,
            exhibit_id INT(6) UNSIGNED,
            art_id INT(6) UNSIGNED,
            uuid VARCHAR(50),
            quantity INT,
            price INT,
            paid BOOLEAN,
            date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (exhibit_id) REFERENCES exhibit(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (art_id) REFERENCES art(id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table order created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create transaction table
  public function createTransactionTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `transaction` (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6) UNSIGNED NOT NULL,
            order_id INT(6) UNSIGNED NOT NULL,
            amount FLOAT NOT NULL,
            date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (order_id) REFERENCES `order`(id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table transaction created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create gallery_art table
  public function createGalleryArtTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `gallery_art` (
            gallery_id INT(6) UNSIGNED,
            art_gallery_id INT(6) UNSIGNED,
            PRIMARY KEY (gallery_id, art_gallery_id),
            FOREIGN KEY (gallery_id) REFERENCES gallery(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (art_gallery_id) REFERENCES art(gallery_id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table gallery_art created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create gallery_exhibit table
  public function createGalleryExhibitTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `gallery_exhibit` (
            gallery_id INT(6) UNSIGNED,
            exhibit_gallery_id INT(6) UNSIGNED,
            PRIMARY KEY (gallery_id, exhibit_gallery_id),
            FOREIGN KEY (gallery_id) REFERENCES gallery(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (exhibit_gallery_id) REFERENCES exhibit(gallery_id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table gallery_exhibit created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create users_cart table
  public function createUsersCartTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `users_cart` (
            users_id INT(6) UNSIGNED,
            cart_user_id INT(6) UNSIGNED,
            PRIMARY KEY (users_id, cart_user_id),
            FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (cart_user_id) REFERENCES cart(user_id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table users_cart created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create exhibit_cart table
  public function createExhibitCartTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `exhibit_cart` (
            exhibit_id INT(6) UNSIGNED,
            cart_exhibit_id INT(6) UNSIGNED,
            PRIMARY KEY (exhibit_id, cart_exhibit_id),
            FOREIGN KEY (exhibit_id) REFERENCES exhibit(id) ON DELETE CASCADE ON UPDATE CASCADE, 
            FOREIGN KEY (cart_exhibit_id) REFERENCES cart(exhibit_id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table exhibit_cart created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create users_order table
  public function createUsersOrderTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `users_order` (
            users_id INT(6) UNSIGNED,
            order_id INT(6) UNSIGNED,
            PRIMARY KEY (users_id, order_id),
            FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (order_id) REFERENCES `order`(user_id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table users_order created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create exhibit_order table
  public function createExhibitOrderTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `exhibit_order` (
            exhibit_id INT(6) UNSIGNED,
            order_exhibit_id INT(6) UNSIGNED,
            PRIMARY KEY (exhibit_id, order_exhibit_id),
            FOREIGN KEY (exhibit_id) REFERENCES exhibit(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (order_exhibit_id) REFERENCES `order`(exhibit_id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table exhibit_order created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create art_order table
  public function createArtOrderTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `art_order` (
            art_id INT(6) UNSIGNED,
            order_art_id INT(6) UNSIGNED,
            PRIMARY KEY (art_id, order_art_id),
            FOREIGN KEY (art_id) REFERENCES art(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (order_art_id) REFERENCES `order`(art_id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table art_order created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create users_transaction table
  public function createUsersTransactionTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `users_transaction` (
            users_id INT(6) UNSIGNED,
            transaction_user_id INT(6) UNSIGNED,
            PRIMARY KEY (users_id, transaction_user_id),
            FOREIGN KEY (users_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (transaction_user_id) REFERENCES transaction(user_id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table users_transaction created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }

  // Method to create order_transaction table
  public function createOrderTransactionTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `order_transaction` (
            order_id INT(6) UNSIGNED,
            transaction_order_id INT(6) UNSIGNED,
            PRIMARY KEY (order_id, transaction_order_id),
            FOREIGN KEY (order_id) REFERENCES `order`(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (transaction_order_id) REFERENCES transaction(order_id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

    if ($this->freerun($sql)) {
      return "Table order_transaction created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }
  public function createStkTransactionsTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `stk_transactions` (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            order_no VARCHAR(255) NOT NULL,
            amount FLOAT NOT NULL,
            phone VARCHAR(20) NOT NULL,
            CheckoutRequestID VARCHAR(255) NOT NULL,
            MerchantRequestID VARCHAR(255) NOT NULL,
            date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

    if ($this->freerun($sql)) {
      return "Table stk_transactions created successfully";
    } else {
      return "Error creating table: " . mysqli_error($this->connectionstring);
    }
  }
  // Method to create all tables
  public function createAllTables()
  {
    $output = "";

    // Array to store table creation functions
    $tableCreationFunctions = array(
      "createUsersTable",
      "createGalleryTable",
      "createArtTable",
      "createExhibitTable",
      "createCartTable",
      "createOrderTable",
      "createTransactionTable",
      "createGalleryArtTable",
      "createGalleryExhibitTable",
      "createUsersCartTable",
      "createExhibitCartTable",
      "createUsersOrderTable",
      "createExhibitOrderTable",
      "createArtOrderTable",
      "createUsersTransactionTable",
      "createOrderTransactionTable",
      "createStkTransactionsTable"
    );

    // Iterate through table creation functions and execute them
    foreach ($tableCreationFunctions as $functionName) {
      $result = $this->$functionName();
      $output .= $result . "\n";
    }

    return $output;
  }
  // only use this method when you want to migrate down
  public function dropAllTables()
  {
    $output = "";

    // Array to store table names in reverse order to drop child tables first
    $tableNames = array(
      "stk_transactions",
      "order_transaction",
      "users_transaction",
      "art_order",
      "exhibit_order",
      "users_order",
      "exhibit_cart",
      "users_cart",
      "gallery_exhibit",
      "gallery_art",
      "transaction",
      "`order`",
      "cart",
      "art",
      "exhibit",
      "gallery",
      "users"
    );

    // Iterate through table names and drop each table
    foreach ($tableNames as $tableName) {
      $sql = "DROP TABLE IF EXISTS $tableName";
      if ($this->freerun($sql)) {
        $output .= "Table $tableName dropped successfully\n";
      } else {
        $output .= "Error dropping table $tableName: " . mysqli_error($this->connectionstring) . "\n";
      }
    }

    return $output;
  }
}
