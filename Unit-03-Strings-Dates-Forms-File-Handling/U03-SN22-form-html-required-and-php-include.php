<!-- what do you meant by required and include? using code-->
<?php
// include file (database or header)
include __DIR__ . "/U03-SN22-include-target-hello-world.php"; // This will include the content of footer.php here
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Example</title>
</head>
<body>

<form method="post">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" value="Submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    echo "Name: " . $name . "<br>";
    echo "Email: " . $email;
}
?>

</body>
</html>
<!-- 🧠 1. required Attribute (HTML)
🔹 Definition
required is an HTML validation attribute
It ensures that the field must be filled before submitting the form
🔹 Code from Your Program
<input type="text" name="name" required>
<input type="email" name="email" required>
🔹 Working

👉 When user clicks Submit:

If fields are empty → form will NOT submit
Browser shows validation message
🔹 Key Point

required works on client-side (browser validation)

🧠 2. include in PHP
🔹 Definition
include is used to insert one PHP file into another
Helps in code reusability
🔹 Code from Your Program
include __DIR__ . "/U03-SN22-include-target-hello-world.php";
🔹 Working

👉 PHP will:

Open hello.php
Insert its content at this line
Execute it as part of the current file
🔹 Example Use
Header file
Footer file
Database connection file
🔹 Important Behavior

👉 If file is NOT found:

include gives warning
Script will STILL continue execution
🔄 3. Flow of Execution (Your Code)
Step 1: Include File
include __DIR__ . "/U03-SN22-include-target-hello-world.php";
External file is loaded first
Step 2: Display Form
User sees:
Name field
Email field
Step 3: Validation using required
If fields empty → submission blocked
If filled → form submitted
Step 4: Check Request Type
if ($_SERVER["REQUEST_METHOD"] == "POST")
Step 5: Get Data
$name = $_POST['name'];
$email = $_POST['email'];
Step 6: Display Output
echo "Name: " . $name;
echo "Email: " . $email;
🔑 4. Difference (Very Important)
Feature	required	include
Type	HTML attribute	PHP statement
Purpose	Validation	File inclusion
Works On	Client-side	Server-side
Usage	Form fields	Code reuse
📌 5. Final Summary (Exam Line)

👉 required is an HTML attribute used for client-side form validation, while include is a PHP statement used to insert and execute another file within the current script. -->