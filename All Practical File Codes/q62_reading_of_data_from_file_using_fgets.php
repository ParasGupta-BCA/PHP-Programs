<?php
  $file = fopen("Welcome.txt", "r") or exit("Unable to open file");
  while (!feof($file)) {
    echo fgets($file) . "<br>";
  }
  fclose($file);
  echo "<br>This Program is Written & executed by Paras";
?>