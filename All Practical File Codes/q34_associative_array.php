<?php
  // 1st method
  $salaries = array("Roshan" => 2000, "Twinkle" => 1000, "Zara" => 500);
  echo "Salary of Roshan is: " . $salaries["Roshan"] . "<br>";
  echo "Salary of Twinkle is: " . $salaries["Twinkle"] . "<br>";
  echo "Salary of Zara is: " . $salaries["Zara"] . "<br>";

  // 2nd method
  $salaries["Roshan"] = "high";
  $salaries["Qadis"] = "medium";
  $salaries["Twinkle"] = "low";
  echo "Salary of Roshan is: " . $salaries["Roshan"] . "<br>";
  echo "Salary of Qadis is: " . $salaries["Qadis"] . "<br>";
  echo "Salary of Twinkle is: " . $salaries["Twinkle"] . "<br>";
  echo "<br>This Program is Written & executed by Paras";
?>