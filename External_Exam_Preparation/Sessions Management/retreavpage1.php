<html>
    <body>
<?php

        if(isset($_POST['submit'])){

        $username=$_POST['username'];
        $password=$_POST['password'];
        
        echo "POST Users Info Display:<br><br>";

        echo "Username: ".$username."<br>";
        echo "Password: ".$password."<br>";

        // Code For Session To Retrive that Login Details To Other page also.

        session_start();
        $_SESSION['username']=$username;
        $_SESSION['password']=$password;

        }
?>

<form method="POST" action="retreavpage2.php">
    <br><button type="submit" name="nextpage" vlaue="nextpage">Go To Next Page</button>
</form>
</body>
</html>