<?php
// -------------------------------
// 1. SET LATITUDE & LONGITUDE
// -------------------------------

// You can change these values
$latitude = 28.6139;   // Example: Delhi
$longitude = 77.2090;


// -------------------------------
// 2. SET TIMEZONE BASED ON LONGITUDE
// -------------------------------
// 360° = 24 hours
// 15° = 1 hour

$timezoneOffset = round($longitude / 15);

// Create timezone name like UTC+5 or UTC-3
if ($timezoneOffset >= 0) {
    $timezoneName = "Etc/GMT-" . $timezoneOffset;
} else {
    $timezoneName = "Etc/GMT+" . abs($timezoneOffset);
}

// Set timezone
date_default_timezone_set($timezoneName);


// -------------------------------
// 3. GET CURRENT DATE & TIME
// -------------------------------

$currentDate = date("Y-m-d");
$currentTime = date("H:i:s");
$dayName     = date("l");


// -------------------------------
// 4. DISPLAY RESULTS
// -------------------------------

echo "<h2>Location Information</h2>";
echo "Latitude: " . $latitude . "<br>";
echo "Longitude: " . $longitude . "<br><br>";

echo "<h3>Date & Time Details</h3>";
echo "Timezone: " . $timezoneName . "<br>";
echo "Current Date: " . $currentDate . "<br>";
echo "Current Time: " . $currentTime . "<br>";
echo "Day: " . $dayName . "<br>";

?>