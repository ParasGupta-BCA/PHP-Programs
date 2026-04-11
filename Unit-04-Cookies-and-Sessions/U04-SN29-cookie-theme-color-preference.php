<!-- Personalize User Theme Color -->
<?php
// Set cookie when user selects a color
if (isset($_POST['color'])) {
    $color = $_POST['color'];
    setcookie("theme_color", $color, time() + (86400 * 30)); // 30 days
    $_COOKIE['theme_color'] = $color; // update instantly
}

// Default color
$theme = isset($_COOKIE['theme_color']) ? $_COOKIE['theme_color'] : "#ffffff";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Theme Color</title>
</head>

<body style="background-color: <?php echo $theme; ?>;">

<h2>Choose Your Theme Color</h2>

<form method="post">
    <select name="color">
        <option value="#ffffff">White</option>
        <option value="#f8d7da">Light Red</option>
        <option value="#d4edda">Light Green</option>
        <option value="#d1ecf1">Light Blue</option>
        <option value="#fff3cd">Light Yellow</option>
    </select>
    <br><br>
    <input type="submit" value="Save Theme">
</form>

<h3>Your selected theme is saved using cookies!</h3>

</body>
</html>
<!-- 🧠 1. Concepts Used (Theory)
🔹 1. Cookies in PHP
Cookies store data on the client-side (browser)
Used to remember user preferences

👉 Here:

Theme color is saved in a cookie
🔹 2. Form Handling (POST)
<form method="post">
User selects a color and submits it
🔹 3. Conditional Checking
isset($_POST['color'])
Checks if user selected a color
🔹 4. Default Value Handling
$theme = isset($_COOKIE['theme_color']) ? $_COOKIE['theme_color'] : "#ffffff";

👉 If cookie exists → use saved color
👉 Otherwise → use default (white)

🔹 5. Dynamic Styling
<body style="background-color: <?php echo $theme; ?>;">

👉 Changes page background dynamically based on cookie value

🔄 2. Flow of Execution
Step 1: User Selects Color
<select name="color">

Options:

White
Light Red
Light Green
Light Blue
Light Yellow
Step 2: Form Submission
if (isset($_POST['color']))

👉 Executes when form is submitted

Step 3: Store Selected Color
$color = $_POST['color'];
Step 4: Set Cookie
setcookie("theme_color", $color, time() + (86400 * 30));
📌 Explanation:
"theme_color" → cookie name
$color → selected value
time() + (86400 * 30) → expires in 30 days
Step 5: Immediate Update
$_COOKIE['theme_color'] = $color;

👉 Updates cookie value instantly in current request
👉 Otherwise, change would reflect only after reload

Step 6: Set Theme Value
$theme = isset($_COOKIE['theme_color']) ? $_COOKIE['theme_color'] : "#ffffff";

👉 Determines background color

Step 7: Apply Theme
<body style="background-color: <?php echo $theme; ?>;">

👉 Page background changes based on selected color

Step 8: Display Message
<h3>Your selected theme is saved using cookies!</h3>

👉 Confirms persistence

🔑 3. Key Functions Used
Function	Purpose
setcookie()	Store cookie
isset()	Check variable existence
$_POST	Get form data
$_COOKIE	Access stored cookie
time()	Set expiry time
📌 4. Important Logic
✅ Cookie Persistence
Theme remains even after:
Page reload
Browser restart (within 30 days)
✅ Default Handling
If no cookie → white background is used
✅ Instant Update Trick
$_COOKIE['theme_color'] = $color;
Reflects change immediately without reload
📊 5. Overall Working (In One Line)

👉 This program allows users to select a theme color, stores it in a cookie, and applies it dynamically to the page background for future visits. -->