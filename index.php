<?php
include "db_connection.php";  // Connecting to the databawse

session_start();

// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }
// ?>

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

    <?php
       include "sidebar.php"
    ?>
    
<div class="mainContainer">
      <div class="container mt-15">
      <h2 class="mb-4 txtDes" id="registration">Insert User</h2>

      <!-- CREATE/UPDATE FORM -->
      <form id="userForm" method="POST" action="operations.php" enctype="multipart/form-data" >
            <input type="hidden" name="id" value="<?= $_SESSION['user_id'] ?>">
            <input type="hidden" name="action" value="upload">
        <div class="mb-3">
          <label for="name" class="form-label fw-medium">Name</label>
          <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            placeholder="Enter name"
            required
          />
        </div> 

        <div class="mb-3 fw-medium">
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

        <div class="mb-3 fw-medium">
          <label for="phone" class="form-label">Phone</label>
          <input
            type="text"
            class="form-control"
            id="phone"
            name="phone"
            placeholder="Enter phone"
          />
        </div>

        <div class="mb-3">
  <label class="form-label d-block fw-medium">Gender</label>

  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="radio"
      name="gender"
      id="genderMale"
      value="Male"
      required
    />
    <label class="form-check-label" for="genderMale">Male</label>
  </div>

  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="radio"
      name="gender"
      id="genderFemale"
      value="Female"
    />
    <label class="form-check-label" for="genderFemale">Female</label>
  </div>

  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="radio"
      name="gender"
      id="genderOther"
      value="Other"
    />
    <label class="form-check-label" for="genderOther">Other</label>
  </div>
</div>  

      <div class="mb-3">
  <label class="form-label d-block fw-medium">Skills</label>

  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="checkbox"
      name="skills[]"
      id="skillProgramming"
      value="Programming"
    />
    <label class="form-check-label" for="skillProgramming">Programming</label>
  </div>

  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="checkbox"
      name="skills[]"
      id="skillSwimming"
      value="Swimming"
    />
    <label class="form-check-label" for="skillSwimming">Swimming</label>
  </div>

  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="checkbox"
      name="skills[]"
      id="skillFootball"
      value="Football"
    />
    <label class="form-check-label" for="skillFootball">Football</label>
  </div>

  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="checkbox"
      name="skills[]"
      id="skillTennis"
      value="Tennis"
    />
    <label class="form-check-label" for="skillTennis">Tennis</label>
  </div>

  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="checkbox"
      name="skills[]"
      id="skillCricket"
      value="Cricket"
    />
    <label class="form-check-label" for="skillCricket">Cricket</label>
  </div>
</div>


       <!-- DOCUMENT UPLOAD SECTION - NEW -->

          <div class="mb-3">
            <label for="document" class="form-label fw-medium">Choose Document</label>
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

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>


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
        // let userId = this.dataset.id;

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
                window.location.href = 'operations.php?delete_id=';
            }
        });
    });
});
</script>
<?php endif; ?>
  </body>
</html>