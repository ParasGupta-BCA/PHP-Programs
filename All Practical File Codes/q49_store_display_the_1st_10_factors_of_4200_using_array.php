<?php
  $number = 4200;
  $factors = array();
  for ($i = 10; $i <= $number; $i++) {
    if ($number % $i == 0) {
      $factors[] = $i;
    }
    if (count($factors) == 10) {
      break;
    }
  }
  echo "First 10 factors of 4200 are: <br>";
  foreach ($factors as $factor) {
    echo $factor . "<br>";
  }
  echo "Program is Written & executed by Paras";
  echo "<br>This Program is Written & executed by Paras";
?>