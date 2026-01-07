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

?>

<!DOCTYPE html>
<html>
<head>
  <title>Update User Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./Styles/style.css">
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
    <?php include 'sidebar.php' ?>
<div class="mainContainer">
  <div class="mt-5 container">
        <h3>Edit User</h3>

<form method="POST" action="operations.php" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?= $user['id']; ?>">
  <input type="hidden" name="action" value="update">


  <div class="mb-3 fw-medium">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="<?= $user['name']; ?>" required>
  </div>

  <div class="mb-3 fw-medium">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="<?= $user['email']; ?>" required>
  </div>

  <div class="mb-3 fw-medium">
    <label>Phone</label>
    <input type="text" name="phone" class="form-control" value="<?= $user['phone']; ?>">
  </div>

<!-- Gender -->
 <div class="mb-3 fw-medium">
  <label class="form-label d-block">Gender</label>

  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="radio"
      name="gender"
      id="genderMale"
      value="Male"
      <?= ($user['gender'] ?? '') === 'Male' ? 'checked' : '' ?>
    >
    <label class="form-check-label" for="genderMale">Male</label>
  </div>

  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="radio"
      name="gender"
      id="genderFemale"
      value="Female"
      <?= ($user['gender'] ?? '') === 'Female' ? 'checked' : '' ?>
    >
    <label class="form-check-label" for="genderFemale">Female</label>
  </div>
</div>

<!-- Skills -->
<div class="mb-3 fw-medium">
  <label class="form-label d-block">Skills</label>

  <?php
    $userSkills = isset($user['skills']) ? explode(', ', $user['skills']) : [];
  ?>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="skills[]" value="Programming"
      <?= in_array('Programming', $userSkills) ? 'checked' : '' ?>>
    <label class="form-check-label">Programming</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="skills[]" value="Swimming"
      <?= in_array('Swimming', $userSkills) ? 'checked' : '' ?>>
    <label class="form-check-label">Swimming</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="skills[]" value="Football"
      <?= in_array('Football', $userSkills) ? 'checked' : '' ?>>
    <label class="form-check-label">Football</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="skills[]" value="Tennis"
      <?= in_array('Tennis', $userSkills) ? 'checked' : '' ?>>
    <label class="form-check-label">Tennis</label>
  </div>

  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="skills[]" value="Cricket"
      <?= in_array('Cricket', $userSkills) ? 'checked' : '' ?>>
    <label class="form-check-label">Cricket</label>
  </div>
</div>



<div class="mb-3">
    <label class="fw-medium">Current Document</label><br>

    <?php if (!empty($user['document_path']) && file_exists($user['document_path'])): ?>

        <p class="mb-1">
            <strong>File Name:</strong>
            <?= htmlspecialchars($user['document_name']); ?>
        </p>

        <?php if (preg_match('/\.(jpg|jpeg|png)$/i', $user['document_path'])): ?>
            <img
                src="<?= $user['document_path']; ?>"
                width="120"
                class="mb-2"
                style="border-radius:8px;object-fit:cover;"
            >
        <?php else: ?>
            <a href="<?= $user['document_path']; ?>" target="_blank">
                View / Download Document
            </a>
        <?php endif; ?>

    <?php else: ?>
        <p class="text-muted">No document uploaded</p>
    <?php endif; ?>
</div>
<div class="mb-3 fw-medium">
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
