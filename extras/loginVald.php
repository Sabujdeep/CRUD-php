<?php
session_start();
$emailErrorClass = "";
$alert = null;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");

    // Gmail validation regex
    $gmailRegex = "/^[a-zA-Z0-9._%+-]+@gmail\.com$/";

    if (preg_match($gmailRegex, $email)) {
    $_SESSION['login_success'] = true;
    header("Location: index.php");
    exit;
} else {
        // ✅ Valid Gmail (assume login success)
        $emailErrorClass = "is-valid";
        $alert = [
            "type" => "success",
            "title" => "Login Successful",
            "message" => ""
        ];
    }
}
?>
