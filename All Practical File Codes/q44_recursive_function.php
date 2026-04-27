<?php
  function SelfMultiply(& $number) {
    $number = $number * $number;
    return $number;
  }
  $mynum = 5;
  echo $mynum . "<br>";
  SelfMultiply($mynum);
  echo $mynum;
  echo "<br>This Program is Written & executed by Paras";
?>