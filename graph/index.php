<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="gicon.ico">
	<meta charset="UTF-8">
	<meta name="description" content="Linear Google charts test">
	<meta name="keywords" content="HTML, CSS, PHP">
	<meta name="author" content="Gonzalo Orellana">
	<title>Lidar form test</title>
	<script type="text/javascript">
		var datefield=document.createElement("input")
		datefield.setAttribute("type", "date")
		if (datefield.type!="date"){
			document.write('<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />\n')
			document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"><\/script>\n')
			document.write('<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"><\/script>\n') 
		}
	</script>
	<script>
		if (datefield.type!="date"){
			jQuery(function($){
				$('#startDate').datepicker({
					dateFormat: "yy-mm-dd"
				});
				$('#endDate').datepicker({
					dateFormat: "yy-mm-dd"
				});
			})
		}
	</script>
	<script>
	function refreshgraph() {
	    document.getElementById("graph").submit();
				console.log("yolo");
					setTimeout(refreshgraph, 10000);
	}
	</script>
</head>

<body onload="refreshgraph()">
<form id="graph" action="report.php" method="post" target="report_iframe">
	<select name="device">
		<option value="1">Pointing to Espoon keskus</option>
		<option value="2">Pointing to Leppävaara</option>
	</select><br>
	Date (format yyyy-mm-dd):<br>
	Starting date: <input type="date" id="startDate" name="startDate" value="2015-11-01"><br>
	End date: <input type="date" id="endDate" name="endDate" value="2015-12-09"><br>
	Time (format hh:mm):<br>
	From: <input type="time" id="startTime" name="startTime" value="00:00"><br>
	To:   <input type="time" id="endTime" name="endTime" value="23:59"><br>
	<input type="submit" value="Submit">
</form>
<iframe name="report_iframe" src="report.php" width="80%" height="800"></iframe>
</body>
</html>
