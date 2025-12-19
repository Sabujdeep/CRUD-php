<?php
include "db_connection.php";  // Connecting to the databawse
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
  </head>
  <body>
<div class="mainContainer">
      <div class="container mt-15">
      <h2 class="mb-4 txtDes">User Management</h2>

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
      <div class="mt-5">
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
        $img = $row['document_path'];
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
  </body>
</html>


