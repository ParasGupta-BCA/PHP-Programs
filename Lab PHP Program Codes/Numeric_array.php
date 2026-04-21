<html>
    <head>
        <title>
            PHP NUMERIC ARRAY
        </title>
    </head>
<body>
   <?php
$number = array(1, 2, 3, 4, 5);

foreach ($number as $value) {
    echo "Value is $value\n";
}

$number[0] = "One";
$number[1] = "Two";
$number[2] = "Three";
$number[3] = "Four";
$number[4] = "Five";

foreach ($number as $value) {
    echo "Value is $value\n";
}

?>
</body>
<footer style="position:fixed; left:0; right:0; bottom:0; background:#f8f8f8; padding:10px 0; text-align:center; border-top:1px solid #e0e0e0; font-family:Arial, sans-serif;">
    <strong>Code Is Writen By <a href="https://www.linkedin.com/in/parasgupta-binary0101">Paras Gupta</a></strong>
</footer>
</html>