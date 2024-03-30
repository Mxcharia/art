<?php

include 'config/config.php';

class mysql extends db_config
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

  function selectwhere($tablename, $rowname, $operator, $value, $valuetype)
  {
    $this->sqlquery = 'select * from ' . $tablename . ' where ' . $rowname . ' ' . $operator . ' ';
    if ($valuetype == 'int') {
      $this->sqlquery .= $value;
    } else if ($valuetype == 'char') {
      $this->sqlquery .= "'" . $value . "'";
    }
    $this->dataset = mysqli_query($this->connectionstring, $this->sqlquery);
    $this->sqlquery = null;
    return $this->dataset;
  }


  function insertinto($tablename, $values)
  {
    $i = null;

    $this->sqlquery = 'insert into ' . $tablename . ' values (';
    $i = 0;
    while ($values[$i]["val"] != null && $values[$i]["type"] != null) {
      if ($values[$i]["type"] == "char") {
        $this->sqlquery .= "'";
        $this->sqlquery .= $values[$i]["val"];
        $this->sqlquery .= "'";
      } else if ($values[$i]["type"] == 'int') {
        $this->sqlquery .= $values[$i]["val"];
      }
      $i++;
      if ($values[$i]["val"] != null) {
        $this->sqlquery .= ',';
      }
    }
    $this->sqlquery .= ')';
    mysqli_query($this->connectionstring, $this->sqlquery);
    return $this->sqlquery;
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
