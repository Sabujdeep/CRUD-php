make sure all the crud operations in a single page.

upload an image to the db to another table make that foreoghn key  with respesct to the primary key of the first table
\
===========================================================


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


<!-- Insert User -->

<?php
include "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // ---------- INSERT USER ----------
    $sql = "INSERT INTO usermgmt (name, email, phone)
            VALUES ('$name', '$email', '$phone')";

    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {

    $file_name = $_FILES['document']['name'];
    $file_tmp  = $_FILES['document']['tmp_name'];
    $file_size = $_FILES['document']['size'];

    // 5MB max
    if ($file_size <= 5242880) {

        // Create uploads folder if not exists
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Unique file name
        $new_file_name = time() . "_" . basename($file_name);
        $file_path = $upload_dir . $new_file_name;

        // Move file to uploads folder
        if (move_uploaded_file($file_tmp, $file_path)) {

            // Insert document record
            $doc_stmt = $conn->prepare(
                "INSERT INTO userdoc (doc_id, user_id, document_name, document_path)
                 VALUES (?, ?, ?, ?)"
            );

            $doc_stmt->bind_param(
                "iss",
                $new_user_id,      // FK from usermgmt
                $file_name,        // Original file name
                $file_path         // Stored file path
            );

            if ($doc_stmt->execute()) {
                $document_uploaded = true;
            } else {
                $upload_error = "Document insert failed: " . $doc_stmt->error;
            }

            $doc_stmt->close();

        } else {
            $upload_error = "File upload failed.";
        }

    } else {
        $upload_error = "File size exceeds 5MB limit.";
    }
}

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

$conn->close();
?>










