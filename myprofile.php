<?php
session_start();
include "db_connection.php";

/* 🔒 Protect page */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* Fetch admin details */
$stmt = $conn->prepare("SELECT name, email FROM admins WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./Styles/style.css">
<!-- style side bar -->
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

<?php include "sidebar.php"; ?>

<div class="mainContainer">
  <div class="container mt-5" style="max-width: 700px;">

    <h3 class="mb-4 text-center">My Profile</h3>

    <!-- ================= ADMIN DETAILS ================= -->
    <div class="card mb-4 shadow-sm">
      <div class="card-header fw-bold">Admin Details</div>
      <div class="card-body">
        <p><strong>Name:</strong> <?= htmlspecialchars($admin['name']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']); ?></p>
      </div>
    </div>

    <!-- ================= CHANGE PASSWORD ================= -->
    <div class="card shadow-sm">
      <div class="card-header fw-bold">Change Password</div>
      <div class="card-body">

        <form method="POST" action="operations.php">

          <input type="hidden" name="action" value="change_password">

          <div class="mb-3">
            <label class="form-label fw-medium">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary">
            Update Password
          </button>

        </form>

      </div>
    </div>

  </div>
</div>

</body>
</html>
