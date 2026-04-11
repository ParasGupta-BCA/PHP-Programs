<?php
/**
 * Program: Factorial Calculation
 * Description: Calculates the factorial of a number using a for loop.
 */

$num = 5;
$factorial = 1;

echo "<h2>Factorial Calculator</h2>";

if ($num < 0) {
    echo "Factorial is not defined for negative numbers.";
} else {
    for ($i = 1; $i <= $num; $i++) {
        $factorial = $factorial * $i;
    }
    echo "The factorial of <strong>$num</strong> is: <strong>$factorial</strong>";
}

echo "<hr>";
echo "<h3>Formula:</h3>";
echo "n! = n × (n-1) × (n-2) × ... × 1";
?>
