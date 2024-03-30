<?php
include './lib/migrations.php';
echo $_SERVER['HTTP_HOST'];
$migration = new Migration();
echo $migration->createUserTable();
