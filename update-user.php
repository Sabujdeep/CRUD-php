<?php
include "db_connection.php";

$id = $_GET['id'];

$sql = "
SELECT 
    u.*, 
    d.document_name,
    d.document_path
FROM users u
LEFT JOIN user_documents d ON u.id = d.user_id
WHERE u.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

include 'sidebar.php'

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 250px;
        padding: 20px;
        z-index: 1000;  
        overflow: hidden;
        }

        .sidebar::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: url(./Styles/bg.jpg);
        /* background-size: cover; */
        /* background-position: center; */
        filter: blur(20px);
        transform: scale(1.1); /* prevent edge blur cut */
        z-index: -1;
        }
      
      .sidebar h3 {
        color: white;
        font-size: 1.5rem;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(255,255,255,0.3);
      }
      
      .sidebar .nav-link {
        color: rgba(255,255,255,0.8);
        padding: 12px 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
      }
      
      .sidebar .nav-link:hover {
        background: rgba(255,255,255,0.2);
        color: white;
        transform: translateX(5px);
      }
      
      .sidebar .nav-link.active {
        background: rgba(255,255,255,0.25);
        color: white;
      }
      
      .mainContainer {
        margin-left: 250px;
      }
      
      @media (max-width: 768px) {
        .sidebar {
          width: 100%;
          height: auto;
          position: relative;
        }
        
        .mainContainer {
          margin-left: 0;
        }
      }
  </style>
</head>
<body>
    <div class="mainContainer">
        <div class="container">
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
    </div>
</body>
</html>