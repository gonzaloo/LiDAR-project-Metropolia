<?php
//read the json file contents
$jsondata = file_get_contents('http://localhost/~joakim/json.php');
//convert json object to php associative array
$data = json_decode($jsondata, true);

$servername = "localhost";
$username = "joakim";
$password = "mysqlpassword";
$dbname = "joakim";

$input_device = $_GET['deviceID'];
$input_date = $_GET['date'];
$input_time = $_GET['time'];
$input_speed = $_GET['speed'];


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// prepare and bind
$stmt = $conn->prepare("INSERT INTO readings (deviceID, date, time, speed) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $deviceID, $date, $time, $speed);


    foreach ($data as $key => $value) {
      echo "key: " . $key;
      echo "speed: " . $value;
      echo "<br />\n";
      $deviceID = $input_device;
      $date = mb_substr($key, 0, 8);
      $time = mb_substr($key, 8);
      $speed = $value;
      $stmt->execute();
    };
    /*
$deviceID = $input_device;
$date = $input_date;
$time = $input_time;
$speed = $input_speed;
$stmt->execute();
*/
echo "New records created successfully";

$stmt->close();
$conn->close();


    //get the employee details
    /* $id = $data['empid'];
    $name = $data['personal']['name'];
    $gender = $data['personal']['gender'];
    $age = $data['personal']['age'];
    $streetaddress = $data['personal']['address']['streetaddress'];
    $city = $data['personal']['address']['city'];
    $state = $data['personal']['address']['state'];
    $postalcode = $data['personal']['address']['postalcode'];
    $designation = $data['profile']['designation'];
    $department = $data['profile']['department'];

    //insert into mysql table
    $sql = "INSERT INTO tbl_emp(empid, empname, gender, age, streetaddress, city, state, postalcode, designation, department)
    VALUES('$id', '$name', '$gender', '$age', '$streetaddress', '$city', '$state', '$postalcode', '$designation', '$department')";
      if(!mysql_query($sql,$con))
    {
        die('Error : ' . mysql_error());
    }
*/
?>
