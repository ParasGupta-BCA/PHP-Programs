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
<footer style="position:fixed; left:0; right:0; bottom:0; background:#f8f8f8; padding:10px 0; text-align:center; border-top:1px solid #e0e0e0; font-family:Arial, sans-serif;">
    <strong>Code Is Writen By <a href="https://www.linkedin.com/in/parasgupta-binary0101">Paras Gupta</a></strong>
</footer>
</html>
