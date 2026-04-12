<?php
date_default_timezone_set("Asia/Kolkata");

// Fixed birth date: 26 November 2005
$birth = strtotime("2005-11-26 00:00:00");
$now   = time();

$seconds = $now - $birth;
$minutes = floor($seconds / 60);
$hours   = floor($seconds / 3600);
$days    = floor($seconds / 86400);

echo "<h2>Age Calculator</h2>";

echo "<h3>Current Date & Time:</h3>";
echo date("Y-m-d H:i:s", $now);

echo "<h3>Total Age Since 26 Nov 2005:</h3>";
echo "Days: $days <br>";
echo "Hours: $hours <br>";
echo "Minutes: $minutes <br>";
echo "Seconds: $seconds <br>";
?>