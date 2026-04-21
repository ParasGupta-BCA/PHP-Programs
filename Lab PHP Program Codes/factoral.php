<?php

$number = 4200;
$factors = array();
$count = 0;

for ($i = 1; $i <= $number; $i++) {

    if ($number % $i == 0) {
        $factors[] = $i;
        $count++;
    }

    if ($count == 10) {
        break;
    }
}

foreach ($factors as $factor) {
    echo "factor is " . $factor . "<br>";
}

?>
<footer style="position:fixed; left:0; right:0; bottom:0; background:#f8f8f8; padding:10px 0; text-align:center; border-top:1px solid #e0e0e0; font-family:Arial, sans-serif;">
    <strong>Code Is Writen By <a href="https://www.linkedin.com/in/parasgupta-binary0101">Paras Gupta</a></strong>
</footer>