<?php
  $year = 2019;
  if (($year % 4 == 0) || (($year % 100 != 0) && ($year % 4 == 0))) {
    echo "$year is not a leap year";
  }
  echo "<br>This Program is Written & executed by Paras";
?>