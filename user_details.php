<?php
include "db_connection.php";  // Connecting to the database
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Details - User Management</title>
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
    filter: blur(20px);
    transform: scale(1.1);
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
    <?php
    include "sidebar.php";
    ?>

    <div class="mainContainer">
      <div class="container mt-5">
        <h2 class="mb-4 txtDes">User Details</h2>

        <!-- Search and Filter Section -->
        <div class="row mb-3">
          <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by name, email, or phone...">
          </div>
          <div class="col-md-6 text-end">
            <a href="index.php" class="btn btn-primary">
              <svg width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
              </svg>
              Add Details
            </a>
          </div>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <!-- <th>Email</th> -->
                <th>Phone</th>
                <th>Gender</th>
                <th>Skills</th>
                <th>Document</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="userTableBody">
              <?php
              // SQL to fetch all users
              $sql = "
                SELECT 
                   u.id, u.name, u.email, u.phone, u.gender, u.skills,
                   d.document_path
                FROM usermgmt u
                LEFT JOIN userdoc_new d ON u.id = d.user_id
              ";

              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      $img = "uploads/default.png"; // fallback image

                      if (!empty($row['document_path']) && file_exists($row['document_path'])) {
                          $img = $row['document_path'];
                      }

                      echo "<tr>";
                      echo "<td>{$row['id']}</td>";
                      echo "<td>{$row['name']}</td>";
                      // echo "<td>{$row['email']}</td>";
                      echo "<td>{$row['phone']}</td>";
                      echo "<td>{$row['gender']}</td>";
                      echo "<td>{$row['skills']}</td>";

                      // IMAGE PREVIEW
                      echo "<td class='text-center'>
                              <a href='{$img}' target='_blank'>
                                <img src='{$img}' width='60' height='60'
                                     style='object-fit:cover;border-radius:6px;'>
                              </a>
                            </td>";

                      echo "<td class='text-center'>
                              <a href='edit_user.php?id={$row['id']}' class='btn btn-warning btn-sm me-2'>
                                <svg width='12' height='12' fill='currentColor' viewBox='0 0 16 16'>
                                  <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
                                </svg>
                                Update
                              </a>
                              <button class='btn btn-danger btn-sm deleteUser' data-id='{$row['id']}'>
                                <svg width='12' height='12' fill='currentColor' viewBox='0 0 16 16'>
                                  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/>
                                  <path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/>
                                </svg>
                                Delete
                              </button>
                            </td>";
                      echo "</tr>";
                  }
              } else {
                  echo "<tr><td colspan='6' class='text-center text-muted'>No users found.</td></tr>";
              }

              $conn->close();
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <?php
    $alert = $_SESSION['alert'] ?? null;
    unset($_SESSION['alert']);
    ?>
    
    <?php if ($alert): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        icon: '<?= $alert['type'] ?>',
        title: '<?= $alert['title'] ?>',
        text: '<?= addslashes($alert['message']) ?>',
        confirmButtonColor: '#0d6efd'
    });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      // Delete confirmation with SweetAlert
      document.querySelectorAll('.deleteUser').forEach(function(btn) {
          btn.addEventListener('click', function(e) {
              e.preventDefault();
              let userId = this.dataset.id;

              Swal.fire({
                  title: 'Are you sure?',
                  text: "This action cannot be undone!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#dc3545',
                  cancelButtonColor: '#6c757d',
                  confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.location.href = 'operations.php?delete_id=' + userId;
                  }
              });
          });
      });

      // Search functionality
      document.getElementById('searchInput').addEventListener('keyup', function() {
          const searchValue = this.value.toLowerCase();
          const tableRows = document.querySelectorAll('#userTableBody tr');

          tableRows.forEach(row => {
              const text = row.textContent.toLowerCase();
              if (text.includes(searchValue)) {
                  row.style.display = '';
              } else {
                  row.style.display = 'none';
              }
          });
      });
    </script>
  </body>
</html>