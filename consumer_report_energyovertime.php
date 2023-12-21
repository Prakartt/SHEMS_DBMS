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
    <label>Select time period</label>
        <!-- <select name="service_location">
        <option value disabled selected>Select Enrolled Device</option>
            <?php
                    include("connection.php");
                    $user_id= $_SESSION['user_id'];
                    while($c=mysqli_fetch_array($service)){
            ?>
            <option value="<?php echo $c['location_id']?>"><?php echo $c['address_full'] , $c['unit_no']?></option>
            <?php } ?>
        </select> -->

        
        <input type="date" name="start_date" value="<?= isset($_GET['date_start'])==true ? $_GET['date_start'] :'' ?>">
        <input type="date" name="end_date" value="<?= isset($_GET['date_end'])==true ? $_GET['date_end'] :'' ?>">
        <button type="submit">Filter</button>
    </div>
</form>
<p> Engergy consumption vs date </p>

<div style="width: 500px;">
  <canvas id="myChart"></canvas>
</div>
<?php  
    if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] !='' && $_GET['end_date'] !='' ){

            $start_date = ($_GET['start_date']);
            $end_date=($_GET['end_date']);
            $enquiry = $con->execute_query("SELECT
            
            DATE(e.timestamp) AS times,
            SUM(eu.kwh_used) AS amount
        FROM
            user_data u
        JOIN
            service_location sl ON u.user_id = sl.user_id
        JOIN
            enrolled_devices ed ON sl.location_id = ed.location_id
        JOIN
            events e ON ed.enrolled_device_id = e.device_id
        JOIN
            event_usage eu ON e.event_id = eu.event_id
        WHERE 
             DATE(e.timestamp)  between '$start_date' and '$end_date' AND  u.user_id = $user_id
        GROUP BY
            u.user_id, DATE(e.timestamp)");
    }
    else{
        $enquiry = $con->execute_query("SELECT
            
        DATE(e.timestamp) AS times,
        SUM(eu.kwh_used) AS amount
    FROM
        user_data u
    JOIN
        service_location sl ON u.user_id = sl.user_id
    JOIN
        enrolled_devices ed ON sl.location_id = ed.location_id
    JOIN
        events e ON ed.enrolled_device_id = e.device_id
    JOIN
        event_usage eu ON e.event_id = eu.event_id
    WHERE 
         u.user_id = $user_id
    GROUP BY
        u.user_id, DATE(e.timestamp)");

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
    options: {
        plugins: {
            subtitle: {
                display: true,
                text: 'Custom Chart Subtitle'
            }
        }
    },
    labels: labels,
    datasets: [{
      label:  "Total Cost",
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
  type: 'line',
  data: data,
};
  var myChart = new Chart(
    document.getElementById('myChart'),
    config
  );
</script>

</body>
</html>