<?php
/**
 * Program: Prime Number Checker
 * Description: Checks whether a given number is prime or not.
 */

$number = 17;
$isPrime = true;

echo "<h2>Prime Number Checker</h2>";
echo "Number to check: <strong>$number</strong><br><br>";

if ($number <= 1) {
    $isPrime = false;
} else {
    for ($i = 2; $i <= sqrt($number); $i++) {
        if ($number % $i == 0) {
            $isPrime = false;
            break;
        }
    }
}

if ($isPrime) {
    echo "<span style='padding: 5px 10px; background: #238636; color: white; border-radius: 4px;'>$number is a PRIME number</span>";
} else {
    echo "<span style='padding: 5px 10px; background: #da3633; color: white; border-radius: 4px;'>$number is NOT a prime number</span>";
}

echo "<br><br><em>Definition: A prime number is a natural number greater than 1 that is not a product of two smaller natural numbers.</em>";
?>
