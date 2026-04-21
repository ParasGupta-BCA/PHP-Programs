<?php
/**
 * Program: Fibonacci Series
 * Description: Generates the Fibonacci sequence up to a specified number of terms.
 */

$n = 10; // Number of terms
$first = 0;
$second = 1;

echo "<h2>Fibonacci Series</h2>";
echo "Generating $n terms:<br><br>";

echo "<div style='font-family: monospace; font-size: 1.2rem; background: #f4f4f4; padding: 10px; border-radius: 5px; color: #333;'>";

for ($i = 0; $i < $n; $i++) {
    if ($i <= 1) {
        $next = $i;
    } else {
        $next = $first + $second;
        $first = $second;
        $second = $next;
    }
    
    echo $next;
    if ($i < $n - 1) {
        echo ", ";
    }
}

echo "</div>";

echo "<p>The Fibonacci sequence is a series of numbers where each number is the sum of the two preceding ones.</p>";
?>
