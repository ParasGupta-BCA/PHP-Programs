<!-- Income Tax Calculator with employee id, bs, da (50% of bs), hra (30% of bs), and tax calculation in table format -->
<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Income Tax Calculator</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
        }
    </style>
</head>
<body>

<h1>Income Tax Calculator</h1>

<form method="post">
    <label>Employee ID:</label>
    <input type="text" name="employee_id" required><br><br>

    <label>Basic Salary:</label>
    <input type="number" name="basic_salary" min="0" required><br><br>

    <input type="submit" name="submit" value="Add">
</form>

<?php
if (isset($_POST['submit'])) {

    $employee_id = $_POST['employee_id'];
    $basic_salary = $_POST['basic_salary'];

    if (is_numeric($basic_salary) && $basic_salary >= 0) {

        $da = 0.5 * $basic_salary;
        $hra = 0.3 * $basic_salary;
        $net_salary = $basic_salary + $da + $hra;

        $found = false;

        // ✅ Check & Update existing record
        if (!empty($_SESSION['records'])) {
            foreach ($_SESSION['records'] as &$row) {
                if ($row['id'] == $employee_id) {
                    $row['bs'] = $basic_salary;
                    $row['da'] = $da;
                    $row['hra'] = $hra;
                    $row['net'] = $net_salary;
                    $found = true;
                    break;
                }
            }
        }

        // ✅ Add new only if not found
        if (!$found) {
            $_SESSION['records'][] = [
                'id' => $employee_id,
                'bs' => $basic_salary,
                'da' => $da,
                'hra' => $hra,
                'net' => $net_salary
            ];
        }

    } else {
        echo "<p style='color:red;'>Invalid salary</p>";
    }
}

// ✅ Display table
if (!empty($_SESSION['records'])) {

    echo "<h2>All Records</h2>";
    echo "<table>";
    echo "<tr>
            <th>Employee ID</th>
            <th>Basic Salary</th>
            <th>DA</th>
            <th>HRA</th>
            <th>Net Salary</th>
          </tr>";

    foreach ($_SESSION['records'] as $row) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>" . number_format($row['bs'], 2) . "</td>
                <td>" . number_format($row['da'], 2) . "</td>
                <td>" . number_format($row['hra'], 2) . "</td>
                <td>" . number_format($row['net'], 2) . "</td>
              </tr>";
    }

    echo "</table>";
}
?>

</body>
</html>
<!-- 🧠 1. Concepts Used (Theory)
🔹 1. Session Handling
session_start();
Used to store employee records
Data persists across page reloads

👉 Stores all employee salary data in:

$_SESSION['records']
🔹 2. Form Handling (POST)
<form method="post">
Sends employee ID and basic salary to server
🔹 3. Input Validation
is_numeric($basic_salary) && $basic_salary >= 0

👉 Ensures:

Salary is a number
Salary is not negative
🔹 4. Salary Calculation Logic
DA (Dearness Allowance) = 50% of Basic Salary
HRA (House Rent Allowance) = 30% of Basic Salary
Net Salary = BS + DA + HRA
🔹 5. Associative Arrays

Each employee record is stored as:

[
  'id' => employee_id,
  'bs' => basic_salary,
  'da' => da,
  'hra' => hra,
  'net' => net_salary
]
🔹 6. Looping & Updating Data
foreach loop is used to:
Search existing employee
Update or insert data
🔹 7. Table Display
HTML table used to show all records
number_format() formats numbers to 2 decimal places
🔄 2. Flow of Execution
Step 1: Start Session
session_start();
Enables session storage
Step 2: Display Form
Inputs:
Employee ID
Basic Salary
Step 3: Form Submission Check
if (isset($_POST['submit']))
Runs logic only when "Add" button is clicked
Step 4: Get Input Values
$employee_id = $_POST['employee_id'];
$basic_salary = $_POST['basic_salary'];
Step 5: Validate Salary
if (is_numeric($basic_salary) && $basic_salary >= 0)

👉 If invalid → show error message

Step 6: Calculate Salary Components
$da = 0.5 * $basic_salary;
$hra = 0.3 * $basic_salary;
$net_salary = $basic_salary + $da + $hra;

👉 Formula:

DA = 50%
HRA = 30%
Net = Total
Step 7: Check Existing Record
$found = false;
foreach ($_SESSION['records'] as &$row)
Loop through session records
🔹 If Employee Exists
if ($row['id'] == $employee_id)

👉 Update values:

$row['bs'] = $basic_salary;
$row['da'] = $da;
$row['hra'] = $hra;
$row['net'] = $net_salary;
Set $found = true
🔹 If Employee Not Found
if (!$found)

👉 Add new record:

$_SESSION['records'][] = [...]
Step 8: Display Records Table
if (!empty($_SESSION['records']))

👉 Table headings:

Employee ID
Basic Salary
DA
HRA
Net Salary
Step 9: Loop & Display Data
foreach ($_SESSION['records'] as $row)

👉 Print each row:

<td>{$row['id']}</td>
<td>" . number_format($row['bs'], 2) . "</td>
🔑 3. Key Functions Used
Function	Purpose
session_start()	Start session
isset()	Check form submission
is_numeric()	Validate numeric input
foreach	Loop through records
number_format()	Format numbers
$_SESSION	Store data
📊 4. Salary Formula Summary
DA = 50% of Basic Salary
HRA = 30% of Basic Salary
Net Salary = BS + DA + HRA
📌 5. Special Logic (Important)
✅ Update Existing Record
If same Employee ID is entered again
Old data is replaced
✅ Insert New Record
If Employee ID is new
New row is added
📌 6. Overall Working (In One Line)

👉 This program calculates employee salary components, stores multiple records in session, updates existing entries, and displays all data in a table format. -->