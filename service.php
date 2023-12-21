<?php
session_start();
    include("navigation.php");
    include("connection.php");
    include("functions.php");
    $user_data = check_login($con);
    
    $user_id=$_SESSION['user_id'];
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        //something was posted
        $unit_no = $_POST['unit_no'];
        $zipcode = $_POST['zipcode'];
        $address = $_POST['address'];
        $sq_footage = $_POST['sq_footage'];
        $no_of_bedroom = $_POST['no_of_bedroom'];
        $no_of_occupants = $_POST['no_of_occupants'];

        if(!empty($unit_no) && !empty($zipcode) && !empty($address) && !empty($sq_footage) && !empty($no_of_bedroom) && !empty($no_of_occupants)){

            //saving to database
            $location_id = random_num(10);
            $query = "insert into service_location (location_id,user_id,unit_no,zipcode,address_full,sq_footage,no_of_bedrooms,no_of_occupants) values ('$location_id','$user_id','$unit_no','$zipcode','$address','$sq_footage','$no_of_bedroom','$no_of_occupants')";
            mysqli_query($con,$query);
            header('Location: index.php');
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
    <title> Registration Service Location </title> 
    <link rel="stylesheet" href="style2.css">
   </head>
<body>
  <div class="wrapper">
    <h2>Resigter Service Location</h2>
    <form method="post" action="#">
      <div class="input-box">
        <input type="text" name="unit_no" placeholder="Enter your unit_no" required>
      </div>
      <div class="input-box">
        <input type="text" name="zipcode" placeholder="Enter your zipcode" required>
      </div>
      <div class="input-box">
        <input type="text" name="address" placeholder="Enter your full address" required>
      </div>
      <div class="input-box">
        <input type="text" name="sq_footage" placeholder="Sqaure Footage" required>
      </div>
      <div class="input-box">
        <input type="text" name="no_of_bedroom" placeholder="Number of bedrooms" required>
      </div>
      <div class="input-box">
        <input type="text" name="no_of_occupants" placeholder="Number of occupants" required>
      </div>
      
      
      <div class="input-box button">
        <input type="Submit" value="Register">
      </div>
      <!-- <div class="text">
        <h3>Already have this service location registered <a href="index.php">Check</a></h3>
      </div> -->
    </form>
  </div>
</body>