<?php
include "db_connection.php";  // Connecting to the databawse
// include "sidebar.php"
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CRUD - User Management</title>
    <!-- Bootstrap 5 CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
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
    <!-- SIDEBAR -->
    <div class="sidebar">
      <h3>📋 Menu</h3>
      <nav class="nav flex-column">
        <a class="nav-link active" href="#registration">
          <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
          </svg>
          User Registration
        </a>
        <a class="nav-link" href="#userlist">
          <svg width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
            <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM5 4h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1zm0 2h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1z"/>
          </svg>
          User Details
        </a>
      </nav>
    </div>

<div class="mainContainer">
      <div class="container mt-15">
      <h2 class="mb-4 txtDes" id="registration">User Management</h2>

      <!-- CREATE/UPDATE FORM -->
      <form id="userForm" method="POST" action="operations.php" enctype="multipart/form-data" >
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            placeholder="Enter name"
            required
          />
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            placeholder="Enter email"
            required
          />
        </div>

        <div class="mb-3">
          <label for="phone" class="form-label">Phone</label>
          <input
            type="text"
            class="form-control"
            id="phone"
            name="phone"
            placeholder="Enter phone"
          />
        </div>

       <!-- DOCUMENT UPLOAD SECTION - NEW -->

          <div class="mb-3">
            <label for="document" class="form-label">Choose Document</label>
            <input
              type="file"
              class="form-control"
              id="document"
              name="document"
              accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png"
              required
            />
            <small class="text-muted">Accepted formats: PDF, DOC, DOCX, TXT, JPG, PNG (Max 5MB)</small>
          </div>
          <input type="hidden" name="action" value="create">


        <button type="submit" class="btn btn-primary">Submit</button>
      </form>


      <!-- TABLE (READ & DELETE) -->
      <div class="mt-5" id="userlist">
        <h4>List of Users</h4>
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Document</th>
              <th>Further Action</th>

            </tr>
          </thead>
          <tbody id="userTableBody">
            <!-- Example Row -->
             <?php

// SQL to fetch all users
$sql = "
SELECT 
    u.id, u.name, u.email, u.phone,
    d.document_path
FROM usermgmt u
LEFT JOIN userdoc_new d ON u.id = d.user_id
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

    $img = "uploads/default.png"; // fallback image

    if (!empty($row['document_path']) && file_exists($row['document_path'])) {
        $img = $row['document_path'];   //both has to be true or else blank img will show
    }

    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['name']}</td>";
    echo "<td>{$row['email']}</td>";
    echo "<td>{$row['phone']}</td>";

    // IMAGE PREVIEW
    echo "<td class='text-center'>
            <a href='{$img}' target='_blank'>
    <img src='{$img}' width='60' height='60'
         style='object-fit:cover;border-radius:6px;'>
</a>
          </td>";

    echo "<td class='text-center'>
            <a href='edit_user.php?id={$row['id']}' class='btn btn-warning btn-sm me-2'>Update</a>
            <a href='operations.php?delete_id={$row['id']}'
               class='btn btn-danger btn-sm'
               onclick=\"return confirm('Are you sure?');\">Delete</a>
          </td>";
    echo "</tr>";
}

} else {
    echo "<tr><td colspan='5'>No users found.</td></tr>";
}

$conn->close();
?>

            <!-- Dynamic rows go here -->
          </tbody>
        </table>
      </div>
    </div>
</div>
<!-- <?php
session_start();
$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);
?> -->
<?php if ($alert): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: '<?= $alert['type'] ?>',
    title: '<?= $alert['title'] ?>',
    text: '<?= addslashes($alert['message']) ?>',
    confirmButtonColor: '#0d6efd'
});

document.querySelectorAll('.deleteUser').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        let userId = this.dataset.id;

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545', // red
            cancelButtonColor: '#6c757d',  // gray
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to delete action
                window.location.href = 'operations.php?delete_id=' + userId;
            }
        });
    });
});
</script>
<?php endif; ?>

<script>
  // Smooth scroll and active link handling
  document.querySelectorAll('.sidebar .nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      
      // Remove active class from all links
      document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
      
      // Add active class to clicked link
      this.classList.add('active');
      
      // Smooth scroll to section
      const targetId = this.getAttribute('href');
      document.querySelector(targetId).scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    });
  });
</script>
  </body>
</html>