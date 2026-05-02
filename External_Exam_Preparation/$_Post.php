<html>
    <head>
        <title>
            Registration Form For POST Method
        </title>
    </head>
    <body>
        <h2>Registration Form For POST</h2>
        <form method="POST" action="$_Post.php">
            Username: <input type="text" name="username" required><br><br>
            Password: <input type="password" name="password" required><br><br>
            
            <button type="submit" name="submit" value="submit">Submit</button>
            <button type="reset" name="reset" value="reset">Reset</button>
        </form>

        <?php

        if(isset($_POST['submit'])){

        $username=$_POST['username'];
        $password=$_POST['password'];

        echo "<h3>Display Output For Registration Form With POST Method</h3>";
        echo "Username: ".$username."<br>";
        echo "Password: ".$password."<br>";

        }
        ?>
    </body>
</html>