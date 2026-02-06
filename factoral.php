<?php

$number = 4200;
$factors = array();
$count = 0;

for ($i = 1; $i <= $number; $i++) {

    if ($number % $i == 0) {
        $factors[] = $i;
        $count++;
    }

    if ($count == 10) {
        break;
    }
}

foreach ($factors as $factor) {
    echo "factor is " . $factor . "<br>";
}

?>