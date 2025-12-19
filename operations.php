<?php


session_start();
include "db_connection.php";

/* ==================== DELETE USER ==================== */
/* ==================== DELETE USER + DOCUMENT ==================== */
if (isset($_GET['delete_id'])) {

    $id = (int)$_GET['delete_id'];

    $conn->begin_transaction();

    try {
        // 1️⃣ Delete documents first
        $stmt1 = $conn->prepare("DELETE FROM userdoc WHERE user_id = ?");
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
        $stmt1->close();

        // 2️⃣ Delete user
        $stmt2 = $conn->prepare("DELETE FROM usermgmt WHERE id = ?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $stmt2->close();

        $conn->commit();

        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "Delete failed: " . $e->getMessage();
    }
}


/* ==================== UPDATE USER ==================== */
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id    = $_POST['id'];
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE usermgmt SET name=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $phone, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
    exit();
}

/* ==================== INSERT USER WITH DOCUMENT ==================== */
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST['id'])) {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // ---------- INSERT USER FIRST ----------
    $stmt = $conn->prepare("INSERT INTO usermgmt (name, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $phone);

    if ($stmt->execute()) {
        // Get the auto-incremented user ID
        $new_user_id = $conn->insert_id;
        
        $document_uploaded = false;
        $upload_error = "";

        // ---------- DEBUG: Check if file is being uploaded ----------
        if (isset($_FILES['document'])) {
            error_log("File upload detected. Error code: " . $_FILES['document']['error']);
        }

        // ---------- HANDLE DOCUMENT UPLOAD ----------
        if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {

            $file_name = $_FILES['document']['name'];
            $file_tmp  = $_FILES['document']['tmp_name'];
            $file_size = $_FILES['document']['size'];

            error_log("Processing file: $file_name, Size: $file_size bytes");

            // 5MB max
            if ($file_size <= 5242880) {

                // Create uploads folder if not exists
                $upload_dir = "uploads/";
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                    error_log("Created uploads directory");
                }

                // Unique file name
                $new_file_name = time() . "_" . basename($file_name);
                $file_path = $upload_dir . $new_file_name;

                error_log("Attempting to move file to: $file_path");

                // Move file to uploads folder
                if (move_uploaded_file($file_tmp, $file_path)) {
                    
                    error_log("File moved successfully. Inserting into database...");

                    // Insert document record into userdoc table
                    $doc_stmt = $conn->prepare(
                        "INSERT INTO userdoc (user_id, document_name, document_path) VALUES (?, ?, ?)"
                    );

                    $doc_stmt->bind_param("iss", $new_user_id, $file_name, $file_path);

                    if ($doc_stmt->execute()) {
                        $document_uploaded = true;
                        error_log("Document inserted successfully with ID: " . $doc_stmt->insert_id);
                    } else {
                        $upload_error = "Document insert failed: " . $doc_stmt->error;
                        error_log("DB Insert Error: " . $doc_stmt->error);
                    }

                    $doc_stmt->close();

                } else {
                    $upload_error = "File upload failed. Check folder permissions.";
                    error_log("move_uploaded_file failed");
                }

            } else {
                $upload_error = "File size exceeds 5MB limit.";
                error_log("File too large: $file_size bytes");
            }
        } else {
            if (isset($_FILES['document'])) {
                error_log("File upload error code: " . $_FILES['document']['error']);
            } else {
                error_log("No file uploaded");
            }
        }

        // ---------- SHOW SUCCESS MESSAGE ----------
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Success</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="style.css">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body class="main">
            <script>
                <?php if ($document_uploaded): ?>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        html: 'User and document saved successfully!<br><small>User ID: <?php echo $new_user_id; ?></small>',
                        confirmButtonColor: '#0d6efd'
                    }).then((result) => {
                        window.location.href = 'index.php';
                    });
                <?php elseif (!empty($upload_error)): ?>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Partial Success',
                        html: 'User saved (ID: <?php echo $new_user_id; ?>) but<br><?php echo addslashes($upload_error); ?>',
                        confirmButtonColor: '#ffc107'
                    }).then((result) => {
                        window.location.href = 'index.php';
                    });
                <?php else: ?>
                    Swal.fire({
                        icon: 'info',
                        title: 'User Saved',
                        html: 'User saved successfully!<br> But <br>User ID: <?php echo $new_user_id; ?><br><b>No document uploaded!</b>',
                        confirmButtonColor: '#0d6efd'
                    }).then((result) => {
                        window.location.href = 'index.php';
                    });
                <?php endif; ?>
            </script>
        </body>
        </html>
        <?php
        $stmt->close();
    } else { 
        echo "Error inserting user: " . $stmt->error; 
    } 
} 

$conn->close();
?>