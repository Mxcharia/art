<?php

session_start();
if(isset($_SESSION['user'])){
    header("Location: userPage.php");
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
    <!-- Login form -->
   
    <div class="form-container">
        <?php
        
        //checking whether the submit button is clicked or not
        if (isset($_POST['login'])) {

            $email = $_POST[ 'email' ];
            $password = $_POST[ 'password' ];


            require_once "database.php";
            $sql = "SELECT * FROM registration WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            //checking if password matches with the email
            if($user){
                if(password_verify($password, $user["password"])){
                    //keeping the user loged in
                    session_start();
                    $_SESSION["user"] = "yes";
                    header("Location: userPage.php");
                    die();
                }else{
                    echo "invalid login";
                }

            }else{
                echo "Email does not exist";
            }
        }
        
        ?>







        <form action="login.php" method="post" autocomplete="off" novalidate>

        

            <h1>Login</h1>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" name="email">
            </div>


            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" name="password">
            </div>




            <button class="submit-btn" name="login">Login</button>

            <div class="login">
                <p>New here? <a href="registration.php">Signup</a></p>
            </div>


        </form>

    </div>


</body>

</html>