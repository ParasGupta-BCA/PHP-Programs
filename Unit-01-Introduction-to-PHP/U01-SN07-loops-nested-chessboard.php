<!-- display a chess board  of 8x8 size  -->
<?php
$size = 8;
echo "<table border='1' cellspacing='0' cellpadding='10'>";
for ($i = 0; $i < $size; $i++) {
    echo "<tr>";
    for ($j = 0; $j < $size; $j++) {
        // Determine color based on position
        if (($i + $j) % 2 == 0) {
            echo "<td style='background-color: black; width: 50px; height:
    50px;'></td>";
        } else {
            echo "<td style='background-color: white; width: 50px; height:
    50px;'></td>";
        }
    }
    echo "</tr>";
}
echo "</table>";
?>
    