<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login In</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <link rel="stylesheet" href="./Styles/style.css" />
</head>

<body>
  <div class="mainContainer">
    <div class="container mt-15 w-25">
      <h2 class="mb-4 txtDes">Sign Up</h2>
      <form
        id="signupForm"
        action="operations.php"
        method="POST"
        autocomplete="off">
        <!-- REQUIRED for PHP -->
        <input type="hidden" name="action" value="signup">

        <div class="mb-3">
          <label for="name" class="form-label fw-medium">Name</label>
          <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            placeholder="Enter name"
            required />
        </div>

        <div class="form-group">
          <label>Email address</label>
          <input
            type="email"
            class="form-control"
            name="email"
            id="email"
            placeholder="Enter Email"
            required />
        </div>

        <div class="form-group mt-2">
          <label>Password</label>
          <input
            type="password"
            class="form-control"
            name="password"
            id="password"
            placeholder="Password"
            required />
        </div>

  <div class="d-flex justify-content-between align-items-center mt-3">
    <button type="submit" class="btn btn-primary">
      Submit
    </button>

    <div>
      <small class="me-2">Already an user?</small>
        <a href="login.php" class="btn btn-outline-secondary btn-sm">
          Login
        </a>
    </div>
  </div>
      </form>

    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="./signup.js"></script>
</body>

</html>