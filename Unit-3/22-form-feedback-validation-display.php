<!-- feedback form with name, address, mobileno, email and rating from 1-5 options and submit and reset buttons with validation-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <style>
        .feedback-form {
            width: 400px;
            padding: 20px;
            border: 1px solid #ccc;     
            border-radius: 5px;
            margin: 0 auto;
        }
        .feedback-form label {
            display: block;
            margin-bottom: 5px; 
        }
        .feedback-form input, .feedback-form select {
            width: 90%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .feedback-form button {
            width: 90%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;       
            border-radius: 3px;
            cursor: pointer;
        }
        .feedback-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="feedback-form">
        <h1 style="text-align: center;">Feedback Form</h1>
        <hr>
        <form method="post" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <label for="phone">Mobile No:</label>
            <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="rating">Rating:</label>
            <select id="rating" name="rating" required>
                <option value="">Select Rating</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <button type="submit" name="submit">Submit</button>
            <button type="reset">Reset</button>
        </form>
    </div>
    <?php
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];           
        $email = $_POST['email'];
        $rating = $_POST['rating'];
        echo "<h2>Feedback Received</h2>";
        echo "<p>Name: $name</p>";
        echo "<p>Address: $address</p>";
        echo "<p>Mobile No: $phone</p>";
        echo "<p>Email: $email</p>";
        echo "<p>Rating: $rating</p>";
    }
    ?>
</body>
</html>
<!-- 🧠 1. Concepts Used (Theory)
🔹 1. HTML Form Handling
Form collects user input:
Name
Address
Mobile Number
Email
Rating
<form method="post">

👉 Data is sent to the same page using POST method

🔹 2. Client-Side Validation

Validation using HTML attributes:

required → mandatory fields
type="email" → valid email format
pattern="[0-9]{10}" → exactly 10-digit mobile number
<select required> → forces user to choose rating

👉 Prevents invalid input before sending to server

🔹 3. Form Controls Used
Control	Purpose
input type="text"	Name, Address
input type="tel"	Mobile number
input type="email"	Email validation
select	Rating (1–5)
button submit	Send data
button reset	Clear form
🔹 4. Server-Side Handling (PHP)
Uses:
$_POST

to receive data

Checks form submission:
isset($_POST['submit'])
🔹 5. Output Display
Data is directly displayed using:
echo

👉 This shows submitted feedback on the same page

🔄 2. Flow of Execution
Step 1: Display Form
User sees a styled feedback form inside a box
CSS centers and formats it
Step 2: User Enters Data

Fields:

Name
Address
Mobile Number
Email
Rating (1–5)

👉 Validation ensures correct input

Step 3: Submit Button Click
if (isset($_POST['submit']))
Executes PHP block only after submission
Step 4: Collect Data
$name = $_POST['name'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$rating = $_POST['rating'];

👉 Data is fetched from form inputs

Step 5: Display Feedback
echo "<h2>Feedback Received</h2>";

Then each field is printed:

echo "<p>Name: $name</p>";
echo "<p>Address: $address</p>";
echo "<p>Mobile No: $phone</p>";
echo "<p>Email: $email</p>";
echo "<p>Rating: $rating</p>";

👉 Output is shown on the same page

🔑 3. Key Functions Used
Function	Purpose
isset()	Check form submission
$_POST	Retrieve form data
echo	Display output
📌 4. Validation Summary
Field	Validation
Name	Required
Address	Required
Mobile	10 digits
Email	Valid email format
Rating	Must be selected
📊 5. Buttons Behavior
🔹 Submit Button
<button type="submit" name="submit">
Sends data to server
Triggers PHP code
🔹 Reset Button
<button type="reset">
Clears all input fields
No server interaction
📌 6. Overall Working (In One Line)

👉 This program collects user feedback with validation and displays the submitted data on the same page. -->