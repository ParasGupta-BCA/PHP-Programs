<?php
  date_default_timezone_set("Asia/Kolkata");
  $india_time = date("y-m-d, H:i:s");

  echo "<h3>India date and time:</h3>";
  echo $india_time;

  date_default_timezone_set("Europe/Berlin");
  $berlin_time = date("y-m-d, H:i:s");

  echo "<h3>Berlin data and time:</h3>";
  echo $berlin_time;

  $time1 = strtotime($india_time);
  $time2 = strtotime($berlin_time);

  $difference = abs($time1 - $time2);
  $hours = floor($difference / 3600);
  $mins = floor(($difference % 3600) / 60);

  echo "<h3>Time difference</h3>";
  echo $hours . " hours" . $mins . " minutes";
  echo "<br>This Program is Written & executed by Paras";
?>