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



===


<?php
include "db_connection.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Note: In a real project, use prepared statements to prevent SQL injection
    $sql = "INSERT INTO usermgmt (name, email, phone) VALUES ('$name', '$email', '$phone')";

    if ($conn->query($sql) === TRUE) { 
        // 1. CLOSE the PHP tag to write HTML/JavaScript
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Success</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="style.css">
            <!-- Include SweetAlert2 Library -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body class="main">
            <div>
                <!-- SweetAlert will trigger automatically when this page loads -->
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User saved successfully!',
                        confirmButtonColor: '#0d6efd'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.php'; // Redirect back to your form
                        }
                    });
                </script>
            </div>
        </body>
        </html>
        <?php // 2. RE-OPEN PHP for the 'else' block
    } else { 
        echo "Error: " . $conn->error; 
    } 
} 
?>
