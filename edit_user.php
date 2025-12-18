<?php
include "db_connection.php";

$id = $_GET['id'];

$sql = "SELECT * FROM usermgmt WHERE id = $id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Update User Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="main">

<div class=" mt-5 container">
    <h3>Edit User</h3>

<form method="POST" action="update_user.php">
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

  <button type="submit" class="btn btn-success">Submit</button>
  <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

</div>
</body>
</html>
