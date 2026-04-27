<?php
  // define a string
  $str = "Hello World";

  // strlen() -> Return length of string
  echo strlen($str);
  echo "<br>";

  // str_word_count() -> counts number of words
  echo str_word_count($str);
  echo "<br>";

  // str_replace() -> replaces word in string
  echo str_replace("World", "PHP", $str);
  echo "<br>";

  // strrev() -> reverses string
  echo strrev($str);
  echo "<br>";

  // strtolower() -> converts string to lowercase
  echo strtolower($str);
  echo "<br>";

  // strtoupper() -> converts string to uppercase
  echo strtoupper($str);
  echo "<br>";

  // ucfirst() -> capitalizes first letter of string
  echo ucfirst($str);
  echo "<br>";

  // ucwords() -> capitalizes first letter of each word
  echo ucwords($str);
  echo "<br>";

  // strpos() -> finds position of first occurrence of word
  echo strpos($str, "World");
  echo "<br>";

  // substr() -> extracts part of string
  echo substr($str, 0, 5);
  echo "<br>This Program is Written & executed by Paras";
?>