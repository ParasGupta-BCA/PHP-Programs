<!-- Student Record Management System where student name, attendance, marks of 3 subjects and percentage will be calculated 
 person having attance percentage x and is its higher than that will be passwed other will be failed and a result it should show where pass or fail  with percentage  -->

 <!-- display in tabular format just below form in php-->
 
<html>
<head>
    <title>Student Record System</title>
</head>

<body>

<h1>Student Record System</h1>

<!-- Form to take input -->
<form method="post">

    Name: <input type="text" name="name" required><br>

    Attendance (%): <input type="number" name="attendance"><br>

    Subject 1 Marks: <input type="number" name="sub1"><br>

    Subject 2 Marks: <input type="number" name="sub2"><br>

    Subject 3 Marks: <input type="number" name="sub3"><br>

    //Submit Button
    <input type="submit" name="submit" value="Submit">

</form>

<?php
// Check if form is submitted
if (isset($_POST['submit'])) {

    // Get form data
    $name = $_POST['name'];
    $attendance = $_POST['attendance'];
    $s1 = $_POST['sub1'];
    $s2 = $_POST['sub2'];
    $s3 = $_POST['sub3'];

    // Calculate total and percentage
    $total = $s1 + $s2 + $s3;
    $percentage = ($total / 300) * 100;

    // Check result (Pass/Fail)
    if ($attendance >= 75 && $percentage >= 40) {
        $result = "Pass";
    } else {
        $result = "Fail";
    }

    // Display output
    echo "<h2>Result</h2>";
    echo "Name: $name <br>";
    echo "Attendance: $attendance% <br>";
    echo "Total Marks: $total <br>";
    echo "Percentage: " . round($percentage, 2) . "% <br>";
    echo "Result: $result <br>";
}
?>

</body>
</html>


<!-- 🧠 1. Concepts Used (Theory)
🔹 1. HTML Form Handling
<form method="post">
Collects student details:
Name
Attendance
Marks (3 subjects)
🔹 2. Client-Side Validation
required → mandatory fields
min="0" and max="100" → valid range for marks & attendance

👉 Ensures valid input before submission

🔹 3. Server-Side Handling (PHP)
isset($_POST['submit'])
Checks whether form is submitted
🔹 4. Arithmetic Calculations
Total Marks
Percentage calculation
🔹 5. Conditional Logic (Decision Making)
($attendance >= 75 && $percentage >= 40)

👉 Conditions:

Attendance ≥ 75%
Percentage ≥ 40%
🔹 6. Ternary Operator
$result = condition ? "Pass" : "Fail";

👉 Short form of if-else

🔹 7. Table Display
Output displayed using HTML table
number_format() used for formatting percentage
🔄 2. Flow of Execution
Step 1: Display Form
User enters:
Name
Attendance %
Marks of 3 subjects
Step 2: Submit Form
if (isset($_POST['submit']))
Step 3: Fetch Input Data
$name = $_POST['name'];
$attendance = $_POST['attendance'];
$subject1 = $_POST['subject1'];
$subject2 = $_POST['subject2'];
$subject3 = $_POST['subject3'];
Step 4: Calculate Total Marks
$totalMarks = $subject1 + $subject2 + $subject3;

👉 Maximum = 300

Step 5: Calculate Percentage
$percentage = ($totalMarks / 300) * 100;

👉 Formula:

Percentage = (Obtained / Total) × 100
Step 6: Determine Result
$result = ($attendance >= 75 && $percentage >= 40) ? "Pass" : "Fail";

👉 Logic:

If BOTH conditions true → Pass
Otherwise → Fail
Step 7: Display Result in Table
Table Headers:
Name
Attendance
Subject 1, 2, 3 marks
Total Marks
Percentage
Result
Output Row:
echo "<tr>
<td>$name</td>
<td>$attendance</td>
<td>$subject1</td>
<td>$subject2</td>
<td>$subject3</td>
<td>$totalMarks</td>
<td>" . number_format($percentage, 2) . "</td>
<td>$result</td>
</tr>";
🔑 3. Key Functions / Features
Element	Purpose
isset()	Check form submission
$_POST	Get input data
Arithmetic operators	Calculate marks
Ternary operator	Decide result
number_format()	Format percentage
HTML table	Display output
📊 4. Logic Summary
Total = Sum of 3 subjects
Percentage = (Total / 300) × 100
Pass Condition:
Attendance ≥ 75%
Percentage ≥ 40%
📌 5. Important Condition Insight

👉 BOTH conditions must be satisfied:

Attendance condition AND Percentage condition
If one fails → Result = Fail
📌 6. Overall Working (In One Line)

👉 This program collects student details, calculates total and percentage, evaluates pass/fail based on attendance and marks, and displays the result in a table format. -->