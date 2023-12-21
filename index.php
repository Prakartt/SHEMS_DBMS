<?php
session_start();
    include("navigation.php");
    include("connection.php");
    include("functions.php");
    $user_data = check_login($con);
    


?>


<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="style.css">
        <title>
            My Website
        </title>
    </head>

<body>
    <?php

$user_id = $user_data['user_id'];
$result=$con->execute_query("SELECT
u.user_id as user,
sl.address_full as location,
SUM(eu.kwh_used) AS total_kwh_used,
SUM(eu.kwh_used * akc.price) AS total_cost
FROM
user_data u
RIGHT JOIN
service_location sl ON u.user_id = sl.user_id
JOIN
enrolled_devices ed ON sl.location_id = ed.location_id
JOIN
events e ON ed.enrolled_device_id = e.device_id
JOIN
event_usage eu ON e.event_id = eu.event_id
JOIN
(
    SELECT
        a1.zipcode,
        a1.timestamp AS start_time,
        MIN(a2.timestamp) AS end_time,
        a1.price
    FROM
        area_kwh_cost a1
    LEFT JOIN
        area_kwh_cost a2 ON a1.zipcode = a2.zipcode AND a1.timestamp < a2.timestamp
    GROUP BY
        a1.zipcode, a1.timestamp, a1.price
) akc ON sl.zipcode = akc.zipcode AND e.timestamp >= akc.start_time AND e.timestamp < akc.end_time
WHERE
u.user_id = $user_id -- replace with the specific customer_id
AND e.timestamp >= NOW() - INTERVAL 2 Year
GROUP BY
u.user_id,sl.location_id;");

   
echo '<font size="4" face="Courier New" ><table width="100%" border="2" cellspacing="30" cellpadding="30"> 
<tr style="color: #fff;"> 
    
    <td> <font face="Arial">Address</font> </td> 
    <td> <font face="Arial">Kilowatts used</font></td>
    <td> <font face="Arial">Cost</font> </td> 
    
</tr></font>';

if ($result) {
while ($row = $result->fetch_assoc()) {
 
  $location = $row["location"];
  $amount_used = $row["total_kwh_used"];
  $total_cost = $row["total_cost"];
   

  echo '<tr style="color: #fff; "> 
           
            <td>'.$location.'</td> 
            <td>'.$amount_used.'</td>
            <td>' .$total_cost.'</td> 
            
        </tr>';
}
}
    ?>
    <h1>
       <div style="color: white;">
    Hello,<?php echo $user_data['user_name']; ?>.  Given below is the data collected by your properties in the last one month</div>
<!-- HTML !-->

</body>
<!-- <a href="billing_cost_total.php">
<button style="background-color: transparent;color:white;padding: 15px 32px; text-align:center;display:inline-block;font: size 16px;margin: 4px 2px;" >Consumer Report</button>
</a>
<a href="billing_device.php">
<button style="background-color: transparent;color:white;padding: 15px 32px; text-align:center;display:inline-block;font: size 16px;margin: 4px 2px;" >Device usage</button>
</a> -->
</html>