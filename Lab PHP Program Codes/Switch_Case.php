<?php
/**
 * Program: Switch Case Demonstration
 * Description: Uses a switch statement to display the day of the week.
 */

$day = date("w"); // Get current day of the week (0 for Sunday, 6 for Saturday)

echo "<h2>Switch Case: Day of the Week</h2>";
echo "Today is: ";

switch ($day) {
    case 0:
        echo "<strong>Sunday</strong> - Time to relax!";
        break;
    case 1:
        echo "<strong>Monday</strong> - Back to work/college.";
        break;
    case 2:
        echo "<strong>Tuesday</strong> - Keeping the momentum.";
        break;
    case 3:
        echo "<strong>Wednesday</strong> - Hump day!";
        break;
    case 4:
        echo "<strong>Thursday</strong> - Almost there.";
        break;
    case 5:
        echo "<strong>Friday</strong> - Weekend is coming!";
        break;
    case 6:
        echo "<strong>Saturday</strong> - Enjoy your weekend!";
        break;
    default:
        echo "Invalid day!";
}

echo "<br><br>Note: This program uses the server's current date to determine the day.";
?>
