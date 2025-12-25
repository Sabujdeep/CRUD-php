<?php
session_start();
include "db_connection.php";

/* ==================== DELETE USER + DOCUMENT ==================== */
if (isset($_GET['delete_id'])) {

    $id = (int)$_GET['delete_id'];
    $conn->begin_transaction();     //runs all the code at one time if it fails, it begins from the start
    try {
        // 1️⃣ Get document paths
        $stmt = $conn->prepare(
            "SELECT document_path FROM userdoc_new WHERE user_id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            if (file_exists($row['document_path'])) {
                unlink($row['document_path']); // delete file
            }
        }
        $stmt->close();

        // 2️⃣ Delete documents from DB
        $stmt = $conn->prepare(
            "DELETE FROM userdoc_new WHERE user_id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // 3️⃣ Delete user
        $stmt = $conn->prepare(
            "DELETE FROM usermgmt WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        header("Location: index.php"); //redirects user
        exit();     

    } catch (Exception $e) {
        $conn->rollback();
        echo "Delete failed: " . $e->getMessage();
    }
}



/* ==================== UPDATE USER ==================== */
if (isset($_POST['id']) && !empty($_POST['id'])) {

    $id    = (int)$_POST['id'];
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $conn->begin_transaction();

    try {
        // 1️⃣ Update user
        $stmt = $conn->prepare(
            "UPDATE usermgmt SET name=?, email=?, phone=? WHERE id=?"
        );
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
        $stmt->execute();
        $stmt->close();

        // 2️⃣ Handle document replace (if new file uploaded)
        if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {

            // Get old file
            $stmt = $conn->prepare(
                "SELECT document_path FROM userdoc_new WHERE user_id = ?"
            );
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            $old = $res->fetch_assoc();
            $stmt->close();

            if ($old && file_exists($old['document_path'])) {
                unlink($old['document_path']); // delete old file
            }

            // Upload new file
            $upload_dir = "uploads/"; //prevents upload failure
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_name = time() . "_" . basename($_FILES['document']['name']); 
            $file_path = $upload_dir . $file_name;

            move_uploaded_file($_FILES['document']['tmp_name'], $file_path);

            // Replace DB record
            $stmt = $conn->prepare(
                "REPLACE INTO userdoc_new (user_id, document_name, document_path)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param(
                "iss",
                $id,
                $_FILES['document']['name'],
                $file_path
            );
            $stmt->execute();
            $stmt->close();
        }

        $conn->commit();
        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "Update failed: " . $e->getMessage();
    }
}

/* ==================== INSERT USER WITH DOCUMENT ==================== */
if (isset($_POST['action']) && $_POST['action'] === 'create') {

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

                    // Insert document record into userdoc_new table
                    $doc_stmt = $conn->prepare(
                        "INSERT INTO userdoc_new (user_id, document_name, document_path) VALUES (?, ?, ?)"
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

        // ---------- SET FLASH MESSAGE ----------
        $_SESSION['alert'] = [
            'type' => $document_uploaded ? 'success' : (!empty($upload_error) ? 'warning' : 'info'),
            'title' => $document_uploaded ? 'Success' : (!empty($upload_error) ? 'Partial Success' : 'User Saved'),
            'message' => $document_uploaded
                ? "User and document saved successfully!<br><small>User ID: $new_user_id</small>"
                : (!empty($upload_error)
                    ? "User saved (ID: $new_user_id) but<br>$upload_error"
                    : "User saved successfully!<br>User ID: $new_user_id<br><b>No document uploaded!</b>")
        ];

        $stmt->close();

        header("Location: success.php");
        exit();

    } else { 
        echo "Error inserting user: " . $stmt->error; 
    } 
} 

$conn->close();
?>