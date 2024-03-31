<?php
class Db_config
{
  protected $servername;
  protected $username;
  protected $password;
  protected $dbname;
  function __construct()
  {
    $this->servername = 'localhost';
    $this->username = 'root';
    $this->password = '';
    $this->dbname = 'art_gallery';
  }
}
