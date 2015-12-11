<!DOCTYPE html>
<html>
<head>
<link rel="icon" href="gicon.ico">
<meta charset="UTF-8">
<meta name="description" content="Linear Google charts test">
<meta name="keywords" content="HTML, CSS, JS, PHP, MySQL, Google">
<meta name="author" content="Gonzalo Orellana">
<title>Gonzalo-Lidar Google Charts!</title>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={
	'modules':[{
		'name' : 'visualization',
		'version' : '1',
		'packages' :['corechart', 'table']
	}]
}"></script>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<style>
#linechart_div, #histchart_div, #tablechart_div {
	height: 100%;
	width: 100%;
}

#table_stats {
	border: 1px solid black;
	width: 100%;
	text-align: left;
}
</style>

</head>

<body>
<?php
$db_host = "localhost";
$db_user = "joakim";
$db_password = "joakim123";
$db_database = "joakim";

$conn = mysqli_connect($db_host, $db_user, $db_password, $db_database);

if(mysqli_connect_error()) {
	echo "Connection failed: " . mysqli_connect_error();
}

$stDate = $_POST["startDate"];
$edDate = $_POST["endDate"];
$stTime = $_POST["startTime"];
$edTime = $_POST["endTime"];
$devID = $_POST["device"];

$diferencia = ((strtotime($edDate) - strtotime($stDate))/24/3600) + 1;

$query = "SELECT date, time, speed FROM readings WHERE deviceID = '$devID' AND time BETWEEN '$stTime' AND '$edTime' AND date BETWEEN '$stDate' AND '$edDate' ORDER BY date, time";
$result = mysqli_query($conn, $query);

$rows = array();
$table = array();
$tableT = array();
$table['cols'] = array(
	array('label' => 'time', 'type' => 'string'),
	array('label' => 'speed', 'type' => 'number')
);
$tableT['cols'] = array(
	array('label' => 'date', 'type' => 'string'),
	array('label' => 'time', 'type' => 'string'),
	array('label' => 'speed', 'type' => 'number')
);
foreach($result as $r) {
	$temp = array();
	$temp[] = array('v' => (string) $r['time']);
	$temp[] = array('v' => (int) $r['speed']);
	$rows[] = array('c' => $temp);
}
$table['rows'] = $rows;

foreach($result as $rT) {
	$tempT = array();
	$tempT[] = array('v' => (string) $rT['date']);
	$tempT[] = array('v' => (string) $rT['time']);
	$tempT[] = array('v' => (int) $rT['speed']);
	$rowsT[] = array('c' => $tempT);
}
$tableT['rows'] = $rowsT;

$maxQuery = "SELECT MAX(speed) FROM readings WHERE deviceID = '$devID' AND time BETWEEN '$stTime' AND '$edTime' AND date BETWEEN '$stDate' AND '$edDate'";
$maxSpeed = mysqli_fetch_row(mysqli_query($conn, $maxQuery));

$minQuery = "SELECT MIN(speed) FROM readings WHERE deviceID = '$devID' AND time BETWEEN '$stTime' AND '$edTime' AND date BETWEEN '$stDate' AND '$edDate'";
$minSpeed = mysqli_fetch_row(mysqli_query($conn, $minQuery));

$avgQuery = "SELECT AVG(speed) FROM readings WHERE deviceID = '$devID' AND time BETWEEN '$stTime' AND '$edTime' AND date BETWEEN '$stDate' AND '$edDate'";
$avgSpeed = mysqli_fetch_row(mysqli_query($conn, $avgQuery));

$qtyQuery = "SELECT COUNT(*) FROM readings WHERE deviceID = '$devID' AND time BETWEEN '$stTime' AND '$edTime' AND date BETWEEN '$stDate' AND '$edDate'";
$qttyCars = mysqli_fetch_row(mysqli_query($conn, $qtyQuery));

$qabQuery = "SELECT COUNT(*) FROM readings WHERE deviceID = '$devID' AND time BETWEEN '$stTime' AND '$edTime' AND date BETWEEN '$stDate' AND '$edDate' AND speed > 30";
$qttyAbve = mysqli_fetch_row(mysqli_query($conn, $qabQuery));

$ratioAbv = round($qttyAbve[0] / $qttyCars[0] * 100);

mysqli_close($conn);
?>

<script type="text/javascript">
google.setOnLoadCallback(drawLineChart);
function drawLineChart() {
	var table = JSON.parse('<?php echo json_encode($table); ?>');
	var data = new google.visualization.DataTable(table);
	var options = {
		title: 'Gonzalo\'s Google Charts test',
		legend: { position: 'bottom' },
		pointSize: 7,
		pointShape: {type: 'star', sides: 5},
	};
	var chart = new google.visualization.LineChart(document.getElementById('linechart_div'));
	chart.draw(data, options);
}
</script>

<script type="text/javascript">
google.setOnLoadCallback(drawHistChart);
function drawHistChart() {
	var table = JSON.parse('<?php echo json_encode($table); ?>');
	var data = new google.visualization.DataTable(table);
	var options = {
		title: 'Gonzalo\'s Google Charts test',
		legend: { position: 'bottom' }
	};
	var chart = new google.visualization.Histogram(document.getElementById('histchart_div'));
	chart.draw(data, options);
}
</script>

<script type="text/javascript">
google.setOnLoadCallback(drawTable);
function drawTable() {
	var table = JSON.parse('<?php echo json_encode($tableT); ?>');
	var data = new google.visualization.DataTable(table);
	var chart = new google.visualization.Table(document.getElementById('tablechart_div'));
	chart.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
}
</script>

<p><div id="information">
<h3>You've selected <?php echo $diferencia ?> days.</h3>
</div></p>
<p><div id="linechart_div"></div></p>
<p><div id="histchart_div"></div></p>
<p>
<table id="table_stats">
<tr>
<th>Max speed</th>
<td><?php echo round($maxSpeed[0]) ?></td>
<th># total cars</th>
<td><?php echo round($qttyCars[0]) ?></td>
</tr>
<tr>
<th>Min speed</th>
<td><?php echo round($minSpeed[0]) ?></td>
<th># cars above speed limit</th>
<td><?php echo round($qttyAbve[0]) ?></td>
</tr>
<tr>
<th>Avg speed</th>
<td><?php echo round($avgSpeed[0]) ?></td>
<th>Percentage of cars above the speed limit</th>
<td><?php echo $ratioAbv . " %" ?></td>
</tr>
</table>
</p>
<p><div id="tablechart_div"></div></p>
</body>
</html>
