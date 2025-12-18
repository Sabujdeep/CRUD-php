<!-- Delete User -->

<?php
session_start();
include "db_connection.php";

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $sql = "DELETE FROM usermgmt WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

?>



<!-- Insert User -->

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



<!-- Update User -->

<?php

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






