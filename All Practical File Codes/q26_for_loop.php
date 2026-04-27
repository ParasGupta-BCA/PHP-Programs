<?php
  echo "First Way<br>";
  for ($i = 1; $i <= 10; $i++) {
    echo $i;
    echo "<br>";
  }

  echo "<br> Another Way <br>";
  for ($i = 1; ; $i++) {
    if ($i > 10) {
      break;
    }
    echo $i;
    echo "<br>";
  }
  echo "<br>This Program is Written & executed by Paras";
?>