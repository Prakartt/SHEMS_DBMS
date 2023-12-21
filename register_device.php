<?php
session_start();
    include("navigation.php");
    include("connection.php");
    include("functions.php");
    $user_data = check_login($con);
    $device_type =$con->execute_query("Select * from device_name");
   

    
    $user_id= $_SESSION['user_id'];

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $service_location = $_POST['service_location'];
        $device_type = $_POST['device'];
        $model_id=$_POST['model'];
        if(!empty($service_location) && !empty($device_type) && !empty($model_id)){
            $device_id = random_num(20);
            $query = "insert into enrolled_devices (enrolled_device_id,location_id,device_type,model_id) values ('$device_id','$service_location','$device_type','$model_id')";
            mysqli_query($con,$query);
            header('Location: index.php');
            exit;
        }
        else{
                    echo "Please enter some valid information";
        
                }
    }
    // if($_SERVER['REQUEST_METHOD'] == "POST"){
    //     //something was posted
    //     $unit_no = $_POST['unit_no'];
    //     $zipcode = $_POST['zipcode'];
    //     $address = $_POST['address'];
    //     $sq_footage = $_POST['sq_footage'];
    //     $no_of_bedroom = $_POST['no_of_bedroom'];
    //     $no_of_occupants = $_POST['no_of_occupants'];

    //     if(!empty($unit_no) && !empty($zipcode) && !empty($address) && !empty($sq_footage) && !empty($no_of_bedroom) && !empty($no_of_occupants)){

    //         //saving to database
    //         $location_id = random_num(10);
    //         $query = "insert into service_location (location_id,user_id,unit_no,zipcode,address_full,sq_footage,no_of_bedrooms,no_of_occupants) values ('$location_id','$user_id','$unit_no','$zipcode','$address','$sq_footage','$no_of_bedroom','$no_of_occupants')";
    //         mysqli_query($con,$query);
    //         header('Location: index.php');
    //         exit;
    //     }
    //     else{
    //         echo "Please enter some valid information";

    //     }
    // }
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Registration Device </title> 
    <link rel="stylesheet" href="style2.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
   </head>



<body>
  <div class="wrapper">
    <h2>Resigter Device</h2>
    <form method="post" action="#">
      <div class="input-box">
      <!-- <input type="text" name="service_location" placeholder="Enter your service location" required> -->
      <label>Select service location</label>
        <select name="service_location">
        <option value disabled selected>Select Service Location</option>
            <?php
                    include("connection.php");
                    $user_id= $_SESSION['user_id'];
                    $categories = mysqli_query($con,"Select * from service_location WHERE user_id = $user_id");
                    while($c=mysqli_fetch_array($categories)){
            ?>
            <option value="<?php echo $c['location_id']?>"><?php echo $c['address_full'] ?></option>
            <?php } ?>
        </select>
        
      </div>

<!-- 
    Drop down for devices -->

    <div class="input-box">
      <label>Select Device Type</label>
        <select name="device" id="device">
            <option value disabled selected>Select Device</option>
            <?php while($row = mysqli_fetch_assoc($device_type)):?>
                <option value="<?php echo $row['device_id']; ?>"><?php echo $row['device_type']; ?></option>
            <?php endwhile; ?>
        </select>
        
      </div>

      <div class="input-box">
      <!-- <input type="text" name="service_location" placeholder="Enter your service location" required> -->
      <label>Select Device Model</label>
        <select name="model" id="model">
            
            <option value="">Select Device Model</option>
            
        </select>
        
      </div>



      
      
      <div class="input-box button">
        <input type="Submit" value="Register">
      </div>
      <!-- <div class="text">
        <h3>Already have this service location registered ?<a href="index.php">Check</a></h3>
      </div> -->
    </form>
  </div>
</body>

<script>

$('#device').on('change',function(){

    var device_id= this.value;
    // console.log(device_id);

    $.ajax({
        url: 'getState.php',
        type: "POST",
        data: {
            device_data: device_id
        },
        success: function(result) {
            $('#model').html(result);
            // console.log(result);
        }
    })

});

</script>