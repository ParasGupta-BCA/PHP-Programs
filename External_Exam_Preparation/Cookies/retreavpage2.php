<?php

$username=$_COOKIE['userinfo'];
$password=$_COOKIE['Password'];

echo "With the help of cookies we have retreve all the details from here in second page!<br><br>";
echo "Username In Second Page: ".$username."<br>";
echo "Password In Second Page: ".$password;

?>