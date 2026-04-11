<!-- chatbox single side with string validation with send and clear buttons IN PHP IN A BOX  ALL PREVIOUS CHATS ALSO-->

    <?php
    session_start();
    if (!isset($_SESSION['chats'])) {
        $_SESSION['chats'] = [];
    }
    if (isset($_POST['send'])) {
        $message = trim($_POST['message']);
        if (!empty($message)) {
            $_SESSION['chats'][] = $message;
        }
    }
    if (isset($_POST['clear'])) {
        $_SESSION['chats'] = [];
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chatbox</title>
        <style>
            .chatbox {
                width: 300px;
                height: 400px;
                border: 1px solid #ccc;
                padding: 10px;
                overflow-y: scroll;
            }
            .message {
                margin-bottom: 10px;
                padding: 5px;
                background-color: #f1f1f1;
                border-radius: 5px;
            }
            .input-area {
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <h1>Chatbox</h1>
        <div class="chatbox">
            <?php
            foreach ($_SESSION['chats'] as $chat) {
                echo "<div class='message'>" . htmlspecialchars($chat) . "</div>";
            }
            ?>
        </div>
        <form method="post" class="input-area">
            <input type="text" name="message" placeholder="Type your message here..." required>
            <button type="submit" name="send">Send</button>
            <button type="submit" name="clear">Clear</button>
        </form>
    </body>
    </html>

<!-- 🧠 1. Basic Concept Used (Theory)
🔹 1. Session Handling ($_SESSION)
PHP sessions are used to store data temporarily on the server
Data remains available across multiple page reloads
Here, session is used to store chat messages

👉 Key idea:

Session = Server-side memory for a user

🔹 2. Superglobals ($_POST, $_SESSION)
$_POST → used to receive form data
$_SESSION → used to store chat messages
🔹 3. Form Handling (POST Method)
The form sends data using method="post"
PHP checks which button is clicked:
send → add message
clear → delete all messages
🔹 4. String Validation
trim() → removes extra spaces
!empty() → ensures message is not blank

👉 Prevents:

Empty messages
Only spaces input
🔹 5. Output Sanitization
htmlspecialchars() → converts special characters into safe HTML

👉 Prevents:

HTML injection
Script execution

🔄 2. Flow of Execution (Step-by-Step)

Step 1: Start Session

session_start();
Initializes session
Allows access to $_SESSION

Step 2: Initialize Chat Storage
if (!isset($_SESSION['chats'])) {
    $_SESSION['chats'] = [];
}
If chat history doesn't exist → create empty array
This array will store all messages

Step 3: Handle Send Button

if (isset($_POST['send'])) {
Checks if "Send" button is clicked
Get message:
$message = trim($_POST['message']);
Validate:
if (!empty($message)) {
Store message:
$_SESSION['chats'][] = $message;

👉 Adds message to session array

Step 4: Handle Clear Button
if (isset($_POST['clear'])) {
    $_SESSION['chats'] = [];
}
Clears entire chat history
Resets session array

🖥️ 3. Frontend (HTML + CSS)
🔹 Chatbox UI
<div class="chatbox">
Fixed height box
Scroll enabled (overflow-y: scroll)

🔹 Display Messages
foreach ($_SESSION['chats'] as $chat)
Loop through all stored messages
echo "<div class='message'>" . htmlspecialchars($chat) . "</div>";
Each message shown inside a styled box

🔹 Input Form
<form method="post">
Sends data to same page
Input field:
<input type="text" name="message" required>
Buttons:
<button name="send">Send</button>
<button name="clear">Clear</button>
send → adds message
clear → deletes messages

🔑 4. Key Functions Used
Function	Purpose
session_start()	Start session
isset()	Check if variable exists
trim()	Remove extra spaces
empty()	Check if value is empty
htmlspecialchars()	Secure output
foreach	Loop through messages

📌 5. Overall Working (In One Line)

👉 This program creates a single-user chatbox where messages are:

Stored in session
Displayed on screen
Can be cleared anytime -->