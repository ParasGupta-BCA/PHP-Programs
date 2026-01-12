<html>
    <head>
        <title>
            PHP CONSTANTS
        </title>
    </head>
<Body>
    <center>
    <h1>PHP CONSTANTS</h1>
<?php
     define('PI',3.14);
     echo PI;
     /***
      * Now This Code Will Give Warning Now
      * Not Because Constant Cannot That Variable 
      *
      * define('PI',13.14);
      * echo PI; 
     ***/

    // Now If We To Calculate Area See The Code:
    $r=12.45;
    $a=PI*$r*$r;
    echo "<br><b>Area Is: </b>", $a;

    // Now If We To Calculate Circumference For Circle See The Code:
    $c=2*PI*$r;
    echo "<br><b>Circumference Is: </b>",$c;
?>
    </center>
</Body>
</html>