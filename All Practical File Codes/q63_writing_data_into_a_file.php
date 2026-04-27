<?php
  $myfile = fopen("newfile.txt") or die("Unable to open file");
  $txt = "John Doe\n";
  fputs($myfile . $txt);
  fwrite($myfile, $txt);
  fclose($myfile);
  echo "<br>This Program is Written & executed by Paras";
?>