<?php
session_start();
    include("connection.php");
    include("functions.php");
    
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        //something was posted
        // $firstname = $_POST['first_name'];
        // $lastname = $_POST['last_name'];
        // $username = $_POST['user_name'];
        // $billingaddress = $_POST['billing_address'];
        // $password = $_POST['password'];

        $firstname = !empty($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '';
$lastname = !empty($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '';
$username = !empty($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : '';
$billingaddress = !empty($_POST['billing_address']) ? htmlspecialchars($_POST['billing_address']) : '';
$password = !empty($_POST['password']) ? htmlspecialchars($_POST['password']) : '';


        if(!empty($firstname) && !empty($lastname) && !empty($username) && !empty($billingaddress) && !empty($password)){

            //saving to database
            $user_id = random_num(20);
            $query = "insert into user_data (user_id,user_name,password,first_name,last_name,billing_address) values ('$user_id','$username','$password','$firstname','$lastname','$billingaddress')";
            mysqli_query($con,$query);
            header('Location: login.php');
            exit;
        }
        else{
            echo "Please enter some valid information";

        }
    }
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Registration or Sign Up form in HTML CSS | CodingLab </title> 
    <link rel="stylesheet" href="style2.css">
   </head>
<body>
  <div class="wrapper">
    <h2>Registration</h2>
    <form method="post" action="#">
      <div class="input-box">
        <input type="text" name="first_name" placeholder="Enter your first name" required>
      </div>
      <div class="input-box">
        <input type="text" name="last_name" placeholder="Enter your last name" required>
      </div>
      <div class="input-box">
        <input type="text" name="user_name" placeholder="Enter your user name" required>
      </div>
      <div class="input-box">
        <input type="text" name="billing_address" placeholder="Billing Address" required>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Create password" required>
      </div>
      
      <div class="policy">
        <input type="checkbox">
        <h3>I accept all terms & condition</h3>
      </div>
      <div class="input-box button">
        <input type="Submit" value="signup">
      </div>
      <div class="text">
        <h3>Already have an account? <a href="login.php">Login now</a></h3>
      </div>
    </form>
  </div>
</body>
</html>