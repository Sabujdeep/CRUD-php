<?php
session_start();
include "db_connection.php";


/* ==================== USER LOGIN ==================== */
if (isset($_POST['action']) && $_POST['action'] === 'login') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $stmt = $conn->prepare(
        "SELECT id, password FROM admins WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // ❌ No user found
    if ($result->num_rows === 0) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Login Failed',
            'message' => 'Email not registered'
        ];
        header("Location: login.php");
        exit();
    }

    $user = $result->fetch_assoc();

    // ❌ Wrong password
    if (!password_verify($password, $user['password'])) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Login Failed',
            'message' => 'Incorrect password'
        ];
        header("Location: login.php");
        exit();
    }

    // ✅ SUCCESS → create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['logged_in'] = true;

    header("Location: welcome.php");
    exit();
}


/* ==================== USER SIGNUP ==================== */
if (isset($_POST['action']) && $_POST['action'] === 'signup') {

    $name = $_POST['name'] ?? '';
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check email
    $check = $conn->prepare("SELECT id FROM admins WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Email Exists',
            'message' => 'This email is already registered'
        ];
        header("Location: login.php");
        exit();
    }
    $check->close();

    // INSERT USER
    $stmt = $conn->prepare(
        "INSERT INTO admins (name, email, password)
         VALUES (?, ?, ?)"
    );

    $stmt->bind_param(
        "sss",
        $name,
        $email,
        $hashedPassword
    );

    if (!$stmt->execute()) {
        die("Signup failed: " . $stmt->error);
    }

    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Signup Successful',
        'message' => 'You can now login'
    ];

    header("Location: login.php");
    exit();
}





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
    $gender = $_POST['gender'] ?? '';
    $skills = $_POST['skills'] ?? [];
    $skillsStr = implode(', ', $skills);

    $conn->begin_transaction();

    try {
        // 1️⃣ Update user
        $stmt = $conn->prepare(
            "UPDATE usermgmt
            SET name=?, email=?, phone=?, gender=?, skills=?
            WHERE id=?"
            );
            $stmt->bind_param(
                "sssssi",
                $name,
                $email,
                $phone,
                $gender,
                $skillsStr,
                $id
            );

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

if (isset($_POST['action']) && $_POST['action'] === 'upload') {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'] ?? '';
    $skills = $_POST['skills'] ?? [];
    $skillsStr = implode(', ', $skills);

    if (!preg_match('/^\d{10}$/', $phone)) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Invalid Phone Number',
            'message' => 'Phone number must be exactly 10 digits.'
        ];
        header("Location: index.php");
        exit;
    }

    $conn->begin_transaction();

    try {
        // Insert user
        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, phone, gender, skills)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssss", $name, $email, $phone, $gender, $skillsStr);
        $stmt->execute();
        $user_id = $conn->insert_id;
        $stmt->close();

        // Upload document
        if (!empty($_FILES['document']['name'])) {
            $upload_dir = "uploads/";
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $file_name = $_FILES['document']['name'];
            $file_path = $upload_dir . time() . "_" . basename($file_name);

            move_uploaded_file($_FILES['document']['tmp_name'], $file_path);

            $stmt = $conn->prepare(
                "INSERT INTO user_documents (user_id, document_name, document_path)
                 VALUES (?, ?, ?)"
            );
            $stmt->bind_param("iss", $user_id, $file_name, $file_path);
            $stmt->execute();
            $stmt->close();
        }

        $conn->commit();

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'User and document added successfully!'
        ];

        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        die("Insert failed: " . $e->getMessage());
    }
}



$conn->close();
?>