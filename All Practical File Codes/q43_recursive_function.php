<?php
  function selfMultiply(& $number) {
    $number = $number * $number;
    return $number;
  }
  $mynum = 5;
  echo $mynum . "<br>";
  selfMultiply($mynum);
  echo $mynum;
  echo "<br>This Program is Written & executed by Paras";
?>