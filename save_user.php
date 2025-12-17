<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="main">
         <?php
include "db_connection.php"; // include db connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Insert query
    $sql = "INSERT INTO usermgmt (name, email, phone) VALUES ('$name', '$email', '$phone')";

    if ($conn->query($sql) === TRUE) { 
        // echo "New user saved successfully!";
        echo '<div class="container2 box">';
        echo '<h2>New user saved successfully!</h2>';
        echo "</div>";
    }
    else { 
        echo "Error: " . $sql . "<br />" . $conn->error; 
    } 
} 
$conn->close();
    ?>
    </div>
  </body>
</html>
