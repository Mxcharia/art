
<?php

include 'sql.php';

class Migration extends mysql
{
  // Method to create users table
  public function createUserTable()
  {
    $sql = "CREATE TABLE IF NOT EXISTS users (
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
}

?>
