<?php
  $marks = array(
    "Rakshit" => array("Physics" => 35, "maths" => 30, "history" => 39),
    "Zanish" => array("Physics" => 30, "maths" => 32, "history" => 31),
    "Ajeet" => array("Physics" => 31, "maths" => 22, "history" => 34)
  );

  // Accessing Values
  echo "Marks for Rakshit in Physics: ";
  echo $marks["Rakshit"]["Physics"] . "<br>";
  echo "Marks for Zanish in maths: ";
  echo $marks["Zanish"]["maths"] . "<br>";
  echo "Marks for Ajeet in history: ";
  echo $marks["Ajeet"]["history"] . "<br>";
  echo "<br>This Program is Written & executed by Paras";
?>