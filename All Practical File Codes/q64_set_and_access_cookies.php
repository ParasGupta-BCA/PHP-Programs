<?php
  setcookie("Username", "Shavi Bansal", time() + 30 * 24 * 60 * 60);
  if (isset($_COOKIE["Username"])) {
    echo "Hi" . $_COOKIE["Username"] . "<br>";
  }
  print_r($_COOKIE);
  echo "<br>This Program is Written & executed by Paras";
?>