<html>
    <head>
        <title>
            Registration Form For GET Method
        </title>
    </head>
    <body>
        <h2>Registration Form For GET</h2>
        <form method="GET" action="$_Get.php">
            Username: <input type="text" name="username" required><br><br>
            Password: <input type="password" name="password" required><br><br>
            
            <button type="submit" name="submit" value="submit">Submit</button>
            <button type="reset" name="reset" value="reset">Reset</button>
        </form>

        <?php

        if(isset($_GET['submit'])){

        $username=$_GET['username'];
        $password=$_GET['password'];

        echo "<h3>Display Output For Registration Form With GET Method</h3>";
        echo "Username: ".$username."<br>";
        echo "Password: ".$password."<br>";

        }
        ?>
    </body>
</html>