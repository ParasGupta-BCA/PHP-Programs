<?php
  $products = array(
    "Hair care" => array(
      array("Name" => "Shampoo", "Brand" => "Dove", "Price" => 250),
      array("Name" => "Hair oil", "Brand" => "Parachute", "Price" => 180),
      array("Name" => "Conditioner", "Brand" => "Pantene", "Price" => 300)
    ),
    "Beauty" => array(
      array("Name" => "Face Wash", "Brand" => "Garnier", "Price" => 150),
      array("Name" => "Lipstick", "Brand" => "Lakme", "Price" => 450),
      array("Name" => "Face bleach cream", "Brand" => "Ponds", "Price" => 220)
    )
  );
  foreach ($products as $category => $items) {
    echo "<tr>";
    echo "<td>" . $items["Name"] . "</td>";
    echo "<td>" . $items["Brand"] . "</td>";
    echo "<td>" . $items["Price"] . "</td>";
    echo "</tr>";
  }
  echo "</table><br><br>";
  echo "<br>This Program is Written & executed by Paras";
?>