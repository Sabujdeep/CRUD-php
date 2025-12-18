<!-- Delete User -->

<?php

include "db_connection.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM usermgmt WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        echo '<div class="main">';
        echo '<div class="container2 box">';
        echo '<h2>User Deleted Successfully!</h2>';
        echo "</div>";
        echo "</div>";
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>



<!-- Insert User -->

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
        echo '<div class="main">';
        echo '<div class="container2 box">';
        echo '<h2>New User Saved Successfully!</h2>';
        echo "</div>";
        echo "</div>";
    }
    else { 
        echo "Error: " . $sql . "<br />" . $conn->error; 
    } 
} 
$conn->close();
    ?>


<!-- Update User -->

<?php
include "db_connection.php";

if (isset($_POST['id'])) {
    $id    = $_POST['id'];
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE usermgmt 
            SET name='$name', email='$email', phone='$phone' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>



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
    <div class="main"></div>
  </body>
</html>


