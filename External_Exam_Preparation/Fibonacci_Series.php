<?php

// First two numbers
$firstNumber = 0;
$secondNumber = 1;

// Print first two numbers
echo $firstNumber . " " . $secondNumber . " ";

// Generate next numbers
for ($i = 1; $i <= 7; $i++) {
    
    $nextNumber = $firstNumber + $secondNumber;
    echo $nextNumber . " ";
    
    // Update values
    $firstNumber = $secondNumber;
    $secondNumber = $nextNumber;
}

?>

<!-- 

💡 Simple Explanation:

Start with 0 and 1
Next number = sum of previous two numbers
Keep updating values and printing 

-->