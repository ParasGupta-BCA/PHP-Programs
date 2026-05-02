<html>
    <body>
<?php

        if(isset($_POST['submit'])){

        $username=$_POST['username'];
        $password=$_POST['password'];
        
        echo "POST Users Info Display:<br><br>";

        echo "Username: ".$username."<br>";
        echo "Password: ".$password."<br>";

        // Code For Cookies To Retrive that Login Details To Other page also.

        setcookie("userinfo", $username, time() + 3600, "/"); 
        setcookie("Password", $password, time() + 3600, "/");

        
        echo "<br>Cookies Users Info Display:<br><br>";
        echo "Username: ".$username."<br>";
        echo "Password: ".$password."<br>";
        }
?>

<form method="POST" action="retreavpage2.php">
    <br><button type="submit" name="nextpage" vlaue="nextpage">Go To Next Page</button>
</form>
</body>
</html>