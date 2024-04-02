<?php

include '/srv/http/art/config/config.php';

class Mysql extends Db_config
{

  public $connectionstring;
  public $dataset;
  private $sqlquery;

  protected $databasename;
  protected $hostname;
  protected $username;
  protected $password;

  function __construct()
  {
    parent::__construct(); // Call the parent constructor


    $this->sqlquery = null;
    $this->dataset = null;

    $this->databasename = $this->dbname;
    $this->hostname = $this->servername;
    $this->username = $this->username;
    $this->password = $this->password;
    $this->connectionstring = mysqli_connect($this->hostname, $this->username, $this->password, $this->databasename);
  }

  function dbdisconnect()
  {
    $this->connectionstring = null;
    $this->sqlquery = null;
    $this->dataset = null;
    $this->databasename = null;
    $this->hostname = null;
    $this->username = null;
    $this->password = null;
  }

  function selectall($tablename)
  {
    $this->sqlquery = 'select * from ' . $this->databasename . '.' . $tablename;
    $this->dataset = mysqli_query($this->connectionstring, $this->sqlquery);
    return $this->dataset;
  }

  function selectwhere($tablename, $rowname, $operator, $value)
  {
    // Validate and sanitize input values

    $tablename = mysqli_real_escape_string($this->connectionstring, $tablename);
    $rowname = mysqli_real_escape_string($this->connectionstring, $rowname);
    $operator = mysqli_real_escape_string($this->connectionstring, $operator);
    $value = mysqli_real_escape_string($this->connectionstring, $value);

    // Prepare the SQL query using parameterized query
    $this->sqlquery = "SELECT * FROM $tablename WHERE $rowname $operator ?";

    // Prepare the statement
    $stmt = mysqli_prepare($this->connectionstring, $this->sqlquery);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, 's', $value);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result set
    $this->dataset = mysqli_stmt_get_result($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);

    return $this->dataset;
  }



  function insertInto($tableName, $values)
  {
    if (empty($values) || !is_array($values)) {
      return false; // Return false if values are empty or not an array
    }

    // Constructing the prepared statement
    $columns = array();
    $placeholders = array();
    $params = array();
    $types = '';

    foreach ($values as $column => $data) {
      $columns[] = $column;
      $placeholders[] = '?';
      $params[] = &$data['val']; // Note the use of reference here
      $types .= isset($data['type']) ? $data['type'] : 's'; // Use the specified type if available, otherwise default to 's' for string
    }

    $columnsString = implode(',', $columns);
    $placeholdersString = implode(',', $placeholders);
    $sql = "INSERT INTO $tableName ($columnsString) VALUES ($placeholdersString)";

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

    if (!$result && $tableName ==  'users') {
      // Check for integrity constraint violation
      $error_code = mysqli_errno($this->connectionstring);
      if ($error_code === 1062) {
        // Duplicate entry error code
        $error_message = mysqli_error($this->connectionstring);
        if (strpos($error_message, 'username') !== false) {
          return "Username already exists";
        } elseif (strpos($error_message, 'email') !== false) {
          return "Email already exists";
        } else {
          return "Duplicate entry error";
        }
      } else {
        return false; // Other execution error
      }
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    return true; // Insert successful
  }

  function selectfreerun($query)
  {
    $this->dataset = mysqli_query($this->connectionstring, $query);
    return $this->dataset;
  }

  function freerun($query)
  {
    return mysqli_query($this->connectionstring, $query);
  }
}
