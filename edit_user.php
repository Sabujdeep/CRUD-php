<?php
include "db_connection.php";

$id = $_GET['id'];

$sql = "
SELECT 
    u.*, 
    d.document_name,
    d.document_path
FROM usermgmt u
LEFT JOIN userdoc_new d ON u.id = d.user_id
WHERE u.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Update User Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./Styles/style.css">
</head>
<body class="main">

<div class=" mt-5 container">
    <h3>Edit User</h3>

<form method="POST" action="operations.php" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?= $user['id']; ?>">

  <div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="<?= $user['name']; ?>" required>
  </div>

  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="<?= $user['email']; ?>" required>
  </div>

  <div class="mb-3">
    <label>Phone</label>
    <input type="text" name="phone" class="form-control" value="<?= $user['phone']; ?>">
  </div>

  <div class="mb-3">
    <label>Current Document</label><br>

    <?php if (!empty($user['document_path']) && file_exists($user['document_path'])): ?>

        <?php if (preg_match('/\.(jpg|jpeg|png)$/i', $user['document_path'])): ?>
            <img src="<?= $user['document_path']; ?>"
                 width="120"
                 class="mb-2"
                 style="border-radius:8px;object-fit:cover;">
        <?php else: ?>
            <a href="<?= $user['document_path']; ?>" target="_blank">
                View Document
            </a>
        <?php endif; ?>

    <?php else: ?>
        <p class="text-muted">No document uploaded</p>
    <?php endif; ?>
</div>
<div class="mb-3">
    <label>Change Document</label>
    <input type="file" name="document" class="form-control"
           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
    <small class="text-muted">Leave empty to keep existing document</small>
</div>


  <button type="submit" class="btn btn-success">Submit</button>
  <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

</div>
</body>
</html>
