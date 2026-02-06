<?php
// Creating a multidimensional associative array
$Student = array(
    array(
        "name" => "Nripesh Kumar",
        "course" => "Teacher",
        "Semester" => 6
    ),
    array(
        "name" => "Paras Gupta",
        "course" => "BCA",
        "Semester" => 6
    ),
    array(
        "name" => "Tanish Gupta",
        "course" => "BCA",
        "Semester" => 6
    )
);

// Displaying the data
foreach ($Student as $key => $value) {
    echo "Student " . ($key + 1) . "<br>";
    echo "Name: " . $value["name"] . "<br>";
    echo "Course: " . $value["course"] . "<br>";
    echo "Semester: " . $value["Semester"] . "<br><br>";
}
?>
<footer style="position:fixed; left:0; right:0; bottom:0; background:#f8f8f8; padding:10px 0; text-align:center; border-top:1px solid #e0e0e0; font-family:Arial, sans-serif;">
    <strong>Code Is Writen By <a href="https://www.linkedin.com/in/parasgupta-binary0101">Paras Gupta</a></strong>
</footer>