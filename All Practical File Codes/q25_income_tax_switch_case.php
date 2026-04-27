<?php
  $income = 2500000;
  $tax = 0;
  switch (true) {
    case ($income < 500000):
      echo $tax = 0;
      break;
    case ($income >= 500000 && $income < 1000000):
      $tax = $income * 0.10;
      break;
    case ($income >= 1000000 && $income < 1500000):
      $tax = $income * 0.15;
      break;
    case ($income >= 1500000 && $income < 2000000):
      $tax = $income * 0.20;
      break;
    default:
      $tax = $income * 0.25;
  }
  echo "Income: ₹" . $income . "<br>";
  echo "Tax: ₹" . $tax . "<br>";
  echo "<br>This Program is Written & executed by Paras";
?>