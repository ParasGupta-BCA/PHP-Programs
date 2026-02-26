<?php
$latitude = 40.7128;
$longitude = -74.0060;
$timezoneOffset = -5;
$dayOfYear = date('z') + 1;

function equationOfTime($dayOfYear) {
    $B = 2 * M_PI * ($dayOfYear - 81) / 364;
    return 9.87 * sin(2 * $B) - 7.53 * cos($B) - 1.5 * sin($B);
}

function solarNoon($longitude, $timezoneOffset, $dayOfYear) {
    $EoT = equationOfTime($dayOfYear);
    return 12 + ($timezoneOffset - $longitude / 15) - $EoT / 60;
}

$solarNoon = solarNoon($longitude, $timezoneOffset, $dayOfYear);

echo "Latitude: $latitude°\n";
echo "Longitude: $longitude°\n";
echo "Day of Year: $dayOfYear\n";
echo "Local Solar Noon: " . gmdate("H:i", $solarNoon * 3600) . "\n";
?>