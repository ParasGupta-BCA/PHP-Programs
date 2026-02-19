<html>
    <head>
        <title>Function In PHP</title>
    </head>
    <body>
    <center>
        <h1>
        <?php
            function display(){// Definition of function
                echo "This is a function code<br>";
            }
            function sum($a,$b){
                $c=$a+$b;
                echo "Addition is: ".$c."<br>";
            }
            display(); //calling of function
            sum(50,20); //Arguments
            echo "End Program";
        ?>
    </h1>
    </center>
    </body>
<footer style="position:fixed; left:0; right:0; bottom:0; background:#f8f8f8; padding:10px 0; text-align:center; border-top:1px solid #e0e0e0; font-family:Arial, sans-serif;">
    <strong>Code Is Writen By <a href="https://www.linkedin.com/in/parasgupta-binary0101">Paras Gupta</a></strong>
</footer>
</html>