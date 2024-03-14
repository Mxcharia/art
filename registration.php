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
    <link rel="stylesheet" href="register.css" >
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css"> -->
    <!-- <title>Signup</title> -->
</head>

<body>
    

    <!-- Signup form -->

    <div class="form-container">
        

        <form action="registration.php"  method="post" autocomplete="off" novalidate>

        <?php
        
        if(isset($_POST["submit"])){
            $firstname = $_POST['fname'];
            $lastname = $_POST['lname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordConfirm = $_POST['cpassword'];
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();
            $name = $_POST["fname"] . ' ' . $_POST["lname"];


            //checking if the user  left any field empty 
            if(empty( $firstname) OR empty($lastname) OR empty( $email) OR empty($password) OR empty( $passwordConfirm)){
                array_push($errors, "All fields are required");
            }
            //checking the validity of the email
            if(!filter_var($email , FILTER_VALIDATE_EMAIL)) {
                array_push($errors,"Email is invalid!");
            }

            //checking the password length
            if(strlen($password)<8){
                array_push($errors, "Password must be atleast 8 characters long");
            }
            
            // checking if password and confirm password match
            if ($password !== $passwordConfirm) {
                array_push($errors, 'Passwords do not match');
            }

            //checking if an email already existes in the database
            require_once "database.php";
            $sql = "SELECT * FROM registration WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if($rowCount > 0){
                array_push( $errors, "This Email is already registered.");
            }

            //checking the amount of errors todisplay to the users
            if(count($errors)>0){
                foreach($errors as $error){
                    echo "<div class='error-message'> $error </div>";
                }
            }

           
            else{
               //connecting the database
                

                 // Inserting the data into the database
                 $sql = "INSERT INTO registration (name, email, password) VALUES( ?,?,?)";
                 $stmt = mysqli_stmt_init($conn);
                 $preparestmt = mysqli_stmt_prepare( $stmt, $sql );

                 if($preparestmt){
                    mysqli_stmt_bind_param( $stmt, "sss", $name, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class = 'success' > Registration Successful!</div>";
                 }else{
                    die("Something went wrong");
                 }



            }
            
        }
    
        ?>

            <h1>Signup</h1>


            <div class="form-group">
                <label for="fname">First name</label>
                <input type="text" id="fname"  class="form-control"   name="fname">
            </div>

            <div class="form-group">
                <label for="lname">Last name</label>
                <input type="text" id="lname"  class="form-control"  name="lname">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" name="email">
            </div>


            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" name="password">
            </div>


            <div class="form-group">
                <label for="cpassword">Confirm password</label>
                <input type="password" id="cpassword" class="form-control" name="cpassword">
            </div>

            <button class="submit-btn" name="submit">Sign up</button>
           

            <div class="login">
                <p>Have an account? <a href="login.php">Login</a></p>
            </div>


        </form>

    </div>
</body>

</html>