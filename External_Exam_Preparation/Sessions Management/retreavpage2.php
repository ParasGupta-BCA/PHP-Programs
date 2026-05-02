<?php

session_start();

$uname=$_SESSION['username'];
$upass=$_SESSION['password'];

echo "With the help of session we have retreve all the details from here in second page!<br><br>";
echo "Username In Second Page: ".$uname."<br>";
echo "Password In Second Page: ".$upass;

session_unset();
session_destroy();

?>