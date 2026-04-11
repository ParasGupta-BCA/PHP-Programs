<?php
/**
 * Program: Even or Odd
 * Description: Checks if a given number is even or odd.
 */

$number = 15; // You can change this value

echo "<h2>Even or Odd Checker</h2>";
echo "Number: " . $number . "<br>";

if ($number % 2 == 0) {
    echo "<p style='color: #238636; font-weight: bold;'>$number is an EVEN number.</p>";
} else {
    echo "<p style='color: #da3633; font-weight: bold;'>$number is an ODD number.</p>";
}

// Additional demonstration with a loop
echo "<h3>Checking numbers from 1 to 5:</h3>";
for ($i = 1; $i <= 5; $i++) {
    $status = ($i % 2 == 0) ? "Even" : "Odd";
    echo "Number $i is $status<br>";
}
?>
