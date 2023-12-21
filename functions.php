<?php

function check_login($con){

   if(isset($_SESSION['user_id'])){
    $id = $_SESSION['user_id'];
    $result = $con->execute_query("select * from user_data where user_id=$id limit 1");

    if($result && mysqli_num_rows($result) > 0){
        $user_data = mysqli_fetch_assoc($result);
        return $user_data;
    }
   }

   //redirect to login
   header("Location: login.php");
   die;

}

function random_num($length){
    $text = "";
    if($length < 5){
        $length =5;

    }
    $len = rand(4,$length);

    for($i=0;$i<$len;$i++){
        $text .= rand(0,9);

    }
    return $text;

}

?>