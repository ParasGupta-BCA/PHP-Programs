<?php

$emailError = ""; // Blank Varable of string
$phoneError = ""; // Blank Varable

if (isset($_POST['submit'])) {

    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email";
    }

    // Check phone (10 digits)
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $phoneError = "Enter 10 digit phone";
    }

    // Success
    if ($emailError == "" && $phoneError == "") {
        echo "<h3>Registration Successful</h3>";
    }
}
?>

<html>
<head>
    <title>Registration Form</title>
</head>
<body>

<h2>Register Form</h2>

<form method="post">
    
    Email: <input type="text" name="email">
    <?php echo $emailError; ?>
    <br><br>

    Phone: <input type="text" name="phone">
    <?php echo $phoneError; ?>
    <br><br>

    <input type="submit" name="submit" value="Submit">
</form>

</body>
</html>