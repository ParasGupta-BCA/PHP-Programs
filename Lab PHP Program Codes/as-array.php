<?php

$salaries = array("roshan"=>2000, "twinkle"=>1000, "zara"=>500);

echo "Salary of Roshan is " . $salaries['roshan'] . "\n<br>";
echo "Salary of Twinkle is " . $salaries['twinkle'] . "\n<br>";
echo "Salary of Zara is " . $salaries['zara'] . "\n<br>";

$salaries['roshan'] = "high";
$salaries['qadir'] = "medium";
$salaries['twinkle'] = "low";

echo "Salary of Roshan is " . $salaries['roshan'] . "\n<br>";
echo "Salary of Twinkle is " . $salaries['twinkle'] . "\n<br>";
echo "Salary of Zara is " . $salaries['zara'] . "\n<br>";

?>
