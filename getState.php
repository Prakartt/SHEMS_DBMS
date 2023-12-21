<?php
 include("connection.php");

 $device_id=$_POST['device_data'];
 


 $result = $con->execute_query("SELECT * FROM model_desc WHERE device_id = $device_id");
 $output = '<option value=""> Select Model</option>';

 while($state = mysqli_fetch_assoc($result)){
    $output .= '<option value="' . $state['model_id'] . '">'.$state['model_id']. '</option>';
 }
 echo $output;
