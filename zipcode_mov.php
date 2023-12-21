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

<a href="billing.php">Go back</a>
<form method="GET">
    <div>
    <label>Select zipcode</label>
        <select name="zipcode">
        <option value disabled selected>Select zipcode</option>
            <?php
                    include("connection.php");
                    $user_id= $_SESSION['user_id'];
                    while($c=mysqli_fetch_array($service)){
            ?>
            <option value="<?php echo $c['zipcode']?>"><?php echo $c['zipcode']?></option>
            <?php } ?>
        </select>

        
        <input type="date" name="start_date" value="<?= isset($_GET['date_start'])==true ? $_GET['date_start'] :'' ?>">
        <input type="date" name="end_date" value="<?= isset($_GET['date_end'])==true ? $_GET['date_end'] :'' ?>">
        <button type="submit">Filter</button>
    </div>
</form>

<p>Price per KW in a particular zipcode</p>

<div style="width: 500px;">
  <canvas id="myChart"></canvas>
</div>
<?php  
    if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] !='' && $_GET['end_date'] !='' && $_GET['zipcode']){

            $start_date = ($_GET['start_date']);
            $end_date=($_GET['end_date']);
            $zipcode = ($_GET['zipcode']);
            $enquiry = mysqli_query($con,"SELECT timestamp as times , price from area_kwh_cost WHERE DATE(timestamp)  between '$start_date' and '$end_date' and zipcode=$zipcode");
    }
    else if(isset($_GET['start_date']) && isset($_GET['end_date']) && $_GET['start_date'] =='' && $_GET['end_date'] =='' && $_GET['zipcode']){
        $zipcode = ($_GET['zipcode']);
        $enquiry = mysqli_query($con,"SELECT timestamp as times , price from area_kwh_cost WHERE timestamp >= NOW() - INTERVAL 2 DAY AND zipcode=$zipcode");

    }else{
        $enquiry = mysqli_query($con,"Select timestamp as times , price from area_kwh_cost WHERE timestamp >= NOW() - INTERVAL 2 DAY AND zipcode='12345'");
    }
  foreach($enquiry as $data)
  {
    $month[] = $data['times'];
    $amount[] = $data['price'];
  }


    
?>
<script>
  // === include 'setup' then 'config' above ===
  const labels = <?php echo json_encode($month) ?>;
  const data = {
    labels: labels,
    datasets: [{
      label:  "Price per KW",
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