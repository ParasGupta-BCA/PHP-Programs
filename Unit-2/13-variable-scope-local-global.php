<!-- Scope of Variables in PHP -->
<?php
function testScope() {
    $localVar = "I am local to testScope()";
    echo $localVar . "<br>";
}
testScope();
// echo $localVar; // This will cause an error because $localVar is not accessible

$globalVar = "I am a global variable";
function accessGlobal() {
    global $globalVar; // Accessing the global variable
    echo $globalVar . "<br>";
}
accessGlobal();
?>
<!-- 🧠 1. Scope of Variables (Theory)

👉 Scope defines where a variable can be accessed in a program.

🔹 Types of Variable Scope in PHP
1. Local Scope
Declared inside a function
Accessible only within that function
2. Global Scope
Declared outside any function
Cannot be used inside function directly (unless specified)
3. Global Keyword
Used to access global variables inside a function
🔄 2. Code Explanation (Step-by-Step)
🔹 Part 1: Local Scope
function testScope() {
    $localVar = "I am local to testScope()";
    echo $localVar . "<br>";
}
📌 Explanation:
$localVar is declared inside function
It is local variable
Function Call
testScope();

👉 Output:

I am local to testScope()
❌ Outside Access (Not Allowed)
// echo $localVar;

👉 This will give error because:

$localVar exists only inside function
🔹 Part 2: Global Scope
$globalVar = "I am a global variable";

👉 Declared outside function → global variable

🔹 Accessing Global Variable Inside Function
function accessGlobal() {
    global $globalVar;
    echo $globalVar . "<br>";
}
📌 Explanation:
global $globalVar; → imports global variable into function
Makes it accessible inside function
Function Call
accessGlobal();

👉 Output:

I am a global variable
🔑 3. Key Points
Scope Type	Defined Where	Accessible Where
Local	Inside function	Only inside that function
Global	Outside function	Everywhere (with global keyword in function)
🔧 4. Functions / Keywords Used
Element	Purpose
function	Define function
global	Access global variable inside function
echo	Display output
🔄 5. Flow of Execution
Function testScope() defined
Local variable created and printed
Global variable declared
Function accessGlobal() defined
global keyword used
Global variable printed
📌 6. Final Summary (Exam Line)

👉 In PHP, variables declared inside a function have local scope, while variables declared outside have global scope, and global variables can be accessed inside functions using the global keyword. -->