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