<?php
echo "LOGICAL OPERATORS\n";
echo "<br>-----------------\n";

$a = true;
$b = false;

// Logical AND
echo "<br>a AND b : ";
var_dump($a && $b);

// Logical OR
echo "<br>a OR b : ";
var_dump($a || $b);

// Logical NOT
echo "<br>NOT a : ";
var_dump(!$a);

// Logical XOR
echo "<br>a XOR b : ";
var_dump($a xor $b);


echo "\nBITWISE OPERATORS\n";
echo "-----------------\n";

$x = 5;  // 0101
$y = 3;  // 0011

// Bitwise AND
echo "<br>x & y = " . ($x & $y) . "\n";

// Bitwise OR
echo "<br>x | y = " . ($x | $y) . "\n";

// Bitwise XOR
echo "<br>x ^ y = " . ($x ^ $y) . "\n";

// Bitwise NOT
echo "<br>~x = " . (~$x) . "\n";

// Left Shift
echo "<br>x << 1 = " . ($x << 1) . "\n";

// Right Shift
echo "<br>x >> 1 = " . ($x >> 1) . "\n";
?>