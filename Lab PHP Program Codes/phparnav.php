<?php
  $myfile = fopen("newfile.txt","w");
  txt = "John Doe \n";
  fwrite($myfile,$txt);
  $txt = "John Doe \n";
  fwrite($myfile,$txt);
  fclose($myfile);
?>