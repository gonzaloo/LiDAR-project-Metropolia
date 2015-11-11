<!DOCTYPE html>
<html>
<head>
	<title>Gonzalo's form test</title>
</head>

<body>
<form action="lidar_report_a.php" method="post">
	<select name="device">
		<option value="1">Pointing to Espoon keskus</option>
		<option value="2">Pointing to Leppävaara</option>
	</select><br>
	Date (format yyyy-mm-dd):<br>
	Starting date: <input type="text" name="startDate" value="2015-11-10"><br>
	End date: <input type="text" name="endDate" value="2015-11-10"><br>
	Time (format hh:mm):<br>
	From: <input type="text" name="startTime" value="00:00"><br>
	To:   <input type="text" name="endTime" value="23:59"><br>
	<input type="submit" value="Submit">
</form>
</body>
</html>
