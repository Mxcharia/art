<?php

session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">

    <title>Login</title>
</head>


<body>

<h1>WELCOME TO DASHBOARD</h1>
<a href="logout.php" class="logout"> Logout</a>
</body>
</html>