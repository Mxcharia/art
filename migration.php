<?php
include './lib/migrations.php';

// Create an instance of the Migration class
$migration = new Migration();

// Check if the command-line argument is --migrate-up
// using $argv[] to get shell arguments passed to the script
if (isset($argv[1]) && $argv[1] === '--migrate-up') {
  echo $migration->createAllTables();
}
// Check if the command-line argument is --migrate-down
elseif (isset($argv[1]) && $argv[1] === '--migrate-down') {
  echo $migration->dropAllTables();
} else {
  echo "Usage:\n";
  echo "php script.php --migrate-up   : Create all tables\n";
  echo "php script.php --migrate-down : Drop all tables {NOTE:This is desctructive and should be used only locally not on production}\n";
}
