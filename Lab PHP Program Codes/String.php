<?php
/* 1️⃣  Create a string literal  */
$greeting = "Hello, World!";

/* 2️⃣  Create another string (this one uses a variable inside the double‑quoted string) */
$name = "Alice";
$welcome = "Welcome, $name!";

/* 3️⃣  Concatenate two strings together  */
$fullMessage = $greeting . " " . $welcome;

/* 4️⃣  Print the resulting string  */
echo $fullMessage;   // → Hello, World! Welcome, Alice!
?>