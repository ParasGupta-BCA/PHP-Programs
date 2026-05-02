<?php

// Define array manually
$numbers = array(10, 25, 7, 99, 100,700);

// Assume first element is the largest
$largest = $numbers[0];

// Loop through array WITHOUT using count()
for ($i = 0; $i<=5; $i++) {
    
    if ($numbers[$i] > $largest) {
        $largest = $numbers[$i];
    }
}

// Print result
echo "Largest number is: " . $largest;

?>