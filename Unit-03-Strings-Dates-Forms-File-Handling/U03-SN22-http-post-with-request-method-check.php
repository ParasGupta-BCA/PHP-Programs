<!-- post method  -->
 <!-- POST Method -->
<!DOCTYPE html>
<html>
<head>
    <title>GET and POST Methods in PHP</title>
</head>
<body>
<h1>GET and POST Methods in PHP</h1>
 <h2>POST Method</h2>
<form method="post">
    Name: <input type="text" name="name"><br><br>
    Email: <input type="email" name="email"><br><br>
    <input type="submit" value="Submit">
</form>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['email'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    echo "POST Method - Name: " . $name . "<br>";
    echo "POST Method - Email: " . $email;
}
?>
</body>
</html>
<!-- 🧠 1. Concept Overview (Theory)
🔹 GET Method
Sends data via URL
Data is visible in address bar
Used for:
Fetching data
Non-sensitive operations

👉 Example:

page.php?name=Akshay&email=test@gmail.com
🔹 POST Method
Sends data via HTTP body
Data is NOT visible in URL
Used for:
Sensitive data
Form submissions

👉 More secure than GET

🔄 2. GET Method – Code Explanation
🔹 Form Definition
<form method="get">
Data is sent using GET method
🔹 Input Fields
<input type="text" name="name">
<input type="email" name="email">

👉 Values will be passed in URL

🔹 Form Submission Handling
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['name']) && isset($_GET['email']))
📌 Explanation:
$_SERVER["REQUEST_METHOD"] == "GET" → ensures request type
isset($_GET['name']) → checks if data exists
🔹 Fetch Data
$name = $_GET['name'];
$email = $_GET['email'];

👉 Retrieves data from URL

🔹 Display Output
echo "GET Method - Name: " . $name;
echo "GET Method - Email: " . $email;
🔄 3. POST Method – Code Explanation
🔹 Form Definition
<form method="post">
Data is sent using POST method
🔹 Form Handling Condition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['email']))
📌 Explanation:
Checks request is POST
Ensures values are present
🔹 Fetch Data
$name = $_POST['name'];
$email = $_POST['email'];

👉 Retrieves data from request body

🔹 Display Output
echo "POST Method - Name: " . $name;
echo "POST Method - Email: " . $email;
🔑 4. Key Differences (Important Table)
Feature	GET	POST
Data Location	URL	Request Body
Visibility	Visible	Hidden
Security	Less secure	More secure
Data Size	Limited	Large
Bookmarking	Possible	Not possible
Use Case	Fetch data	Submit data
🔧 5. Functions / Variables Used
Element	Purpose
$_GET	Get data from URL
$_POST	Get data from form body
$_SERVER["REQUEST_METHOD"]	Check request type
isset()	Check variable existence
🔄 6. Flow of Execution
For GET:
User fills form
Clicks submit
Data goes in URL
PHP reads using $_GET
Output displayed
For POST:
User fills form
Clicks submit
Data sent in background
PHP reads using $_POST
Output displayed
📌 7. Final Summary (Exam Line)

👉 GET method sends data through the URL and is less secure, whereas POST method sends data through the request body and is more secure, making it suitable for sensitive data handling. -->