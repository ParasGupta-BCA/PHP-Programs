<?php
  $cars = array("Volvo", "BMW", "Toyota");
  sort($cars);
  $clength = count($cars);
  for ($x = 0; $x < $clength; $x++) {
    echo $cars[$x];
    echo "<br>";
  }
  echo "<br>This Program is Written & executed by Paras";
?>