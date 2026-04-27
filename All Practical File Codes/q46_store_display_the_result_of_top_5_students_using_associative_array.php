<?php
  $student = ["Rahul" => 85, "Amit" => 90, "Neha" => 88, "Priya" => 92, "Karan" => 80];
  echo "Top 5 Students Result: <br>";
  foreach ($student as $name => $marks) {
    echo "Name: " . $name . " Marks => " . $marks . "<br>";
  }
  echo "<br>This Program is Written & executed by Paras";
?>