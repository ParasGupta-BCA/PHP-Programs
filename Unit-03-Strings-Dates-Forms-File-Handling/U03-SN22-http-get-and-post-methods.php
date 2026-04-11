<!-- GET and POST Methods in PHP -->

<!DOCTYPE html>
<html>
<head>
    <title>GET Methods in PHP</title>
</head>
<body>
<h1>GET Methods in PHP</h1>
<!-- GET Method -->
<h2>GET Method</h2>
<form method="get">
    Name: <input type="text" name="name"><br><br>
    Email: <input type="email" name="email"><br><br>
    <input type="submit" value="Submit">
</form>
<?php
    $name = $_GET['name'];
    $email = $_GET['email'];    
    echo "GET Method - Name: " . $name . "<br>";
    echo "GET Method - Email: " . $email;
?>
</body>
</html>

<!-- POST Methods in PHP -->

<!DOCTYPE html>
<html>
<head>
    <title>POST Methods in PHP</title>
</head>
<body>
<h1>POST Methods in PHP</h1>
<!-- POST Method -->
<h2>POST Method</h2>
<form method="POST">
    Name: <input type="text" name="name"><br><br>
    Email: <input type="email" name="email"><br><br>
    <input type="submit" value="Submit">
</form>
<?php
    $name = $_POST['name'];
    $email = $_POST['email'];    
    echo "POST Method - Name: " . $name . "<br>";
    echo "POST Method - Email: " . $email;
?>
</body>
</html>