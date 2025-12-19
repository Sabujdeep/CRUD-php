<?php
session_start();

if (!isset($_SESSION['alert'])) {
    header("Location: index.php");
    exit();
}

$alert = $_SESSION['alert'];
unset($_SESSION['alert']); // flash message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
Swal.fire({
    icon: '<?= $alert['type']; ?>',
    title: '<?= $alert['title']; ?>',
    html: '<?= addslashes($alert['message']); ?>',
    confirmButtonColor: '#0d6efd'
}).then(() => {
    window.location.href = 'index.php';
});
</script>

</body>
</html>
