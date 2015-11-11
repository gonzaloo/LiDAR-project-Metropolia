<!DOCTYPE html>
<html>
<head>
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
#chart_div {
	height: 100%;
	width: 100%;
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

/*echo json_encode($table);*/

mysqli_close($conn);
?>

<script type="text/javascript">

google.setOnLoadCallback(drawLineChart);
	
function drawLineChart() {
	/*var jsonData = $.ajax({
		url: "data1.php",
		dataType: "json",
		async: false
	}).responseText;*/

	var table = JSON.parse('<?php echo json_encode($table); ?>');

	var data = new google.visualization.DataTable(table);

/*	var data = new google.visualization.DataTable(data1.php); */

	var options = {
		title: 'Gonzalo\'s Google Charts test',
		/*curveType: 'function',*/
		legend: { position: 'bottom' }
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
		/*curveType: 'function',*/
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

	<p><div id="linechart_div"></div></p>
	<p><div id="histchart_div"></div></p>
	<p><div id="tablechart_div"></div></p>
</body>
</html>
