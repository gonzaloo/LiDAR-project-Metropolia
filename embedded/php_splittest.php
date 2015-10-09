<?php
$date = $_GET['timestamp'];
echo "date: " . mb_substr($date, 0, 9);
echo "time: " . mb_substr($date, 8);
?>
