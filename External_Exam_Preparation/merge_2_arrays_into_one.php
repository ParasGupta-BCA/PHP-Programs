<?php

// First array
$firstArray = array(10, 20, 30);

// Second array
$secondArray = array(40, 50, 60);

// Third array to store merged result
$mergedArray = array();

// Index for merged array
$mergedIndex = 0;

// Copy elements from first array
for ($firstIndex = 0; isset($firstArray[$firstIndex]); $firstIndex++) {
    $mergedArray[$mergedIndex] = $firstArray[$firstIndex];
    $mergedIndex++;
}

// Copy elements from second array
for ($secondIndex = 0; isset($secondArray[$secondIndex]); $secondIndex++) {
    $mergedArray[$mergedIndex] = $secondArray[$secondIndex];
    $mergedIndex++;
}

// Display merged array
echo "Merged Array: <br>";

for ($displayIndex = 0; isset($mergedArray[$displayIndex]); $displayIndex++) {
    echo $mergedArray[$displayIndex] . " ";
}

?>