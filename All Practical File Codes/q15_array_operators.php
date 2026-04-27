<?php
  $y = array("c" => "Blue", "d" => "Yellow");
  $x = array("a" => "Red", "b" => "Green", "c" => "Blue");

  $z = $x + $y;
  var_dump($z);
  var_dump($x == $y);
  var_dump($x != $y);
  var_dump($x !== $y);
  echo "<br>This Program is Written & executed by Paras";
?>