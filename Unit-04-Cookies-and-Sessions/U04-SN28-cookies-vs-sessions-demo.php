<!-- Difference between Cookies and Sessions in PHP using code -->

<html>
<head>
    <title>Difference between Cookies and Sessions in PHP</title>
</head>
<body>
<h1>Difference between Cookies and Sessions in PHP</h1>
<h2>Cookies</h2>
<p>Cookies are small pieces of data stored on the client's browser. They are sent to the server with every request. Cookies can be set to expire after a certain time or when the browser is closed.</p>

<?php
// Setting a cookie
setcookie("username", "Akshay", time() + (86400 * 30)); // Cookie expires in 30 days
?>

<p>A cookie named "username" has been set with the value "Akshay".</p>
<h2>Sessions</h2>
<p>Sessions are a way to store information on the server for individual users. A session is started with the session_start() function, and data can be stored in the $_SESSION superglobal array. Sessions are more secure than cookies as the data is stored on the server and not accessible to the client.</p>

<?php
// Starting a session
session_start();
// Setting a session variable
$_SESSION["username"] = "Akshay";
?>

<p>A session variable named "username" has been set with the value "Akshay".</p>  
 
</body>
</html>

_________________________________________________________________________________________________________________________________________________________________

<!-- 🧠 1. Concept Overview (Theory)
🔹 Cookies
Stored on client-side (browser)
Sent to server with every request
Used for:
Remembering user info
Login persistence

👉 Key idea:

Cookie = Client-side storage

🔹 Sessions
Stored on server-side
Each user gets a unique session ID
Used for:
Secure data storage
Authentication

👉 Key idea:

Session = Server-side storage

🔄 2. Code Explanation (Step-by-Step)
🍪 COOKIES PART
🔹 Setting a Cookie
setcookie("username", "Akshay", time() + (86400 * 30));
📌 Explanation:
"username" → cookie name
"Akshay" → value stored
time() + (86400 * 30) → expiry time (30 days)

👉 86400 seconds = 1 day

🔹 What Happens Internally
Cookie is stored in browser memory
Automatically sent to server on every request
Accessible using:
$_COOKIE["username"]
🔹 Output Line
<p>A cookie named "username" has been set with the value "Akshay".</p>
Just for display (no logic)
🔐 SESSIONS PART
🔹 Start Session
session_start();

📌 Important:

Must be called before using $_SESSION
Creates or resumes a session
🔹 Store Session Data
$_SESSION["username"] = "Akshay";
📌 Explanation:
"username" → key
"Akshay" → value
Stored on server
🔹 What Happens Internally
Server creates a session file
Browser stores only session ID
Data is NOT directly accessible to user
🔹 Output Line
<p>A session variable named "username" has been set with the value "Akshay".</p>
🔑 3. Key Differences (Important Table)
Feature	Cookies	Sessions
Storage	Client (browser)	Server
Security	Less secure	More secure
Size Limit	Small (~4KB)	Larger
Expiry	Set manually	Ends when browser closes (default)
Access	$_COOKIE	$_SESSION
🔄 4. Flow of Execution
Page loads
Cookie is created → stored in browser
Session starts → server allocates session
Session variable stored in $_SESSION
Messages displayed on screen
🔧 5. Functions Used
Function	Purpose
setcookie()	Create cookie
time()	Get current time
session_start()	Start session
$_SESSION[]	Store session data
📌 6. Final Summary (Exam Line)

👉 Cookies store data on the client side, while sessions store data on the server side, making sessions more secure and reliable for sensitive information. -->