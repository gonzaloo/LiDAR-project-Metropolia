<?php
//read the json file contents
$jsondata = $_POST['json'];
//convert json object to php associative array
$data = json_decode($jsondata, true);

$servername = "localhost";
$username = "joakim";
$password = "mysqlpassword";
$dbname = "joakim";
$inserted = 0;
$input_device = $_GET['deviceID'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// prepare and bind
$stmt = $conn->prepare("INSERT INTO readings (deviceID, date, time, speed) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiid", $deviceID, $date, $time, $speed);

// for each entry in the array from our decoded json
    foreach ($data as $key => $value) {
      /* echo "key: " . $key;
      echo "speed: " . $value;
      echo "<br />\n"; */
      $inserted++
      $deviceID = $input_device;      //our deviceID comes from GET
      $date = mb_substr($key, 0, 8);  //split the date from the timestamp
      $time = mb_substr($key, 8);     //split the time from the timestamp
      $speed = $value;                //speed is our value
      $stmt->execute();
    };

echo "Inserted " . $inserted . " rows.";

$stmt->close();
$conn->close();
?>
