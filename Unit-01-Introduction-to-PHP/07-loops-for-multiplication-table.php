<?php
/**
 * Program: Multiplication Table using For Loop
 * Description: Generates a multiplication table for a given number.
 */

$num = 7;

echo "<h2>Multiplication Table of $num</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 200px; text-align: center; border: 1px solid #30363d;'>";
echo "<tr style='background: #1f242c; color: #58a6ff;'><th>Expression</th><th>Result</th></tr>";

for ($i = 1; $i <= 10; $i++) {
    $res = $num * $i;
    $bg = ($i % 2 == 0) ? "#161b22" : "#0d1117";
    echo "<tr style='background: $bg;'><td>$num x $i</td><td>$res</td></tr>";
}

echo "</table>";
?>
