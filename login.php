<?php
session_start();
if (isset($_SESSION['alert']))
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login In</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="./Styles/style.css" />
  </head>
  <body>
    <div class="mainContainer">
      <div class="container mt-15 w-25">
        <h2 class="mb-4 txtDes">Login</h2>
   <form
  id="loginForm"
  action="operations.php"
  method="POST"
  autocomplete="off"
>
  <!-- REQUIRED -->
  <input type="hidden" name="action" value="login">

  <div class="form-group">
    <label>Email address</label>
    <input
      type="email"
      class="form-control"
      name="email"
      id="email"
      placeholder="Enter email"
      required
    />
  </div>

  <div class="form-group mt-2">
    <label>Password</label>
    <input
      type="password"
      class="form-control"
      name="password"
      id="password"
      placeholder="Password"
      required
    />
  </div>

  <!-- Buttons -->
  <div class="d-flex justify-content-between align-items-center mt-3">
    <button type="submit" class="btn btn-primary">
      Login
    </button>

    <div>
      <small class="me-2">New user?</small>
      <button
        type="button"
        id="signupBtn"
        class="btn btn-outline-secondary btn-sm"
      >
        Sign Up
      </button>
    </div>
  </div>
</form>



      </div>
    </div>
<?php if (isset($_SESSION['alert'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
  icon: "<?= $_SESSION['alert']['type'] ?>",
  title: "<?= $_SESSION['alert']['title'] ?>",
  text: "<?= $_SESSION['alert']['message'] ?>",
  confirmButtonColor: "#0d6efd"
});
</script>
<?php unset($_SESSION['alert']); endif; ?>

    <script src="./loginVald.js"></script>
  </body>
</html>
