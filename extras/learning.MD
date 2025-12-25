This message describes a common software development pattern used to handle Post/Redirect/Get (PRG) 
to prevent form resubmission issues, using an alert library (SweetAlert) as the specific example
===============================================================
var_dump($_POST);
var_dump($_FILES);
exit;


C:\wamp64\www\project_1\operations.php:4:
array (size=3)
  'name' => string 'Ranjitsd' (length=8)
  'email' => string 'raa@dhsj.com' (length=12)
  'phone' => string '9773777477' (length=10)
C:\wamp64\www\project_1\operations.php:5:
array (size=1)
  'document' => 
    array (size=6)
      'name' => string '1imgh.jpg' (length=9)
      'full_path' => string '1imgh.jpg' (length=9)
      'type' => string 'image/jpeg' (length=10)
      'tmp_name' => string 'C:\wamp64\tmp\php4F9B.tmp' (length=25)
      'error' => int 0
      'size' => int 165378


 # this most important line
      'error' => int 0
      👉 This means the file upload is 100% successful


==================================================================
# document retrieving and previewing

$sql = "
SELECT 
    u.id, u.name, u.email, u.phone,
    d.document_path
FROM usermgmt u
LEFT JOIN userdoc d ON u.id = d.user_id
";

    👉 LEFT JOIN ensures users show even if no document exists.



========================================================================

⚠️ BEFORE YOU DELETE (IMPORTANT)

This will remove ALL data

Only do this if you’re okay losing current records

Take a backup if needed (phpMyAdmin → Export)

✅ STEP 1: DELETE THE DATABASE
Option A: Using phpMyAdmin (Easiest)

Open phpMyAdmin

Select your database (e.g. project_1)

Click Operations

Click Drop the database

Confirm ✅

============================================================================

<?php
include "db_connection.php";  // Connecting to the database
?>

What this does
Includes your database connection file
$conn -> (MySQLi connection object) becomes available in this file
Without this, no SQL query will work
✔ Best practice: keep DB connection in a separate file (you did this correctly)

===========================================================================

2️⃣ new mysqli
new mysqli(...)


mysqli is a built-in PHP class

new creates a new object of that class

This object represents a live connection to MySQL

=================================================================

new mysqli()
means:

“Create a new MySQL connection object”

==========================================================================

connect_error
$conn->connect_error


Returns a string

Contains the error message

Example:

"Access denied for user 'root'@'localhost'"

🔹 connect_errno
$conn->connect_errno


Returns a number (error code)

=====================================================================================

GET POST PUT PATCH DELETE

===================================================================

$result is a mysqli_result object

num_rows tells how many records came back from the database

Meaning:

“Only proceed if at least one user exists”

If no users → go to the else block.
=======================================================================================

while ($row = $result->fetch_assoc()) {
fetch_assoc() fetches one row at a time

Each row is returned as an associative array

This loop runs once per user.


============================================================================================

Two checks (both must pass):

1️⃣ !empty($row['document_path'])

Ensures DB value is not NULL or empty

2️⃣ file_exists($row['document_path'])

Confirms the file actually exists in /uploads

✔ If both true → use user’s uploaded file
❌ Otherwise → fallback image stays

================================================================================================

-> operator is used to access the methods and properties of the object

unlink() is used to delete a file from the filesystem

bindparam() method sqli extension, primary defense against SQL injection,
t allows the database to pre-compile the SQL query once and then execute it 
multiple times with different values, reducing parsing overhead

$stmt->close() closes a prepared MySQLi statement object, freeing its resources after use.

=============================================================================================

$conn->rollback();
What rollback does
Cancels ALL DB changes

Leaves database unchanged

Note: files already deleted cannot be rolled back
This is why you deleted files before DB commit

===========================================================================================
isset(): Checks if the variable exists and is not null. It returns true for values like 0, false, and "".
empty(): Checks if a variable is "falsy." It returns true for null, 0, false, "", and empty arrays.
is_null(): Only checks if a variable is exactly null. Unlike isset(), it will trigger a warning if the variable is undefined.

=====================================================================================================

if (isset($_POST['id']) && !empty($_POST['id'])) {
Why?
Your edit form sends a hidden id

If id exists → this is an UPDATE

If id is empty → it’s a CREATE

👉 This is how you differentiate insert vs update.


========================================================================


$_FILES is a PHP superglobal associative array that holds information about files uploaded 
to the script via HTTP POST method from HTML forms.


$_POST is a PHP superglobal array that captures data sent to a script via HTTP POST requests, 
typically from HTML form submissions.

==========================================================================

The basename() function strips any directory path from a string, returning only the trailing filename
 component (e.g., "photo.jpg" from "/uploads/photo.jpg").


 $file_name = $_FILES['document']['name'];
$file_tmp  = $_FILES['document']['tmp_name'];
$file_size = $_FILES['document']['size'];
Field	Meaning
name	-> Original filename
tmp_name	-> Temporary server file
size	-> File size in bytes
 =============================================================================