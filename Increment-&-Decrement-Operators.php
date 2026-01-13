<?php

    $x = 10;
    echo "++$x is =" . (++$x) . "\n"; // Outputs: 11
    echo "Now x is =" . $x . "\n";   // Outputs: 11
    
    $x = 10;
    echo "$x++ is =" . ($x++) . "\n"; // Outputs: 10
    echo "Now x is =" . $x . "\n";   // Outputs: 11
    
    $x = 10;
    echo "--$x is =" . (--$x) . "\n"; // Outputs: 9
    echo "Now x is =" . $x . "\n";   // Outputs: 9
    
    $x = 10;
    echo "$x-- is =" . ($x--) . "\n";// Outputs: 10
    echo "Now x is =" . $x;   // Outputs: 9

?>