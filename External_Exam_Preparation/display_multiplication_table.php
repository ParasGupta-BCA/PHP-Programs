<html>
    <head>
        <title>
            Display Multiplication Table Of A User-given Number.
        </title>
    </head>
    <body>
        <h1>Display Multiplication Table Of A User-given Number.</h1><br>

        <form method="POST" action="display_multiplication_table.php">

            Enter The Number For Multiplication: <input type="number" name="multi" required><br><br>

            <button type="submit" name="submit" value="submit">Submit</button>

        </form>
        
        <br>
        <?php
        if(isset($_POST['submit'])){
            $multi=$_POST['multi'];

            echo "<h2>Multiplication Table for $multi:</h2>";
            for ($i = 1; $i <= 10; $i++) {
                $disply_multi = $i * $multi;
                echo "$multi X $i = $disply_multi<br>";
            }
        }
        ?>
    </body>
</html>

// 
