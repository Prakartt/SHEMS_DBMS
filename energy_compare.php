<?php
session_start();
  
    include("connection.php");
    include("functions.php");
    $user_data = check_login($con);
    $user_id = $user_data['user_id'];
    $devices = mysqli_query($con,"SELECT * from enrolled_devices e JOIN service_location s ON e.location_id = s.location_id JOIN user_data u on s.user_id = u.user_id WHERE u.user_id = $user_id");
    $service = mysqli_query($con,"Select * from service_location WHERE user_id = $user_id");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>Document</title>
</head>
<body>

<li><a href="consume_energy.php">Go back</a></li>

<form method="GET">
    <div>
    <label>Select Service Location</label>
        <select name="service_location">
        <option value disabled selected>Select Service Location</option>
            <?php
                    include("connection.php");
                    $user_id= $_SESSION['user_id'];
                    while($c=mysqli_fetch_array($service)){
            ?>
            <option value="<?php echo $c['location_id']?>"><?php echo $c['address_full'] , $c['unit_no']?></option>
            <?php } ?>
        </select>

        
        <input type="date" name="start_date" value="<?= isset($_GET['date_start'])==true ? $_GET['date_start'] :'' ?>">
        <input type="date" name="end_date" value="<?= isset($_GET['date_end'])==true ? $_GET['date_end'] :'' ?>">
        <button type="submit">Filter</button>
    </div>
</form>

<p>Comparison of similar locations</p> 

<div style="width: 500px;">
  <canvas id="myChart"></canvas>
</div>
<?php  
    if(isset($_GET['service_location']) && $_GET['service_location']!=''){

            
            $service = ($_GET['service_location']);
            $enquiry = $con->execute_query("SELECT sl.location_id as times, SUM(eu.kwh_used) AS amount FROM Service_location sl JOIN Service_location sl_given ON (sl.zipcode = sl_given.zipcode OR sl.no_of_occupants = sl_given.no_of_occupants) AND sl.location_id <> sl_given.location_id JOIN Enrolled_devices ed ON sl.location_id = ed.location_id JOIN Events ev ON ed.enrolled_device_id = ev.device_id JOIN Event_usage eu ON ev.event_id = eu.event_id WHERE sl_given.location_id = $service GROUP BY sl.location_id, sl.zipcode, sl.no_of_occupants;");
    }
    else{
        $enquiry = $con->execute_query("SELECT sl.location_id as times, SUM(eu.kwh_used) AS amount FROM Service_location sl JOIN Service_location sl_given ON (sl.zipcode = sl_given.zipcode OR sl.no_of_occupants = sl_given.no_of_occupants) AND sl.location_id <> sl_given.location_id JOIN Enrolled_devices ed ON sl.location_id = ed.location_id JOIN Events ev ON ed.enrolled_device_id = ev.device_id JOIN Event_usage eu ON ev.event_id = eu.event_id  GROUP BY sl.location_id, sl.zipcode, sl.no_of_occupants;");


    }
  foreach($enquiry as $data)
  {
    $month[] = $data['times'];
    $amount[] = $data['amount'];
  }


    
?>
<script>
  // === include 'setup' then 'config' above ===
  const labels = <?php echo json_encode($month) ?>;
  const data = {
    labels: labels,
    datasets: [{
      label:  "My devices",
      data: <?php echo json_encode($amount) ?>,
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(255, 205, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(201, 203, 207, 0.2)'
      ],
      borderColor: [
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)',
        'rgb(153, 102, 255)',
        'rgb(201, 203, 207)'
      ],
      borderWidth: 1
    }]
  };

  const config = {
  type: 'bar',
  data: data,
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  },
};
  var myChart = new Chart(
    document.getElementById('myChart'),
    config
  );
</script>

</body>
</html>